{{-- <div class="flex items-center justify-center">
    {!! $qrCode !!}
</div> --}}

<!-- resources/views/filament/pages/qr-code-login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QR Code Login</title>
</head>
<body>
    <h1>Scan QR Code to Login</h1>

    <!-- Button to trigger QR scan (you can integrate a QR scanner here) -->
    <button id="scan-qr-code">Scan QR Code</button>

    <script>
        document.getElementById('scan-qr-code').addEventListener('click', function() {
            // Simulate token from QR scanner or fetch token from somewhere
            let token = '{{ Cache::get('qr_code_token') }}';
    
            // Send the POST request to the /qr-login route
            fetch('/qr-login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ token: token }) // Pastikan token dikirimkan dalam body
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Invalid token');
                } else {
                    window.location.href = '/dashboard';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
    
</body>
</html>
