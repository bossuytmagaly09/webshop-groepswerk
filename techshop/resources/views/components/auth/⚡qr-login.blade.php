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
            new RendererStyle(200),
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

<div wire:poll.2s="pollForLogin" class="flex flex-col items-center justify-center h-full p-2">
    <div class="flex items-center gap-3 mb-4">
        <div class="p-2 bg-[#d4fae8] rounded-lg shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#0fa76e]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75H16.5v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75H16.5v-.75z" />
            </svg>
        </div>
        <div>
            <h3 class="text-sm font-bold text-[#0d0d0d] dark:text-white">QR-code Login</h3>
            <p class="text-[10px] text-[#0fa76e] dark:text-[#18E299] font-bold uppercase tracking-wider">Premium Access</p>
        </div>
    </div>
    
    <p class="mb-5 text-xs leading-relaxed text-center text-[#666666] dark:text-zinc-400">
        Scan with your smartphone to log in directly to the TechShop platform.
    </p>
    
    <div class="p-3 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] ring-1 ring-black/[0.05]">
        {!! $qrCodeSvg !!}
    </div>
    
    <div class="flex items-center gap-2 mt-5">
        <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
        <p class="text-[11px] font-medium text-zinc-500 dark:text-zinc-400">Expires in 2 min.</p>
    </div>
</div>