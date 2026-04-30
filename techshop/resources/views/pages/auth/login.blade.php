<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        <div class="text-center">
            <h1 class="text-3xl font-semibold tracking-tight text-[#0d0d0d] dark:text-white mb-2">{{ __('Welcome back') }}</h1>
            <p class="text-sm text-[#666666] dark:text-zinc-400">{{ __('Log in to your TechShop account') }}</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        @error('social')
            <div class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 text-sm rounded-xl text-center">
                {{ $message }}
            </div>
        @enderror

        <div class="flex flex-col items-stretch gap-8 lg:flex-row">
            {{-- Left column: Login form --}}
            <div class="flex-1 p-8 bg-white dark:bg-zinc-900 border border-black/[0.05] dark:border-white/[0.1] rounded-[24px] shadow-[0_2px_4px_rgba(0,0,0,0.03)]">
                <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
                    @csrf

                    <!-- Email Address -->
                    <flux:input
                        name="email"
                        :label="__('Email address')"
                        :value="old('email')"
                        type="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="email@example.com"
                        class="!bg-zinc-50 dark:!bg-zinc-800/50 !border-zinc-200 dark:!border-zinc-700 !rounded-xl text-[#0d0d0d] dark:text-zinc-100 autofill:shadow-[inset_0_0_0_1000px_#f9fafb] dark:autofill:shadow-[inset_0_0_0_1000px_#18181b]"
                    />

                    <!-- Password -->
                    <div class="flex flex-col gap-2">
                        <flux:input
                            name="password"
                            :label="__('Password')"
                            type="password"
                            required
                            autocomplete="current-password"
                            :placeholder="__('Password')"
                            viewable
                            class="!bg-zinc-50 dark:!bg-zinc-800/50 !border-zinc-200 dark:!border-zinc-700 !rounded-xl text-[#0d0d0d] dark:text-zinc-100 autofill:shadow-[inset_0_0_0_1000px_#f9fafb] dark:autofill:shadow-[inset_0_0_0_1000px_#18181b]"
                        />

                        @if (Route::has('password.request'))
                            <div class="flex justify-end">
                                <flux:link class="text-xs text-[#0fa76e] dark:text-[#18E299] hover:opacity-80 transition-opacity" :href="route('password.request')" wire:navigate>
                                    {{ __('Forgot password?') }}
                                </flux:link>
                            </div>
                        @endif
                    </div>

                    <!-- Remember Me -->
                    <flux:checkbox name="remember" :label="__('Stay logged in')" :checked="old('remember')" color="emerald" class="text-zinc-600 dark:text-zinc-400" />

                    <div class="mt-2">
                        <flux:button type="submit" class="w-full !bg-[#0d0d0d] dark:!bg-white !text-white dark:!text-[#0d0d0d] !rounded-full !py-3 !font-medium hover:!opacity-90 transition-all shadow-sm" data-test="login-button">
                            {{ __('Log in') }}
                        </flux:button>
                    </div>

                    <div class="mt-6 flex flex-col gap-4">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <span class="w-full border-t border-black/[0.05] dark:border-white/[0.1]"></span>
                            </div>
                            <div class="relative flex justify-center text-[10px] uppercase tracking-widest font-bold">
                                <span class="bg-white dark:bg-zinc-900 px-3 text-zinc-400">{{ __('Or continue with') }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <flux:button href="{{ route('social.redirect', ['provider' => 'github']) }}" variant="outline" class="w-full !rounded-full !py-2.5 !border-black/[0.05] dark:!border-white/[0.1] hover:!bg-zinc-50 dark:hover:!bg-zinc-800">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                    <path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"/>
                                    <path d="M9 18c-4.51 2-5-2-7-2"/>
                                </svg>
                                GitHub
                            </flux:button>
                            <flux:button href="{{ route('social.redirect', ['provider' => 'google']) }}" variant="outline" class="w-full !rounded-full !py-2.5 !border-black/[0.05] dark:!border-white/[0.1] hover:!bg-zinc-50 dark:hover:!bg-zinc-800">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" class="mr-2">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 12-4.53z"/>
                                </svg>
                                Google
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Vertical divider (desktop) / Horizontal divider (mobile) --}}
            <div class="flex items-center gap-4 lg:flex-col lg:gap-4">
                <hr class="flex-1 border-black/[0.05] dark:border-white/[0.1] lg:hidden">
                <div class="hidden w-px lg:block bg-black/[0.05] dark:bg-white/[0.1] self-stretch"></div>
                <span class="text-[10px] font-bold tracking-widest uppercase text-zinc-400 dark:text-zinc-500">OR</span>
                <hr class="flex-1 border-black/[0.05] dark:border-white/[0.1] lg:hidden">
                <div class="hidden w-px lg:block bg-black/[0.05] dark:bg-white/[0.1] self-stretch"></div>
            </div>

            {{-- Right column: QR code --}}
            <div class="flex-1 p-8 bg-white dark:bg-zinc-900 border border-black/[0.05] dark:border-white/[0.1] rounded-[24px] shadow-[0_2px_4px_rgba(0,0,0,0.03)] flex flex-col justify-center">
                <livewire:auth.qr-login />
            </div>
        </div>

        @if (Route::has('register'))
            <div class="text-sm text-center text-[#666666] dark:text-zinc-400">
                <span>{{ __('Don\'t have an account?') }}</span>
                <flux:link class="font-bold text-[#0fa76e] dark:text-[#18E299] hover:underline" :href="route('register')" wire:navigate>{{ __('Register here') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts::auth>
