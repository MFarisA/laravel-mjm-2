<?php

namespace App\Filament\Resources\UseritemResource\Pages;

use App\Filament\Resources\UseritemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUseritems extends ListRecords
{
    protected static string $resource = UseritemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
