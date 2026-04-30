<section class="pt-16 pb-20 max-w-[1200px] mx-auto px-6">
    <div class="mb-10">
        <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-3">
            {{ __('Checkout') }}
        </div>
        <h1 class="text-3xl md:text-4xl font-semibold tracking-[-0.8px] text-[#0d0d0d] dark:text-zinc-50">
            {{ __('Shipping details') }}
        </h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">

        {{-- Left: Address form --}}
        <div class="lg:col-span-3">
            <form wire:submit="submit" novalidate>

                {{-- Contact section --}}
                <div class="mb-8">
                    <h2 class="text-[11px] font-mono tracking-widest uppercase text-[#666666] dark:text-zinc-400 mb-4">
                        {{ __('Contact') }}
                    </h2>

                    <div>
                        <label for="email" class="block text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200 mb-1.5">
                            {{ __('Email address') }} <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="email"
                            type="email"
                            wire:model.blur="form.email"
                            autocomplete="email"
                            placeholder="you@example.com"
                            @class([
                                'w-full px-4 py-3 rounded-[12px] border text-[15px] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-zinc-50 placeholder-[#aaa] dark:placeholder-zinc-600 transition-colors focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/40',
                                'border-red-400 dark:border-red-500' => $errors->has('form.email'),
                                'border-black/[0.1] dark:border-white/[0.08] focus:border-[#0fa76e] dark:focus:border-[#18E299]' => ! $errors->has('form.email'),
                            ])
                        />
                        @error('form.email')
                            <p class="mt-1.5 text-[13px] text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Shipping address section --}}
                <div class="mb-8">
                    <h2 class="text-[11px] font-mono tracking-widest uppercase text-[#666666] dark:text-zinc-400 mb-4">
                        {{ __('Shipping address') }}
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        {{-- First name --}}
                        <div>
                            <label for="firstName" class="block text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200 mb-1.5">
                                {{ __('First name') }} <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="firstName"
                                type="text"
                                wire:model.blur="form.firstName"
                                autocomplete="given-name"
                                placeholder="{{ __('Jane') }}"
                                @class([
                                    'w-full px-4 py-3 rounded-[12px] border text-[15px] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-zinc-50 placeholder-[#aaa] dark:placeholder-zinc-600 transition-colors focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/40',
                                    'border-red-400 dark:border-red-500' => $errors->has('form.firstName'),
                                    'border-black/[0.1] dark:border-white/[0.08] focus:border-[#0fa76e] dark:focus:border-[#18E299]' => ! $errors->has('form.firstName'),
                                ])
                            />
                            @error('form.firstName')
                                <p class="mt-1.5 text-[13px] text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Last name --}}
                        <div>
                            <label for="lastName" class="block text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200 mb-1.5">
                                {{ __('Last name') }} <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="lastName"
                                type="text"
                                wire:model.blur="form.lastName"
                                autocomplete="family-name"
                                placeholder="{{ __('Doe') }}"
                                @class([
                                    'w-full px-4 py-3 rounded-[12px] border text-[15px] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-zinc-50 placeholder-[#aaa] dark:placeholder-zinc-600 transition-colors focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/40',
                                    'border-red-400 dark:border-red-500' => $errors->has('form.lastName'),
                                    'border-black/[0.1] dark:border-white/[0.08] focus:border-[#0fa76e] dark:focus:border-[#18E299]' => ! $errors->has('form.lastName'),
                                ])
                            />
                            @error('form.lastName')
                                <p class="mt-1.5 text-[13px] text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Address line 1 --}}
                    <div class="mb-4">
                        <label for="addressLine1" class="block text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200 mb-1.5">
                            {{ __('Street address') }} <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="addressLine1"
                            type="text"
                            wire:model.blur="form.addressLine1"
                            autocomplete="address-line1"
                            placeholder="{{ __('Kerkstraat 12') }}"
                            @class([
                                'w-full px-4 py-3 rounded-[12px] border text-[15px] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-zinc-50 placeholder-[#aaa] dark:placeholder-zinc-600 transition-colors focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/40',
                                'border-red-400 dark:border-red-500' => $errors->has('form.addressLine1'),
                                'border-black/[0.1] dark:border-white/[0.08] focus:border-[#0fa76e] dark:focus:border-[#18E299]' => ! $errors->has('form.addressLine1'),
                            ])
                        />
                        @error('form.addressLine1')
                            <p class="mt-1.5 text-[13px] text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Address line 2 --}}
                    <div class="mb-4">
                        <label for="addressLine2" class="block text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200 mb-1.5">
                            {{ __('Apartment, suite, etc.') }}
                            <span class="text-[#aaa] dark:text-zinc-500 font-normal">({{ __('optional') }})</span>
                        </label>
                        <input
                            id="addressLine2"
                            type="text"
                            wire:model.blur="form.addressLine2"
                            autocomplete="address-line2"
                            placeholder="{{ __('Bus 3') }}"
                            class="w-full px-4 py-3 rounded-[12px] border border-black/[0.1] dark:border-white/[0.08] text-[15px] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-zinc-50 placeholder-[#aaa] dark:placeholder-zinc-600 transition-colors focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/40 focus:border-[#0fa76e] dark:focus:border-[#18E299]"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                        {{-- Postcode --}}
                        <div>
                            <label for="postcode" class="block text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200 mb-1.5">
                                {{ __('Postcode') }} <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="postcode"
                                type="text"
                                wire:model.blur="form.postcode"
                                autocomplete="postal-code"
                                placeholder="1000"
                                @class([
                                    'w-full px-4 py-3 rounded-[12px] border text-[15px] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-zinc-50 placeholder-[#aaa] dark:placeholder-zinc-600 transition-colors focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/40',
                                    'border-red-400 dark:border-red-500' => $errors->has('form.postcode'),
                                    'border-black/[0.1] dark:border-white/[0.08] focus:border-[#0fa76e] dark:focus:border-[#18E299]' => ! $errors->has('form.postcode'),
                                ])
                            />
                            @error('form.postcode')
                                <p class="mt-1.5 text-[13px] text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- City --}}
                        <div class="sm:col-span-2">
                            <label for="city" class="block text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200 mb-1.5">
                                {{ __('City') }} <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="city"
                                type="text"
                                wire:model.blur="form.city"
                                autocomplete="address-level2"
                                placeholder="{{ __('Brussels') }}"
                                @class([
                                    'w-full px-4 py-3 rounded-[12px] border text-[15px] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-zinc-50 placeholder-[#aaa] dark:placeholder-zinc-600 transition-colors focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/40',
                                    'border-red-400 dark:border-red-500' => $errors->has('form.city'),
                                    'border-black/[0.1] dark:border-white/[0.08] focus:border-[#0fa76e] dark:focus:border-[#18E299]' => ! $errors->has('form.city'),
                                ])
                            />
                            @error('form.city')
                                <p class="mt-1.5 text-[13px] text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Country --}}
                    <div class="mb-4">
                        <label for="country" class="block text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200 mb-1.5">
                            {{ __('Country') }} <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="country"
                            wire:model.blur="form.country"
                            autocomplete="country-name"
                            @class([
                                'w-full px-4 py-3 rounded-[12px] border text-[15px] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-zinc-50 transition-colors focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/40 appearance-none',
                                'border-red-400 dark:border-red-500' => $errors->has('form.country'),
                                'border-black/[0.1] dark:border-white/[0.08] focus:border-[#0fa76e] dark:focus:border-[#18E299]' => ! $errors->has('form.country'),
                            ])
                        >
                            <option value="">{{ __('Select a country') }}</option>
                            <option value="Belgium">{{ __('Belgium') }}</option>
                            <option value="Netherlands">{{ __('Netherlands') }}</option>
                            <option value="Luxembourg">{{ __('Luxembourg') }}</option>
                            <option value="Germany">{{ __('Germany') }}</option>
                            <option value="France">{{ __('France') }}</option>
                            <option value="United Kingdom">{{ __('United Kingdom') }}</option>
                            <option value="Other">{{ __('Other') }}</option>
                        </select>
                        @error('form.country')
                            <p class="mt-1.5 text-[13px] text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200 mb-1.5">
                            {{ __('Phone number') }}
                            <span class="text-[#aaa] dark:text-zinc-500 font-normal">({{ __('optional') }})</span>
                        </label>
                        <input
                            id="phone"
                            type="tel"
                            wire:model.blur="form.phone"
                            autocomplete="tel"
                            placeholder="+32 470 00 00 00"
                            class="w-full px-4 py-3 rounded-[12px] border border-black/[0.1] dark:border-white/[0.08] text-[15px] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-zinc-50 placeholder-[#aaa] dark:placeholder-zinc-600 transition-colors focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/40 focus:border-[#0fa76e] dark:focus:border-[#18E299]"
                        />
                        @error('form.phone')
                            <p class="mt-1.5 text-[13px] text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-60 cursor-not-allowed"
                        class="w-full bg-[#0d0d0d] dark:bg-white text-white dark:text-[#0d0d0d] px-8 py-4 rounded-full text-[15px] font-medium shadow-md dark:shadow-none hover:opacity-90 transition-all"
                    >
                        <span wire:loading.remove>{{ __('Complete Order') }}</span>
                        <span wire:loading class="inline-flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 22 6.477 22 12h-4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('Processing…') }}
                        </span>
                    </button>

                    <div class="text-center mt-4">
                        <a
                            href="{{ route('cart.index') }}"
                            wire:navigate
                            class="text-[14px] text-[#666666] dark:text-zinc-400 hover:text-[#0d0d0d] dark:hover:text-zinc-100 transition-colors"
                        >
                            &larr; {{ __('Back to cart') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Right: Order summary --}}
        <div class="lg:col-span-2">
            <div class="bg-[#fafafa] dark:bg-zinc-900 border border-black/[0.05] dark:border-white/[0.05] p-6 rounded-[24px] sticky top-32 transition-colors">
                <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-3">
                    {{ __('Order summary') }}
                </div>
                <h2 class="text-xl font-semibold tracking-[-0.4px] mb-6 text-[#0d0d0d] dark:text-zinc-50">
                    {{ __('Your items') }}
                </h2>

                <div class="space-y-4 mb-6">
                    @foreach($this->cartItems as $item)
                        <div wire:key="summary-item-{{ $item->product_id }}" class="flex items-center gap-3">
                            <div class="w-12 h-12 flex-shrink-0 bg-gradient-to-br from-[#fafafa] to-[#f0fdf4] dark:from-zinc-800 dark:to-[#0fa76e]/10 rounded-[10px] overflow-hidden border border-black/[0.05] dark:border-white/[0.05] flex items-center justify-center">
                                @if(isset($item->product->hero_image) && $item->product->hero_image)
                                    <img src="{{ asset('storage/' . $item->product->hero_image) }}" class="object-cover w-full h-full" alt="{{ $item->product->name }}">
                                @else
                                    <span class="font-mono text-[8px] text-gray-300 dark:text-zinc-600 uppercase">IMG</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[14px] font-medium text-[#0d0d0d] dark:text-zinc-50 truncate">
                                    {{ $item->product->name ?? $item->product_name }}
                                </p>
                                <p class="text-[13px] text-[#666666] dark:text-zinc-400">
                                    {{ $item->quantity }} &times; &euro;{{ number_format($item->unit_price, 2) }}
                                </p>
                            </div>
                            <div class="text-[14px] font-semibold text-[#0d0d0d] dark:text-zinc-50 flex-shrink-0">
                                &euro;{{ number_format($item->quantity * $item->unit_price, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-black/[0.08] dark:border-white/[0.08] pt-4 space-y-3">
                    <div class="flex justify-between text-[14px] text-[#666666] dark:text-zinc-400">
                        <span>{{ __('Subtotal') }}</span>
                        <span>&euro;{{ number_format($this->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-[14px] text-[#666666] dark:text-zinc-400">
                        <span>{{ __('Shipping') }}</span>
                        <span class="text-[#0fa76e] dark:text-[#18E299] font-medium">{{ __('Free') }}</span>
                    </div>
                    <div class="border-t border-black/[0.08] dark:border-white/[0.08] pt-3 flex justify-between font-semibold text-[17px] text-[#0d0d0d] dark:text-zinc-50">
                        <span>{{ __('Total') }}</span>
                        <span>&euro;{{ number_format($this->total, 2) }}</span>
                    </div>
                </div>

                <p class="mt-4 text-[12px] text-[#999] dark:text-zinc-500 text-center">
                    {{ __('Payment will be processed after order confirmation.') }}
                </p>
            </div>
        </div>
    </div>
</section>
