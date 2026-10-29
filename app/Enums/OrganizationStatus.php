<?php

namespace App\Enums;

enum OrganizationStatus: string
{
    case Team = 'team';
    case UnderReview = 'under_review';
    case PendingCompany = 'pending_company';
    case Company = 'company';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Team => 'تیم',
            self::UnderReview => 'در حال بررسی',
            self::PendingCompany => 'در انتظار ثبت شرکت',
            self::Company => 'شرکت',
            self::Inactive => 'غیرفعال',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Team => 'blue',
            self::UnderReview => 'yellow',
            self::PendingCompany => 'orange',
            self::Company => 'green',
            self::Inactive => 'gray',
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
