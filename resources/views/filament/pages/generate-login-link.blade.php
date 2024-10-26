<x-filament::page>
    <div>
        <x-filament::button wire:click="generateLoginLink" class="filament-button">
            Generate Login Link
        </x-filament::button>

        @if (isset($loginLink))
            <div class="mt-4">
                <p>Login link generated! It will expire in 10 minutes:</p>
                
                <textarea 
                    id="loginLinkTextarea" 
                    rows="3" 
                    readonly 
                    class="border rounded-md p-2 w-full mt-2"
                    style="color: black"
                >{{ $loginLink }}</textarea>

                <x-filament::button 
                    class="mt-2" 
                    onclick="copyToClipboard()">
                    Copy Link
                </x-filament::button>

                <x-filament::button 
                    wire:click="convertLinkToQrCode" 
                    class="mt-2 filament-button">
                    Convert to QR Code
                </x-filament::button>
            </div>
        @endif

        @if (isset($qrCode) && $qrCode)
            <div class="mt-4">
                <p>QR Code:</p>
                <div>{!! $qrCode !!}</div>
            </div>
        @endif
    </div>

    <script>
        function copyToClipboard() {
            const textarea = document.getElementById('loginLinkTextarea');
            textarea.select();
            document.execCommand('copy');
            alert('Login link copied to clipboard!');
        }
    </script>
</x-filament::page>