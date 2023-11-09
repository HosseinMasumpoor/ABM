<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Category extends Model
{
    use HasFactory;
    use Sluggable;
    protected $guarded =  [];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function subCategoriesId()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('id', 'DESC')->get('id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function ScopeChildrenProducts()
    {
        $products =  $this->products;
        foreach ($this->subCategories as  $category) {
            $products->add($category->childrenProducts());
        }
        $products = $products->flatten();
        return $products;
    }

    public function getIconAttribute()
    {
        if ($this->attributes['icon']) {

            $src = $this->attributes['icon'];
            $src = Str::startsWith($src, 'http://') || Str::startsWith($src, 'https://') ? $src : 'storage/' . $src;
            return asset($src);
        }
        return $this->attributes['icon'];
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
