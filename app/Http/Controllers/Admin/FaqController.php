<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faq = Faq::orderby('id','desc')->get();
        return view('admin.faq.index',compact('faq'));
    }

    public function create()
    {
        return view('admin.faq.create');
    }

    public function store(Request $request)
    {
        $faq = new Faq;
        $faq->title = $request->input('title');
        $faq->des = $request->input('des');
        $faq->save();
        return back()->with('success', 'faq Successfully Created !');
    }

    public function edit($id)
    {
        $faq = Faq::find($id);
        return view('admin.faq.edit',compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::find($id);
        $faq->title = $request->input('title');
        $faq->des = $request->input('des');
        $faq->save();
        return redirect()->route('faq.index')->with('success', 'faq Successfully Updated !');
    }

    public function destroy($id)
    {
        $faq = Faq::find($id);
        $faq->delete();
        return back()->with('success', 'faq Successfully Deleted !');
    }

    public function changestatus($id)
     {
         $faq = Faq::find($id);
         if($faq->status == "Active") {
             $faq->status = "Deactive";
             $faq->save();
             return redirect()->back();
         }
         if($faq->status == "Deactive") {
             $faq->status = "Active";
             $faq->save();
             return redirect()->back();
         }
     }
}
