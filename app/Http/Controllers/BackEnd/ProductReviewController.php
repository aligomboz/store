<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductReviewRequest;
use App\Models\ProductReview;
use Database\Seeders\ProductReviewSeeder;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_product_reviews, show_product_reviews')) {
            return redirect('admin/index');
        }
        $reviews = ProductReview::with(['product','user'])
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);
            //   return $reviews;
        return view('backend.product_reviews.index', [
            'reviews' =>$reviews,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductReview  $productReview
     * @return \Illuminate\Http\Response
     */
    public function show(ProductReview $product_review)
    {
        if (!auth()->user()->ability('admin', 'display_product_reviews')) {
            return redirect('admin/index');
        }

        return view('backend.product_reviews.show', [
            'productReview' => $product_review,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductReview  $productReview
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductReview $product_review)
    {
        if (!auth()->user()->ability('admin', 'update_product_reviews')) {
            return redirect('admin/index');
        }

        return view('backend.product_reviews.edit',[
            'productReview' => $product_review,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductReview  $productReview
     * @return \Illuminate\Http\Response
     */
    public function update(ProductReviewRequest $request, ProductReview $product_review)
    {
        if (!auth()->user()->ability('admin', 'update_product_reviews')) {
            return redirect('admin/index');
        }

        $product_review->update($request->validated());

        return redirect()->route('admin.product_reviews.index')->with([
            'message' => 'Updated successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductReview  $productReview
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductReview $product_review)
    {
        if (!auth()->user()->ability('admin', 'delete_product_reviews')) {
            return redirect('admin/index');
        }

        $product_review->delete();

        return redirect()->route('admin.product_reviews.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'success'
        ]);
    }
}
