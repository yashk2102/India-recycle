<?php

namespace App\Http\Controllers\Admin;

use App\Campaing;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;

class CampaingController extends Controller
{
    public function index()
    {
        $campaings = Campaing::orderby('id','desc')->get();
        return view('admin.campaings.index',compact('campaings'));
    }

    public function create()
    {
        return view('admin.campaings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'mimes:png,jpg,jpeg'
        ]);

            $campaings = new Campaing;

            if($request->hasFile('image'))
            {
                $file = $request->file('image');
                $filename = $file->getClientOriginalName();
                $image_resize = Image::make($file->getRealPath());
                $image_resize->resize(1921, 799);
                $image_resize->save(public_path('/images/campaings/' . $filename));
                $campaings->image = $filename;
            }
        $campaings->title = $request->input('title');
        $campaings->address = $request->input('address');

        $campaings->des = $request->input('des');
        $campaings->save();
        return back()->with('success', 'campaings Successfully Created !');
    }

    public function edit($id)
    {
        $campaings = Campaing::find($id);
        return view('admin.campaings.edit',compact('campaings'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'mimes:png,jpg,jpeg'
        ]);

        $campaings = Campaing::find($id);

        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            File::delete(public_path('/images/campaings/'. $campaings->image));
            $filename = $file->getClientOriginalName();
            $image_resize = Image::make($file->getRealPath());
            $image_resize->resize(1921, 799);
            $image_resize->save(public_path('/images/campaings/' . $filename));
            $campaings->image = $filename;
        }
        $campaings->title = $request->input('title');
        $campaings->address = $request->input('address');
        $campaings->des = $request->input('des');
        $campaings->save();
        return redirect()->route('campaings.index')->with('success', 'campaings Successfully Updated !');
    }

    public function destroy($id)
    {
        $campaings = Campaing::find($id);
        File::delete(public_path('/images/campaings/'. $campaings->image));
        $campaings->delete();
        return back()->with('success', 'campaings Successfully Deleted !');
    }

    public function changestatus($id)
     {

         $campaings = Campaing::find($id);
         if($campaings->status == "Active") {
             $campaings->status = "Deactive";
             $campaings->save();
             return redirect()->back();
         }
         if($campaings->status == "Deactive") {
             $campaings->status = "Active";
             $campaings->save();
             return redirect()->back();
         }
     }
}
