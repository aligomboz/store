<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_tags, show_tags')) {
            return redirect('admin/index');
        }

        $tags = Tag::with('products')
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);

        return view('backend.tags.index', [
            'tags' => $tags,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_tags')) {
            return redirect('admin/index');
        }

        return view('backend.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_tags')) {
            return redirect('admin/index');
        }

        // $input['name'] = $request->name;
        // $input['status'] = $request->status;
        // dd($request->validate());

        // Tag::create($request->validated());
        Tag::create([
            'name'=>$request->name,
            'slug' => null,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tags.index')->with([
            'message' => 'Created successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        if (!auth()->user()->ability('admin', 'display_tags')) {
            return redirect('admin/index');
        }

        return view('backend.tags.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        if (!auth()->user()->ability('admin', 'update_tags')) {
            return redirect('admin/index');
        }

        return view('backend.tags.edit',[
            'tag' => $tag,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $request, Tag $tag)
    {
        if (!auth()->user()->ability('admin', 'update_tags')) {
            return redirect('admin/index');
        }

        $tag->update([
            'name'=>$request->name,
            'slug' => null,
            'status' => $request->status,
        ]);

        // $data['name'] = $request->name;
        // $data['slug'] = null;
        // $data['status'] = $request->status;

        // $tag->update($data);

        return redirect()->route('admin.tags.index')->with([
            'message' => 'Updated successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        if (!auth()->user()->ability('admin', 'delete_tags')) {
            return redirect('admin/index');
        }
        $tag->delete();

        return redirect()->route('admin.tags.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'success'
        ]);
    
    }
}
