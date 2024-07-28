<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectOverView extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Project', Project::all()->count()),
        ];
    }
}
