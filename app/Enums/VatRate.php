<?php

namespace App\Enums;

use App\Traits\HasEnums;

enum VatRate : int
{
    use HasEnums;
    case Vat_0 = 0;
    case Vat_10 = 10;
    case Vat_15 = 15;
    case Vat_21 = 21;
    case Vat_23 = 23;
}
