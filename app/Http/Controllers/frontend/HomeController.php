<?php

namespace App\Http\Controllers\frontend;

use App\Blog;
use App\Contact;
use App\Droppick;
use App\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Location;
use App\Locationimage;
use App\Volunteer;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{

    public function index()
    {
        return view('frontend.home.index');
    }

    //contact

    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required',
            'contact' => 'bail|required|min:10|numeric',
        ]);

        $contact = new Contact();
        $contact->name = $request->input('name');
        $contact->email = $request->input('email');
        $contact->contact = $request->input('contact');
        $contact->subject = $request->input('subject');
        $contact->message = $request->input('message');
        $status = $contact->save();
        if($status){
            return back()->with('success','Thank you for Contact us');
        }
        else{
            return back()->with('error','Oops! Something Went wrong!');
        }
    }



    public function location($id)
    {
        $locationdetail = Location::where('id',$id)->first();
        $locationimages = Locationimage::where('location_id', $id)->orderby('id','desc')->get();

        return view('frontend.location.show',compact('locationdetail','locationimages'));
    }





    public function feedback_insert(Request $request)
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
            return back()->with('success','Thank you for feedback');
        }
        else{
            return back()->with('error','Oops! Something Went wrong!');
        }
    }


    public function SearchautoComplete(Request $request)
    {
        $query = $request->get('term','');
        $locations = Location::where('title','LIKE','%'.$query.'%')->where('status', 'Active')->get();

        $data =[];
        foreach ($locations as $items){
            $data [] = [

                'value' =>$items->title,
                'id'  =>$items->id
            ];
        }
        if(count($data)){
            return $data;
        }
        else
        {
            return ['value'=>'No Result Found','id'=>''];
        }
    }
    public function result(Request $request)
    {
        $searchingdata = $request->input('search_location');
        $locations = Location::where('title','LIKE','%'.$searchingdata.'%')->where('status', 'Active')->first();

        if($locations){
            if(isset($_POST['searchbtn']))
            {
                return redirect('drop-off-location/detail/'.$locations->id);
            }
            else
            {
                return redirect('drop-off-location/detail/'.$locations->id);
            }
        }
        else{
            return redirect('/drop-off-location')->with('error','Location Not Available');
        }
    }


    public function blog($id)
    {
        $blogdetail = Blog::where('id',$id)->first();

         $blogKey = 'blog_' . $blogdetail->id;

        if (!Session::has($blogKey)) {
            $blogdetail->increment('view_count');
            Session::put($blogKey,1);
        }

        return view('frontend.blog.show',compact('blogdetail'));
    }




    public function volunteerinsert(Request $request)
    {
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required',
            'contact' => 'bail|required|min:10|numeric',
        ]);

        $volunteers = new Volunteer();
        $volunteers->cat_id = $request->input('cat_id');
        $volunteers->name = $request->input('name');
        $volunteers->email = $request->input('email');
        $volunteers->contact = $request->input('contact');
        // $volunteers->address = $request->input('address');
        $volunteers->message = $request->input('message');

        $status = $volunteers->save();
        if($status){
            return back()->with('success','Thank you for Contact us');
        }
        else{
            return back()->with('error','Oops! Something Went wrong!');
        }
    }

    public function drop_pick_insert(Request $request)
    {
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required',
            'contact' => 'bail|required|numeric',
            'product_image' => 'mimes:png,jpg,jpeg',
        ]);

        $drop_picks = new Droppick();

        if($request->hasFile('product_image'))
        {
            $file = $request->file('product_image');
            $filename = $file->getClientOriginalName();
            $image_resize = Image::make($file->getRealPath());
            $image_resize->resize(400, 400);
            $image_resize->save(public_path('/images/product_image/' . $filename));
            $drop_picks->product_image = $filename;
        }

        $drop_picks->location_id = $request->input('location_id');
        $drop_picks->name = $request->input('name');
        $drop_picks->email = $request->input('email');
        $drop_picks->contact = $request->input('contact');
        $drop_picks->drop_pick = $request->input('drop_pick');
        $drop_picks->dp_date = $request->input('dp_date');
        $drop_picks->time_slot = $request->input('time_slot');
        $drop_picks->product_details = $request->input('product_details');
        $drop_picks->message = $request->input('message');

        $status = $drop_picks->save();
        if($status){
            return back()->with('success','Thank you for Contact us');
        }
        else{
            return back()->with('error','Oops! Something Went wrong!');
        }
    }
}
