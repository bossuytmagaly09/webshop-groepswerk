<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case SHIPPED = 'shipped';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::SHIPPED => 'Shipped',
            self::CANCELLED => 'Cancelled',
            self::REFUNDED => 'Refunded',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
            self::PAID => 'bg-[#d4fae8] text-[#0fa76e] border border-[#18E299]/20 dark:bg-[#0fa76e]/20 dark:text-[#18E299]',
            self::SHIPPED => 'bg-blue-50 text-blue-600 border border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20',
            self::CANCELLED => 'bg-red-50 text-red-600 border border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20',
            self::REFUNDED => 'bg-zinc-100 text-zinc-600 border border-zinc-200 dark:bg-zinc-500/10 dark:text-zinc-400 dark:border-zinc-500/20',
        };
    }

    /** @return OrderStatus[] */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::PENDING => [self::PAID, self::CANCELLED],
            self::PAID => [self::SHIPPED, self::CANCELLED, self::REFUNDED],
            self::SHIPPED => [self::REFUNDED],
            self::CANCELLED => [],
            self::REFUNDED => [],
        };
    }
}
