<?php

namespace App\Http\Controllers\Admin;

use App\Feedback;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{

    public function index()
    {
        $feedback = Feedback::orderby('id','desc')->get();
        return view('admin.feedback.index',compact('feedback'));
    }

    public function destroy($id)
    {
        Feedback::find($id)->delete();
        return back()->with('success','feedback Deleted Successfully!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required',
        ]);

        $feedback = new Feedback();
        $feedback->name = $request->input('name');
        $feedback->email = $request->input('email');
        $feedback->message = $request->input('message');
        $status = $feedback->save();
        if($status){
            return back()->with('success','Feedback Successfully Created !');
        }
        else{
            return back()->with('error','Oops! Something Went wrong!');
        }
    }

    public function changestatus($id)
    {

        $feedback = Feedback::find($id);
        if($feedback->status == "Active") {
            $feedback->status = "Deactive";
            $feedback->save();
            return redirect()->back();
        }
        if($feedback->status == "Deactive") {
            $feedback->status = "Active";
            $feedback->save();
            return redirect()->back();
        }
    }
}
