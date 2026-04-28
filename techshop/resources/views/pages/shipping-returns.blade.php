<x-layouts::shop :title="__('Shipping & Returns')">
    <section class="pt-16 pb-24 max-w-[1200px] mx-auto px-6">

        {{-- Page header --}}
        <div class="mb-14">
            <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-3">
                Shipping &amp; Returns
            </div>
            <h1 class="text-4xl md:text-[52px] font-semibold tracking-[-1px] leading-[1.1] mb-5 text-[#0d0d0d] dark:text-zinc-50">
                Delivery &amp; Returns Policy
            </h1>
            <p class="text-[16px] text-[#666666] dark:text-zinc-400 leading-relaxed max-w-xl">
                Simple, transparent policies — no surprises.
            </p>
        </div>

        {{-- Content + sidebar --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16 items-start">

            {{-- Main: policy content --}}
            <div class="lg:col-span-2 space-y-16">

                {{-- Section 1: Shipping --}}
                <div id="shipping" class="scroll-mt-28">
                    <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-6">
                        Shipping
                    </div>

                    <div class="border border-black/[0.06] dark:border-white/[0.06] rounded-[16px] overflow-hidden mb-8">
                        <table class="w-full text-[14px]">
                            <thead class="bg-[#fafafa] dark:bg-zinc-900 border-b border-black/[0.06] dark:border-white/[0.06]">
                                <tr>
                                    <th class="px-5 py-3.5 text-left text-[11px] font-mono tracking-widest uppercase text-[#666666] dark:text-zinc-400 font-normal">Method</th>
                                    <th class="px-5 py-3.5 text-left text-[11px] font-mono tracking-widest uppercase text-[#666666] dark:text-zinc-400 font-normal">Delivery time</th>
                                    <th class="px-5 py-3.5 text-left text-[11px] font-mono tracking-widest uppercase text-[#666666] dark:text-zinc-400 font-normal">Cost</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/[0.05] dark:divide-white/[0.05]">
                                <tr class="bg-white dark:bg-zinc-950">
                                    <td class="px-5 py-4 font-medium text-[#0d0d0d] dark:text-zinc-50">Standard (EU)</td>
                                    <td class="px-5 py-4 text-[#666666] dark:text-zinc-400">3–5 business days</td>
                                    <td class="px-5 py-4 text-[#0fa76e] dark:text-[#18E299] font-medium">Free over €50</td>
                                </tr>
                                <tr class="bg-white dark:bg-zinc-950">
                                    <td class="px-5 py-4 font-medium text-[#0d0d0d] dark:text-zinc-50">Express (EU)</td>
                                    <td class="px-5 py-4 text-[#666666] dark:text-zinc-400">1–2 business days</td>
                                    <td class="px-5 py-4 font-medium text-[#0d0d0d] dark:text-zinc-200">€9.95</td>
                                </tr>
                                <tr class="bg-white dark:bg-zinc-950">
                                    <td class="px-5 py-4 font-medium text-[#0d0d0d] dark:text-zinc-50">International</td>
                                    <td class="px-5 py-4 text-[#666666] dark:text-zinc-400">Currently unavailable</td>
                                    <td class="px-5 py-4 text-[#666666] dark:text-zinc-400">—</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="space-y-3 text-[15px] text-[#666666] dark:text-zinc-400 leading-relaxed">
                        <p>Orders placed before <span class="font-medium text-[#0d0d0d] dark:text-zinc-200">2:00pm CET</span> on business days are processed the same day.</p>
                        <p>Once your order ships, you'll receive a tracking number by email so you can follow your parcel every step of the way.</p>
                        <p>Deliveries are made on business days only (Monday–Friday). Weekend delivery is not currently available.</p>
                    </div>
                </div>

                {{-- Section 2: Returns --}}
                <div id="returns" class="scroll-mt-28">
                    <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-6">
                        Returns
                    </div>

                    @php
                        $returnSteps = [
                            [
                                'title' => '30-day return window',
                                'body'  => 'Return items within 30 days of delivery for a full refund, no questions asked.',
                            ],
                            [
                                'title' => 'Condition',
                                'body'  => 'Items must be unused, undamaged, and returned in their original packaging with all accessories included.',
                            ],
                            [
                                'title' => 'How to return',
                                'body'  => 'Contact us at returns@techshop.com with your order number. We\'ll send a prepaid return label within 1–2 business days.',
                            ],
                            [
                                'title' => 'Refund timeline',
                                'body'  => 'Refunds are processed within 5–7 business days after we receive and inspect your return. The amount is credited back to your original payment method.',
                            ],
                        ];
                    @endphp

                    <div class="space-y-6">
                        @foreach($returnSteps as $i => $step)
                            <div class="flex gap-5">
                                <div class="shrink-0 w-8 h-8 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] text-[12px] font-bold font-mono flex items-center justify-center">
                                    {{ $i + 1 }}
                                </div>
                                <div class="pt-1">
                                    <div class="text-[15px] font-medium text-[#0d0d0d] dark:text-zinc-50 mb-1">{{ $step['title'] }}</div>
                                    <div class="text-[15px] text-[#666666] dark:text-zinc-400 leading-relaxed">{{ $step['body'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Section 3: Damages & Issues --}}
                <div class="p-7 bg-[#fafafa] dark:bg-zinc-900 rounded-[20px] border border-black/[0.05] dark:border-white/[0.05]">
                    <div class="text-[16px] font-semibold text-[#0d0d0d] dark:text-zinc-50 mb-3">
                        Something went wrong?
                    </div>
                    <p class="text-[15px] text-[#666666] dark:text-zinc-400 leading-relaxed mb-5">
                        If your item arrived damaged, defective, or isn't what you ordered, please contact us within 48 hours of delivery. Include a photo of the issue and your order number — we'll sort it out quickly.
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

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-4">

                    {{-- Key facts at a glance --}}
                    <div class="p-5 bg-[#fafafa] dark:bg-zinc-900 rounded-[20px] border border-black/[0.05] dark:border-white/[0.05]">
                        <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-4">
                            Key facts
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] shrink-0 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>
                                </span>
                                <div>
                                    <div class="text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-100">Free standard shipping</div>
                                    <div class="text-[12px] text-[#666666] dark:text-zinc-400">On orders over €50</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] shrink-0 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3z"/></svg>
                                </span>
                                <div>
                                    <div class="text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-100">Express delivery</div>
                                    <div class="text-[12px] text-[#666666] dark:text-zinc-400">1–2 days for €9.95</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] shrink-0 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11"/></svg>
                                </span>
                                <div>
                                    <div class="text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-100">30-day returns</div>
                                    <div class="text-[12px] text-[#666666] dark:text-zinc-400">Unused items, full refund</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] shrink-0 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><path d="M2 10h20"/></svg>
                                </span>
                                <div>
                                    <div class="text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-100">Refunds in 5–7 days</div>
                                    <div class="text-[12px] text-[#666666] dark:text-zinc-400">Back to your original payment</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] shrink-0 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                                </span>
                                <div>
                                    <div class="text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-100">2-year warranty</div>
                                    <div class="text-[12px] text-[#666666] dark:text-zinc-400">On every product</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Questions CTA --}}
                    <div class="p-5 bg-[#fafafa] dark:bg-zinc-900 rounded-[20px] border border-black/[0.05] dark:border-white/[0.05]">
                        <div class="text-[15px] font-semibold text-[#0d0d0d] dark:text-zinc-50 mb-2">Have a question?</div>
                        <p class="text-[13px] text-[#666666] dark:text-zinc-400 leading-relaxed mb-4">
                            Something not covered here? Our team is ready to help.
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
