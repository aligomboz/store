<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AdminInfoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
class backEndController extends Controller
{
    public function login(){
        return view('backEnd.auth.login');
    }
    public function register(){
        return view('backEnd.auth.register');
    }
    public function index(){
        return view('backEnd.admin.index');
    }
    public function account_settings()
    {
        return view('backend.account_settings');
    }

    public function update_account_settings(AdminInfoRequest $request)
    {
        if($request->validated()){
            $data['first_name'] = $request->first_name;
            $data['laset_name'] = $request->laset_name;
            $data['username'] = $request->username;
            $data['email'] = $request->email;
            $data['mobile'] = $request->mobile;
            if ($request->password != '') {
                $data['password'] = bcrypt($request->password);
            }
            if ($image = $request->file('image')) {
                if (auth()->user()->image != null && File::exists('assets/users/'. auth()->user()->image)){
                    unlink('assets/users/'. auth()->user()->image);
                }
                $file_name = Str::slug($request->username).".".$image->getClientOriginalExtension();
                $path = public_path('/assets/users/' . $file_name);
                Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $data['image'] = $file_name;
            }

            auth()->user()->update($data);

            return redirect()->route('admin.account_settings')->with([
                'message' => 'Updated successfully',
                'alert-type' => 'success'
            ]);

        }
    }


    public function remove_image(Request $request)
    {
        if (!auth()->user()->ability('admin', 'delete_supervisors')) {
            return redirect('admin/index');
        }

        if (File::exists('assets/users/'. auth()->user()->image)){
            unlink('assets/users/'. auth()->user()->image);
            auth()->user()->image = null;
            auth()->user()->save();
        }
        return true;
    }
}
