<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function order()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function product_variations()
    {
        return $this->hasMany(Size::class, 'size_id', 'id');
    }
}
