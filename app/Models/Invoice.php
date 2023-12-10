<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function rows()
    {
        return $this->hasMany(InvoiceRow::class);
    }

    //automatically add value 'totalPrice' to the invoice based on the sum of all rows
}
