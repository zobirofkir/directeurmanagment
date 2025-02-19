<?php

namespace App\Filament\Widgets\Tables;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class UserTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
            )
            ->columns([
                TextColumn::make('name')->label('Nom'),
                TextColumn::make('email')->label('E-mail'),
                TextColumn::make('role')->label('Rôles')->badge(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Créer un utilisateur')->url('/admin/users/create')
            ]);
    }

    public static function canView(): bool
    {
        return Auth::user()->role === 'director';
    }
}
