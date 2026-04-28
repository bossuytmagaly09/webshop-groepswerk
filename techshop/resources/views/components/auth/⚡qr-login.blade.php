<?php

use Livewire\Component;
use App\Models\QrLoginToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

new class extends Component {
    public string $token;
    public string $qrCodeSvg;

    public function mount()
    {
        $this->token = Str::random(64);
        
        QrLoginToken::create([
            'token' => $this->token,
            'expires_at' => now()->addMinutes(2),
        ]);

        $ip = request()->getHost() === 'localhost' || request()->getHost() === '127.0.0.1'
            ? gethostbyname(gethostname())
            : request()->getHost();

        $port = request()->getPort();
        $portStr = in_array($port, [80, 443]) ? '' : ':' . $port;
        $scheme = request()->getScheme();
        
        $url = "{$scheme}://{$ip}{$portStr}/qr-login/{$this->token}";

        $renderer = new ImageRenderer(
            new RendererStyle(250),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $this->qrCodeSvg = $writer->writeString($url);
    }

    public function pollForLogin()
    {
        $tokenRecord = QrLoginToken::where('token', $this->token)->first();

        if ($tokenRecord && $tokenRecord->user_id && $tokenRecord->confirmed_at) {
            Auth::loginUsingId($tokenRecord->user_id);
            $this->redirectIntended(route('dashboard'));
        }
    }
}; ?>

<div wire:poll.2s="pollForLogin" class="flex flex-col items-center justify-center p-6 mt-6 border border-zinc-200 rounded-xl dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/50">
    <h3 class="mb-2 text-lg font-medium text-zinc-900 dark:text-white">QR-code Login</h3>
    <p class="mb-4 text-sm text-center text-zinc-500 dark:text-zinc-400">
        Scan deze code met je smartphone om direct in te loggen zonder wachtwoord.
    </p>
    
    <div class="p-2 bg-white rounded-lg shadow-sm">
        {!! $qrCodeSvg !!}
    </div>
    
    <p class="mt-4 text-xs text-zinc-400">De code verloopt in 2 minuten.</p>
</div>