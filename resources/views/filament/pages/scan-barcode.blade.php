{{-- <x-filament::page>
    <livewire:barcode-scanner />
</x-filament::page>
 --}}

 <!-- codenya yg tk comment, bekerja jon -->
{{-- <x-filament::page>
    <button id="openCamera" class="px-4 py-2 bg-blue-500 text-white rounded">Open Camera</button>
    <video id="video" width="300" height="200" autoplay></video>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#openCamera').click(function() {
                // Send AJAX request to a Filament endpoint or API route
                $.ajax({
                    url: '{{ route("start-camera") }}',
                    method: 'GET',
                    success: function(response) {
                        // If successful, trigger the camera
                        startCamera();
                    },
                    error: function(err) {
                        console.error("Error triggering camera: " + err);
                    }
                });
            });

            function startCamera() {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(function(stream) {
                        let video = document.getElementById('video');
                        video.srcObject = stream;
                    })
                    .catch(function(err) {
                        console.error("Error accessing camera: " + err);
                    });
            }
        });
    </script>
</x-filament::page> --}}

{{-- code dibwh ini juga bekerja, cuman lgi dalam tahap testing scan qr code --}}
{{-- <x-filament::page>
    <x-filament::button size="xl" id="openCamera" style="width: 200px">
        Open Camera
    </x-filament::button>
  <video id="video" width="300" height="200" autoplay></video>
  <p class=" bg-white" id="qrCodeResult"></p>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.2.0/dist/html5-qrcode.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#openCamera').click(function() {
                $.ajax({
                    url: '{{ route("start-camera") }}',
                    method: 'GET',
                    success: function(response) {
                        startCamera();
                    },
                    error: function(err) {
                        console.error("Error triggering camera: " + err);
                    }
                });
            });

            function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
          .then(function (stream) {
            let video = document.getElementById('video');
            video.srcObject = stream;  


            if (typeof Html5QrcodeScanner !== 'undefined') {
              const html5QrcodeScanner = new Html5QrcodeScanner(
                "video", { fps: 10, qrbox: { width: 250, height: 250 } }
              );

              const onScanSuccess = (decodedText, decodedResult) => {
                console.log(`Scanned QR code: ${decodedText}`, decodedResult);

                const resultElement = document.getElementById('qrCodeResult');
                resultElement.textContent = decodedText;

              };


              html5QrcodeScanner.render(onScanSuccess);
            } else {
              console.warn("html5-qrcode library not found");
            }
          })
          .catch(function (err) {
            console.error("Error accessing camera: " + err);
          });
      }
        });
    </script>
</x-filament::page> --}}

<x-filament::page>
  <x-filament::button size="xl" id="openCamera" style="width: 200px">
    Open Camera
  </x-filament::button>
  
  <x-filament::button size="xl" id="closeCamera" style="width: 200px">
    Turn Off Camera
  </x-filament::button>
  
  <video id="video" width="300" height="200" autoplay></video>
  <p id="qrCodeResult" class="bg-white text-black font-bold">Scanned QR Code: </p>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.2.0/dist/html5-qrcode.min.js"></script>
  <script>
    let videoStream = null;

    $(document).ready(function() {
      $('#openCamera').click(function() {
        $.ajax({
          url: '{{ route("start-camera") }}',
          method: 'GET',
          success: function(response) {
            startCamera();
          },
          error: function(err) {
            console.error("Error triggering camera: " + err);
          }
        });
      });

      $('#closeCamera').click(function() {
        stopCamera();
      });

      function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
          .then(function (stream) {
            videoStream = stream;
            let video = document.getElementById('video');
            video.srcObject = stream;

            if (typeof Html5QrcodeScanner !== 'undefined') {
              const html5QrcodeScanner = new Html5QrcodeScanner(
                "video", { fps: 10, qrbox: { width: 250, height: 250 } }
              );

              const onScanSuccess = (decodedText, decodedResult) => {
                console.log(`Scanned QR code: ${decodedText}`, decodedResult);
                document.getElementById('qrCodeResult').textContent = `Scanned QR Code: ${decodedText}`;
              };

              html5QrcodeScanner.render(onScanSuccess);
            } else {
              console.warn("html5-qrcode library not found");
            }
          })
          .catch(function (err) {
            console.error("Error accessing camera: " + err);
          });
      }

      function stopCamera() {
        if (videoStream) {
          let tracks = videoStream.getTracks();
          tracks.forEach(track => track.stop()); // Stop all video tracks
          videoStream = null;
          document.getElementById('video').srcObject = null; // Clear the video element
        }
      }
    });
  </script>
</x-filament::page>


