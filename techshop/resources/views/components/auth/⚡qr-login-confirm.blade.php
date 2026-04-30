<?php

use Livewire\Component;
use App\Models\QrLoginToken;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public string $token;
    public bool $confirmed = false;
    public bool $invalid = false;

    public function mount(string $token)
    {
        $this->token = $token;
        $tokenRecord = QrLoginToken::where('token', $this->token)->first();

        if (!$tokenRecord || $tokenRecord->expires_at->isPast()) {
            $this->invalid = true;
        } elseif ($tokenRecord->confirmed_at) {
            $this->confirmed = true;
        }
    }

    public function confirmLogin()
    {
        if ($this->invalid) return;

        $tokenRecord = QrLoginToken::where('token', $this->token)->first();
        if ($tokenRecord && !$tokenRecord->expires_at->isPast()) {
            $tokenRecord->update([
                'user_id' => Auth::id(),
                'confirmed_at' => now(),
            ]);
            $this->confirmed = true;
        } else {
            $this->invalid = true;
        }
    }
}; ?>

<x-layouts::auth title="Confirm Login">
    <div class="flex flex-col gap-6 text-center">
        @if($invalid)
            <x-auth-header title="Ongeldige of verlopen QR-code" description="Scan de code opnieuw vanaf de inlogpagina." />
        @elseif($confirmed)
            <x-auth-header title="Login succesvol" description="Je bent nu ingelogd op het andere apparaat. Je kunt dit venster sluiten." />
        @else
            <x-auth-header title="Login Bevestigen" description="Weet je zeker dat je wilt inloggen op het andere apparaat met dit account?" />
            
            <flux:button variant="primary" wire:click="confirmLogin" class="w-full mt-4">
                Inloggen goedkeuren
            </flux:button>
        @endif
    </div>
</x-layouts::auth>