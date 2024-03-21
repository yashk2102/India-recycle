<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class TeamController extends Controller
{
    public function index()
    {
        $team = Team::orderby('id','desc')->get();
        return view('admin.team.index',compact('team'));
    }

    public function create()
    {
        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'mimes:png,jpg,jpeg'
        ]);

            $team = new Team;
            if($request->hasFile('image'))
            {

            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $image_resize = Image::make($file->getRealPath());
            $image_resize->resize(400, 450);
            $image_resize->save(public_path('/images/team/' . $filename));
            $team->image = $filename;

            }

        $team->name = $request->input('name');
        $team->des = $request->input('des');

        $team->save();
        return back()->with('success', 'team Successfully Created !');
    }

    public function edit($id)
    {
        $team = Team::find($id);
        return view('admin.team.edit',compact('team'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'mimes:png,jpg,jpeg',
            'document' => 'mimes:pdf'
        ]);

        $team = Team::find($id);

        if($request->hasFile('image'))
        {

        $file = $request->file('image');
        File::delete(public_path('/images/team/'. $team->image));
        $filename = $file->getClientOriginalName();
        $image_resize = Image::make($file->getRealPath());
        $image_resize->resize(400, 450);
        $image_resize->save(public_path('/images/team/' . $filename));
        $team->image = $filename;
        }

        $team->name = $request->input('name');
        $team->des = $request->input('des');

        $team->save();
        return redirect()->route('team.index')->with('success', 'team Successfully Updated !');
    }

    public function destroy($id)
    {
        $team = Team::find($id);
        File::delete(public_path('/images/team/'. $team->image));
        $team->delete();
        return back()->with('success', 'team Successfully Deleted !');
    }

    public function changestatus($id)
     {

         $team = Team::find($id);
         if($team->status == "Active") {
             $team->status = "Deactive";
             $team->save();
             return redirect()->back();
         }
         if($team->status == "Deactive") {
             $team->status = "Active";
             $team->save();
             return redirect()->back();
         }
     }
}
