<?php

namespace App\Enums\VatCountries;

use App\Traits\HasEnums;

enum CzVat : int
{
    use HasEnums;
    case Zero = 0;
    case Ten = 10;
    case Fifteen = 15;
    case TwentyOne = 21;
}
