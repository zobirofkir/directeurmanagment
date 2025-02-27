<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\Pages\CalendarView;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Events';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required(),
                Textarea::make('description'),
                DateTimePicker::make('start_time')->required(),
                DateTimePicker::make('end_time')->required(),
                TextInput::make('location'),
                Select::make('type')
                    ->options(['meeting' => 'Meeting', 'deadline' => 'Deadline']),
                Hidden::make('user_id')->default(Auth::user()->id)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Event::query()->where('user_id', Auth::id()))
            ->columns([
                TextColumn::make('title')->sortable(),
                TextColumn::make('start_time')->sortable(),
                TextColumn::make('end_time')->sortable(),
                SelectColumn::make('type')->options([
                    'meeting' => 'Meeting',
                    'deadline' => 'Deadline',
                ]),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'meeting' => 'Meeting',
                        'deadline' => 'Deadline',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('visit_calendar')
                        ->label('View Calendar')
                        ->icon('heroicon-o-calendar')
                        ->url(fn ($record) => route('filament.admin.resources.events.calendar', ['record' => $record->id]))
                        ->openUrlInNewTab(),
                ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
            'calendar' => CalendarView::route('/calendar'),
        ];
    }
}
