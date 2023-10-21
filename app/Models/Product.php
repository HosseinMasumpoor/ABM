<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cviebrock\EloquentSluggable\Sluggable;


class Product extends Model
{
    use HasFactory;
    use Sluggable;
    protected $guarded =  [];

    /**
     * Relations
     */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * seters and geters
     */

    public function setOffPriceAttribute($value)
    {
        if($value){
            $this->attributes['offPrice'] = $value;
        }
        else{
            $this->attributes['offPrice'] = $this->attributes['price'];
        }
    }

    public function getOffPercentAttribute()
    {
        if($this->offPrice)
        {
           return round((($this->price - $this->offPrice ) / $this->price) *100);
        }
        return null;
    }

    public function getRateAttribute()
    {
        return round($this->comments->avg('rate'), 1);
    }

    /**
     * Scopes
     */

    public function scopeHasDiscount($query)
    {
        return $query->whereNotNull('offPrice')->whereColumn('offPrice', '<>', 'price')->where('off_date_from', '<', Carbon::now())->where('off_date_to', '>' , Carbon::now());
    }

    public function scopeTopOrders($query)
    {
        return $query->select('products.id', 'products.slug', 'products.name', 'products.image')
        ->join('sizes','products.id','=','sizes.product_id')
        ->join('order_items','sizes.id','=','order_items.size_id')
        ->groupBy('products.id')
        ->orderByRaw("COUNT(order_items.id) desc");
    }

    public function scopeFilter($query)
    {
        if(request()->has('brands')){
            foreach (explode('-', request()->brands)  as $index => $brand) {
                $brand = Brand::where('name', $brand)->firstOrFail();
                if($index == 0)
                    $query->where('brand_id', $brand->id);
                else
                    $query->orWhere('brand_id', $brand->id);
            }
        }

        if(request()->has('colors')){
            foreach (explode('-', request()->colors)  as $index => $color) {
                if($index == 0)
                    $query->where('color', $color);
                else
                    $query->orWhere('color', $color);
            }
        }

        if(request()->has('sizes')){
            foreach (explode('-', request()->sizes)  as $index => $size) {
                $ids = Size::where('size', $size)->get('product_id');
                if($index == 0)
                    $query->whereIn('id', $ids);
                else
                    $query->orWhereIn('id', $ids);
            }
        }

        if(request()->has('min')){
            $query->where('offPrice', '>', request()->min);
        }

        if(request()->has('max')){
            $query->where('offPrice', '<', request()->max);
        }

        if(request()->has('sort')){
            $sort = request()->get('sort');
            switch ($sort) {
                case 'bestselling':
                    $query->select('products.*')
                    ->join('sizes','products.id','=','sizes.product_id')
                    ->leftJoin('order_items','sizes.id','=','order_items.size_id')
                    ->groupBy('products.id')
                    ->orderByRaw("COUNT(order_items.id) desc");
                    break;
                case 'favorite':
                    $query->select('products.*')->join('comments', 'products.id', '=', 'comments.product_id')
                    ->groupBy('products.id')->orderByRaw('AVG(rate) desc')->toSql();
                    break;
                case 'newest':
                    $query->latest();
                    break;
                case 'min':
                    // $query->orderByRaw('COALESCE(offPrice, price) ASC');
                    $query->orderBy('offPrice');
                    break;
                case 'max':
                    $query->orderBy('offPrice', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        }
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
