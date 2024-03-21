<?php

namespace App\Http\Controllers\Admin;

use App\Blog;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class BlogController extends Controller
{

    public function index()
    {
        $list = Blog::get();
        // $category = Category::where('status','Active')->get();
        return view('admin.blog.index',compact('list'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'blog_image' => 'mimes:png,jpg,jpeg',
        ]);
        $blog = new Blog;
        if($request->hasFile('blog_image'))
        {
        $file = $request->file('blog_image');
        $filename = $file->getClientOriginalName();
        $image_resize = Image::make($file->getRealPath());
        $image_resize->resize(800, 400);
        $image_resize->save(public_path('/images/blog/' . $filename));
        $blog->blog_image = $filename;

        }

        $blog->blog_name = $request->input('blog_name');
        // $blog->cat_id = $request->input('cat_id');
        $blog->sml_blog_description = $request->input('sml_blog_description');
        $blog->blog_description = $request->input('blog_description');

        $blog->save();
        return back()->with('success', 'blog Successfully Created !');
    }


    public function show(Blog $blog)
    {
        //
    }


    public function edit($id)
    {
        $blogs = Blog::find($id);
        $category=Category::where('status','Active')->get();
        return view('admin.blog.edit',compact('blogs','category'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'blog_image' => 'mimes:png,jpg,jpeg',
        ]);
        $blog = Blog::find($id);
        if($request->hasFile('blog_image'))
        {

        $file = $request->file('blog_image');
        File::delete(public_path('/images/blog/'. $blog->blog_image));
        $filename = $file->getClientOriginalName();
        $image_resize = Image::make($file->getRealPath());
        $image_resize->resize(800, 400);
        $image_resize->save(public_path('/images/blog/' . $filename));
        $blog->blog_image = $filename;

        }

        $blog->blog_name = $request->input('blog_name');
        // $blog->cat_id = $request->input('cat_id');
        $blog->sml_blog_description = $request->input('sml_blog_description');
        $blog->blog_description = $request->input('blog_description');
        $blog->save();
        return redirect()->route('blog.index')->with('success', 'blog Successfully Updated !');
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);
        File::delete(public_path('/images/blog/'. $blog->blog_image));
        $blog->delete();
        return back()->with('success', 'blog Successfully Deleted !');
    }

    public function changestatus($id)
     {
         $blog = Blog::find($id);
         if($blog->status == "Active") {
             $blog->status = "Deactive";
             $blog->save();
             return redirect()->back();
         }
         if($blog->status == "Deactive") {
             $blog->status = "Active";
             $blog->save();
             return redirect()->back();
         }
     }
}
