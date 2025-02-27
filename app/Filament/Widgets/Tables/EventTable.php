<?php

namespace App\Filament\Widgets\Tables;

use App\Enums\RolesEnum;
use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class EventTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()
            )
            ->columns([
                TextColumn::make('title')->label('Titre'),
                TextColumn::make('start_time')->label('Date de début'),
                TextColumn::make('end_time')->label('Date de fin'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Créer des événements')->url('/admin/events/create')
            ]);
    }

    public static function canView(): bool
    {
        $personnelRole = Role::firstOrCreate(['name' => RolesEnum::Staff->value]);
        $proffeseurRole = Role::firstOrCreate(['name' => RolesEnum::Professor->value]);

        return Auth::user()->hasRole($personnelRole) || Auth::user()->hasRole($proffeseurRole);
    }

}
