<div>
    <div id="reader" style="width: 500px;"></div>
    <div>
        <h3>Scanned Code: {{ $scannedCode }}</h3>
    </div>
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script>
        document.addEventListener('livewire:load', function () {
            const html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            });

            html5QrcodeScanner.render((decodedText, decodedResult) => {
                Livewire.emit('barcodeScanned', decodedText);
            });
        });
    </script>
</div>
