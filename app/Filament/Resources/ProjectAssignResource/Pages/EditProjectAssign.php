<?php

namespace App\Filament\Resources\ProjectAssignResource\Pages;

use App\Filament\Resources\ProjectAssignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectAssign extends EditRecord
{
    protected static string $resource = ProjectAssignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
