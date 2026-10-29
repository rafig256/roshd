<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case NotRequired = 'not_required';
    case Pending = 'pending';
    case Paid = 'paid';
    case Refunded = 'refunded';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::NotRequired => 'نیازی نیست',
            self::Pending => 'در انتظار',
            self::Paid => 'پرداخت شده',
            self::Refunded => 'بازگشت داده شده',
            self::Failed => 'ناموفق',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NotRequired => 'gray',
            self::Pending => 'yellow',
            self::Paid => 'green',
            self::Refunded => 'blue',
            self::Failed => 'red',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
