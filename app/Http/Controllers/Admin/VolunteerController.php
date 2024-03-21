<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VolunteerController extends Controller
{

    public function index(Request $request)
    {
        $volunteers =  Volunteer::join('categories','volunteers.cat_id','=','categories.id')
        ->select('volunteers.*','categories.cat_name')->get();
        $category = Category::where('status', 'Active')->get();

        return view('admin.volunteers.index',compact('category','volunteers'));
    }



    public function create()
    {
        $category = Category::where('status', 'Active')->get();
        return view('admin.volunteers.create',compact('category'));
    }

    public function store(Request $request)
    {
        $volunteers = new Volunteer;
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:volunteers,email,' . $volunteers->id,
            'contact' => 'required|numeric',
        ]);

        $volunteers = new Volunteer;

        $volunteers->name = $request->input('name');
        $volunteers->email = $request->input('email');
        $volunteers->contact = $request->input('contact');

        // $volunteers->address = $request->input('address');
        $volunteers->message = $request->input('message');
        $volunteers->cat_id = $request->input('cat_id');

        $volunteers->save();

        return back()->with('success', 'volunteers Successfully Created !');
    }

    public function edit($id)
    {
        $volunteers = Volunteer::find($id);
        $category = Category::where('status', 'Active')->get();
        return view('admin.volunteers.edit', compact('volunteers','category'));
    }

    public function update(Request $request, $id)
    {
        $volunteers = Volunteer::find($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:volunteers,email,' . $volunteers->id,
            'contact' => 'required|numeric',
        ]);

        $volunteers->name = $request->input('name');
        $volunteers->email = $request->input('email');
        $volunteers->contact = $request->input('contact');

        // $volunteers->address = $request->input('address');
        $volunteers->message = $request->input('message');
        $volunteers->cat_id = $request->input('cat_id');
        $volunteers->save();
        return redirect()->route('volunteers.index')->with('success', 'volunteers Successfully Updated !');
    }

    public function destroy($id)
    {
        $volunteers = Volunteer::find($id);
        $volunteers->delete();
        return back()->with('success', 'volunteers Successfully Deleted !');
    }

    public function changestatus($id)
    {

        $volunteers = Volunteer::find($id);
        if ($volunteers->status == "Active") {
            $volunteers->status = "Deactive";
            $volunteers->save();
            return redirect()->back();
        }
        if ($volunteers->status == "Deactive") {
            $volunteers->status = "Active";
            $volunteers->save();
            return redirect()->back();
        }
    }
}
