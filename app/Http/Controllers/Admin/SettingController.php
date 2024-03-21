<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class SettingController extends Controller
{
     // footer
     public function footer()
     {
         $footer = Setting::first();
         return view('admin.frontendsetting.footer',compact('footer'));
     }
     public function footer_update(Request $request)
     {
         $request->validate([
            'favicon' => 'mimes:png,jpg,jpeg',
            'logo' => 'mimes:png,jpg,jpeg',
            'footerlogo' => 'mimes:png,jpg,jpeg'
         ]);

          $footer = Setting::first();

          if($request->hasFile('footerlogo'))
          {
             $image = $request->file('footerlogo');
             File::delete(public_path('/images/footer-image/'. $footer->footerlogo));
             $imageName = $image->getClientOriginalName();
             $image->move(public_path('images/footer-image'), $imageName);
             $footer->footerlogo = $imageName;
          }

          if($request->hasFile('favicon'))
         {
            $image = $request->file('favicon');
            File::delete(public_path('/images/header-image/'. $footer->favicon));
            $imageName = $image->getClientOriginalName();
            $image->move(public_path('images/header-image'), $imageName);
            $footer->favicon = $imageName;
         }

         if($request->hasFile('logo'))
         {
            $image = $request->file('logo');
            File::delete(public_path('/images/header-image/'. $footer->logo));
            $imageName = $image->getClientOriginalName();
            $image->move(public_path('images/header-image'), $imageName);
            $footer->logo = $imageName;
         }

          $footer->email = $request->input('email');
          $footer->contact = $request->input('contact');
          $footer->address = $request->input('address');

          $footer->copyright = $request->input('copyright');

          $footer->twitter = $request->input('twitter');
          $footer->facebook = $request->input('facebook');
          $footer->instagram = $request->input('instagram');
          $footer->linkedin = $request->input('linkedin');

          $footer->footer_about = $request->input('footer_about');
          $footer->privacy = $request->input('privacy');
          $footer->terms = $request->input('terms');
          $footer->co_policy = $request->input('co_policy');
          $footer->cancellation_policy = $request->input('cancellation_policy');

          $footer->save();
          return redirect()->back()->with('success','Footer Updated Successfully!');
      }

     //  end footer

     public function mission_vision()
     {
         $missions = Setting::first();
         return view('admin.frontendsetting.mission',compact('missions'));
     }

     public function mission_vision_update(Request $request)
     {
        $missions = Setting::first();

    //     if($request->hasFile('mission_img'))
    //     {

    //        $file = $request->file('mission_img');
    //        File::delete(public_path('/images/mission-vision/'. $missions->mission_img));
    //        $filename = $file->getClientOriginalName();
    //        $image_resize = Image::make($file->getRealPath());
    //        $image_resize->resize(800, 600);
    //        $image_resize->save(public_path('/images/mission-vision/' . $filename));
    //        $missions->mission_img = $filename;
    //     }

    //     if($request->hasFile('vision_img'))
    //    {

    //        $file = $request->file('vision_img');
    //        File::delete(public_path('/images/mission-vision/'. $missions->vision_img));
    //        $filename = $file->getClientOriginalName();
    //        $image_resize = Image::make($file->getRealPath());
    //        $image_resize->resize(800, 600);
    //        $image_resize->save(public_path('/images/mission-vision/' . $filename));
    //        $missions->vision_img = $filename;
    //    }


       $missions->mission_des = $request->input('mission_des');
       $missions->vision_des = $request->input('vision_des');

        $missions->save();
        return redirect()->back()->with('success','donated Updated Successfully!');
     }


     //counter
     public function counter()
     {
         $counter = Setting::first();
         return view('admin.frontendsetting.counter',compact('counter'));
     }

     public function counter_update(Request $request)
     {
        $counter = Setting::first();
        $counter->counter_title_one = $request->input('counter_title_one');
        $counter->counter_number_one = $request->input('counter_number_one');
        $counter->counter_title_two = $request->input('counter_title_two');
        $counter->counter_number_two = $request->input('counter_number_two');
        $counter->counter_title_three = $request->input('counter_title_three');
        $counter->counter_number_three = $request->input('counter_number_three');

        $counter->save();
        return redirect()->back()->with('success','counter Updated Successfully!');
     }

     public function about()
     {
         $abouts = Setting::first();
         return view('admin.frontendsetting.about',compact('abouts'));
     }

     public function about_update(Request $request)
     {
        $abouts = Setting::first();

        if($request->hasFile('about_img'))
        {


           $file = $request->file('about_img');
           File::delete(public_path('/images/about/'. $abouts->about_img));
           $filename = $file->getClientOriginalName();
           $image_resize = Image::make($file->getRealPath());
           $image_resize->resize(700, 400);
           $image_resize->save(public_path('/images/about/' . $filename));
           $abouts->about_img = $filename;


        }

       $abouts->about_title = $request->input('about_title');
       $abouts->about_des_one = $request->input('about_des_one');
       $abouts->about_des_two = $request->input('about_des_two');

        $abouts->save();
        return redirect()->back()->with('success','About Updated Successfully!');
     }

}
