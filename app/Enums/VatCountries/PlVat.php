<?php

namespace App\Enums\VatCountries;

use App\Traits\HasEnums;

enum PlVat: int
{
    use HasEnums;
    case Zero = 0;
    case Five = 5;
    case Eight = 8;
    case TwentyThree = 23;

}
