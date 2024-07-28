<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ScanBarcode extends Page
{
    protected static ?string $navigationIcon = 'heroicon-s-camera';

    protected static string $view = 'filament.pages.scan-barcode';
}
