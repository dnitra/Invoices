<?php

namespace App\Enums;

use App\Traits\HasEnums;

enum Currency: string
{
    use HasEnums;
    case czk = 'CZK';
    case eur = 'EUR';
    case zloty = 'PLN';


}
