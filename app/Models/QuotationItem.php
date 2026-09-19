<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'description',
        'charge_type',
        'vendor_id',
        'qty',
        'unit',
        'currency',
        'sell_rate',
        'buy_rate',
        'amount_sell',
        'amount_buy',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Customer::class);
    }
}
