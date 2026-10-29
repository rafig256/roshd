<?php

namespace App\Enums;

enum EventLocationType: string
{
    case Physical = 'physical';
    case Online = 'online';
    case Hybrid = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::Physical => 'حضوری',
            self::Online => 'آنلاین',
            self::Hybrid => 'ترکیبی',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Physical => 'blue',
            self::Online => 'green',
            self::Hybrid => 'orange',
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
