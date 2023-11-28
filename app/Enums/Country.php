<?php

namespace App\Enums;

use App\Traits\HasEnums;

enum Country : string
{
    use HasEnums;
    case Cesko = 'CZ';
    case Polsko = 'PL';
    case Slovensko = 'SK';

}
