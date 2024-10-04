<?php

namespace App\Livewire;

use Livewire\Component;

class BarcodeScanner extends Component
{
    public $scannedCode;

    protected $listeners = ['barcodeScanned'];

    public function barcodeScanned($code)
    {
        $this->scannedCode = $code;
        // Add your additional logic here, e.g., saving to a database.
    }
    
    public function render()
    {
        return view('livewire.barcode-scanner');
    }
}
