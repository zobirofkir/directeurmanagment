<?php

namespace App\Filament\Resources\ArchivedDocumentResource\Pages;

use App\Filament\Resources\ArchivedDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArchivedDocument extends EditRecord
{
    protected static string $resource = ArchivedDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
