<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'purchase_id',
        'po_number',
        'purchase_quantity',
        'discount_id'
    ];

    public function discountJoin()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }
    public function itemsJoin()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function purchaseJoin()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
}
