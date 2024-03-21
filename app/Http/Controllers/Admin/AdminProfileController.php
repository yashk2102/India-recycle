<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;

class AdminProfileController extends Controller
{
    public function profile()
    {
        $profile = Auth()->user();
        //   $profile= User::where('id',auth()->user()->id)->get();
        return view('admin.profile.index')->with('profile', $profile);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'mimes:png,jpg,jpeg|max:2048',
            'name' => 'required',
            'email' => 'required',
            'contact' => 'required|max:15|min:10',
        ]);

        $profile = User::findOrFail($id);

        if ($request->hasfile('image')) {

            $file = $request->file('image');
            File::delete(public_path('/images/users/'. $profile->image));

            $filename = $file->getClientOriginalName();
            $image_resize = Image::make($file->getRealPath());
            $image_resize->resize(400, 400);
            // $image_resize->save(public_path('storage/users/' . $filename));
            // $profile->image = '/storage/users/'. $filename;

            $image_resize->save(public_path('/images/users/' . $filename));
            $profile->image = $filename;
        }

        $profile->name = $request->input('name');
        $profile->email = $request->input('email');
        $profile->contact = $request->input('contact');
        $profile->update();
        return redirect()->back()->with('success', 'Profile Update Successfully!');
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required', 'string', 'min:6'],
            'new_password' => ['required', 'string', 'min:6'],
            'confirm_password' => ['required', 'string', 'min:6', 'same:new_password'],
        ]);
        if (!Hash::check($request->current_password, Auth()->user()->password)) {
            return redirect()->back()->with('error', 'Old Password Does not Match!');
        } else {
            $password = Hash::make($request->new_password);
            User::find(Auth()->user()->id)->update(['password' => $password]);
            return redirect()->back()->with('success', 'Password Update Successfully!');
        }
    }
}
