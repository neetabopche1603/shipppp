<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Booking;
use App\Models\Shipment;
use App\Models\Schedule;
use App\Models\Item;
use App\Models\Advertisment;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SubadminController extends Controller
{
// Logged in SubAdmin Dashboard

    public function module()
    {
        $image = User::select('profileimage')->where('email','=',Auth::user()->email)->get();
        $img = json_decode($image);

        return view('subadmin.module', compact('img'));
    }

// Logged in User Module CLient

    public function subAdminClients()
    {
        $data = Client::where('roles','=','1c')->get();
        $image = User::select('profileimage')->where('email','=',Auth::user()->email)->get();
        $img = json_decode($image);

        return view('subadmin.clients', compact('data','img'));
    }

// Bookings Page With All Bookings With Item List and User Details

    public function subAdminViewBooking()
    {
        $data = Booking::all();
        $image = User::select('profileimage')->where('email','=',Auth::user()->email)->get();
        $img = json_decode($image);
        return view('subadmin.bookings', compact('data','img'));
    }

// Delete Bookings 

    public function subAdmindeleteBooking($id)
    {
        Booking::find($id)->delete();
        return redirect()->route('subadmin.subAdminViewBooking');
    }        

// Function for Shipment View With All Shipment Data

    public function subAdminShipment()
    {
        $data = Shipment::where('roles','=',1)->get();
        $image = User::select('profileimage')->where('email','=',Auth::user()->email)->get();
        $img = json_decode($image);
        return view('subadmin.shipment', compact('data','img'));
    }

// Delete Shipment 

    public function subAdminDeleteShipment($id)
    {
        Shipment::find($id)->delete();
        return redirect()->route('subadmin.subAdminShipment');
    }    

// View Create Advertisment Page

    public function subAdminViewAdsManagement()
    {
        $image = User::select('profileimage')->where('email','=',Auth::user()->email)->get();
        $img = json_decode($image);
        return view('subadmin.advertisment', compact('img'));
    }

// View All Advertisment Page With Data

    public function subAdminViewAdvertisment()
    {
        $data = Advertisment::all();
        $image = User::select('profileimage')->where('email','=',Auth::user()->email)->get();
        $img = json_decode($image);
        return view('subadmin.viewAdvertisment', compact('data','img'));
    }    

// Create Advertisment By Super Admin     

    public function subAdmincreateAdvertisment(Request $request)
    {
        //Validation 

        $validator = Validator::make($request->all(), [
            "title" => "required",
            "file" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "descrption" => "required"
        ]);
        $destinationPath = public_path('/advertisment');
        if($request->hasfile('file'))
        {
            $image = $request->file('file');        
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $filename);
            
            $ads = new Advertisment();
            $ads->title = $request->title;
            $ads->image = $filename;
            $ads->description = $request->description;
            $ads->save();
        } 
        return redirect()->route('subadmin.subAdminViewAdvertisment');    
    }    
 
// Delete Advertisment    

    public function subAdmindeleteAdvertisment($id)
    {
        Advertisment::find($id)->delete();
        return redirect()->route('subadmin.subAdminViewAdvertisment');
    }   

// Function to view Sub Admin Profile

    public function subAdminProfile()
    {
        $data = User::where('email','=',Auth::user()->email)->get();
        $image = User::select('profileimage')->where('email','=',Auth::user()->email)->get();
        $img = json_decode($image);
        return view('subadmin.profile', compact('data','img'));
    }

// Function to Update Sub Admin Profile

    public function subAdminUpdateProfile(Request $request)
    {
        // DB::enableQueryLog();
        // VALIDATION OF Admin Profile DETAILS 

        $validator = Validator::make($request->all(), [
            "name" => "required|regex:/^[a-zA-Z]+$/u|max:255", 
            "lname" => "required|regex:/^[a-zA-Z]+$/u|max:255", 
            "phone" => "required|min:10|numeric",
            "country" => "required",
            "address" => "required",
            "file" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $destinationPath = public_path('/profile');
        if($request->hasfile('file')){
            $image = $request->file('file');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $filename);
            
        User::where('email','=',$request->email)->update(['name' => $request->name ,
                                            'lname' => $request->lname ,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                            'profileimage' =>  $filename
                                           ]);
                                        //    dd(DB::getQueryLog());
        }
        else{
            User::where('email','=',$request->email)->update(['name' => $request->name ,
                                            'lname' => $request->lname ,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                           ]);
        }
        return redirect()->route('subadmin.subAdminProfile'); 
    }

// Function to Reset Sub Admin Password

    public function subAdminResetAdminPassword(Request $request)
    {
        $request->validate([
            "password" => "min:6 | max:18", 
        ]);
   
        User::where('email', $request->email)->first()->update([
            'password' => Hash::make($request->password)
        ]);
        return redirect()->route('subadmin.subAdminProfile');
    }

// Schedule Shipment Page WIth All Schedule Shipments

    public function subAdminScheduleShipment()
    {
        $data = Schedule::all();
        $image = User::select('profileimage')->where('email','=',Auth::user()->email)->get();
        $img = json_decode($image);
        return view('subadmin.scheduleShipment', compact('data','img'));
    }

// Delete Schedule 

    public function subAdminDeleteSchedule($id)
    {
        Schedule::find($id)->delete();
        return redirect()->route('subadmin.subAdminScheduleShipment');
    }

// Get Scheduled Shipment Details 

    public function subAdminViewScheduleDetails($id)
    {
        $data = DB::table('schedules')
            ->join('shipments', 'schedules.sid','=', 'shipments.id')->where('schedules.sid',$id)
            ->first(['shipments.companyname']);

        return response()->json($data);
    }



}
