<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\{TextInput, Select, DatePicker, Textarea};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\{TextColumn, DateColumn};

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Gestion des employés';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('Utilisateur'),
                TextInput::make('position')
                    ->required()
                    ->label('Poste'),
                DatePicker::make('hiring_date')
                    ->required()
                    ->label('Date d\'embauche'),
                Textarea::make('career_path')
                    ->label('Parcours professionnel'),
                Select::make('contract_type')
                    ->options([
                        'permanent' => 'Permanent',
                        'temporary' => 'Temporaire',
                        'contractual' => 'Contractuel',
                    ])
                    ->required()
                    ->label('Type de contrat'),
                TextInput::make('degree')
                    ->label('Diplôme'),
            ])->columns(1);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Utilisateur'),
                TextColumn::make('position')->label('Poste'),
                TextColumn::make('hiring_date')->label('Date d\'embauche'),
                TextColumn::make('contract_type')->label('Type de contrat'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('contract_type')
                    ->options([
                        'permanent' => 'Permanent',
                        'temporary' => 'Temporaire',
                        'contractual' => 'Contractuel',
                    ])
                    ->label('Type de contrat'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
