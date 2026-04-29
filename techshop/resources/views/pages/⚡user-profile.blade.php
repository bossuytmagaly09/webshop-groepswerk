<?php

use Livewire\Component;
use App\Concerns\ProfileValidationRules;
use Illuminate\Support\Facades\Auth;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.shop')] #[Title('Mijn Profiel')] class extends Component
{
    use ProfileValidationRules;

    public string $name = '';
    public string $email = '';
    public string $address_line_1 = '';
    public string $address_line_2 = '';
    public string $postcode = '';
    public string $city = '';
    public string $country = '';
    public string $phone = '';

    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->address_line_1 = $user->address_line_1 ?? '';
        $this->address_line_2 = $user->address_line_2 ?? '';
        $this->postcode = $user->postcode ?? '';
        $this->city = $user->city ?? '';
        $this->country = $user->country ?? '';
        $this->phone = $user->phone ?? '';
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated');
    }
};
?>

<div class="max-w-[1200px] mx-auto px-6 py-12">
    <div class="mb-10">
        <div class="text-[11px] font-mono text-[#18E299] tracking-widest uppercase mb-3">
            {{ __('Account — Profile') }}
        </div>
        <h1 class="text-3xl md:text-4xl font-semibold tracking-[-0.8px] text-[#0d0d0d] dark:text-white">
            {{ __('My Profile') }}
        </h1>
        <p class="text-[#666666] dark:text-zinc-400 mt-2 text-sm">
            {{ __('Manage your personal information and delivery address.') }}
        </p>
    </div>

    <div class="rounded-[16px] border border-black/[0.05] dark:border-white/[0.08] bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
        <form wire:submit="updateProfileInformation" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Basic Information --}}
                <div class="space-y-6">
                    <h3 class="text-[11px] font-mono text-[#18E299] tracking-widest uppercase">
                        {{ __('Basic Information') }}
                    </h3>

                    <div class="space-y-4">
                        <flux:input
                            wire:model="name"
                            :label="__('Full Name')"
                            placeholder="John Doe"
                            required
                        />

                        <flux:input
                            wire:model="email"
                            type="email"
                            :label="__('Email Address')"
                            placeholder="john@example.com"
                            required
                        />

                        <flux:input
                            wire:model="phone"
                            :label="__('Phone Number')"
                            placeholder="+32 400 00 00 00"
                        />
                    </div>
                </div>

                {{-- Address Information --}}
                <div class="space-y-6">
                    <h3 class="text-[11px] font-mono text-[#18E299] tracking-widest uppercase">
                        {{ __('Delivery Address') }}
                    </h3>

                    <div class="space-y-4">
                        <flux:input
                            wire:model="address_line_1"
                            :label="__('Address Line 1')"
                            placeholder="Street Name 123"
                        />

                        <flux:input
                            wire:model="address_line_2"
                            :label="__('Address Line 2 (Optional)')"
                            placeholder="Apartment, suite, etc."
                        />

                        <div class="grid grid-cols-2 gap-4">
                            <flux:input
                                wire:model="postcode"
                                :label="__('Postcode')"
                                placeholder="1000"
                            />
                            <flux:input
                                wire:model="city"
                                :label="__('City')"
                                placeholder="Brussels"
                            />
                        </div>

                        <flux:input
                            wire:model="country"
                            :label="__('Country')"
                            placeholder="Belgium"
                        />
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-8 border-t border-black/[0.05] dark:border-white/[0.05] flex items-center justify-end gap-4">
                {{-- Success Message --}}
                <div
                    x-data="{ show: false, timeout: null }"
                    x-on:profile-updated.window="
                        show = true;
                        clearTimeout(timeout);
                        timeout = setTimeout(() => show = false, 5000);
                    "
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-4"
                    class="flex items-center gap-2 text-[#18E299] font-medium text-sm"
                    style="display: none;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ __('Profile updated successfully.') }}
                </div>

                <flux:button type="submit" variant="primary" class="rounded-full px-8">
                    {{ __('Save Changes') }}
                </flux:button>
            </div>
        </form>
    </div>
</div>