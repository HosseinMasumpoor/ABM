<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Banner extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'link',
        'src',
        'order',
    ];

    public function getSrcAttribute()
    {
        if ($this->attributes['src']) {
            $src = $this->attributes['src'];
            $src = Str::startsWith($src, 'http://') || Str::startsWith($src, 'https://') ? $src : 'storage/' . $src;
            return asset($src);
        }
        return $this->attributes['src'];
    }
}
