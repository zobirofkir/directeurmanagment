<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Project;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Titre'),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->label('Description'),
                Select::make('status')
                    ->options([
                        'To Do' => 'À faire',
                        'In Progress' => 'En cours',
                        'Done' => 'Terminé',
                    ])
                    ->required()
                    ->label('Statut'),
                DatePicker::make('deadline')
                    ->required()
                    ->label('Date limite'),
                TextInput::make('meeting_link')
                    ->maxLength(255)
                    ->label('Lien de réunion'),
                Select::make('project_id')
                    ->options(Project::where('user_id', Auth::user()->id)->pluck('name', 'id'))
                    ->required()
                    ->label('Projet'),

                Hidden::make('user_id')
                    ->default(Auth::user()->id),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(Task::query()->where('user_id', '=', Auth::id()))

            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->label('Titre'),
                TextColumn::make('status')->badge('success')
                    ->label('Statut'),
                TextColumn::make('deadline')
                    ->date()
                    ->sortable()
                    ->label('Date limite'),
                TextColumn::make('meeting_link')
                    ->searchable()
                    ->label('Lien de réunion'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
