<?php

namespace App\Enums;

use App\Traits\HasEnums;

enum TaxMode : string
{
    use HasEnums;
    case Domestic = 'Domácí';
    case OSS = 'OSS';
    case ReverseCharge = 'Reverse charge';
}
