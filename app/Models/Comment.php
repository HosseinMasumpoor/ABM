<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    // protected $guarded = [];

    const approvedValues = [0, 1, "null"];

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
        // dd(request()->get('approved'));
        if (request()->has('approved')) {
            $approved = request()->get('approved') == "null" ? null : request()->get('approved');
            $query->where('approved', $approved);
        }
    }

    protected static function booted()
    {
        static::addGlobalScope('approved', function (Builder $builder) {
            $builder->where('approved', 1);
        });
    }
}
