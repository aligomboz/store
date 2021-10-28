<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ProfileRequest;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function dashboard()
    {
        return view('frontEnd.customer.index');
    }

    public function profile()
    {
        return view('frontEnd.customer.profile');
    }

    public function update_profile(ProfileRequest $request)
    {
        
        return $request;
        $user = auth()->user();
        $data['first_name'] = $request->first_name;
        $data['laset_name'] = $request->laset_name;
        $data['email'] = $request->email;
        $data['mobile'] = $request->mobile;

        if (!empty($request->password) && !Hash::check($request->password, $user->password)) {
            $data['password'] = bcrypt($request->password);
        }

        if ($image = $request->file('image')) {
            if ($user->image != '') {
                if (File::exists('assets/users/' . $user->image)){
                    unlink('assets/users/' . $user->image);
                }
            }

            $file_name = $user->username . '.' . $image->extension();
            $path = public_path('assets/users/'. $file_name);
            Image::make($image->getRealPath())->resize(300, null, function ($constraints) {
                $constraints->aspectRatio();
            })->save($path, 100);
            $data['image'] = $file_name;
        }

        $user->update($data);

        toast('Profile updated', 'success');
        return back();
    }

    public function remove_profile_image()
    {
        $user = auth()->user();
        if (isset($user->image)) {
            if (File::exists('assets/users/' . $user->image)){
                unlink('assets/users/' . $user->image);
            }
        }
        $user->image = null;
        $user->save();
        toast('Profile image deleted', 'success');
        return back();
    }


    public function addresses()
    {
        return view('frontEnd.customer.addresses');
    }

    public function orders()
    {
        return view('frontEnd.customer.orders');
    }
}
