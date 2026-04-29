<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.app')] #[Title('Dashboard Analytics')] class extends Component {
    public function with(): array
    {
        // Calculate basic stats
        $totalRevenue = Order::where('status', 'paid')->sum('total_price');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Revenue over the last 7 days for the chart
        $revenueData = Order::where('status', 'paid')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top products
        $topProducts = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select('products.name', 'products.image', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Recent orders
        $recentOrders = Order::with('user')->latest()->limit(8)->get();

        return [
            'stats' => [
                'revenue' => '€ ' . number_format($totalRevenue, 2, ',', '.'),
                'orders' => $totalOrders,
                'customers' => $totalCustomers,
                'avg_value' => '€ ' . number_format($avgOrderValue, 2, ',', '.'),
            ],
            'revenueData' => $revenueData,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
        ];
    }
}; ?>

<div>
    <div class="max-w-[1400px] mx-auto px-6 pt-12 pb-24">
        
        {{-- Header --}}
        <div class="mb-10">
            <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-3">
                {{ __('Admin — Analytics') }}
            </div>
            <h1 class="text-3xl md:text-4xl font-semibold tracking-[-0.8px] text-[#0d0d0d] dark:text-white">
                {{ __('Dashboard') }}
            </h1>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            {{-- Revenue --}}
            <div class="p-6 rounded-[24px] bg-white dark:bg-zinc-900 border border-black/[0.05] dark:border-white/[0.08] shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2.5 rounded-xl bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <span class="text-[12px] font-medium text-[#0fa76e]">+12.5%</span>
                </div>
                <div class="text-[13px] text-[#666666] dark:text-zinc-400 mb-1">{{ __('Total Revenue') }}</div>
                <div class="text-2xl font-semibold text-[#0d0d0d] dark:text-white tracking-tight">{{ $stats['revenue'] }}</div>
            </div>

            {{-- Orders --}}
            <div class="p-6 rounded-[24px] bg-white dark:bg-zinc-900 border border-black/[0.05] dark:border-white/[0.08] shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m20.9 11-3 3.5-3.5-3.5"/><path d="m20.9 14.5-3 3.5-3.5-3.5"/><path d="M13 18.5H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v6"/></svg>
                    </div>
                    <span class="text-[12px] font-medium text-blue-600">+8.2%</span>
                </div>
                <div class="text-[13px] text-[#666666] dark:text-zinc-400 mb-1">{{ __('Total Orders') }}</div>
                <div class="text-2xl font-semibold text-[#0d0d0d] dark:text-white tracking-tight">{{ $stats['orders'] }}</div>
            </div>

            {{-- Customers --}}
            <div class="p-6 rounded-[24px] bg-white dark:bg-zinc-900 border border-black/[0.05] dark:border-white/[0.08] shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2.5 rounded-xl bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="19" cy="11" r="2"/></svg>
                    </div>
                    <span class="text-[12px] font-medium text-purple-600">+5.1%</span>
                </div>
                <div class="text-[13px] text-[#666666] dark:text-zinc-400 mb-1">{{ __('Total Customers') }}</div>
                <div class="text-2xl font-semibold text-[#0d0d0d] dark:text-white tracking-tight">{{ $stats['customers'] }}</div>
            </div>

            {{-- Avg Order Value --}}
            <div class="p-6 rounded-[24px] bg-white dark:bg-zinc-900 border border-black/[0.05] dark:border-white/[0.08] shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2.5 rounded-xl bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19 11-4-7"/><path d="m5 11 4-7"/><path d="M2 11h20"/><path d="m3.5 11 1.6 7.4a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6l1.7-7.4"/><path d="M4.5 15.5h15"/><path d="m5 11 4-7"/><path d="m19 11-4-7"/></svg>
                    </div>
                    <span class="text-[12px] font-medium text-orange-600">+2.4%</span>
                </div>
                <div class="text-[13px] text-[#666666] dark:text-zinc-400 mb-1">{{ __('Avg. Order Value') }}</div>
                <div class="text-2xl font-semibold text-[#0d0d0d] dark:text-white tracking-tight">{{ $stats['avg_value'] }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
            {{-- Main Chart Placeholder (Using CSS/SVG for premium feel without heavy JS) --}}
            <div class="lg:col-span-2 p-8 rounded-[32px] bg-white dark:bg-zinc-900 border border-black/[0.05] dark:border-white/[0.08] shadow-sm overflow-hidden relative">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-lg font-semibold text-[#0d0d0d] dark:text-white leading-tight">{{ __('Revenue over time') }}</h3>
                        <p class="text-[13px] text-[#666666] dark:text-zinc-400">{{ __('Performance of the last 7 days') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#f0fdf4] dark:bg-[#0fa76e]/10 border border-[#0fa76e]/10">
                            <div class="size-2 rounded-full bg-[#0fa76e] dark:bg-[#18E299]"></div>
                            <span class="text-[11px] font-medium text-[#0fa76e] dark:text-[#18E299] uppercase tracking-wider">{{ __('Revenue') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Mock Chart Visualization --}}
                <div class="h-[280px] w-full relative mt-4 flex items-end justify-between gap-2 px-2">
                    <div class="absolute inset-x-0 top-0 bottom-0 flex flex-col justify-between pointer-events-none">
                        <div class="border-t border-black/[0.03] dark:border-white/[0.03] w-full h-0"></div>
                        <div class="border-t border-black/[0.03] dark:border-white/[0.03] w-full h-0"></div>
                        <div class="border-t border-black/[0.03] dark:border-white/[0.03] w-full h-0"></div>
                        <div class="border-t border-black/[0.03] dark:border-white/[0.03] w-full h-0"></div>
                        <div class="border-t border-black/[0.03] dark:border-white/[0.03] w-full h-0"></div>
                    </div>

                    @foreach($revenueData as $day)
                        <div class="flex-1 flex flex-col items-center group relative">
                            <div 
                                class="w-full max-w-[40px] rounded-t-xl bg-gradient-to-t from-[#0fa76e]/40 to-[#18E299] dark:from-[#0fa76e]/20 dark:to-[#18E299] transition-all duration-500 group-hover:brightness-110 cursor-pointer relative"
                                style="height: {{ max(10, ($day->total / 2000) * 100) }}%"
                            >
                                <div class="absolute -top-10 left-1/2 -translate-x-1/2 px-2 py-1 rounded bg-[#0d0d0d] text-white text-[10px] opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                    € {{ number_format($day->total, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="mt-3 text-[10px] font-mono text-[#999999] dark:text-zinc-500 uppercase">{{ date('D', strtotime($day->date)) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Top Products --}}
            <div class="p-8 rounded-[32px] bg-white dark:bg-zinc-900 border border-black/[0.05] dark:border-white/[0.08] shadow-sm">
                <h3 class="text-lg font-semibold text-[#0d0d0d] dark:text-white mb-6">{{ __('Top Products') }}</h3>
                
                <div class="space-y-5">
                    @foreach($topProducts as $product)
                        <div class="flex items-center gap-4">
                            <div class="size-12 rounded-xl bg-[#fafafa] dark:bg-zinc-800 border border-black/[0.05] dark:border-white/[0.05] overflow-hidden shrink-0">
                                @if($product->image)
                                    <img src="{{ Storage::disk('public')->url($product->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-[#cccccc] dark:text-zinc-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-[14px] font-medium text-[#0d0d0d] dark:text-white truncate">{{ $product->name }}</div>
                                <div class="text-[12px] text-[#666666] dark:text-zinc-400">{{ $product->total_sold }} {{ __('sold') }}</div>
                            </div>
                            <div class="size-2 rounded-full bg-[#18E299]/30"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Recent Orders Table --}}
        <div class="rounded-[32px] border border-black/[0.05] dark:border-white/[0.08] bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
            <div class="p-8 border-b border-black/[0.05] dark:border-white/[0.06] flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-[#0d0d0d] dark:text-white leading-tight">{{ __('Recent Orders') }}</h3>
                    <p class="text-[13px] text-[#666666] dark:text-zinc-400">{{ __('Monitor the latest transactions') }}</p>
                </div>
                <a href="#" class="text-[13px] font-medium text-[#0fa76e] dark:text-[#18E299] hover:underline">{{ __('View all orders') }}</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-[14px] text-left">
                    <thead class="bg-[#fafafa] dark:bg-zinc-800/50">
                        <tr>
                            <th scope="col" class="px-8 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Order ID') }}</th>
                            <th scope="col" class="px-8 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Customer') }}</th>
                            <th scope="col" class="px-8 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Total') }}</th>
                            <th scope="col" class="px-8 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Status') }}</th>
                            <th scope="col" class="px-8 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.04] dark:divide-white/[0.04]">
                        @foreach ($recentOrders as $order)
                            <tr class="group hover:bg-[#fafafa] dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-8 py-5 font-mono text-[13px] text-[#0d0d0d] dark:text-white">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="size-8 rounded-full bg-gradient-to-tr from-gray-200 to-gray-100 dark:from-zinc-800 dark:to-zinc-700 flex items-center justify-center text-[11px] font-bold text-gray-500">
                                            {{ substr($order->user->name, 0, 2) }}
                                        </div>
                                        <span class="font-medium text-[#0d0d0d] dark:text-white">{{ $order->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 font-semibold text-[#0d0d0d] dark:text-white">€ {{ number_format($order->total_price, 2, ',', '.') }}</td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex px-3 py-1.5 rounded-full text-[11px] font-medium uppercase tracking-wider
                                        @if($order->status === 'paid') bg-[#d4fae8] text-[#0fa76e] dark:bg-[#0fa76e]/20 dark:text-[#18E299]
                                        @elseif($order->status === 'pending') bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400
                                        @else bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 @endif
                                    ">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-[#666666] dark:text-zinc-500 text-[13px]">{{ $order->created_at->format('M d, H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
