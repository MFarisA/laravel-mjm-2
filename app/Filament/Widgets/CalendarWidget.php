<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\Widget;
use App\Filament\Resources\EventResource;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Event::class;
 
    public function fetchEvents(array $fetchInfo): array
    {
        return Event::where('start', '>=', $fetchInfo['start'])
            ->where('end', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (Event $task) {
                return [
                    'id'    => $task->id,
                    'title' => $task->title,
                    'deskripsi' => $task->deskripsi,
                    'start' => $task->start,
                    'end'   => $task->end,
                ];
            })
            ->toArray();
    }
 
    public function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('title'),
            Forms\Components\TextInput::make('deskripsi'),
            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\DateTimePicker::make('start'),
                    
                    Forms\Components\DateTimePicker::make('end'),
                ]),
        ];
    }

    public static function canView(): bool
    {
        return false;
    }
}
