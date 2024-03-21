<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Project;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderby('id','desc')->get();
        return view('admin.projects.index',compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'mimes:png,jpg,jpeg'
        ]);

            $projects = new Project;

            if($request->hasFile('image'))
            {
                $file = $request->file('image');
                $filename = $file->getClientOriginalName();
                $image_resize = Image::make($file->getRealPath());
                $image_resize->resize(500, 250);
                $image_resize->save(public_path('/images/projects/' . $filename));
                $projects->image = $filename;
            }
        $projects->title = $request->input('title');

        $projects->des = $request->input('des');
        $projects->save();
        return back()->with('success', 'projects Successfully Created !');
    }

    public function edit($id)
    {
        $projects = Project::find($id);
        return view('admin.projects.edit',compact('projects'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'mimes:png,jpg,jpeg'
        ]);

        $projects = Project::find($id);

        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            File::delete(public_path('/images/projects/'. $projects->image));
            $filename = $file->getClientOriginalName();
            $image_resize = Image::make($file->getRealPath());
            $image_resize->resize(500, 250);
            $image_resize->save(public_path('/images/projects/' . $filename));
            $projects->image = $filename;
        }
        $projects->title = $request->input('title');
        $projects->des = $request->input('des');
        $projects->save();
        return redirect()->route('projects.index')->with('success', 'projects Successfully Updated !');
    }

    public function destroy($id)
    {
        $projects = Project::find($id);
        File::delete(public_path('/images/projects/'. $projects->image));
        $projects->delete();
        return back()->with('success', 'projects Successfully Deleted !');
    }

    public function changestatus($id)
     {

         $projects = Project::find($id);
         if($projects->status == "Active") {
             $projects->status = "Deactive";
             $projects->save();
             return redirect()->back();
         }
         if($projects->status == "Deactive") {
             $projects->status = "Active";
             $projects->save();
             return redirect()->back();
         }
     }
}
