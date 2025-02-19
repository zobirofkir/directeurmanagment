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
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('role')->label('Roles')->badge(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Create User')->url('/admin/users/create'),
                Tables\Actions\ViewAction::make()->label('List User'),
            ]);
    }

    public static function canView(): bool
    {
        // Check if the authenticated user has the 'director' role
        return Auth::user()->role === 'director';
    }
}
