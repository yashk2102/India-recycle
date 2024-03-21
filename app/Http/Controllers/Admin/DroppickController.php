<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
use App\Droppick;
use Illuminate\Http\Request;

class DroppickController extends Controller
{
    public function index()
    {
        $drop_picks = Droppick::join('locations','droppicks.location_id','=','locations.id')
        ->select('droppicks.*','locations.title','locations.address')->orderby('id','desc')->get();
        return view('admin.droppick.index',compact('drop_picks'));
    }

    public function create()
    {
        return view('admin.droppick.create');
    }


    public function store(Request $request)
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
            return back()->with('success','Successfully Created');
        }
        else{
            return back()->with('error','Oops! Something Went wrong!');
        }
    }


    public function show($id)
    {
        $data['drop'] = Droppick::find($id);
        // return view('admin.droppick.show',compact('drop_picks'));
        return response()->json(['success' => true,'data' => $data], 200);
    }


    public function edit($id)
    {
        $data['drop']   = Droppick::find($id);
        // return view('admin.droppick.edit',compact('drop_picks'));
        return response()->json(['success' => true,'data' => $data], 200);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required',
            'contact' => 'bail|required|numeric',
            // 'product_image' => 'mimes:png,jpg,jpeg',
        ]);

        $droppicks = Droppick::find($id);

        if($request->hasFile('product_image'))
        {
            $file = $request->file('product_image');
            File::delete(public_path('/images/product_image/'. $droppicks->product_image));
            $filename = $file->getClientOriginalName();
            $image_resize = Image::make($file->getRealPath());
            $image_resize->resize(400, 400);
            $image_resize->save(public_path('/images/product_image/' . $filename));
            $droppicks->product_image = $filename;
        }

        $droppicks->location_id = $request->location_id;
        $droppicks->name = $request->name;
        $droppicks->email = $request->email;
        $droppicks->contact = $request->contact;
        $droppicks->drop_pick = $request->drop_pick;
        $droppicks->dp_date = $request->dp_date;
        $droppicks->time_slot = $request->time_slot;
        $droppicks->product_details = $request->product_details;
        $droppicks->message = $request->message;

        $drop =  $droppicks->save();

            // return redirect()->route('droppick.index')->with('success', 'Successfully Updated !');
        return response()->json(['success' => true, 'data' => $drop, 'msg' => 'Edited successfully..!!'], 200);


    }


    public function destroy($id)
    {
        $drop_picks = Droppick::find($id);
        File::delete(public_path('/images/product_image/'. $drop_picks->product_image));
        $drop_picks->delete();
        return back()->with('success', 'Successfully Deleted !');
    }

    public function changestatus($id)
     {

         $drop_picks = Droppick::find($id);
         if($drop_picks->status == "Pending") {
             $drop_picks->status = "Approved";
             $drop_picks->save();
             return redirect()->back();
         }
         if($drop_picks->status == "Approved") {
             $drop_picks->status = "Pending";
             $drop_picks->save();
             return redirect()->back();
         }
     }
}
