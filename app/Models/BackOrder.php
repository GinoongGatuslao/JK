<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'item_id',
        'backorder_quantity',
        'status',
        'delivery_id',
    ];

    public function purchaseJoin()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
    public function deliveryJoin()
    {
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }
    public function itemJoin()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

}
