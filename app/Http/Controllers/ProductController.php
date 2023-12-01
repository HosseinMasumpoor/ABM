<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryBreadcrumbResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentResourceCollection;
use App\Http\Resources\ProductCardResource;
use App\Http\Resources\ProductResource;
use App\Http\Responses\ErrorResponse;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $products = Product::paginate($request->items_perpage ?? 10);
        $products = ProductCardResource::collection($products);
        return $products;
    }

    public function filter(Request $request, Category $category)
    {
        $categoryIds = $this->getCategoriesId($category);
        $products = $category->products()->filter($categoryIds)->paginate($request->items_perpage ?? 12)->withQueryString();
        $products = ProductCardResource::collection($products);
        return $products;
    }

    public function getFilters(Category $category)
    {

        $categoryIds = $this->getCategoriesId($category);
        $categoryIds->push($category->id);
        // $categories = Category::whereIn('id', $categoryIds)->get();

        $products = Product::whereIn('category_id', $categoryIds);
        $price = [
            'min' => 0,
            'max' => $products->max('offPrice')
        ];


        $brandIds = $products->pluck('brand_id');

        $productIds = $products->pluck('id')->toArray();

        $order = ['sm', 'md', 'lg', 'xl', '2xl', '3xl'];
        $sizes = Size::whereIn('product_id', $productIds)->distinct('size')->get(['id', 'size'])->unique('size')->sortBy('size');
        $sizes = $sizes->sort(function ($a, $b) use ($order) {
            $pos_a = array_search($a->size, $order);
            $pos_b = array_search($b->size, $order);
            return $pos_a - $pos_b;
        })->values();
        $sizes = $sizes->map(function ($size) {
            return [
                'id' => $size->id,
                'name' => $size->size,
                'value' => $size->id
            ];
        });

        $brands = Brand::whereIn('id', $brandIds)->orderBy('name')->get(['id', 'name']);
        $brands = $brands->map(function ($brand) {
            return [
                'id' => $brand->id,
                'name' => $brand->name,
                'value' => $brand->id
            ];
        });

        $colors = $products->get(['id', 'color', 'colorCode'])->unique('color')->sortBy('color')->values();
        $colors = $colors->map(function ($color) {
            return [
                'id' => $color->id,
                'name' => $color->color,
                'value' => $color->colorCode
            ];
        });
        return [
            'data' => [
                'sizes' => $sizes,
                'colors' => $colors,
                'brands' => $brands,
                'price' => $price
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
            'slug' => 'unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric',
            'offPrice' => 'nullable|numeric',
            'off_date_from' => 'required_with:offPrice|date',
            'off_date_to' => 'required_with:offPrice|date',
            'color' => 'required',
            'colorCode' => 'required',
            'images' => 'array|required',
            'images.*' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
            'attributes' => 'array',
            'attributes.*' => 'required',
            'sizes' => 'array|required',
            'sizes.*' => 'required'
        ]);

        $images = collect();
        $primaryImage = $request->file('image')->store(env('PRODUCT_IMAGE_UPLOAD_PATH'), 'public');
        $images->add($primaryImage);
        $requestImages = $request->file('images');

        foreach ($requestImages as $image) {
            $image_uploaded_path = $image->store(env('PRODUCT_IMAGE_UPLOAD_PATH'), 'public');

            $images->add($image_uploaded_path);
        }
        try {
            DB::beginTransaction();
            $product = Product::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'image' => $primaryImage,
                'price' => $request->price,
                'offPrice' => $request->offPrice,
                'off_date_from' => $request->off_date_from,
                'off_date_to' => $request->off_date_to,
                'color' => $request->color,
                'colorCode' => $request->colorCode,
            ]);

            $imagesResult = collect();
            foreach ($images as $image) {
                $imageRecord = Image::create([
                    'src' => $image,
                    'product_id' => $product->id
                ]);
                $imagesResult->push($imageRecord);
            }
            if ($request->get('attributes'))
                $attributes = $product->attributes()->createMany($request->get("attributes"));
            $sizes = $product->sizes()->createMany($request->sizes);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return new ErrorResponse($th, 'محصول به درستی ذخیره نشد', 500);
        }

        return response([
            'message' => 'محصول با موفقیت ایجاد شد',
            'data' => [
                'product' => $product,
                'images' => $imagesResult,
                'attributes' => $attributes ?? null,
                'sizes' => $sizes
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product = new ProductResource($product);
        $breadCrumb = $product->category->parents;
        $breadCrumb = CategoryBreadcrumbResource::collection($breadCrumb);
        return response([
            'data' => [
                'product' => $product,
                'breadcrumb' => $breadCrumb
            ]
        ]);
    }


    public function showComments(Request $request, Product $product)
    {

        $comments = CommentResource::collection($product->comments()->paginate($request->items_perpage ?? 8));
        return $comments;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'unique:products,slug,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric',
            'offPrice' => 'nullable|numeric',
            'off_date_from' => 'required_with:offPrice|date',
            'off_date_to' => 'required_with:offPrice|date',
            'color' => 'required',
            'colorCode' => 'required',
            'images' => 'array',
            'images.*' => 'image:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'image:jpeg,png,jpg,gif,svg|max:2048',
            'attributes' => 'array',
            'attributes.*' => 'required',
            'sizes' => 'array',
            // 'sizes.*' => 'required'
        ]);

        $images = collect();
        if ($request->has('image')) {

            $primaryImage = $request->file('image')->store(env('PRODUCT_IMAGE_UPLOAD_PATH'), 'public');
            $images->add($primaryImage);
        } else {
            $primaryImage = $product->image;
        }
        $requestImages = $request->file('images');

        if ($requestImages) {

            foreach ($requestImages as $image) {
                $image_uploaded_path = $image->store(env('PRODUCT_IMAGE_UPLOAD_PATH'), 'public');

                $images->add($image_uploaded_path);
            }
        }

        try {
            DB::beginTransaction();
            $product->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'image' => $primaryImage,
                'price' => $request->price,
                'offPrice' => $request->offPrice,
                'off_date_from' => $request->off_date_from,
                'off_date_to' => $request->off_date_to,
                'color' => $request->color,
                'colorCode' => $request->colorCode,
            ]);


            if ($request->has('deletingImages')) {
                $deletingImages = $product->images()->whereIn('id', $request->deletingImages)->get();
                foreach ($deletingImages as $deletingImage) {
                    if (!Str::contains($deletingImage->src, 'storage/products/test/'))
                        Storage::delete($deletingImage->getRawOriginal('src'));
                }
                $product->images()->whereIn('id', $request->deletingImages)->delete();
            }

            if ($images->isNotEmpty()) {
                foreach ($images as $image) {
                    Image::create([
                        'src' => $image,
                        'product_id' => $product->id
                    ]);
                }
            }
            $product->attributes()->delete();
            if ($request->has('attributes')) {
                $product->attributes()->createMany($request->get('attributes'));
            }

            if ($request->has('deletingSizes')) {

                $product->sizes()->whereIn('id', $request->deletingSizes)->delete();
            }

            if ($request->has('sizes')) {
                $product->sizes()->createMany($request->sizes);
            }

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
        try {
            foreach ($product->images as $image) {
                if (!Str::contains($image->src, 'storage/products/test/'))
                    Storage::delete($image->getRawOriginal('src'));
            }
            $product->images()->delete();
            $product->attributes()->delete();
            $product->sizes()->delete();
            $product->delete();
        } catch (\Throwable $th) {
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
        // if(Cache::has('top-orders-products'))
        //     $products = Cache::get('top-orders-products');
        // else{
        $products = Product::topOrders()->get();
        //     Cache::add('top-orders-products', $products);
        // }

        return [
            'data' => $products
        ];
    }

    private function getCategoriesId($category)
    {
        $categoryIds = collect();
        // $categoryIds->push($category->id);
        $categoryIds->push($category->subCategoriesId());
        foreach ($category->subCategories as $subCategory) {
            $categoryIds->push($this->getCategoriesId($subCategory));
        }
        return $categoryIds->flatten();
    }
}
