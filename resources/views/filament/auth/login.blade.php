{{-- resources/views/vendor/filament/auth/login.blade.php --}}

@extends('filament::auth.layout')

@section('content')
    <div class="flex flex-col items-center">
        <h1 class="mb-4 text-xl">Login</h1>
        
        {{-- Form Login --}}
        {!! $this->form !!}

        {{-- Tempat untuk menampilkan QR Code --}}
        <div id="qr-code-container" style="display:none;">
            <h3 class="mt-4">Scan QR Code</h3>
            <div id="qr-code-output">{!! session('qr_code') ?? '' !!}</div>
            <button id="scan-qr-code" class="mt-2 btn btn-primary">Scan QR Code</button>
        </div>

        <script>
            // Cek apakah ada QR Code di session
            @if(session('qr_code'))
                document.getElementById('qr-code-container').style.display = 'block';
            @endif

            document.getElementById('scan-qr-code').addEventListener('click', function() {
                // Simulasi token dari scanner QR atau ambil token dari cache
                let token = '{{ Cache::get('qr_code_token') }}';

                // Kirim request POST ke route /qr-login
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
                        window.location.href = '/dashboard'; // Redirect jika berhasil
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        </script>
    </div>
@endsection
