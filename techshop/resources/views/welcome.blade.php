<x-layouts::shop>
    <section class="relative pt-16 pb-20 overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[600px] bg-[radial-gradient(circle_at_center,_#d4fae8_0%,_transparent_70%)] opacity-40 -z-10"></div>

        <div class="max-w-[1200px] mx-auto px-6 text-center">
            <div class="inline-block bg-[#d4fae8] text-[#0fa76e] text-[12px] font-bold tracking-[0.6px] uppercase px-3 py-1 rounded-full mb-6">
                {{ __('New Season') }} {{ date('Y') }}
            </div>

            <h1 class="text-5xl md:text-[64px] font-semibold leading-[1.15] tracking-[-1.28px] mb-6">
                {{ __('Tools for the next') }} <br/> {{ __('generation of creators.') }}
            </h1>

            <p class="text-lg text-[#666666] max-w-2xl mx-auto mb-10">
                {{ __('High-performance hardware engineered for developers, designers, and digital architects.') }}
            </p>

            <div class="flex flex-wrap justify-center gap-4">
                <a href="/products" class="bg-[#0d0d0d] text-white px-8 py-3 rounded-full text-[15px] font-medium shadow-md hover:opacity-90 transition-all">
                    {{ __('Browse Collection') }}
                </a>
                <a href="/products" class="bg-white border border-black/[0.08] text-[#0d0d0d] px-8 py-3 rounded-full text-[15px] font-medium hover:bg-gray-50 transition-all">
                    {{ __('View Lookbook') }}
                </a>
            </div>
        </div>
    </section>
</x-layouts::shop>
