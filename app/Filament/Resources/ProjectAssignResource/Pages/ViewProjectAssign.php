<?php

namespace App\Filament\Resources\ProjectAssignResource\Pages;

use App\Filament\Resources\ProjectAssignResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectAssign extends ViewRecord
{
    protected static string $resource = ProjectAssignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
