<?php

namespace App\Enums;

enum EventRegistrationStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
    case Attended = 'attended';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'در انتظار',
            self::Confirmed => 'تأیید شده',
            self::Cancelled => 'لغو شده',
            self::Attended => 'حاضر شده',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Confirmed => 'green',
            self::Cancelled => 'red',
            self::Attended => 'blue',
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
