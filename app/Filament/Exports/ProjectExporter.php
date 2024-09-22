<?php

namespace App\Filament\Exports;

use App\Models\Project;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ProjectExporter extends Exporter
{
    protected static ?string $model = Project::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('perusahaan'),
            ExportColumn::make('order'),
            ExportColumn::make('deskripsi'),
            ExportColumn::make('supervisor'),
            ExportColumn::make('quantity'),
            ExportColumn::make('deadline'),
            ExportColumn::make('status'),
            ExportColumn::make('picture'),
            // ExportColumn::make('item_titles'),
        ];
    }

    public function getData(): array
    {
        return Project::with('items')->get()->map(function (Project $project) {
            return [
                'id' => $project->id,
                'perusahaan' => $project->perusahaan,
                'order' => $project->order,
                'deskripsi' => $project->deskripsi,
                'supervisor' => $project->supervisor,
                'quantity' => $project->quantity,
                'deadline' => $project->deadline,
                'status' => $project->status,
                'picture' => $project->picture,
                // 'item_titles' => $project->items->pluck('title')->implode(', '), 
            ];
        })->toArray();
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your project export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
