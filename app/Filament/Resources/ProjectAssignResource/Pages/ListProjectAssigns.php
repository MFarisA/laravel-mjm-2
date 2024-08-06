<?php

namespace App\Filament\Resources\ProjectAssignResource\Pages;

use App\Filament\Resources\ProjectAssignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectAssigns extends ListRecords
{
    protected static string $resource = ProjectAssignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
