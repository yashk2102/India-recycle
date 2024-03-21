<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class CompanyController extends Controller
{
    public function index()
    {
        $client = Company::orderby('id','desc')->get();
        return view('admin.client.index',compact('client'));
    }

    public function create()
    {
        return view('admin.client.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'mimes:png,jpg,jpeg'
        ]);

            $client = new Company;
            if($request->hasFile('image'))
            {

            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $image_resize = Image::make($file->getRealPath());
            $image_resize->resize(225, 56);
            $image_resize->save(public_path('/images/client/' . $filename));
            $client->image = $filename;

            }
        $client->save();
        return back()->with('success', 'client Successfully Created !');
    }

    public function edit($id)
    {
        $client = Company::find($id);
        return view('admin.client.edit',compact('client'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'mimes:png,jpg,jpeg'
        ]);

        $client = Company::find($id);

        if($request->hasFile('image'))
        {

            $file = $request->file('image');
            File::delete(public_path('/images/client/'. $client->image));
            $filename = $file->getClientOriginalName();
            $image_resize = Image::make($file->getRealPath());
            $image_resize->resize(225, 56);
            $image_resize->save(public_path('/images/client/' . $filename));
            $client->image = $filename;

        }
        $client->save();
        return redirect()->route('client.index')->with('success', 'client Successfully Updated !');
    }

    public function destroy($id)
    {
        $client = Company::find($id);
        File::delete(public_path('/images/client/'. $client->image));
        $client->delete();
        return back()->with('success', 'client Successfully Deleted !');
    }

    public function changestatus($id)
     {

         $client = Company::find($id);
         if($client->status == "Active") {
             $client->status = "Deactive";
             $client->save();
             return redirect()->back();
         }
         if($client->status == "Deactive") {
             $client->status = "Active";
             $client->save();
             return redirect()->back();
         }
     }
}
