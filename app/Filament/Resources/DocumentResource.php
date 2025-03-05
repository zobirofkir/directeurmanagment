<?php

namespace App\Filament\Resources;

use App\Enums\RolesEnum;
use App\Filament\Resources\DocumentResource\Pages;
use App\Filament\Resources\DocumentResource\RelationManagers;
use App\Mail\DocumentCreated;
use App\Models\Document;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('Utilisateur'),
                TextInput::make('title')->required()->label('Titre du document'),
                FileUpload::make('file_path')
                    ->directory('documents')
                    ->required()
                    ->label('Téléverser le fichier'),
                Select::make('category')
                    ->options([
                        'contract' => 'Contrat',
                        'certificate' => 'Certificat',
                        'leave' => 'Congé',
                        'other' => 'Autre',
                    ])
                    ->required()
                    ->label('Catégorie'),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(Document::query()->where('archived', false))
        ->columns([
                TextColumn::make('user.name')->label('Utilisateur'),
                TextColumn::make('title')->label('Titre'),
                TextColumn::make('category')->label('Catégorie'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Télécharger')
                    ->url(fn (Document $record) => Storage::url($record->file_path))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('sign')
                    ->label('Signer')
                    ->url(fn (Document $record) => route('document.sign', $record))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('send_email')
                    ->label('Envoyer Email')
                    ->color('success')
                    ->action(function (Document $record) {
                        $users = User::role(RolesEnum::Director->value)->orWhere('role', RolesEnum::Secretary->value)->orWhere('role', RolesEnum::SecretaryGeneral->value)->get();

                        foreach ($users as $user) {
                            Mail::to($user->email)->send(new DocumentCreated($record));
                        }
                        Notification::make()
                        ->title('Email Sent Successfully')
                        ->success()
                        ->send();
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('view_archived')
                    ->label('Archives')
                    ->url('/admin/archived-documents')
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }

    protected function afterCreate(): void
    {
        // Emit event for calendar refresh
        $this->dispatch('document-created');
    }
}
