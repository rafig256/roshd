<?php

namespace App\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Blocked = 'blocked';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'فعال',
            self::Blocked => 'مسدود',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'green',
            self::Blocked => 'red',
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
