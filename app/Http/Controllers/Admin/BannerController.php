<?php

namespace App\Http\Controllers\Admin;

use App\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
class BannerController extends Controller
{

    public function index()
    {
        $banner = Banner::orderby('id','desc')->get();
        return view('admin.banner.index',compact('banner'));
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'mimes:png,jpg,jpeg'
        ]);

            $banner = new Banner;

            if($request->hasFile('image'))
            {
                $file = $request->file('image');
                $filename = $file->getClientOriginalName();
                $image_resize = Image::make($file->getRealPath());
                $image_resize->resize(1921, 799);
                $image_resize->save(public_path('/images/banner/' . $filename));
                $banner->image = $filename;
            }
        $banner->title = $request->input('title');
        $banner->des = $request->input('des');
        $banner->save();
        return back()->with('success', 'banner Successfully Created !');
    }

    public function edit($id)
    {
        $banner = Banner::find($id);
        return view('admin.banner.edit',compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'mimes:png,jpg,jpeg'
        ]);

        $banner = Banner::find($id);

        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            File::delete(public_path('/images/banner/'. $banner->image));
            $filename = $file->getClientOriginalName();
            $image_resize = Image::make($file->getRealPath());
            $image_resize->resize(1921, 799);
            $image_resize->save(public_path('/images/banner/' . $filename));
            $banner->image = $filename;

        }
        $banner->title = $request->input('title');
        $banner->des = $request->input('des');
        $banner->save();
        return redirect()->route('banner.index')->with('success', 'banner Successfully Updated !');
    }

    public function destroy($id)
    {
        $banner = Banner::find($id);
        File::delete(public_path('/images/banner/'. $banner->image));
        $banner->delete();
        return back()->with('success', 'banner Successfully Deleted !');
    }

    public function changestatus($id)
     {

         $banner = Banner::find($id);
         if($banner->status == "Active") {
             $banner->status = "Deactive";
             $banner->save();
             return redirect()->back();
         }
         if($banner->status == "Deactive") {
             $banner->status = "Active";
             $banner->save();
             return redirect()->back();
         }
     }
}
