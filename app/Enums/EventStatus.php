<?php

namespace App\Enums;

enum EventStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Cancelled = 'cancelled';
    case Finished = 'finished';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'پیش‌نویس',
            self::Published => 'منتشر شده',
            self::Cancelled => 'لغو شده',
            self::Finished => 'پایان یافته',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'yellow',
            self::Published => 'green',
            self::Cancelled => 'red',
            self::Finished => 'gray',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Draft => 'bg-yellow-100 text-yellow-800',
            self::Published => 'bg-emerald-100 text-emerald-800',
            self::Cancelled => 'bg-red-100 text-red-800',
            self::Finished => 'bg-slate-200 text-slate-700',
        };
    }

    public function dotColor(): string
    {
        return match ($this) {
            self::Draft => 'bg-yellow-500',
            self::Published => 'bg-emerald-500',
            self::Cancelled => 'bg-red-500',
            self::Finished => 'bg-slate-500',
        };
    }
}
