<?php

namespace App\Http\Controllers\Admin;

use App\Gallery;
use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    function index()
    {
        $gallerys = Gallery::orderby('id','desc')->simplePaginate(12);
        return view('admin.gallery.index',compact('gallerys'));
    }

    function upload(Request $request)
    {

        $image = $request->file('file');
        // $imageName = time() . '.' . $image->extension();
        $imageName = $image->getClientOriginalName();

        $image->move(public_path('images/gallery/'), $imageName);

        $imageUpload = new Gallery();
        $imageUpload->images = $imageName;
        $imageUpload->save();
        return response()->json(['success' => $imageName]);
    }

    function delete($id)
    {
        $image = Gallery::select('*')->where('id', $id)->get();
        foreach ($image as $ptr) {
         File::delete(public_path('images/gallery/' . $ptr->images));

        }
        $image = Gallery::find($id)->delete();
        return $image;
    }

    public function changestatus($id)
    {

        $gallery = Setting::find($id);
        if($gallery->gallery_status == "Active") {
            $gallery->gallery_status = "Deactive";
            $gallery->save();
            return redirect()->back();
        }
        if($gallery->gallery_status == "Deactive") {
            $gallery->gallery_status = "Active";
            $gallery->save();
            return redirect()->back();
        }
    }
}
