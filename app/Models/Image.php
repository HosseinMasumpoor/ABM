<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Image extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getSrcAttribute()
    {
        $src = $this->attributes['src'];
        $src = Str::startsWith($src, 'http://') || Str::startsWith($src, 'https://') ? $src : 'storage/' . $src;
        return asset($src);
    }
}
