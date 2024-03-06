<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    // protected $guarded = [];

    const APPROVED_VALUES = [0, 1];

    protected $fillable = ['user_id', 'text', 'rate', 'product_id', 'is_anonymous', 'approved'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeFilter($query)
    {
        if (request()->has('approved')) {
            $approved = request()->get('approved');
            if ($approved) {
                $query->whereNotNull('approved');
            } else {
                $query->whereNull('approved');
            }
        }
    }

    public function scopeApproved($query)
    {
        $query->where('approved', 1);
    }
}
