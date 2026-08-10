<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingGoodItem extends Model
{
    protected $fillable = [
        'incoming_good_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function incomingGood()
    {
        return $this->belongsTo(IncomingGood::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
