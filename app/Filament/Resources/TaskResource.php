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
                    ->label('Title'),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->label('Description'),
                Select::make('status')
                    ->options([
                        'To Do' => 'To Do',
                        'In Progress' => 'In Progress',
                        'Done' => 'Done',
                    ])
                    ->required()
                    ->label('Status'),
                DatePicker::make('deadline')
                    ->required()
                    ->label('Deadline'),
                TextInput::make('meeting_link')
                    ->maxLength(255)
                    ->label('Meeting Link'),
                Select::make('project_id')
                    ->options(Project::where('user_id', Auth::id())->pluck('name', 'id'))
                    ->required()
                    ->label('Project'),
                Hidden::make('user_id')
                    ->default(Auth::id()),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Task::query()->where('user_id', Auth::id()))
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->label('Title'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'To Do' => 'gray',
                        'In Progress' => 'warning',
                        'Done' => 'success',
                    })
                    ->label('Status'),
                TextColumn::make('deadline')
                    ->date()
                    ->sortable()
                    ->label('Deadline'),
                TextColumn::make('project.name')
                    ->label('Project'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'To Do' => 'To Do',
                        'In Progress' => 'In Progress',
                        'Done' => 'Done',
                    ]),
                Tables\Filters\SelectFilter::make('project_id')
                    ->options(Project::where('user_id', Auth::id())->pluck('name', 'id'))
                    ->label('Project'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('open')
                    ->label('Open')
                    ->url(fn (Task $record) => url($record->meeting_link))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make()
                    ->url(fn (Task $record) => TaskResource::getUrl('view', ['record' => $record])),
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
            // No additional relations since we're limited to the fillable fields
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
            'view' => Pages\ViewTask::route('/{record}'),
        ];
    }
}
