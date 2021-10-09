<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_product_categories, show_product_categories')) {
            return redirect('admin/index');
        }
        $categories = ProductCategory::withCount('products')
            ->when(request()->keyword != null, function ($q) {
                $q->search(request()->keyword);
            })
            ->when(request()->status != null, function ($q) {
                $q->whereStatus(request()->status);
            })
            ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'desc')
            ->paginate(request()->limit_by ?? 10);

        return view('backEnd.product_category.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_product_categories')) {
            return redirect('admin/index');
        }
        $parent_categories = ProductCategory::whereNull('parent_id')->get(['id', 'name']);
        return view('backEnd.product_category.create', [
            'parent_categories' => $parent_categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCategoryRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_product_categories')) {
            return redirect('admin/index');
        }
        $data['name'] = $request->name;
        $data['status'] = $request->status;
        $data['parent_id'] = $request->parent_id;

        if ($image = $request->file('cover')) {
            $file_name = Str::slug($request->name) . "." . $image->getClientOriginalExtension();
            $path = public_path('/assets/product_categories/' . $file_name);
            Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $data['cover'] = $file_name;
        }

        ProductCategory::create($data);

        return redirect()->route('admin.product_categories.index')->with([
            'message' => 'Created successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCategory $product_category)
    {
        if (!auth()->user()->ability('admin', 'display_product_categories')) {
            return redirect('admin/index');
        }
        return view('backEnd.product_category.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCategory $product_category)
    {
        if (!auth()->user()->ability('admin', 'update_product_categories')) {
            return redirect('admin/index');
        }
        // $category = $product_category->findOrFail($product_category->id);
        $parent_category = $product_category->whereNull('parent_id')->get(['id', 'name']);
        return view('backEnd.product_category.edit', [
            'productCategory' => $product_category,
            'parent_categories' => $parent_category,
        ]);

            // return $product_category;

        // $productCategory = $product_category;
        // $main_categories = ProductCategory::whereNull('parent_id')->get(['id', 'name']);
        // return view('backEnd.product_category.edit', compact('main_categories','productCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function update(ProductCategoryRequest $request, ProductCategory $product_category)
    {
        if (!auth()->user()->ability('admin', 'update_product_categories')) {
            return redirect('admin/index');
        }
        $data['name'] = $request->name;
        $data['slug'] = null;
        $data['status'] = $request->status;
        $data['parent_id'] = $request->parent_id;

        if ($image = $request->file('cover')) {
            if ($product_category->cover != null && File::exists('assets/product_categories/'. $product_category->cover)){
                unlink('assets/product_categories/'. $product_category->cover);
            }
            $file_name = Str::slug($request->name).".".$image->getClientOriginalExtension();
            $path = public_path('/assets/product_categories/' . $file_name);
            Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $data['cover'] = $file_name;
        }

        $product_category->update($data);

        return redirect()->route('admin.product_categories.index')->with([
            'message' => 'Updated successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategory $product_category)
    {
        if (!auth()->user()->ability('admin', 'delete_product_categories')) {
            return redirect('admin/index');
        }
        if (File::exists('assets/product_categories/'. $product_category->cover)){
            unlink('assets/product_categories/'. $product_category->cover);
        }
        $product_category->delete();

        return redirect()->route('admin.product_categories.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'success'
        ]);
    }

    public function remove_image(Request $request)
    {
        if (!auth()->user()->ability('admin', 'delete_product_categories')) {
            return redirect('admin/index');
        }

        $category = ProductCategory::findOrFail($request->product_category_id);
        if (File::exists('assets/product_categories/' . $category->cover)) {
            unlink('assets/product_categories/' . $category->cover);
            $category->cover = null;
            $category->save();
        }
        return true;
    }
}
