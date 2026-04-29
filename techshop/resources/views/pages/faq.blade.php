<x-layouts::shop :title="__('FAQ')">
    <section class="pt-16 pb-24 max-w-[1200px] mx-auto px-6">

        {{-- Page header --}}
        <div class="mb-14">
            <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-3">
                Support
            </div>
            <h1 class="text-4xl md:text-[52px] font-semibold tracking-[-1px] leading-[1.1] mb-5 text-[#0d0d0d] dark:text-zinc-50">
                Frequently Asked Questions
            </h1>
            <p class="text-[16px] text-[#666666] dark:text-zinc-400 leading-relaxed max-w-xl">
                Find answers to the most common questions about orders, shipping, and products.
            </p>
        </div>

        {{-- Content + sidebar --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16">

            {{-- Main: accordion categories --}}
            <div class="lg:col-span-2 space-y-14">

                {{-- Category 1: Orders & Payment --}}
                <div id="orders" class="scroll-mt-28">
                    <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-5">
                        Orders &amp; Payment
                    </div>
                    <div
                        x-data="{ active: null }"
                        class="border-t border-black/[0.06] dark:border-white/[0.06] divide-y divide-black/[0.06] dark:divide-white/[0.06]"
                    >
                        @php
                            $ordersItems = [
                                [
                                    'q' => 'How do I place an order?',
                                    'a' => 'Browse our products, add items to your cart, and proceed to checkout. We accept all major credit cards via Stripe\'s secure payment system.',
                                ],
                                [
                                    'q' => 'Can I change or cancel my order after placing it?',
                                    'a' => 'Orders can be modified or cancelled within 1 hour of placement. After that, the order enters processing and changes may not be possible. Contact us immediately if you need to make changes.',
                                ],
                                [
                                    'q' => 'What payment methods do you accept?',
                                    'a' => 'We accept Visa, Mastercard, American Express, and other major credit cards. All payments are processed securely via Stripe.',
                                ],
                                [
                                    'q' => 'How do I know my order was confirmed?',
                                    'a' => 'You\'ll receive a confirmation email with your order number immediately after placing your order.',
                                ],
                            ];
                        @endphp

                        @foreach($ordersItems as $i => $item)
                            <div>
                                <button
                                    @click="active = active === {{ $i }} ? null : {{ $i }}"
                                    class="w-full flex items-center justify-between py-5 gap-6 text-left group"
                                >
                                    <span class="text-[16px] font-medium text-[#0d0d0d] dark:text-zinc-50 group-hover:text-[#0fa76e] dark:group-hover:text-[#18E299] transition-colors">
                                        {{ $item['q'] }}
                                    </span>
                                    <span
                                        class="shrink-0 inline-flex items-center justify-center w-6 h-6 text-[#999] dark:text-zinc-500 transition-transform duration-200"
                                        :class="{ 'rotate-45': active === {{ $i }} }"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                    </span>
                                </button>
                                <div
                                    x-show="active === {{ $i }}"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-1"
                                    class="pb-5"
                                >
                                    <p class="text-[15px] text-[#666666] dark:text-zinc-400 leading-relaxed">{{ $item['a'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Category 2: Shipping & Delivery --}}
                <div id="shipping" class="scroll-mt-28">
                    <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-5">
                        Shipping &amp; Delivery
                    </div>
                    <div
                        x-data="{ active: null }"
                        class="border-t border-black/[0.06] dark:border-white/[0.06] divide-y divide-black/[0.06] dark:divide-white/[0.06]"
                    >
                        @php
                            $shippingItems = [
                                [
                                    'q' => 'How long does shipping take?',
                                    'a' => 'Standard shipping within Europe takes 3–5 business days. Express shipping (1–2 days) is available at checkout for an additional fee.',
                                ],
                                [
                                    'q' => 'Do you ship internationally?',
                                    'a' => 'We currently ship to all EU countries. International shipping outside the EU is not available at this time.',
                                ],
                                [
                                    'q' => 'How can I track my order?',
                                    'a' => 'Once your order has shipped, you\'ll receive a tracking number via email to follow your package.',
                                ],
                            ];
                        @endphp

                        @foreach($shippingItems as $i => $item)
                            <div>
                                <button
                                    @click="active = active === {{ $i }} ? null : {{ $i }}"
                                    class="w-full flex items-center justify-between py-5 gap-6 text-left group"
                                >
                                    <span class="text-[16px] font-medium text-[#0d0d0d] dark:text-zinc-50 group-hover:text-[#0fa76e] dark:group-hover:text-[#18E299] transition-colors">
                                        {{ $item['q'] }}
                                    </span>
                                    <span
                                        class="shrink-0 inline-flex items-center justify-center w-6 h-6 text-[#999] dark:text-zinc-500 transition-transform duration-200"
                                        :class="{ 'rotate-45': active === {{ $i }} }"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                    </span>
                                </button>
                                <div
                                    x-show="active === {{ $i }}"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-1"
                                    class="pb-5"
                                >
                                    <p class="text-[15px] text-[#666666] dark:text-zinc-400 leading-relaxed">{{ $item['a'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Category 3: Returns & Products --}}
                <div id="returns" class="scroll-mt-28">
                    <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-5">
                        Returns &amp; Products
                    </div>
                    <div
                        x-data="{ active: null }"
                        class="border-t border-black/[0.06] dark:border-white/[0.06] divide-y divide-black/[0.06] dark:divide-white/[0.06]"
                    >
                        @php
                            $returnsItems = [
                                [
                                    'q' => 'What is your return policy?',
                                    'a' => 'We accept returns within 30 days of delivery. Items must be unused and in their original packaging. See our Shipping & Returns page for details.',
                                ],
                                [
                                    'q' => 'My item arrived damaged — what do I do?',
                                    'a' => 'We\'re sorry to hear that. Please contact us within 48 hours of delivery with a photo of the damaged item. We\'ll make it right.',
                                ],
                                [
                                    'q' => 'Are your products covered by a warranty?',
                                    'a' => 'All products come with a 2-year manufacturer\'s warranty covering manufacturing defects.',
                                ],
                            ];
                        @endphp

                        @foreach($returnsItems as $i => $item)
                            <div>
                                <button
                                    @click="active = active === {{ $i }} ? null : {{ $i }}"
                                    class="w-full flex items-center justify-between py-5 gap-6 text-left group"
                                >
                                    <span class="text-[16px] font-medium text-[#0d0d0d] dark:text-zinc-50 group-hover:text-[#0fa76e] dark:group-hover:text-[#18E299] transition-colors">
                                        {{ $item['q'] }}
                                    </span>
                                    <span
                                        class="shrink-0 inline-flex items-center justify-center w-6 h-6 text-[#999] dark:text-zinc-500 transition-transform duration-200"
                                        :class="{ 'rotate-45': active === {{ $i }} }"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                    </span>
                                </button>
                                <div
                                    x-show="active === {{ $i }}"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-1"
                                    class="pb-5"
                                >
                                    <p class="text-[15px] text-[#666666] dark:text-zinc-400 leading-relaxed">{{ $item['a'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-4">

                    {{-- Browse by topic --}}
                    <div class="p-5 bg-[#fafafa] dark:bg-zinc-900 rounded-[20px] border border-black/[0.05] dark:border-white/[0.05]">
                        <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-4">
                            Browse by topic
                        </div>
                        <ul class="space-y-1" x-data>
                            <li>
                                <a href="#orders" @click.prevent="document.getElementById('orders').scrollIntoView({behavior: 'smooth'})" class="flex items-center gap-2 py-1.5 text-[14px] text-[#0d0d0d] dark:text-zinc-200 hover:text-[#0fa76e] dark:hover:text-[#18E299] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#0fa76e] dark:text-[#18E299]"><rect width="20" height="14" x="2" y="5" rx="2"/><path d="M2 10h20"/></svg>
                                    Orders &amp; Payment
                                </a>
                            </li>
                            <li>
                                <a href="#shipping" @click.prevent="document.getElementById('shipping').scrollIntoView({behavior: 'smooth'})" class="flex items-center gap-2 py-1.5 text-[14px] text-[#0d0d0d] dark:text-zinc-200 hover:text-[#0fa76e] dark:hover:text-[#18E299] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#0fa76e] dark:text-[#18E299]"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>
                                    Shipping &amp; Delivery
                                </a>
                            </li>
                            <li>
                                <a href="#returns" @click.prevent="document.getElementById('returns').scrollIntoView({behavior: 'smooth'})" class="flex items-center gap-2 py-1.5 text-[14px] text-[#0d0d0d] dark:text-zinc-200 hover:text-[#0fa76e] dark:hover:text-[#18E299] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#0fa76e] dark:text-[#18E299]"><path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11"/></svg>
                                    Returns &amp; Products
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Good to know --}}
                    <div class="p-5 bg-[#fafafa] dark:bg-zinc-900 rounded-[20px] border border-black/[0.05] dark:border-white/[0.05]">
                        <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-4">
                            Good to know
                        </div>
                        <div class="space-y-3.5">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>
                                </span>
                                <span class="text-[13px] text-[#0d0d0d] dark:text-zinc-200">Free shipping over €50</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11"/></svg>
                                </span>
                                <span class="text-[13px] text-[#0d0d0d] dark:text-zinc-200">30-day returns</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                                </span>
                                <span class="text-[13px] text-[#0d0d0d] dark:text-zinc-200">2-year warranty on all products</span>
                            </div>
                        </div>
                    </div>

                    {{-- Still need help --}}
                    <div class="p-5 bg-[#fafafa] dark:bg-zinc-900 rounded-[20px] border border-black/[0.05] dark:border-white/[0.05]">
                        <div class="text-[15px] font-semibold text-[#0d0d0d] dark:text-zinc-50 mb-2">Still need help?</div>
                        <p class="text-[13px] text-[#666666] dark:text-zinc-400 leading-relaxed mb-4">
                            Can't find what you're looking for? Our team is happy to help.
                        </p>
                        <a
                            href="{{ route('contact') }}"
                            wire:navigate
                            class="inline-flex items-center gap-1.5 text-[14px] font-medium text-[#0fa76e] dark:text-[#18E299] hover:underline underline-offset-2"
                        >
                            Contact us <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </section>
</x-layouts::shop>