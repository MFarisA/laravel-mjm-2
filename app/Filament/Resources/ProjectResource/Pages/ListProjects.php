<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action; // Correct namespace for actions
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectExport;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            // Action::make('export')
            //     ->label('Export')
            //     ->icon('bi-filetype-xlsx')
            //     ->color('warning')
            //     ->requiresConfirmation()
            //     ->action(function () {
            //         return Excel::download(new ProjectExport, 'Project.xlsx');
            //     }),
        ];
    }

}
