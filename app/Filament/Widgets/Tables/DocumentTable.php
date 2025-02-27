<?php

namespace App\Filament\Widgets\Tables;

use App\Enums\RolesEnum;
use App\Models\Document;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class DocumentTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Document::query()
            )
            ->columns([
                TextColumn::make('user.name')->label('Utilisateur'),
                TextColumn::make('title')->label('Titre'),
                TextColumn::make('category')->label('Catégorie'),
            ])
            ->actions([
                Tables\Actions\Action::make('sign')
                    ->label('Signer')
                    ->url(fn (Document $record) => route('document.sign', $record))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('download')
                    ->label('Télécharger')
                    ->url(fn (Document $record) => Storage::url($record->file_path))
                    ->openUrlInNewTab(),

            ])
            ->headerActions([
                //
            ]);
    }

    public static function canView(): bool
    {
        $directorRole = Role::firstOrCreate(['name' => RolesEnum::Director->value]);
        $secretaryRole = Role::firstOrCreate(['name' => RolesEnum::Secretary->value]);
        $secretaryGenerale = Role::firstOrCreate(['name' => RolesEnum::SecretaryGeneral->value]);

        return Auth::user()->hasRole($directorRole) || Auth::user()->hasRole($secretaryRole) || Auth::user()->hasRole($secretaryGenerale);
    }
}
