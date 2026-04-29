<x-layouts::shop :title="__('Contact')">
    <section class="pt-16 pb-24 max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 lg:gap-16 items-start">

            {{-- Right: Intro & contact info (shown first on mobile) --}}
            <div class="lg:col-span-2 lg:order-last">
                <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-3">
                    Get in touch
                </div>
                <h1 class="text-3xl md:text-4xl font-semibold tracking-[-0.8px] mb-6 text-[#0d0d0d] dark:text-zinc-50">
                    We'd love to hear from you.
                </h1>

                <p class="text-[15px] text-[#666666] dark:text-zinc-400 leading-relaxed mb-3">
                    Have a question about your order, a product, or just want to get in touch? Fill in the form and we'll get back to you within 1–2 business days.
                </p>
                <p class="text-[15px] text-[#666666] dark:text-zinc-400 leading-relaxed mb-10">
                    Prefer email? Reach us directly at
                    <a href="mailto:hello@techshop.com" class="text-[#0fa76e] dark:text-[#18E299] hover:underline underline-offset-2">hello@techshop.com</a>.
                </p>

                <div class="space-y-5 mb-8">
                    {{-- Email --}}
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </span>
                        <div>
                            <div class="text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-100">Email</div>
                            <div class="text-[13px] text-[#666666] dark:text-zinc-400">hello@techshop.com</div>
                        </div>
                    </div>

                    {{-- Live chat --}}
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </span>
                        <div>
                            <div class="text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-100">Live chat</div>
                            <div class="text-[13px] text-[#666666] dark:text-zinc-400">Coming soon</div>
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <div>
                            <div class="text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-100">Location</div>
                            <div class="text-[13px] text-[#666666] dark:text-zinc-400">Europe</div>
                        </div>
                    </div>
                </div>

                <div class="p-5 bg-[#fafafa] dark:bg-zinc-900 rounded-[16px] border border-black/[0.05] dark:border-white/[0.05]">
                    <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-2">Response time</div>
                    <div class="text-[14px] font-medium text-[#0d0d0d] dark:text-zinc-100 mb-0.5">Mon–Fri, 9am–6pm CET</div>
                    <div class="text-[13px] text-[#666666] dark:text-zinc-400">We reply within 1–2 business days.</div>
                </div>
            </div>

            {{-- Left: Contact form (visual left on desktop, second on mobile) --}}
            <div class="lg:col-span-3 lg:order-first">
                <form onsubmit="return false;" novalidate class="space-y-4">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200 mb-1.5">First name</label>
                            <input
                                id="first_name"
                                type="text"
                                name="first_name"
                                autocomplete="given-name"
                                placeholder="Jane"
                                class="w-full px-4 py-3 rounded-[12px] border border-black/[0.1] dark:border-white/[0.08] text-[15px] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-zinc-50 placeholder-[#aaa] dark:placeholder-zinc-600 transition-colors focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/40 focus:border-[#0fa76e] dark:focus:border-[#18E299]"
                            >
                        </div>
                        <div>
                            <label for="last_name" class="block text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200 mb-1.5">Last name</label>
                            <input
                                id="last_name"
                                type="text"
                                name="last_name"
                                autocomplete="family-name"
                                placeholder="Doe"
                                class="w-full px-4 py-3 rounded-[12px] border border-black/[0.1] dark:border-white/[0.08] text-[15px] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-zinc-50 placeholder-[#aaa] dark:placeholder-zinc-600 transition-colors focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/40 focus:border-[#0fa76e] dark:focus:border-[#18E299]"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200 mb-1.5">
                            Email address <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            autocomplete="email"
                            placeholder="you@example.com"
                            required
                            class="w-full px-4 py-3 rounded-[12px] border border-black/[0.1] dark:border-white/[0.08] text-[15px] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-zinc-50 placeholder-[#aaa] dark:placeholder-zinc-600 transition-colors focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/40 focus:border-[#0fa76e] dark:focus:border-[#18E299]"
                        >
                    </div>

                    <div>
                        <label for="subject" class="block text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200 mb-1.5">Subject</label>
                        <div class="relative">
                            <select
                                id="subject"
                                name="subject"
                                class="w-full px-4 py-3 rounded-[12px] border border-black/[0.1] dark:border-white/[0.08] text-[15px] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-zinc-50 transition-colors focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/40 focus:border-[#0fa76e] dark:focus:border-[#18E299] appearance-none pr-10"
                            >
                                <option value="">Select a topic</option>
                                <option value="order">Order inquiry</option>
                                <option value="product">Product question</option>
                                <option value="returns">Returns &amp; refunds</option>
                                <option value="other">Other</option>
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[#aaa] dark:text-zinc-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </span>
                        </div>
                    </div>

                    <div>
                        <label for="message" class="block text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200 mb-1.5">
                            Message <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="message"
                            name="message"
                            rows="7"
                            placeholder="How can we help you?"
                            required
                            class="w-full px-4 py-3 rounded-[12px] border border-black/[0.1] dark:border-white/[0.08] text-[15px] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-zinc-50 placeholder-[#aaa] dark:placeholder-zinc-600 transition-colors focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/40 focus:border-[#0fa76e] dark:focus:border-[#18E299] resize-none"
                        ></textarea>
                    </div>

                    <div class="pt-1">
                        <button
                            type="submit"
                            class="bg-[#0d0d0d] dark:bg-white text-white dark:text-[#0d0d0d] px-8 py-3 rounded-full text-[15px] font-medium shadow-md dark:shadow-none hover:opacity-90 transition-all"
                        >
                            Send Message
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </section>
</x-layouts::shop>
