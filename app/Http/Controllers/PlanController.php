<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Shipment;
use App\Models\Schedule;
use App\Models\Booking;
use App\Models\Item;
use App\Models\Advertisment;
use App\Models\Subadmin;
use App\Models\Blog;
use App\Models\Marketp;
use App\Models\ClientStatus;
use App\Models\ShipmentStatus;
use App\Models\Query;
use App\Models\QueryStatus;
use App\Models\Coupon;
use App\Models\ItemCategory;
use App\Models\Notification;
use DB;
use PDF;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
// Function to view Admin Profile

    public function profile()
    {
        $data = User::where('is_admin','=',1)->get();
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.profile', compact('data','img'));
    }

// Function to Update Admin Profile

    public function updateProfile(Request $request)
    {  


        // DB::enableQueryLog();
        // VALIDATION OF Admin Profile DETAILS 

        $validator = Validator::make($request->all(), [
            "name" => "regex:/^[a-zA-Z]+$/u|max:255",
            "lname" => "regex:/^[a-zA-Z]+$/u|max:255",
            "phone" => "min:10|numeric",
            "file" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $destinationPath = public_path('/image');
        if($request->hasfile('file')){
            $image = $request->file('file');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $filename);
            
        User::where('is_admin', 1)->update(['name' => $request->name ,
                                            'lname' => $request->lname ,
                                            'email' => $request->email,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                            'profileimage' =>  $filename
                                           ]);
        }
        else{
            User::where('is_admin', 1)->update(['name' => $request->name ,
                                            'lname' => $request->lname ,
                                            'email' => $request->email,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                           ]);
        }
                                        //    dd(DB::getQueryLog());
        
        return redirect()->route('admin.profile'); 
    }

// Function for admin to update Client

    public function updateClientProfile(Request $request)
    {  


        // DB::enableQueryLog();
        // VALIDATION OF Admin Profile DETAILS 

        $validator = Validator::make($request->all(), [
            "name" => "regex:/^[a-zA-Z]+$/u|max:255",
            "lname" => "regex:/^[a-zA-Z]+$/u|max:255",
            "phone" => "min:10|numeric",
            "file" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $destinationPath = public_path('/image');
        if($request->hasfile('file')){
            $image = $request->file('file');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $filename);
            
        Client::where('email','=',$request->email)->update(['name' => $request->name ,
                                            'lname' => $request->lname ,
                                            'email' => $request->email,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                            'profileimage' =>  $filename ,
                                            'about_me' => $request->about_me,
                                            'language' => $request->language
                                           ]);
        }
        else{
            Client::where('email','=',$request->email)->update(['name' => $request->name ,
                                            'lname' => $request->lname ,
                                            'email' => $request->email,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                            'about_me' => $request->about_me,
                                            'language' => $request->language
                                           ]);
        }
                                        //    dd(DB::getQueryLog());
        
        return redirect()->route('admin.clients'); 
    
    }

// Function for admin to update Client

    public function updateShipmentProfile(Request $request)
    {  
        
        $validator = Validator::make($request->all(), [
            "name" => "regex:/^[a-zA-Z]+$/u|max:255",
            "lname" => "regex:/^[a-zA-Z]+$/u|max:255",
            "phone" => "min:10|numeric",
            "file" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $destinationPath = public_path('/image');
        if($request->hasfile('file')){
            $image = $request->file('file');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $filename);
            
        Shipment::where('email','=',$request->email)->update(['name' => $request->name ,
                                            'lname' => $request->lname ,
                                            'email' => $request->email,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                            'profileimage' =>  $filename ,
                                            'about_me' => $request->about_me,
                                            'language' => $request->language,
                                            'companyname' => $request->companyname,
                                            'annualshipment' => $request->annualshipment
                                        ]);
        }
        else{
            Shipment::where('email','=',$request->email)->update(['name' => $request->name ,
                                            'lname' => $request->lname ,
                                            'email' => $request->email,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                            'about_me' => $request->about_me,
                                            'language' => $request->language,
                                            'companyname' => $request->companyname,
                                            'annualshipment' => $request->annualshipment
                                        ]);
        }
        
        return redirect()->route('admin.shipment'); 

    }

// Function to View Client All Details in Admin panel

    public function clientDetails($id)
    {
        $data = Client::where('id','=',$id)->get();
        $book = DB::table('clients')
        ->join('bookings', 'bookings.uid','=', 'clients.id')->where('bookings.uid',$id)->count();
        $pending = Booking::where('uid',$id)->where('status','=','Not Confirmed')->count();
        $confirmed = Booking::where('uid',$id)->where('status','=','Confirmed')->count();
        $completed = Booking::where('uid',$id)->where('status','=','Completed')->count();
        $item = DB::table('clients')
        ->join('items', 'items.uid','=', 'clients.id')->where('items.uid',$id)->count();
        $clientDetails = ClientStatus::where('uid','=',$id)->orderBy('id', 'DESC')->get();
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.clientDetails', compact('data','img','book','item','clientDetails','pending','confirmed','completed'));
    }

// Function to View Client All Details in Admin panel From Support

    // public function clientDetailss($name)
    // {
    //     $data = Client::where('name','=',$name)->get();
        
    //     $id = $data[0]->id;

    //     $book = DB::table('clients')
    //     ->join('bookings', 'bookings.uid','=', 'clients.id')->where('bookings.uid',$id)->count();
    //     $item = DB::table('clients')
    //     ->join('items', 'items.uid','=', 'clients.id')->where('items.uid',$id)->count();
    //     $clientDetails = ClientStatus::where('uid','=',$id)->orderBy('id', 'DESC')->get();
    //     $image = User::select('profileimage')->where('is_admin','=',1)->get();
    //     $img = json_decode($image);
    //     return view('admin.clientDetails', compact('data','img','book','item','clientDetails'));
    // }

// Function to View Shipment All Details in Admin panel

    public function shipmentDetails($id)
    {
        $data = Shipment::where('id','=',$id)->get();
        $book = DB::table('shipments')
        ->join('schedules', 'shipments.id','=', 'schedules.sid')->where('schedules.sid',$id)->count();
        $shipmentDetails = ShipmentStatus::where('sid','=',$id)->orderBy('id', 'DESC')->get();
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.shipmentDetails', compact('data','img','book','shipmentDetails'));
    }

// Function to Download I9 Document PDF

    public function generatePDF($id)
    {
        // $data = Shipment::select('docs')->where('id','=',$id)->get();
        $data = Shipment::where('id','=',$id)->get();
        // $data = $data[0];


        // $htmlContent = '';

        // foreach (json_decode($data->docs) as $row){
        //     $image = asset('image/'. $row);
        //     $htmlContent = $htmlContent.'<img class="img-size img-responsive" src="'.$image.'" />';
        // }

        // print_r($data); die;
        $pdf = PDF::loadView('admin.I9document', ['data' => $data]);

        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed'=> TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );

        return $pdf->download('I9document.pdf');
    }

// Function to Download Tax Document PDF

    public function generatePDFT($id)
    {
        // $data = Shipment::select('docs')->where('id','=',$id)->get();
        $data = Shipment::where('id','=',$id)->get();
        // $data = $data[0];


        // $htmlContent = '';

        // foreach (json_decode($data->tax_docs) as $row){
        //     $image = asset('image/'. $row);
        //     $htmlContent = $htmlContent.'<img class="img-size img-responsive" src="'.$image.'" />';
        // }

        // print_r($data); die;
        $pdf = PDF::loadView('admin.taxDocument', ['data' => $data]);

        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed'=> TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );

        return $pdf->download('Taxdocument.pdf');
    }

// Function to Download Driving Licence Document PDF

    public function generatePDFD($id)
    {
        // $data = Shipment::select('docs')->where('id','=',$id)->get();
        $data = Shipment::where('id','=',$id)->get();
        // $data = $data[0];


        // $htmlContent = '';

        // foreach (json_decode($data->driving_licence) as $row){
        //     $image = asset('image/'. $row);
        //     $htmlContent = $htmlContent.'<img class="img-size img-responsive" src="'.$image.'" />';
        // }

        // print_r($data); die;
        $pdf = PDF::loadView('admin.drivingDocument', ['data' => $data]);

        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed'=> TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );

        return $pdf->download('DrivingLicencedocument.pdf');
    }

// Function to Change Client Status

    public function changeClientStatus($id)
    {
        $data = Client::where('id','=',$id)->first();
        return response()->json($data);
    }

// Function to Change Client Status

    public function changeClientMarketStatus($id)
    {
        $data = Marketp::where('id','=',$id)->first();
        return response()->json($data);
    }

// Function to Update Client Status 

    public function updateStatus(Request $request)
    {    
        $cStatus = new ClientStatus();
        $cStatus->uid = $request->uid;
        $cStatus->status = $request->status;
        $cStatus->comment = $request->comment;
        $cStatus->save();

        Client::where('email', $request->email)->update(['status' => $request->status  ]);
        
        return redirect()->route('admin.clients');
    }

// Function to Change Client Status

    public function changeShipmentStatus($id)
    {
        $data = Shipment::where('id','=',$id)->first();
        return response()->json($data);
    }

// Function to Update Shipment Status 

    public function updateStatusShipment(Request $request)
    {    
        $sStatus = new ShipmentStatus();
        $sStatus->sid = $request->sid;
        $sStatus->status = $request->status;
        $sStatus->comment = $request->comment;
        $sStatus->save();

        Shipment::where('email', $request->email)->update(['status' => $request->status  ]);
        Schedule::where('sid', $request->sid)->update(['permission_status' => $request->status  ]);
        Schedule::where('sid', $request->sid)->update(['status' => 'Open'  ]);

        if($request->status == 'Approved')
        {
            $notification = new Notification();
            $notification->msg = "Verification done, Now you can schedule your shipments.";
            $notification->title = "Congratulations !!";
            $notification->sid = $request->sid;
            $notification->save();
        }
        else
        {
            $notification = new Notification();
            $notification->msg = "Verification fail, Please contact to admin for more query.";
            $notification->title = "Ops  !!";
            $notification->sid = $request->sid;
            $notification->save();
        }
        return redirect()->route('admin.shipment');
    }

// Fucntion to Update Market Place Status For Client

    public function updateClientMarketStatus(Request $request)
    {
        Marketp::where('id', $request->id)->update(['status' => $request->status]);
        return redirect()->route('admin.marketPlace');
    }

// Function to Reset Admin Password

    public function resetAdminPassword(Request $request)
    {
        $request->validate([
            "password" => "min:6 | max:18", 
        ]);
   
        User::where('email', $request->email)->first()->update([
            'password' => Hash::make($request->password)
        ]);
        return redirect()->route('admin.profile');
    }

// Function for Clients View With All Clients Data

    public function clients()
    {
        $data = Client::where('roles','=',1)->orderBy('created_at', 'DESC')->get();
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.clients', compact('data','img'));
    }

// Function for Shipment View With All Shipment Data

    public function shipment()
    {
        $data = Shipment::where('roles','=',1)->orderBy('created_at', 'DESC')->get();
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.shipment', compact('data','img'));
    }

// Function to view Market Place

    public function marketPlace()
    {
        $data = DB::table('marketps')
        ->join('clients', 'clients.id','=', 'marketps.uid')
        ->join('shipments', 'shipments.id','=','marketps.sid')
        ->join('market_bookings', 'market_bookings.mid','=','marketps.id')
        ->select('shipments.companyname','marketps.*', 'clients.name','market_bookings.transaction_id','market_bookings.total_amount')
        ->orderBy('marketps.created_at','DESC')->get();
        
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.marketPlace', compact('data','img'));
    }

// Function to View Client All Details in Admin panel

    public function marketDetails($id)
    {
        $data = DB::table('marketps')
        ->join('clients', 'clients.id','=', 'marketps.uid')->where('marketps.id','=',$id)
        ->select('marketps.*', 'clients.name')->get();
        // $items = DB::table('marketps')
        // ->join('items', 'items.uid','=','marketps.uid')->where('marketps.id','=',$id)
        // ->select('items.*','marketps.shipping_price','shipping_tax','pickup_fee')->get();
        $shipment = DB::table('marketps')
        ->join('shipments', 'shipments.id','=','marketps.sid')->where('marketps.id','=',$id)
        ->select('shipments.*')->get();
        // ->select('marketps.*', 'items.*', 'clients.name','shipments.companyname')->where('marketps.id','=',$request->id)->get();
         // print_r($data); die;
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.marketPlaceDetails', compact('data','shipment','img'));
    }

// Function to View Client All Details in Admin panel

    public function scheduleShipmentDetails($id)
    {
        $data = DB::table('schedules')
            ->join('shipments', 'schedules.sid','=', 'shipments.id')->where('schedules.id',$id)
            ->select('shipments.companyname','shipments.status as shipmentStatus','schedules.*')->get();

        $id = $data[0]->id;

        $item = DB::table('schedules')->select('item_type')->where('id',$id)->get();
        // dd($item);
        //  dd(json_decode($data->item_type));
        // $id = $data->id;
        $avalible = DB::table('schedule_items')
            ->join('item_master', 'item_master.schedule_item_id','=','schedule_items.id')
            ->join('item_category', 'item_category.id','=','item_master.category')
            ->where('schedule_items.schedule_id',$id)
            ->select('schedule_items.*','item_master.*','item_category.category_name')->get();
        //  dd($avalible);
        $clients = DB::table('schedules')
            ->join('bookings', 'schedules.id','=','bookings.schedule_id')->where('schedules.id',$id)
            ->select('schedules.id','bookings.*')->get();

             
        foreach($clients as $res){
            $res->client_data = Client::where('id', $res->uid)->first();
        }


        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.scheduleShipmentDetails', compact('data','img','avalible','clients','item'));
    }

// Function to View Booking Details On Details Button Click

    public function bookingDetails($id)
    {
        // $book = Booking::where('id',$id)->get();
        $book = DB::table('bookings')
                ->join('clients','bookings.uid','=','clients.id')
                ->select('clients.name','bookings.*')->where('bookings.id',$id)->get();
        $datas = DB::table('bookings')
              ->join('booking_items', 'bookings.id','=', 'booking_items.booking_id')
              ->select('booking_items.*','bookings.*')->where('bookings.id',$id)->get();
           
        $ship = DB::table('bookings')
                ->join('schedules','bookings.schedule_id','=','schedules.id')
                ->select('schedules.*')->where('bookings.id',$id)->get();
                // print_r($ship[0]->sid); die;
        $sid = $ship[0]->sid;
        $sname = Shipment::where('id',$sid)->get();
                //  /print_r($sname); die;

        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.bookingDetails', compact('datas','book','img','sname'));
    }

// Delete Clients    

    public function deleteClient($id)
    {
        
        Client::find($id)->delete();
        return redirect()->route('admin.clients'); 
    }
    
// Delete Shipment 

    public function deleteShipment($id)
    {
        Shipment::find($id)->delete();
        return redirect()->route('admin.shipment');
    }

// Schedule Shipment Page WIth All Schedule Shipments

    public function scheduleShipment()
    {
        $data = DB::table('schedules')
            ->join('shipments', 'schedules.sid', '=', 'shipments.id')
            ->select('schedules.id','schedules.shipment_type','schedules.from','schedules.to','schedules.departure_date','schedules.destination_warehouse','schedules.status','schedules.item_type', 'shipments.companyname','arrival_address')
            ->orderBy('schedules.created_at','DESC')
            ->get();
            // $data = Schedule::orderBy('created_at', 'desc')->get();
            $image = User::select('profileimage')->where('is_admin','=',1)->get();
            $img = json_decode($image);
            return view('admin.scheduleShipment', compact('data','img'));
    }

// Delete Schedule 

    public function deleteSchedule($id)
    {
        Schedule::find($id)->delete();
        return redirect()->route('admin.scheduleShipment');
    }

// Bookings Page With All Bookings With Item List and User Details

    public function viewBooking()
    {
        // $data = DB::table('bookings')
        //     ->join('items', 'bookings.uid', '=', 'items.uid')
        //     ->join('users', 'bookings.uid', '=', 'users.id')
        //     ->select('bookings.id','bookings.booking_date','bookings.booking_type','bookings.from',
        //         'bookings.to','bookings.shipment_company','bookings.status','bookings.pickup_review', 
        //         'items.image','items.description', 'users.name')
        //     ->get();
        // $data = Booking::all();
        $data = DB::table('bookings')
              ->join('clients', 'bookings.uid','=', 'clients.id')
              ->where('bookings.status','!=','Not Confirmed')
              ->select('clients.name','bookings.*')
              ->orderBy('bookings.created_at','DESC')
              ->get();
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.bookings', compact('data','img'));
    }

// Bookings Page With All Bookings With Item List and User Details

    public function viewClientBook($id)
    {
        $data = DB::table('bookings')
            ->join('clients', 'bookings.uid','=', 'clients.id')
            ->select('clients.name','bookings.*')->where('bookings.uid','=',$id)->get();

        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.viewClientBook', compact('data','img'));
    }

// Delete Bookings 

    public function deleteBooking($id)
    {
        Booking::find($id)->delete();
        return redirect()->route('admin.viewBooking');
    }    

// On View Button Display Booking Items And User for Respected Click

    public function viewBookingDetails($id)
    {
        $data = DB::table('items')
              ->join('clients', 'items.uid','=', 'clients.id')->where('items.uid',$id)
              ->first(['items.image','items.description','clients.name']);
        // $data =  Item::where('uid',$id)->first(['image','description']);
        return response()->json($data);     
    }

// Display Client Bookings in Clients Page 

    public function clientBook($id)
    {
        $data = Booking::where('uid','=',$id)->first();
        return response()->json($data);
    }

// View Create Advertisment Page

    public function viewAdsManagement()
    {
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.advertisment', compact('img'));
    }

// View All Advertisment Page With Data

    public function viewAdvertisment()
    {
        $data = Advertisment::all();
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.viewAdvertisment', compact('data','img'));
    }    

// Create Advertisment By Super Admin     

    public function createAdvertisment(Request $request)
    {
        //Validation 

        $request->validate([
            "title" => "required",
            "description" => "required|max:250",
            "file" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max-width=1025,max-height=513'
        ],
        [ 'file.dimensions' => 'The Dimensions Can not be grater than 1024x512 pixels.',
            'description.max' =>  "Description Can't Be More than 250 Character"]);

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
        return redirect()->route('admin.viewAdvertisment');    
    }

// Delete Advertisment    

    public function deleteAdvertisment($id)
    {
        Advertisment::find($id)->delete();
        return redirect()->route('admin.viewAdvertisment');
    } 
  
// To View Create User BY Admin Page    

    public function createUsers()
    {
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.createUser', compact('img'));
    }

// To view All USers

    public function viewUser()
    {
        $data = User::all()->where('is_admin','=',NUll);
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.viewUser', compact('data','img'));
    }

// Function To Create User With Selected Permission

    public function createUser(Request $request)
    {
        //Validation 

        $request->validate([
            "name" => "required|regex:/^[a-zA-Z]+$/u|max:255",
            "lname" => "required|regex:/^[a-zA-Z]+$/u|max:255",
            "email" => 'required|email|unique:users,email|regex:/^.+@.+$/i', 
            "password" => "required|min:6 | max:18",
            "phone" => "required|min:10|numeric",
            "address" => "required",
            "country" => "required",
            "file" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $destinationPath = public_path('/profile');
        $filename = '';
        if($request->hasfile('file'))
        {
            $image = $request->file('file');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $filename);
            // $permission = $request->input('permission');
        }

            $user = new User();
            $user->name = $request->name;
            $user->lname = $request->lname;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;
            $user->country = $request->country;
            $user->address = $request->address;
            $user->profileimage = $filename;
            $user->roles = json_encode($request->input('permission'));
            $user->is_admin = NULL;
            $user->save();
        return redirect()->route('admin.viewUser');  
    }

// Delete User By Admin 

    public function deleteUser($id)
    {
        User::find($id)->delete();
        return redirect()->route('admin.viewUser');
    }

// Get User Details in Modal To update

    public function updateUserDetails($id)
    {
        $data = User::where('id','=',$id)->first();
        return response()->json($data);
    }

// Update User Details

    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|regex:/^[a-zA-Z]+$/u|max:255",
            "lname" => "required|regex:/^[a-zA-Z]+$/u|max:255",
            "phone" => "required|min:10|numeric",
            "country" => "required",
            "address" => "required",
            "permission" => 'required|in:1',
        ]);
        if($request->input('permission') == null){
            $permission = ["1"];
        }
        else{
            $permission = json_encode($request->input('permission'));
        }
            
        User::where('email', $request->email)->update(['name' => $request->name ,
                                            'lname' => $request->lname,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                            'roles' => $permission,
                                           ]);
        
        return redirect()->route('admin.viewUser');
    }

// Get Scheduled Shipment Details 

    public function viewScheduleDetails($id)
    {
        $data = DB::table('schedules')
              ->join('shipments', 'schedules.sid','=', 'shipments.id')->where('schedules.sid',$id)
              ->first(['shipments.companyname']);

        return response()->json($data);
    }

// Get Shipment Company Documents

    public function viewShipmentDocs($id)
    {
        $data = Shipment::select('id','docs')->where('id','=',$id)->get();
        return response()->json($data);
    }
  
// Update Status Shipment Company 

    public function scheduleStatus(Request $request)
    {
            
        Shipment::where('id', $request->stats)->update(['status' => $request->status]);
        Schedule::where('sid',$request->stats)->update(['status' => $request->status]);
        return redirect()->route('admin.shipment');
    }  

// View Create Advertisment Page

    public function createBlogs()
    {
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.createBlog', compact('img'));
    }

// View All Advertisment Page With Data

    public function viewBlog()
    {
        $data = Blog::all();
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.viewBlog', compact('data','img'));
    }    

// Create Advertisment By Super Admin     

    public function createBlog(Request $request)
    {
        //Validation 

        $validator = Validator::make($request->all(), [
            "title" => "required",
            "file" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "descrption" => "required"
        ]);
        $destinationPath = public_path('/blog');
        if($request->hasfile('file'))
        {
            $image = $request->file('file');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $filename);
            
            $blog = new Blog();
            $blog->title = $request->title;
            $blog->image = $filename;
            $blog->description = $request->description;
            $blog->save();
        } 
        return redirect()->route('admin.viewBlog');    
    }

// Delete Advertisment    

    public function deleteBlog($id)
    {
        Blog::find($id)->delete();
        return redirect()->route('admin.viewBlog');
    } 

// View Review Details For Blogs
 
    public function viewReviewDetails($id)
    {
        $data = DB::table('blogs')
              ->join('clients', 'blogs.client_id','=', 'clients.id')->where('blogs.id',$id)
              ->first(['blogs.rating','blogs.reviews','clients.name']);
        return response()->json($data);
    }

// View Support page with All Queries Function

    public function viewSupport()
    {
        // $data = Query::all();
        $data1 = DB::table('queries')
        ->join('clients', 'clients.id','=', 'queries.uid')
        ->select('queries.*', 'clients.name','clients.roles')->get();
        $data2 = DB::table('queries')
        ->join('shipments', 'shipments.id','=','queries.sid')
        ->select('shipments.name','queries.*','shipments.roles')->get();
        // var_dump($data1); 
        // var_dump($data2); die;
        
        $merged = $data1->merge($data2);
        $data = $merged->all();
        // print_r($data[0]->roles); die;
        //  print_r($data); die;

        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.support', compact('data','img'));
    }

// // View Query On Modal

//     public function viewQuery($id)
//     {
//         $data = Query::where('id', $id)->get(['id', 'query']);
//         return response()->json($data);
//     }

// // Function to get Query Status 

//     public function changeQueryStatus($id)
//     {
//         $data = Query::where('id','=',$id)->first();
//         return response()->json($data);
//     }

// Function to View Query All Details in Admin panel

    public function queryDetails($id)
    {
        $data = Query::where('id','=',$id)->first();
        // print_r($data->query); die;

        $querytDetails = QueryStatus::where('qid','=',$id)->get();
        // print_r($querytDetails); die;

        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.queryDetails', compact('data','img','querytDetails'));
    }
   
// Function to Update Support Query Status

    public function updateQueryStatus(Request $request)
    {
        $qStatus = new QueryStatus();
        $qStatus->qid = $request->qid;
        $qStatus->status = $request->status;
        $qStatus->comment = $request->comment;
        $qStatus->save();

        Query::where('id', $request->qid)->update(['status' => $request->status  ]);
        
        return redirect()->route('admin.viewSupport');
    }

// Function To View Create Category Page

    public function createCategory()
    {
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.createCategory', compact('img'));
    }

// Function to View Categories Page

    public function viewCategory()
    {
        $data = DB::table('item_category')->select('*')->orderBy('id','DESC')->get();
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.viewCategory', compact('data','img'));
    }

// Function To Create Category

    public function createCategories(Request $request)
    {
     
        $request->validate([
            "category_name" => "required",
            "file" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:width=10,height=10'
        ],
        [ 'file.dimensions' => 'The Dimensions Can not be grater than 10x10 pixels.']);

        $destinationPath = public_path('/image');
        if($request->hasfile('file'))
        {
            $image = $request->file('file');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $filename);
            
            $item = new ItemCategory();
            $item->category_name = $request->category_name;
            $item->icon = $filename;
            $item->save();
        }

        // DB::insert('insert into item_category (category_name,icon) values(?,?)',[$category,$filename]);
            
        return redirect()->route('admin.viewCategory');  
    }

// Delete Category By Admin 

    public function deleteCategory($id)
    {
        DB::table('item_category')->delete($id);
        return redirect()->route('admin.viewCategory');
    }

// Function To View Create Items BY Admin Page    

    public function createItems()
    {
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        $data = DB::table('item_category')->select('*')->orderBy('id','DESC')->get();
        return view('admin.createItem', compact('img','data'));
    }

// To view All Items

    public function viewItem()
    {
        $data = DB::table('item_master')
                ->join('item_category','item_master.category','=','item_category.id')
                ->select('item_master.*','item_category.category_name')
                ->orderBy('id','DESC')
                ->get();
                // dd($data);
        $category = DB::table('item_category')->select('*')->get();
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.viewItem', compact('data','img','category'));
    }

// Function To Create Item 

    public function createItem(Request $request)
    {
        //Validation 

        $request->validate([
            "item_name" => "required",
            "category" => "required",
        ]);

        $name = $request->input('item_name');
        $category = $request->input('category');
        DB::insert('insert into item_master (item_name,category) values(?, ?)',[$name, $category]);
        return redirect()->route('admin.viewItem');  
    }

// Delete Item By Admin 

    public function deleteItem($id)
    {
        DB::table('item_master')->delete($id);
        return redirect()->route('admin.viewItem');
    }

// Get Item Details in Modal To update

    public function updateItemDetails($id)
    {
        // $data = User::where('id','=',$id)->first();
        $data = DB::table('item_master')
                ->join('item_category','item_master.category','=','item_category.id')
                ->select('item_master.*','item_category.category_name')
                ->where('item_master.id','=',$id)->get();
        return response()->json($data);
    }

// Update User Details

    public function updateItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "item_name" => "required",
            "category" => "required",
        ]);

        DB::table('item_master')
            ->where('id','=', $request->id)
            ->update([
                'item_name'     => $request->input('item_name'),
                'category'     => $request->input('category')
        ]);
       
        return redirect()->route('admin.viewItem');
    }

// Function to View Coupon Page With All Coupons

    public function viewCoupon()
    {
        $data = Coupon::orderBy('created_at','desc')->get();
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.viewCoupons', compact('data','img'));
    }

// Function to Delete Coupon

    public function deleteCoupon($id)
    {
        Coupon::find($id)->delete();
        return redirect()->route('admin.viewCoupons');
    }

// To View Create Coupon BY Admin Page    

    public function createCoupons()
    {
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.createCoupon', compact('img'));
    }

// Function To Create Coupon 

    public function createCoupon(Request $request)
    {
        //Validation 

        $request->validate([
            "coupon_name" => "required",
            "coupon_code" => "required|unique:coupons,coupon_code|regex:/^(?=.*)(?=.*[A-Z])(?=.*\d).+$/",
            "coupon_description" => "required",
            "coupon_type" => "required",
            "coupon_amount" => "required",
        ]);
        if($request->once != "")
        {
            $users = array();
            $coupon = new Coupon();
            $coupon->coupon_name = $request->coupon_name;
            $coupon->coupon_code = $request->coupon_code;
            $coupon->coupon_description = $request->coupon_description;
            $coupon->coupon_type = $request->coupon_type;
            $coupon->coupon_amount = $request->coupon_amount;
            $coupon->once = $request->once;
            $coupon->users = json_encode($users);
            $coupon->status = $request->active;
            $coupon->save();
          
        }
        else
        {
            $users = array();
            $coupon = new Coupon();
            $coupon->coupon_name = $request->coupon_name;
            $coupon->coupon_code = $request->coupon_code;
            $coupon->coupon_description = $request->coupon_description;
            $coupon->coupon_type = $request->coupon_type;
            $coupon->coupon_amount = $request->coupon_amount;
            // $coupon->once = $request->once;
            $coupon->users = json_encode($users);
            $coupon->status = $request->active;
            $coupon->save();
        }
        // $coupon_name = $request->input('coupon_name');
        // $coupon_code = $request->input('coupon_code');
        // $coupon_description = $request->input('coupon_description');
        // DB::insert('insert into coupons (coupon_name,coupon_code,coupon_description) values(?, ?, ?)',[$coupon_name, $coupon_code, $coupon_description]);
        return redirect()->route('admin.viewCoupons');  
    }

// Function to view Update Coupon Page

    public function updateCoupon($id)
    {
        $data = Coupon::where('id','=',$id)->first();
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.editCoupon', compact('img','data'));
    }

// Function To update Coupons

    public function updateCoupons(Request $request)
    {
        $request->validate([
            "coupon_name" => "required",
            // "coupon_code" => "required|unique:coupons,coupon_code|regex:/^(?=.*)(?=.*[A-Z])(?=.*\d).+$/",
            "coupon_code" => "required|regex:/^(?=.*)(?=.*[A-Z])(?=.*\d).+$/",
            "coupon_description" => "required",
            "coupon_type" => "required",
            "coupon_amount" => "required",
        ]);
        DB::table('coupons')
        ->where('id','=', $request->id)
        ->update([
            'coupon_name'     => $request->input('coupon_name'),
            'coupon_code'     => $request->input('coupon_code'),
            'coupon_description'     => $request->input('coupon_description'),
            'coupon_type'     => $request->input('coupon_type'),
            'coupon_amount'     => $request->input('coupon_amount'),
            'status' => $request->input('active')
        ]);
       
        return redirect()->route('admin.viewCoupons');
    }

// Function To get Notifications

//     public function getNotification()
//     {
//         $data = Notification::where('is_admin','=',1)->orderBy('created_at', 'desc')->get();
//         $msg = '';
//         foreach($data as $row)
//         {
//             $msg = $msg.'<div class="notification-item">
//                                 <h4 class="item-title">'.$row->title .'</h4>
//                                 <p class="item-info">'.$row->msg.'</p>
//                             </div>';
//         }
//         return response()->json(['success'=>'Notifications','msg'=>$msg]);
//     }

// FUnction to Mark Notification as Read

    public function mark()
    {
        Notification::where('is_admin','=',1)->update(['status' => 1]);
        return redirect()->route('admin.dashboard');
    }

// Function to View Booking Payment Page

    public function bookingPayment()
    {
        $data = DB::table('bookings')
              ->join('clients', 'bookings.uid','=', 'clients.id')
              ->where('bookings.status','!=','Not Confirmed')
              ->select('clients.name','bookings.*')
              ->orderBy('bookings.created_at','DESC')
              ->get();
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.bookingPayment', compact('img','data'));
    }

// Function to View Booking Payment Page

    public function marketPayment()
    {
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.marketPayment', compact('img'));
    }

// Function to open set Timer Page

    public function timer()
    {
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);
        return view('admin.timer', compact('img'));
    }
   
// Function to Save Time into DB

    public function setTimer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "time" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => []
            ]);
        }
        else
        {
            DB::table('timer')->insert(['hours' => $request->time]);
            return redirect()->route('admin.viewBooking');
        }

    }

}
