@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="TechShop" {{ $attributes }} class="gap-2.5">
        <x-slot name="logo" class="flex aspect-square size-9 items-center justify-center rounded-xl bg-gradient-to-tr from-[#0fa76e] to-[#18E299] text-white shadow-sm shadow-[#18E299]/20">
            <x-app-logo-icon class="size-6 text-white" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="TechShop" {{ $attributes }} class="gap-2.5">
        <x-slot name="logo" class="flex aspect-square size-9 items-center justify-center rounded-xl bg-gradient-to-tr from-[#0fa76e] to-[#18E299] text-white shadow-sm shadow-[#18E299]/20">
            <x-app-logo-icon class="size-6 text-white" />
        </x-slot>
    </flux:brand>
@endif
