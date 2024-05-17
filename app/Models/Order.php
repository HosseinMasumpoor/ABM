<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function getTotalDataAttribute()
    {
        $items = $this->orderItems;
        $totalDiscount = 0;
        $totalItems = 0;
        $totalPreOffPrice = 0;
        foreach ($items as $item) {
            $totalPreOffPrice += $item->product_price * $item->quantity;
            $totalItems += $item->quantity;
            if ($item->product_offPrice) {
                $totalDiscount += ($item->product_price - $item->product_offPrice) * $item->quantity;
            }
        }
        return [
            'total_discount' => $totalDiscount,
            'total_items' => $totalItems,
            'total_preOffPrice' => $totalPreOffPrice
        ];
    }

    public function getTotalDiscountAttribute()
    {
        $items = $this->orderItems;
        $totalDiscount = 0;
        foreach ($items as $item) {
            if ($item->product_offPrice) {
                $totalDiscount += $item->product_price - $item->product_offPrice;
            }
        }
        return $totalDiscount;
    }
}
