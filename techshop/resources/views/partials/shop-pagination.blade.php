@if ($paginator->hasPages())
    <nav aria-label="{{ __('Pagination Navigation') }}" class="flex items-center gap-1">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="w-9 h-9 flex items-center justify-center rounded-full border border-black/[0.05] text-[#cccccc] cursor-not-allowed select-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </span>
        @else
            <button wire:click="previousPage" x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })" wire:loading.attr="disabled" rel="prev" class="w-9 h-9 flex items-center justify-center rounded-full border border-black/[0.08] hover:border-[#18E299] hover:text-[#18E299] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </button>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="w-9 h-9 flex items-center justify-center text-[13px] text-[#999999]">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="w-9 h-9 flex items-center justify-center rounded-full bg-[#0d0d0d] text-white text-[13px] font-medium select-none">{{ $page }}</span>
                    @else
                        <button wire:click="gotoPage({{ $page }})" x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })" class="w-9 h-9 flex items-center justify-center rounded-full border border-black/[0.08] text-[13px] hover:border-[#18E299] hover:text-[#18E299] transition-colors">{{ $page }}</button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })" wire:loading.attr="disabled" rel="next" class="w-9 h-9 flex items-center justify-center rounded-full border border-black/[0.08] hover:border-[#18E299] hover:text-[#18E299] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </button>
        @else
            <span class="w-9 h-9 flex items-center justify-center rounded-full border border-black/[0.05] text-[#cccccc] cursor-not-allowed select-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </span>
        @endif

    </nav>
@endif
