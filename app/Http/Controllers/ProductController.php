<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentResourceCollection;
use App\Http\Resources\ProductCardResource;
use App\Http\Resources\ProductResource;
use App\Http\Responses\ErrorResponse;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

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
        $productIds = $category->products()->pluck('id')->toArray();
        $brands = Brand::whereIn('id', $brandIds)->pluck('name', 'id');
        $colors = $category->products()->pluck('color', 'colorCode')->unique();
        $sizes = Size::whereIn('product_id', $productIds)->distinct('size')->pluck('size')->toArray();
        $sizes = array_combine($sizes, $sizes);
        return [
            'data' => [
                'sizes' => $sizes,
                'colors' => $colors,
                'brands' => $brands
            ]

        ];
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric',
            'offPrice' => 'nullable|numeric',
            'off_date_from' => 'required_with:offPrice|date',
            'off_date_to' => 'required_with:offPrice|date',
            'color' => 'required',
            'colorCode' => 'required',
            'images' => 'array'
        ]);

        $primaryImage = $request->images[0];


        try {
            DB::beginTransaction();
            $product = Product::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'image' => $primaryImage,
                'price' =>$request->price,
                'offPrice' => $request->offPrice,
                'off_date_from' => $request->off_date_from,
                'off_date_to' => $request->off_date_to,
                'color' => $request->color,
                'colorCode' => $request->colorCode,
            ]);

            $product->images()->createMany($request->images);
            $product->attributes()->createMany($request->get('attributes'));
            $product->sizes()->createMany($request->sizes);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return new ErrorResponse($th, 'محصول به درستی ذخیره نشد', 500);
        }

        return Response::json($product)->setData([
            'message' => 'محصول با موفقیت ایجاد شد',
            'data' => $product
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product = new ProductResource($product);
        return $product;
    }

    public function showComments(Product $product)
    {
        // $comments = new CommentResourceCollection($product->comments()->paginate(8)->load('user'));
        $comments = CommentResource::collection($product->comments()->paginate(8));
        return $comments;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'required',
            'price' => 'required|numeric',
            'offPrice' => 'nullable|numeric',
            'off_date_from' => 'required_with:offPrice|date',
            'off_date_to' => 'required_with:offPrice|date',
            'color' => 'required',
            'colorCode' => 'required',
            'images' => 'array',
        ]);

        try {
            DB::beginTransaction();
            $product->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'image' => $request->image,
                'price' =>$request->price,
                'offPrice' => $request->offPrice,
                'off_date_from' => $request->off_date_from,
                'off_date_to' => $request->off_date_to,
                'color' => $request->color,
                'colorCode' => $request->colorCode,
            ]);

            $product->images()->delete();
            $product->images()->createMany($request->images);

            $product->attributes()->delete();
            $product->attributes()->createMany($request->get('attributes'));
            // $product->sizes()->createMany($request->sizes);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return new ErrorResponse($th, 'محصول به درستی ویرایش نشد', 500);
        }

        return Response::json($product)->setData([
            'message' => 'محصول با موفقیت ویرایش شد',
            'data' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try{

            $product->delete();
        }
        catch(\Throwable $th){
            return new ErrorResponse($th, 'متأسفانه محصول به درستی حذف نشد', 500);
        }

        return response([
            'message' => 'محصول با موفقیت حذف شد'
        ], 200);
    }

    public function getDiscounts()
    {
        $discounts = Product::hasDiscount()->latest()->take(12)->get();
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

       return [
        'data' => $products
       ];
    }
}
