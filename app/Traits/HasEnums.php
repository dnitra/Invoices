<?php

namespace App\Traits;

trait HasEnums
{
    public static function getCases(): array
    {
        return array_map(
            fn($case) => $case->value,
            self::cases()
        );

    }

}
