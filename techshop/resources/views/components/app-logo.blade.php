@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="TechShop" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-[#18E299] text-black">
            <x-app-logo-icon class="size-5 fill-current text-black" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="TechShop" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-[#18E299] text-black">
            <x-app-logo-icon class="size-5 fill-current text-black" />
        </x-slot>
    </flux:brand>
@endif
