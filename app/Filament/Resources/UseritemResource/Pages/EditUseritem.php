<?php

namespace App\Filament\Resources\UseritemResource\Pages;

use App\Filament\Resources\UseritemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUseritem extends EditRecord
{
    protected static string $resource = UseritemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
