{{-- <x-filament::page>
    <div>
        <x-filament::button  wire:click="generateLoginLink" class="filament-button">
            Generate Login Link
        </x-filament::button>

        @if ($loginLink)
            <div class="mt-4">
                <p>Login link generated! It will expire in 10 minutes:</p>
                <a href="{{ $loginLink }}" target="_blank">{{ $loginLink }}</a>
            </div>
        @endif
    </div>
</x-filament::page> --}}

<x-filament::page>
    <div>
        <x-filament::button wire:click="generateLoginLink" class="filament-button">
            Generate Login Link
        </x-filament::button>

        @if ($loginLink)
            <div class="mt-4">
                <p>Login link generated! It will expire in 10 minutes:</p>
                
                <!-- Textarea for displaying the generated link -->
                <textarea 
                    id="loginLinkTextarea" 
                    rows="3" 
                    readonly 
                    class="border rounded-md p-2 w-full mt-2 text-black"
                    style="color: black"
                >{{ $loginLink }}</textarea>

                <!-- Button to copy the link -->
                <x-filament::button 
                    class="mt-2" 
                    onclick="copyToClipboard()">
                    Copy Link
                </x-filament::button>
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
