<?php

namespace App\Enums\VatCountries;

use App\Traits\HasEnums;

enum SkVat : int
{
    use HasEnums;
    case Zero = 0;
    case Five = 5;
    case Ten = 10;
    case Twenty = 20;
}
