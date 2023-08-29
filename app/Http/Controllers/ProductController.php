<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCardResource;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        $products = ProductResource::collection($products);
        return $products;
    }

    public function filter(Category $category)
    {
        $products = $category->products()->filter()->paginate(12)->withQueryString();
        $products = ProductCardResource::collection($products);
        return $products;
    }

    public function getFilters(Category $category)
    {

        $brandIds = $category->products()->pluck('brand_id');

        $brands = Brand::whereIn('id', $brandIds)->pluck('name');
        $products = $category->products()->pluck('color', 'id')->toArray();
        $colors = array_values($products);
        $productIds = array_keys($products);
        $sizes = Size::whereIn('product_id', $productIds)->distinct('size')->pluck('size');
        return [
            'data' => [
                'sizes' => $sizes,
                'colors' => $colors,
                'brands' => $brands
            ]

        ];
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

    public function getDiscounts()
    {
        $discounts = Product::hasDiscount()->take(12)->get();
        $discounts = ProductCardResource::collection($discounts);
        return $discounts;
    }

    public function topOrders()
    {
        if(Cache::has('top-orders-products'))
            $products = Cache::get('top-orders-products');
        else{
            $products = Product::topOrders()->get();
            Cache::add('top-orders-products', $products);
        }
       return $products;
    }
}
