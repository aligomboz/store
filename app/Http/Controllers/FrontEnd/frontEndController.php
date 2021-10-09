<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class frontEndController extends Controller
{
    public function index()
    {
        $product_categories = ProductCategory::whereStatus(1)->whereNull('parent_id')->get();
        return view('frontEnd.index', compact('product_categories'));
    }
    public function cart()
    {
        return view('frontEnd.cart');
    }

    public function checkout()
    {
        return view('frontEnd.checkout');
    }

    public function product($slug)
    {

        $product = Product::with('media', 'category', 'tags', 'reviews')->withAvg('reviews', 'rating')->whereSlug($slug)
            ->Active()->HasQuantity()->ActiveCategory()->firstOrFail();

        $relatedProducts = Product::with('firstMedia')->whereHas('category', function ($query) use ($product) {
            $query->whereId($product->product_category_id);
            $query->whereStatus(true);
        })->inRandomOrder()->Active()->HasQuantity()->take(4)->get();
        return view('frontend.product', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    public function shop()
    {
        return view('frontEnd.shop');
    }
}
