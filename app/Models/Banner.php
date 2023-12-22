<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'link',
        'src',
        'order',
    ];

    // public function getSrcAttribute()
    // {
    //     return asset($this->src);
    // }
}
