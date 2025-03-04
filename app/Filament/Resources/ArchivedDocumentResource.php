<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArchivedDocumentResource\Pages;
use App\Filament\Resources\ArchivedDocumentResource\RelationManagers;
use App\Models\ArchivedDocument;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class ArchivedDocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(Document::query()->where('archived', true))
            ->columns([
                TextColumn::make('title')->label('Titre'),
                TextColumn::make('user.name')->label('Utilisateur'),
            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('download')
                    ->label('Télécharger')
                    ->url(fn (Document $record) => Storage::url('archived_documents/' . basename($record->file_path)))
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
            'index' => Pages\ListArchivedDocuments::route('/'),
        ];
    }
}
