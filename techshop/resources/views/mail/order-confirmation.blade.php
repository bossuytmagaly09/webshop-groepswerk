<x-mail::message>
# Order Confirmation

Hi {{ $order->shipping_first_name }},

Thank you for your order! Your payment has been confirmed and we're getting everything ready for you.

---

**Order #{{ $orderNumber }}**
**Date:** {{ $order->checked_out_at->format('d M Y, H:i') }}

<x-mail::table>
| Product | Qty | Price | Subtotal |
|:--------|:---:|------:|---------:|
@foreach ($orderItems as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | €{{ number_format($item->unit_price, 2) }} | €{{ number_format($item->quantity * $item->unit_price, 2) }} |
@endforeach
| | | **Total** | **€{{ number_format($order->total_price, 2) }}** |
</x-mail::table>

---

**Shipping to:**
{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}
{{ $order->shipping_address_line_1 }}
@if ($order->shipping_address_line_2){{ $order->shipping_address_line_2 }}
@endif{{ $order->shipping_postcode }} {{ $order->shipping_city }}
{{ $order->shipping_country }}

---

If you have any questions about your order, feel free to reach out to us.

Thanks,
**{{ config('app.name') }}**
</x-mail::message>
