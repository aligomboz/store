<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CustomerRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_customers, show_customers')) {
            return redirect('admin/index');
        }

        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);
        return view('backEnd.customers.index',[
            'customers' => $customers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_customers')) {
            return redirect('admin/index');
        }

        return view('backend.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        // return $request;
        if (!auth()->user()->ability('admin', 'create_customers')) {
            return redirect('admin/index');
        }

        $data['first_name'] = $request->first_name;
        $data['laset_name'] = $request->laset_name;
        $data['username'] = $request->username;
        $data['email'] = $request->email;
        $data['mobile'] = $request->mobile;
        $data['password'] = bcrypt($request->password);
        $data['status'] = $request->status;

        if ($image = $request->file('image')) {
            $file_name = Str::slug($request->username).".".$image->getClientOriginalExtension();
            $path = public_path('/assets/users/' . $file_name);
            Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $data['image'] = $file_name;
        }

        $customer = User::create($data);
        $customer->markEmailAsVerified();
        $customer->attachRole(Role::whereName('customer')->first()->id);

        return redirect()->route('admin.customers.index')->with([
            'message' => 'Created successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $customer)
    {
        if (!auth()->user()->ability('admin', 'update_customers')) {
            return redirect('admin/index');
        }
        return view('backend.customers.edit',[
            'customer' =>$customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $customer)
    {
        if (!auth()->user()->ability('admin', 'update_customers')) {
            return redirect('admin/index');
        }
        $data['first_name'] = $request->first_name;
        $data['laset_name'] = $request->laset_name;
        $data['username'] = $request->username;
        $data['email'] = $request->email;
        $data['mobile'] = $request->mobile;
        if (trim($request->password) != ''){
            $data['password'] = bcrypt($request->password);
        }
        $data['status'] = $request->status;

        if ($image = $request->file('image')) {
            if ($customer->image != null && File::exists('assets/users/'. $customer->image)){
                unlink('assets/users/'. $customer->image);
            }
            $file_name = Str::slug($request->username).".".$image->getClientOriginalExtension();
            $path = public_path('/assets/users/' . $file_name);
            Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $data['image'] = $file_name;
        }

        $customer->update($data);

        return redirect()->route('admin.customers.index')->with([
            'message' => 'Updated successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $customer)
    {
        if (!auth()->user()->ability('admin', 'delete_customers')) {
            return redirect('admin/index');
        }

        if (File::exists('assets/users/'. $customer->image)){
            unlink('assets/users/'. $customer->image);
        }
        $customer->delete();

        return redirect()->route('admin.customers.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'success'
        ]);
    }

    public function remove_image(Request $request)
    {
        if (!auth()->user()->ability('admin', 'delete_customers')) {
            return redirect('admin/index');
        }

        $customer = User::findOrFail($request->customer_id);
        if (File::exists('assets/users/'. $customer->user_image)){
            unlink('assets/users/'. $customer->user_image);
            $customer->user_image = null;
            $customer->save();
        }
        return true;
    }

    public function get_customers()
    {
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })
            ->when(\request()->input('query') != '', function ($query) {
                $query->search(\request()->input('query'));
            })
            ->get(['id', 'first_name', 'laset_name', 'email'])->toArray();

        return response()->json($customers);
    }
}
