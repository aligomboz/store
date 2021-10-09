<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SupervisorRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Supervisor;
use App\Models\User;
use App\Models\UserPermissions;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_supervisors, show_supervisors')) {
            return redirect('admin/index');
        }

        $supervisors = User::whereHas('roles', function ($query) {
            $query->where('name', 'supervisor');
        })
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);
        return view('backend.supervisors.index', [
            'supervisors' => $supervisors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_supervisors')) {
            return redirect('admin/index');
        }

        $permissions = Permission::get(['id', 'display_name']);
        return view('backend.supervisors.create',[
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupervisorRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_supervisors')) {
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

        $supervisor = User::create($data);
        $supervisor->markEmailAsVerified();
        $supervisor->attachRole(Role::whereName('supervisor')->first()->id);
        // add permissions
        if (isset($request->permissions) && count($request->permissions) > 0) {
            $supervisor->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.supervisors.index')->with([
            'message' => 'Created successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supervisor  $supervisor
     * @return \Illuminate\Http\Response
     */
    public function show(User $supervisor)
    {
        if (!auth()->user()->ability('admin', 'update_supervisors')) {
            return redirect('admin/index');
        }

        $permissions = Permission::get(['id', 'display_name']);
        $supervisorPermissions = UserPermissions::whereUserId($supervisor->id)->pluck('permission_id')->toArray();
        return view('backend.supervisors.edit', compact('supervisor', 'permissions', 'supervisorPermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supervisor  $supervisor
     * @return \Illuminate\Http\Response
     */
    public function edit(User $supervisor)
    {
        if (!auth()->user()->ability('admin', 'update_supervisors')) {
            return redirect('admin/index');
        }

        $permissions = Permission::get(['id', 'display_name']);
        $supervisorPermissions = UserPermissions::whereUserId($supervisor->id)->pluck('permission_id')->toArray();
        return view('backend.supervisors.edit', compact('supervisor', 'permissions', 'supervisorPermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supervisor  $supervisor
     * @return \Illuminate\Http\Response
     */
    public function update(SupervisorRequest $request, User $supervisor)
    {
        if (!auth()->user()->ability('admin', 'update_supervisors')) {
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
            if ($supervisor->image != null && File::exists('assets/users/'. $supervisor->image)){
                unlink('assets/users/'. $supervisor->image);
            }
            $file_name = Str::slug($request->username).".".$image->getClientOriginalExtension();
            $path = public_path('/assets/users/' . $file_name);
            Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $data['user_image'] = $file_name;
        }

        $supervisor->update($data);
        // add permissions
        if (isset($request->permissions) && count($request->permissions) > 0) {
            $supervisor->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.supervisors.index')->with([
            'message' => 'Updated successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supervisor  $supervisor
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $supervisor)
    {
        
        if (!auth()->user()->ability('admin', 'delete_supervisors')) {
            return redirect('admin/index');
        }

        if (File::exists('assets/users/'. $supervisor->image)){
            unlink('assets/users/'. $supervisor->image);
        }
        $supervisor->delete();

        return redirect()->route('admin.supervisors.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'success'
        ]);
    }

    public function remove_image(Request $request)
    {
        if (!auth()->user()->ability('admin', 'delete_supervisors')) {
            return redirect('admin/index');
        }

        $supervisor = User::findOrFail($request->supervisor_id);
        if (File::exists('assets/users/'. $supervisor->image)){
            unlink('assets/users/'. $supervisor->image);
            $supervisor->image = null;
            $supervisor->save();
        }
        return true;
    }
}
