<x-filament::page>
    <div>
        <button wire:click="generateLoginLink" class="filament-button">
            Generate Login Link
        </button>

        @if ($loginLink)
            <div class="mt-4">
                <p>Login link generated! It will expire in 10 minutes:</p>
                <a href="{{ $loginLink }}" target="_blank">{{ $loginLink }}</a>
            </div>
        @endif
    </div>
</x-filament::page>
