<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Location;
use App\Locationimage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
class LocationController extends Controller
{

    public function index()
    {
        $locations = Location::orderby('id','desc')->get();
        return view('admin.locations.index',compact('locations'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'img' => 'mimes:png,jpg,jpeg'
        ]);

        $locations = new Location;
        if($request->hasFile('img'))
        {
            $file = $request->file('img');
            $filename = $file->getClientOriginalName();
            $image_resize = Image::make($file->getRealPath());
            $image_resize->resize(800,800);
            $image_resize->save(public_path('/images/locations/' . $filename));
            $locations->img = $filename;
        }
        $locations->title = $request->input('title');
        $locations->contact = $request->input('contact');
        $locations->maplink = $request->input('maplink');
        $locations->address = $request->input('address');
        $locations->des = $request->input('des');
        $locations->sml_des = $request->input('sml_des');

        $locations->save();
        return back()->with('success', 'locations Successfully Created !');
    }

    public function show($id)
    {
        $locations = Location::where('id', $id)->first();
        $locationimages = Locationimage::where('location_id', $id)->orderby('id','desc')->Paginate(12);
        return view('admin.locations.show', compact('locationimages', 'locations'));
    }

    public function edit($id)
    {
        $locations = Location::find($id);
        return view('admin.locations.edit',compact('locations'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'img' => 'mimes:png,jpg,jpeg'
        ]);

        $locations = Location::find($id);

        if($request->hasFile('img'))
        {
            $file = $request->file('img');
            File::delete(public_path('/images/locations/'. $locations->img));
            $filename = $file->getClientOriginalName();
            $image_resize = Image::make($file->getRealPath());
            $image_resize->resize(800,800);
            $image_resize->save(public_path('/images/locations/' . $filename));
            $locations->img = $filename;
        }
        $locations->title = $request->input('title');
        $locations->contact = $request->input('contact');
        $locations->maplink = $request->input('maplink');
        $locations->address = $request->input('address');
        $locations->sml_des = $request->input('sml_des');


        $locations->des = $request->input('des');
        $locations->save();
        return redirect()->route('locations.index')->with('success', 'locations Successfully Updated !');
    }

    public function destroy($id)
    {
        $locations = Location::find($id);
        File::delete(public_path('/images/locations/'. $locations->img));
        $locations->delete();
        return back()->with('success', 'locations Successfully Deleted !');
    }

    public function changestatus($id)
     {

         $locations = Location::find($id);
         if($locations->status == "Active") {
             $locations->status = "Deactive";
             $locations->save();
             return redirect()->back();
         }
         if($locations->status == "Deactive") {
             $locations->status = "Active";
             $locations->save();
             return redirect()->back();
         }
     }
}
