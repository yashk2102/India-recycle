<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;


class CategoryController extends Controller
{
    public function index()
    {
        $category=Category::get();
        return view('admin.category.index',compact('category'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $category = new Category;
        $category->cat_name = $request->input('cat_name');
        $category->save();
        return back()->with('success', 'category Successfully Created !');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $category=Category::find($id);
        return view('admin.category.edit',compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category=Category::find($id);
        $category->cat_name = $request->input('cat_name');
        $category->update();
        return redirect()->route('category.index')->with('success', 'category Successfully Updated !');
     
    }

    public function destroy($id)
    {
        $category=Category::find($id);
        $category->delete();
        return back()->with('success', 'category Successfully Deleted !');
     
    }

    public function changestatus($id)
    {

        $category = Category::find($id);
        if($category->status == "Active") {
            $category->status = "Deactive";
            $category->save();
            return redirect()->back();
        }
        if($category->status == "Deactive") {
            $category->status = "Active";
            $category->save();
            return redirect()->back();
        }
    }

}
