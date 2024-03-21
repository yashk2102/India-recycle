<?php




namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Contact;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class ContactController extends Controller
{

    public function index()
    {
        $contacts = Contact::orderby('id','desc')->get();
        return view('admin.contact.index',compact('contacts'));
    }

    public function destroy($id)
    {
        Contact::find($id)->delete();
        return back()->with('success','Contact Deleted Successfully!');
    }


}
