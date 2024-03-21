<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Locationimage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class LocationimageController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $image_resize = Image::make($file->getRealPath());
        $image_resize->resize(800, 800);
        $image_resize->save(public_path('/images/locationimage/' . $filename));
        // $locations->image = $filename;

        $imageUpload = new Locationimage();
        $imageUpload->location_id = $request->input('location_id');
        $imageUpload->image_path = $filename;
        $imageUpload->save();
        return response()->json(['success' => $filename]);
    }

    function delete($id)
    {
        $image = Locationimage::select('*')->where('id', $id)->get();
        foreach ($image as $ptr) {
         File::delete(public_path('images/locationimage/' . $ptr->image_path));

        }
        $image = Locationimage::find($id)->delete();
        return $image;
    }
}
