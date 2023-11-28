<?php

namespace App\Enums;

use App\Traits\HasEnums;

enum InvoiceStatus : string
{
    use HasEnums;
    case Nezaplaceno = 'Nezaplaceno';
    case Zaplaceno = 'Zaplaceno';
    case PoSplatnosti = 'Po splatnosti';
}
