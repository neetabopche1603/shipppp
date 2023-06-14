<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Schedule;
use App\Models\ScheduleItem;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Marketp;
use App\Models\Client;
use App\Models\ItemCategory;
use App\Models\Proposal;
use App\Models\Warehouse;
use App\Models\Notification;
use App\Models\MarketBooking;
use App\Models\ScheduleCategory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use App\Mail\SendDemoMail;
use App\Mail\SendLoginMail;
use App\Mail\SendOtpMail;
use Mail;
use App\Models\Review;

class ShipmentController extends Controller
{
    protected $shipmentId;

    public function checkToken() {
        $x = new \stdClass();
        $headers = getallheaders();
        if(isset($headers['token']))
        {
            $check = DB::table('personal_access_tokens')->where('token',$headers['token'])->select('tokenable_id')->orderBy('id','desc')->first();
            if(!isset($check->tokenable_id))
            {
                return response()->json(['success'=>false,'data'=>$x,'message'=>'token mis matched'], 401);
                die();
            }else{
                $this->shipmentId = $check->tokenable_id;
            }
        }else{
            return response()->json(['success'=>false,'data'=>array(),'message'=>'token blanked'], 401);
            die();
        }
    }

// Function for Creating Shipment Company Api to table with {Role = 1}

    public function registerShipment(Request $request)
    {
        // VALIDATION OF Shipment DETAILS 
   
        $validator = Validator::make($request->all(), [
            "name" => "required|max:255",
            "email" => 'required|email|unique:shipments,email|unique:clients,email|regex:/^.+@.+$/i', 
            "password" => "required",
            // "phone" => "required|min:10|numeric",
            // "companyname" => "required",
            // "annualshipment" => "required",
            // "country" => "required",
            // "file" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            "username" => "required|regex:/^[a-zA-Z0-9-]+$/u|max:255|unique:clients,username|unique:shipments,username"
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
                $filename = '';
                $destinationPath = public_path('/image');
                $pdata = DB::table('subscriptionplans')->where('id',1)->first();
                $duration = $pdata->duration;
                $edate = date('Y-m-d', strtotime(' +'.$duration.' day'));
                if($request->hasfile('file'))
                {
                    $image = $request->file('file');
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $filename);  

                    

                    $shipment = new Shipment();
                    $shipment->name = $request->name;
                    $shipment->lname = $request->lname;
                    $shipment->email = $request->email;
                    $shipment->password = $request->password;
                    $shipment->phone = $request->phone;
                    $shipment->username = $request->username;
                    $shipment->country_code = $request->country_code;
                    $shipment->companyname = $request->companyname;
                    $shipment->annualshipment = $request->annualshipment;
                    $shipment->companyname = $request->companyname;
                    $shipment->country = $request->country;
                    $shipment->roles = "1";
                    $shipment->plan_id = 1;
                    $shipment->expire_date = $edate;
                    $shipment->profileimage = $filename;
                    $shipment->status = 'Not Approve';
                    $shipment->type = "shipments";
                    $shipment->save();
                }   
                else if(!$request->hasfile('file'))
                {
                    $shipment = new Shipment();
                    $shipment->name = $request->name;
                    $shipment->lname = $request->lname;
                    $shipment->email = $request->email;
                    $shipment->password = $request->password;
                    $shipment->phone = $request->phone;
                    $shipment->username = $request->username;
                    $shipment->country_code = $request->country_code;
                    $shipment->companyname = $request->companyname;
                    $shipment->annualshipment = $request->annualshipment;
                    $shipment->companyname = $request->companyname;
                    $shipment->country = $request->country;
                    $shipment->roles = "1";
                    $shipment->plan_id = 1;
                    $shipment->expire_date = $edate;
                    $shipment->profileimage = $request->file;
                    $shipment->status = 'Not Approve';
                    $shipment->type = "shipments";
                    $shipment->save();
                }
                else 
                {
                    $shipment = new Shipment();
                    $shipment->name = $request->name;
                    $shipment->lname = $request->lname;
                    $shipment->email = $request->email;
                    $shipment->password = $request->password;
                    $shipment->phone = $request->phone;
                    $shipment->username = $request->username;
                    $shipment->country_code = $request->country_code;
                    $shipment->companyname = $request->companyname;
                    $shipment->annualshipment = $request->annualshipment;
                    $shipment->companyname = $request->companyname;
                    $shipment->country = $request->country;
                    $shipment->roles = "1";
                    $shipment->plan_id = 1;
                    $shipment->expire_date = $edate;
                    $shipment->profileimage = '';
                    $shipment->status = 'Not Approve';
                    $shipment->type = "shipments";
                    $shipment->save();
                }
                   
                
            }


            $otp = rand(1000,9999);

            Shipment::where('email','=',$request->email)->update(['otp'=>$otp]);
            $maildata = [

                'title' => 'Your OTP is : '. $otp,
                            ];
    
            Mail::to($request->email)->send(new SendOtpMail($maildata));



            $token = $shipment->createToken('my-app-token')->accessToken;

            $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
            
            $notification = new Notification();
            $notification->msg = "Great to have you on board.";
            $notification->title = "Welcome !!";
            $notification->sid = $shipment->id;
            $notification->save();
   
            return response()->json([
                'status' => true,
                'message' => 'Shipment successfully Registered',
                'data' => [$shipment]
            ], 201); 
    }

// Login Function Shipment Api {Role =1}

    public function loginShipment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email',
            // 'password' => 'required',
        ]);
        $email = $request->input('email');
        $password = $request->input('password');

        if ($validator->fails()) {  
       
            $username = $request->email;
            $client = Shipment::where('username', '=', $username)->where('roles','=','1')->first();
            if (!$client) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, please check Username' ,'data' => []]);
            }
            if (!($password == $client->password)) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, Wrong password. Try again or click ‘Forgot password’ to reset it.','data' => []]);
            }
            if($client->status == "Deactivate") {
                return response()->json(['status'=>false, 'message' => 'Login Fail, Your Account is Deactivated','data' => []]);
            }
                $token = $client->createToken('my-app-token')->accessToken;
                $client->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
               return response()->json(['status'=>true,'message'=>'Login Successful', 'data' => [$client] ]);

        }
        else
        {
            $client = Shipment::where('email', '=', $email)->where('roles','=','1')->first();
            if (!$client) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, please check email id','data' => []]);
            }
            if (!($password == $client->password)) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, Wrong password. Try again or click ‘Forgot password’ to reset it.','data' => []]);
            }
            if($client->status == "Deactivate") {
                return response()->json(['status'=>false, 'message' => 'Login Fail, Your Account is Deactivated','data' => []]);
            }
                $token = $client->createToken('my-app-token')->accessToken;
                $client->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
               return response()->json(['status'=>true,'message'=>'Login Successful', 'data' => [$client] ]);
        }
    }

// Shipment Profile Details Show Api 

    public function shipmentProfile()
    {
        $this->checkToken();
        $shipment = Shipment::where('id',$this->shipmentId)->first();
        foreach($shipment as $row)
        {
            // $row->docs = json_decode($row->docs);
        }
        if($shipment->roles == 1)
        {
            return response()->json([
                'status' => true,
                'message' => 'Shipment Details',
                'data' => [$shipment]
            ], 201); 
        }
        if($shipment->roles == 2)
        {
            return response()->json([
                'status' => true,
                'message' => 'profile Details',
                'data' => [$shipment]
            ], 201); 
        }
        if($shipment->roles == 3)
        {
            return response()->json([
                'status' => true,
                'message' => 'profile Details',
                'data' => [$shipment]
            ], 201); 
        }
        if($shipment->roles == 4)
        {
            return response()->json([
                'status' => true,
                'message' => 'profile Details',
                'data' => [$shipment]
            ], 201); 
        }
        if($shipment->roles == 5)
        {
            return response()->json([
                'status' => true,
                'message' => 'profile Details',
                'data' => [$shipment]
            ], 201); 
        }
        return response()->json([
            'status' => false,
            'message' => 'Not a Shipment',
            'data' => []
        ]);
         
    }

// Shipment Profile Update

    public function updateShipmentProfile(Request $request)
    {  
        $this->checkToken();
        $validator = Validator::make($request->all(), [
            "name" => "regex:/^[a-zA-Z]+$/u|max:255",
            "lname" => "regex:/^[a-zA-Z]+$/u|max:255",
            "phone" => "min:10|numeric",
            // "file" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
         [
            'lname.regex' => 'Last name is invalid',
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
            $destinationPath = public_path('/image');
            if($request->hasfile('file'))
            {
                $image = $request->file('file');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $filename);
                
                $shipment = Shipment::where('email','=',$request->email)->update(['name' => $request->name ,
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
            else if(!$request->hasfile('file'))
            {
                $shipment = Shipment::where('email','=',$request->email)->update(['name' => $request->name ,
                                                'lname' => $request->lname ,
                                                'email' => $request->email,
                                                'phone' => $request->phone ,
                                                'country' => $request->country ,
                                                'address' => $request->address ,
                                                'profileimage' =>  $request->file ,
                                                'about_me' => $request->about_me,
                                                'language' => $request->language,
                                                'companyname' => $request->companyname,
                                                'annualshipment' => $request->annualshipment
                                            ]);
            }
            else
            {
                $shipment = Shipment::where('email','=',$request->email)->update(['name' => $request->name ,
                                                'lname' => $request->lname ,
                                                'email' => $request->email,
                                                'phone' => $request->phone ,
                                                'country' => $request->country ,
                                                'address' => $request->address ,
                                                'about_me' => $request->about_me,
                                                'language' => $request->language,
                                                'companyname' => $request->companyname,
                                                'annualshipment' => $request->annualshipment,
                                                'profileimage' => ''
                                            ]);
            }

        }
        
                    $data = Shipment::where('email','=', $request->email)->get();
                    if($shipment)
                    {
                        return response()->json([
                            'status' => true,
                            'message' => 'Profile successfully Updated',
                            'data' => $data
                        ], 201); 
                    }
                    return response()->json([
                        'status' => false,
                        'message' => 'Not a Shipment',
                        'data' => []
                    ]);
                    // return response()->json([
                    //     'status' => true,
                    //     'message' => 'Profile successfully Updated',
                    //     'data' => $data
                    // ], 201);

    }

// Shipment Update Docs in Profile
    
    // public function updateDocs(Request $request)
    // {
    //     // VALIDATION OF Shipment Docs 
    
    //     $validator = Validator::make($request->all(), [
    //         "file" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors()->toJson(), 400);
    //     }
    //     else
    //     {
    //         $filename = '';
    //         $destinationPath = public_path('/image');
    //         if($request->hasfile('file'))
    //         {
    //             $image = $request->file('file');
    //             $filename = time() . '.' . $image->getClientOriginalExtension();
    //             $image->move($destinationPath, $filename);  
    //         } 

    //         $shipment = Shipment::where('id', $request->id)->update([
    //             'docs' => $filename
    //         ]);
       
    //         if(!$shipment)
    //         {
    //             return response([
    //                 'error'=>["Document Not Uploaded"]
    //             ],404);
    //         }
       
    //         $response = [
    //             'status' => 'Document Uploaded Successful'
    //         ];
       
    //         return response($response, 200);
    //     }
    // }

// Shipment Update Multiple Image Docs (i-9 form)

    public function updateDocs(Request $request)
    {
        $this->checkToken();
        $image = array();
        if($file = $request->file('file')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                $destinationPath =  public_path('/image');
                $image_url = $destinationPath.$image_full_name;
                $file->move($destinationPath,$image_full_name);
                $image[] = $image_full_name;
            }
                Shipment::where('id', $this->shipmentId)->update([
                    'docs' => json_encode($image),
                ]);
            
            // Shipment::insert([
            //     'uid' => $request->uid,
            //     // 'image' => implode('|', $image),
            //     'image' => json_encode($image),
            //     'description' => $request->description,
            // ]);
            return response()->json([
                'status' => true,
                'message' => 'Documents Successfully Uploaded',
            ], 201);       
        }
        else if(!$request->file('file'))
            {
                Shipment::where('id', $this->shipmentId)->update([
                    'docs' => $request->file
                ]);

                $ship = Shipment::where('id', $this->shipmentId)->first();
                $notification = new Notification();
                $notification->msg = $ship->companyname . "has summited document";
                $notification->title = "Verification requiered !!";
                $notification->is_admin = 1;
                $notification->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Documents Successfully Uploaded',
                ], 201);   
            }
            else
            {
                return response()->json([
                    'status' => false,
                    'message' => 'something went wrong',
                ], 201);
            }
    }

// Shipment Update Multiple Image of Driving Licence

    public function updateDrivingLicence(Request $request)
    {
        $this->checkToken();
        $image = array();
        if($file = $request->file('file')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                $destinationPath =  public_path('/image');
                $image_url = $destinationPath.$image_full_name;
                $file->move($destinationPath,$image_full_name);
                $image[] = $image_full_name;
            }
            Shipment::where('id', $this->shipmentId)->update([
                            'driving_licence' => json_encode($image),
                        ]);

                $ship = Shipment::where('id', $this->shipmentId)->first();
                $notification = new Notification();
                $notification->msg = $ship->companyname . "has summited document";
                $notification->title = "Verification requiered !!";
                $notification->is_admin = 1;
                $notification->save();

            return response()->json([
                'status' => true,
                'message' => 'Driving Licence Documents Successfully Uploaded',
            ], 201);       
        }
        else if(!$request->file('file'))
        {
            Shipment::where('id', $this->shipmentId)->update([
                'driving_licence' => $request->file
            ]);

            $ship = Shipment::where('id', $this->shipmentId)->first();
            $notification = new Notification();
            $notification->msg = $ship->companyname . "has summited document";
            $notification->title = "Verification requiered !!";
            $notification->is_admin = 1;
            $notification->save();

            return response()->json([
                'status' => true,
                'message' => 'Driving Licence Documents Successfully Uploaded',
            ], 201);   
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong',
            ], 201);
        }
    }

// Shipment Update Multiple Image of Tax Paid Documents

    public function updateTaxDocs(Request $request)
    {
        $this->checkToken();
        $image = array();
        if($file = $request->file('file')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                $destinationPath =  public_path('/image');
                $image_url = $destinationPath.$image_full_name;
                $file->move($destinationPath,$image_full_name);
                $image[] = $image_full_name;
            }
            Shipment::where('id', $this->shipmentId)->update([
                            'tax_docs' => json_encode($image),
                        ]);

                $ship = Shipment::where('id', $this->shipmentId)->first();
                $notification = new Notification();
                $notification->msg = $ship->companyname . "has summited document";
                $notification->title = "Verification requiered !!";
                $notification->is_admin = 1;
                $notification->save();

            return response()->json([
                'status' => true,
                'message' => 'Tax Documents Successfully Uploaded',
            ], 201);       
        }
        else if(!$request->file('file'))
        {
            Shipment::where('id', $this->shipmentId)->update([
                'tax_docs' => $request->file
            ]);

            $ship = Shipment::where('id', $this->shipmentId)->first();
            $notification = new Notification();
            $notification->msg = $ship->companyname . "has summited document";
            $notification->title = "Verification requiered !!";
            $notification->is_admin = 1;
            $notification->save();

            return response()->json([
                'status' => true,
                'message' => 'Tax Documents Successfully Uploaded',
            ], 201);   
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong',
            ], 201);
        }
    }

/**
    * Shippment Company Add Employee to table with 
    * {Role = 2} (Accountant)
    * {Role = 3} (WareHouse Manager) 
    * {Role = 4} (Arrival Manager)
    * {Role = 5} (Pickup Agent) 
*/

    public function addEmployee(Request $request)
    {    
        $this->checkToken();
        $shipment = Shipment::where('id',$this->shipmentId)->first();
        $validator = Validator::make($request->all(), [
            "name" => "required|regex:/^[a-zA-Z]+$/u|max:255",
            "lname" => "required|regex:/^[a-zA-Z]+$/u|max:255",
            "email" => 'required|email|unique:shipments,email|regex:/^.+@.+$/i', 
            "phone" => "required|min:10|numeric",
            "country" => "required|max:255",
            "address" => "required|max:255",
            "password" => "required",
            "file" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            "username" => "required|regex:/^[a-zA-Z0-9-]+$/u|max:255|unique:clients,username|unique:shipments,username"
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
            $filename = '';
            $destinationPath = public_path('/image');
                if($request->hasfile('file'))
                {
                    $image = $request->file('file');
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $filename);
                }
                    $shipment = new Shipment();
                    $shipment->name = $request->name;
                    $shipment->lname = $request->lname;
                    $shipment->email = $request->email;
                    $shipment->password = $request->password;
                    $shipment->phone = $request->phone;
                    $shipment->username = $request->username;
                    $shipment->country = $request->country;
                    $shipment->companyname = $shipment->companyname;
                    $shipment->address = $request->address;
                    $shipment->roles = $request->roles;
                    $shipment->profileimage = $filename;
                    $shipment->shipment_id = $this->shipmentId;
                    $shipment->save();
                
        }

        $maildata = [
                'email' => 'Email : '. $request->email,
                'password' => 'Password : '. $request->npassword,
                'url' => 'https://shipment.netlify.app/#/login'
            ];
    
        Mail::to($request->email)->send(new SendLoginMail($maildata));



        $token = $shipment->createToken('my-app-token')->accessToken;

        $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
        
            return response()->json([
                'status' => true,
                'message' => 'Employee successfully Added',
                'data' => [$shipment]
            ], 201);         
    }

// Function For Schedule Shipment Api for Shipment Company

    public function scheduleShipment(Request $request)
    {
        $this->checkToken();
        // VALIDATION OF Schedule shipment DETAILS 
   
        $validator = Validator::make($request->all(), [
            "shipment_type" => "required",
            "from" => "required",
            "to" => "required",
            "departure_date" => "required",
            "arrival_date" => "required",
            "destination_warehouse" => "required",
            "item_type" => "required",
         
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
                $shipment = Shipment::where('id',$this->shipmentId)->select('status')->get();
                
                $schedule = new Schedule();
                $schedule->shipment_type = $request->shipment_type;
                $schedule->title = $request->title;
                // return $request->item_type;
                $schedule->from = $request->from;
                $schedule->to = $request->to;
                $schedule->departure_date = $request->departure_date;
                $schedule->arrival_date = $request->arrival_date;
                $schedule->departure_warehouse = $request->departure_warehouse;
                $schedule->departure_address = $request->departure_address;
                $schedule->arrival_address = $request->arrival_address;
                $schedule->destination_warehouse = $request->destination_warehouse;
             
                // $shipment = Shipment::find($request->sid)->get();
                // if($shipment[0]->status == 'Approved')
                // {
                //     $schedule->permission_status = 'Approved';
                // }
                // $schedule->item_type = $request->item_type;
                // $schedule->item_type =  json_encode($request->input('item_type'));
                // return count($request->input('item_type'));
                $size = json_decode($request->input('item_type'));
                // $size = $request->input('item_type');
              
                // return count($size);
                $items = [];
                $total_container = 0;
                foreach($size as $key=>$item)
                {
                    // $obj = new \stdClass();
                    // $obj->item_type = $item['item'];
                    // $obj->category_name = $item['category_name'];
                    // $obj->icon = $item['icon'];
                    // $obj->shipping_fee = $item['shipping_fee'];
                    // $obj->pickup_fee = $item['pickup_fee'];
                    // $obj->quantity = $item['quantity'];
                    
                  
                    $obj = new \stdClass();
                    $obj->item_type = $item->item;
                    $obj->category_name = $item->category_name;
                    $obj->icon = $item->icon;
                    $obj->shipping_fee = $item->shipping_fee;
                    // $obj->item_shipping_fee = $item->item_shipping_fee;
                    // $obj->item_pickup_fee = $item->item_pickup_fee;
                    // $obj->item_quantity = $item->item_quantity;
                    if($item->shipping_fee != null)
                    {   
                        $obj->shipping_fee = $item->shipping_fee;
                    }
                    else{ $obj->shipping_fee = 0; }
                    if($item->pickup_fee != null)
                    {   
                        $obj->pickup_fee = $item->pickup_fee;
                    }
                    else{ $obj->pickup_fee = 0; }
                    $obj->quantity = $item->quantity;
               
                    array_push($items, $obj);
                }
               
                $schedule->item_type = json_encode($items);

                // $schedule->shipping_fee = $request->shipping_fee;
                // $schedule->pickup_fee = $request->pickup_fee;
                // $schedule->sid = $request->sid;
                $schedule->sid = $this->shipmentId;
                // $schedule->status = $shipment[0]->status;
                $schedule->status = 'Open';
                $schedule->permission_status = $shipment[0]->status;
                
                $schedule->save();

                $total_container = json_decode($schedule->item_type);

                // return $total_container;

                // return $total_container[0]->item_type;

                // return json_decode($total_container[0]->item_type);

                // $itemCategory = json_decode($total_container[0]->item_type);
          

                foreach($total_container as $total)
                {
                    $scheduleitem = new scheduleItem();
                    $scheduleitem->item_id = $total->category_name;
                    $scheduleitem->icon = $total->icon;
                    $scheduleitem->item_number = $total->quantity;
                    $scheduleitem->schedule_id = $schedule->id;
                    $scheduleitem->available = $total->quantity;
                    $scheduleitem->save();

                    $itemCategory = json_decode($total->item_type);
                    // return count($itemCategory);
                    // return $itemCategory;
                    foreach($itemCategory as $key=>$res)
                    {
                        $schedulecategory = new ScheduleCategory();
                        $schedulecategory->item_name = $res->name;
                        $schedulecategory->item_quantity = $res->item_quantity;
                        $schedulecategory->category_id = $scheduleitem->id;
                        $schedulecategory->available = $res->item_quantity;
                        $schedulecategory->pickup_fee = $res->item_pickup_fee;
                        $schedulecategory->shipping_fee = $res->item_shipping_fee;
                        $schedulecategory->schedule_id = $schedule->id;
                        $schedulecategory->save();
                    }
                }

               
                
                $notification = new Notification();
                $notification->msg = "You have done with new schedule.";
                $notification->title = "Success !!";
                $notification->sid = $this->shipmentId;
                $notification->schedule_id = $schedule->id;
                $notification->save();

                $notification = new Notification();
                $notification->msg = "You recieved a new schedule.";
                $notification->title = "Shipment Schedule !!";
                $notification->sid = $request->departure_warehouse;
                $notification->schedule_id = $schedule->id;
                $notification->save();

                $notification = new Notification();
                $notification->msg = "You recieved a new schedule.";
                $notification->title = "Shipment Schedule !!";
                $notification->sid = $request->destination_warehouse;
                $notification->schedule_id = $schedule->id;
                $notification->save();
                

            }
   
            return response()->json([
                'status' => true,
                'message' => 'Successfully Shipment Scheduled ',
                'data' => [$schedule]
            ], 201); 
    }

// Client View of Scheduled Shipment

    public function viewScheduleShipment()
    {
        // $schedule = Schedule::all();
        // $schedule = Schedule::where('permission_status','Approved')->get();
        $schedule = DB::table('schedules')
                    ->join('shipments','schedules.sid','=','shipments.id')
                    ->select('schedules.*','shipments.companyname')
                    ->where('schedules.permission_status','Approved')
                    ->where('schedules.status','Open')
                    ->orderBy('schedules.created_at', 'desc')
                    ->get();
        // $count = count($schedule);
        // return $count;
        $items = [];
        foreach($schedule as $data)
        {
            $obj = new \stdClass();
            $obj = json_decode($data->item_type);

            array_push($items, $obj);
        }
        // return $items;
        $total = [];
        foreach($schedule as $key=>$row)
        {
            if($key == 0)
            {
                $row->item_type = json_decode($items[$key]);
            }
            else
            {
                $row->item_type = json_decode($items[$key]);
            }
            $available_container = 0;
            $total_container = 0;
         
            $schedule_items = DB::table('schedule_items')
                        ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
                        ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
                        ->where('schedule_items.schedule_id',$row->id)
                        ->get();
                    // return $schedule_items;
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
                    $sf = $sitem->item_id;
                    $name = $sitem->item_name;
                    $pf = $sitem->available;
                    $icon = $sitem->icon;
                    $pfee = $sitem->pickup_fee;
                    $sfee = $sitem->shipping_fee;
                    $id = $sitem->id;
                    $price = ['item_id'=> $id,'category' => $sf,'available' => $pf,'item_name' => $name ,'icon' => $icon, 'item_pickupfee' => $pfee, 'item_shippingfee' => $sfee];
                    array_push($total,$price);
                }
        
            // if(isset($schedule_items->id)) {
            //     $available_container = $schedule_items->available;
            //     $total_container = $schedule_items->item_number;
            // }
            
            $row->available = $total;
            foreach($total as $pop)
            {
                array_pop($total);
            }
            $row->total_container = $total_container;
            $row->available_container = $available_container;
        }

        foreach($schedule as $review)
        {
            $review->rating = Review::where('sid','=',$review->sid)->select('rating')->get();
            $count = 0;
            $cnt = 0;
            foreach($review->rating as $key=>$avg)
            {
                $cnt += $avg->rating;
                $count++;
            }
            if($count != 0)
            {
                $review->rating = round($cnt/$count ,2 );
            }
            else
            {
                $review->rating = 0;
            }
        }
        // return json_decode($schedule[0]->item_type);
        // return $schedule;
        if($schedule)
        {
            return response()->json([
                'status' => true,
                'message' => 'All Schedules Shipments',
                'schedule' => $schedule
                // 'items' => $items
            ], 201); 
        }
        return response()->json([
            'status' => false,
            'message' => "No Approved Scheduled Shipments",
            'data' => [] 
        ]);
    }

/**
    * Login Employees Function Api By There Roles
    * {Role = 2} (Accountant)
    * {Role = 3} (WareHouse Manager) 
    * {Role = 4} (Arrival Manager)
    * {Role = 5} (Pickup Agent) 
*/

    public function loginEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email',
            'roles' => 'required'
            // 'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $roles = $request->input('roles');
        
        if ($validator->fails()) {  
       
            $username = $request->email;
            $shipment = Shipment::where('username', '=', $username)->where('password',$password)->first();
            if (!$shipment) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, please check Username' ,'data' => []]);
            }
            if (!($password == $shipment->password)) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, Wrong password. Try again .','data' => []]);
            }
            if($shipment->status == "Deactivate") {
                return response()->json(['status'=>false, 'message' => 'Login Fail, Your Account is Deactivated','data' => []]);
            }
            if($shipment->roles == 2 && $roles == 2){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Accountant', 'data' => [$shipment] ]);
            }
            if($shipment->roles == 3 && $roles == 3){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Departure Warehouse Manager', 'data' => [$shipment] ]);
            }
            if($shipment->roles == 4 && $roles == 4){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Arrival Warehouse Manager', 'data' => [$shipment] ]);
            }
            if($shipment->roles == 5 && $roles == 5){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Pickup Agent', 'data' => [$shipment] ]);
            }       
            $response = [
                'status' => false,
                'message' => 'Not an Employee',
                'data' => []
            ];
            return response()->json($response, 200);

        }
        else 
        {
            $shipment = Shipment::where('email', '=', $email)->first();
            if (!$shipment) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, please check Email' ,'data' => []]);
            }
            if (!($password == $shipment->password)) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, Wrong password. Try again ','data' => []]);
            }
            if($shipment->status == "Deactivate") {
                return response()->json(['status'=>false, 'message' => 'Login Fail, Your Account is Deactivated','data' => []]);
            }
            if($shipment->roles == 2 && $roles == 2){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Accountant', 'data' => [$shipment] ]);
            }
            if($shipment->roles == 3 && $roles == 3){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Departure Warehouse Manager', 'data' => [$shipment] ]);
            }
            if($shipment->roles == 4 && $roles == 4){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Arrival Warehouse Manager', 'data' => [$shipment] ]);
            }
            if($shipment->roles == 5 && $roles == 5){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Pickup Agent', 'data' => [$shipment] ]);
            }       
            $response = [
                'status' => false,
                'message' => 'Not an Employee',
                'data' => []
            ];
            return response()->json($response, 200);
        }
        
        
    }

/**
    * Function for fetch Shipment Company details and all Employees Profile Details Api By There Roles
    * {Role = 1} (Shipment Company)
    * {Role = 2} (Accountant)
    * {Role = 3} (WareHouse Manager) 
    * {Role = 4} (Arrival Manager)
    * {Role = 5} (Pickup Agent) 
*/   

    public function employeeDetails()
    {
        $this->checkToken();
        $shipment = Shipment::where('id',$this->shipmentId)->first();
        if($shipment)
        {
            return response()->json([
                'status' => true,
                'message' => 'Profile Details',
                'data' => [$shipment]
            ], 201); 
        }
        return response()->json([
            'status' => false,
            'message' => 'Not an Employee',
            'data' => []
        ]); 
    }

// Pickup Agent List Show

    public function pickupAgents()
    {
        $this->checkToken();
        $agent = Shipment::where('shipment_id',$this->shipmentId)->where('roles',5)->get();
        if(!$agent->isEmpty())
        {
            if($agent->isEmpty())
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Not Pickup Agents',
                    'data' => []
                ]);
            }
            else
            {
                return response()->json([
                    'status' => true,
                    'message' => 'All Agents',
                    'data' => $agent
                ], 201); 
            }
        }

        $dagents = Shipment::where('id',$this->shipmentId)->first();
        $dwagents = Shipment::where('shipment_id',$dagents->shipment_id)->where('roles',5)->get();

       
        if($dwagents->isEmpty() && $agent->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'Not Pickup Agents',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Agents',
                'data' => $dwagents
            ], 201); 
        }
        
    }

// Show Bookings With Pickup to Pickup Agent

    public function pickupBookings()
    {
        $this->checkToken();
        $booking = Marketp::where('dropoff',"Pick up")->where('status','!=','rejected')->orderBy('created_at','desc')->get();
        if($booking->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'Not Pickup Bookings',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Bookings',
                'data' => $booking
            ], 201); 
        }
    }

// Show Bookings To Shipment Company From Market Place

    public function shipmentBookings()
    {
        $this->checkToken();
        $booking = Marketp::where('status','=','created')->orderBy('created_at','desc')->get();
        foreach($booking as $book)
        {
            $book->category = json_decode($book->category);
            $book->item_image = json_decode($book->item_image);
            $book->created_at->format('Y-m-d');
            $book->proposal = Proposal::where('mid',$book->id)->get();
            $proposal_data = Proposal::where('mid',$book->id)->where('sid',$this->shipmentId)->first();
            if(isset($proposal_data->id))
            {
                $book->proposal_status = 'applied';
            } else {
                $book->proposal_status = 'nonapplied';
            }
            $book->client = Client::where('id',$book->uid)->get();
        }
        if($booking->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No Bookings',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Bookings',
                'data' => $booking
            ], 201); 
        }
    }   

// Function to view Accepted Market Place Booking

    public function viewAcceptedMarketShipment()
    {
        $this->checkToken();
        $data = MarketBooking::join('marketps','marketps.id','=','market_bookings.mid')
                            ->where('market_bookings.sid',$this->shipmentId)
                            ->orWhere('market_bookings.pickupagent_id',$this->shipmentId)
                            ->orWhere('market_bookings.departure_id',$this->shipmentId)
                            ->orWhere('market_bookings.arrival_id',$this->shipmentId)
                            ->select('marketps.*','market_bookings.status as market_status','market_bookings.*')
                            ->orderBy('marketps.created_at','desc')
                            ->get();
        foreach($data as $row)
        {
            $row->departure_image = json_decode($row->departure_image);
            $row->arrival_image = json_decode($row->arrival_image);
            $row->pickup_itemimage = json_decode($row->pickup_itemimage);
            $row->pickup_itemimage1 = json_decode($row->pickup_itemimage1);
            $row->client = Client::where('id',$row->uid)->get();
        }

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Bookings',
                'data' => [],
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Your Market Place Bookings',
                'data' => $data
            ], 201); 
        }
    }
    
// Accountant And Manager Accept & Reject Orders (MARKET PLACE BOOKINGS)

    public function acceptBooking(Request $request)
    {
        $this->checkToken();
        $validator = Validator::make($request->all(), [
            "status" => "required", 
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
   
            $booking = Marketp::where('id', $request->market_id)->update([
                'status' => $request->status,
                'sid' => $this->shipmentId
            ]);
    
            if(!$booking)
            {
                return response()->json([
                    'status' => false,
                    'message'=>"Failed",
                    'data' => []
                ],404);
            }
            else 
            {
                $book = Marketp::where('id', $request->market_id)->first();
                $ship = Shipment::where('id', $this->shipmentId)->first();
                $notification = new Notification();
                $notification->msg =  $ship->companyname + "has accepted your market place booking ";
                $notification->title = "Great !!";
                $notification->uid = $book->uid;
                $notification->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Status Changed Successfully',
                    'data' => []
                ], 201); 
            }
        }
    }

 /**
    * Function for Reset Password For Shipment & Employees 
    * {Role = 1} (Shipment Company)
    * {Role = 2} (Accountant)
    * {Role = 3} (WareHouse Manager) 
    * {Role = 4} (Arrival Manager)
    * {Role = 5} (Pickup Agent) 
*/

    public function passwordReset(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => []
            ]);
        }
   

        $shipment = Shipment::where('email', $request->email)->first();

        if(!$shipment)
        {
            return response()->json([
                'status' => false,
                'message'=>"Email Not Registered"
            ],404);
        }
        else
        {
            // Check User is Shipment Company

            if($shipment['roles'] == 1)
            {
                $change = Shipment::where('email', $request->email)->update([
                'password' => $request->password,
                'otp' => ''
                ]);

                $response = [
                    'status' => true,
                    'message' => 'Password Successfully Changed'
                ];

                return response()->json($response, 200);
            }

            // Check Employee is Accountant

            if($shipment['roles'] == 2)
            {
                $change = Shipment::where('email', $request->email)->update([
                    'password' => $request->password,
                    'otp' => ''
                ]);
    
                $response = [
                    'status' => true,
                    'message' => 'Password Successfully Changed'
                ];

                return response()->json($response, 200);
            }

            // Check Employee is Departure Warhouse Manager

            if($shipment['roles'] == 3)
            {
                $change = Shipment::where('email', $request->email)->update([
                    'password' => $request->password,
                    'otp' => ''
                ]);
    
                $response = [
                    'status' => true,
                    'message' => 'Password Successfully Changed'
                ];

                return response()->json($response, 200);
            }

            // Check Employee is Arrival Warhouse Manager

            if($shipment['roles'] == 4)
            {
                $change = Shipment::where('email', $request->email)->update([
                    'password' => $request->password,
                    'otp' => ''
                ]);
    
                $response = [
                    'status' => true,
                    'message' => 'Password Successfully Changed'
                ];

                return response()->json($response, 200);
            }

            // Check Employee is Pickup Agent

            if($shipment['roles'] == 5)
            {
                $change = Shipment::where('email', $request->email)->update([
                    'password' => $request->password,
                    'otp' => ''
                ]);
    
                $response = [
                    'status' => true,
                    'message' => 'Password Successfully Changed'
                ];

                return response()->json($response, 200);
            }

            $response = [
                'status' => false,
                'message' => 'Not an Employee'
            ];

            return response()->json($response, 200);
        }
    }

// Api To Get Schedule Items Category

    public function scheduleItem()
    {
        // $data = DB::table('item_category')->select('*')->get();
        // $category = DB::table('item_category')->select('*')->get();
        // $category = ItemCategory::all();
        // foreach($category as $row)
        // {
        //     $row->icon = 'http://18.188.73.21//image/'.$row->icon;
        // }
        // foreach($category as $res)
        // {
        //     $res->items= DB::table('item_master')->select('item_name','category')->where('category',$res->id)->get();
        // }
        // $category->icon =  ['http://18.188.73.21//image/'.$category->$icon];
        // $data = DB::table('item_master')
        // ->join('item_category','item_master.category','=','item_category.id')
        // ->select('item_master.*','item_category.category_name')->get();

        // $response = [
        //     'status' => true,
        //     'message' => 'All Items',
        //     'data' => $data
        // ];

        // return response()->json($response, 200);

        $this->checkToken();
        $data =  DB::table('item_master')->select('*')->where('sid',$this->shipmentId)->get();
        $items = array();
        foreach($data as $row)
        {
            foreach(json_decode($row->item_name) as $res)
            {
                $itms = ['item_name' => $res,'key' => false];
                array_push($items, $itms);
            }
            $row->item_name = $items;
            foreach($items as $pop)
            {
                array_pop($items);
            }
            // return $row->items;
            // $row->item_name = json_decode($row->item_name);
        }

        if($data->isEmpty())
        {
            return response()->json([
                'status' => true,
                'message' => 'No items Added Yet',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Items',
                'data' => $data
            ], 201); 
        }

    }

// Api To Get Schedule Shipment By Shipment Company

    public function mySchedules()
    {
        $this->checkToken();
        $schedule = Schedule::where('sid',$this->shipmentId)->get();
        // $schedule = DB::table('schedules')
        //             ->join('bookings', 'schedules.id','=','bookings.schedule_id')
        //             ->join('clients','bookings.uid','=','clients.id')
        //             ->where('schedules.sid',$this->shipmentId)
        //             ->select('schedules.*','clients.*','bookings.id as bookings_id','schedules.id as schedule_id')->get();
        foreach($schedule as $data)
        {
            $data->item_type = json_decode($data->item_type);
        }
        // return $schedule;

        // $items = [];
        //     foreach($schedule as $data)
        //     {
        //         $obj = new \stdClass();
        //         $obj = json_decode($data->item_type);

        //         array_push($items, $obj);
        //     }

        //     foreach($schedule as $key=>$row)
        //     {
        //         if($key == 0)
        //         {
        //             $row->item_type = json_decode($items[$key]);
        //         }
        //         else
        //         {
        //             $row->item_type = json_decode($items[$key]);
        //         }
        //     }
            $total = [];
            foreach($schedule as $key=>$row)
            {
                // if($key == 0)
                // {
                //     $row->item_type = json_decode($items[$key]);
                // }
                // else
                // {
                //     $row->item_type = json_decode($items[$key]);
                // }
                $available_container = "0";
                $total_container = "0";
                $schedule_items = DB::table('schedule_items')
                    ->select('*')
                    // ->where('schedule_id',$row->schedule_id)
                    ->where('schedule_id',$row->id)
                    ->get();
                  
                    foreach($schedule_items as $key=>$sitem)
                    {
                        $available_container += $sitem->available;
                        $total_container += $sitem->item_number;
                        $sf = $sitem->item_id;
                        $pf = $sitem->available;
                        $icon = $sitem->icon;
                        $price = ['category' => $sf,'available' => $pf,'icon' => $icon];
                        array_push($total,$price);
                    }
                    
                    // return $schedule_items;
                // if(isset($schedule_items->id)) {
                //     $available_container = $schedule_items->available;
                //     $total_container = $schedule_items->item_number;
                // }
                
                $row->available = $total;
                foreach($total as $pop)
                {
                    array_pop($total);
                }
                $row->total_container = $total_container;
                $row->available_container = $available_container;
            }

            if($schedule->isEmpty())
            {
                return response()->json([
                    'status' => false,
                    'message' => "No Scheduled Shipments",
                    'data' => [] 
                ]);
            }
            else
            {
                return response()->json([
                    'status' => true,
                    'message' => 'All Schedules Shipments With Bookings',
                    'schedule' => $schedule
                ], 201); 
            }
    }

// Function to get All Bookings to that Particular Schedule in Shipment Dashboard

    public function getBooking(Request $request)
    {
        $data = Booking::where('schedule_id','=',$request->schedule_id)->get();
        foreach($data as $row)
        {
            $row->pickup_review = json_decode($row->pickup_review);
            $row->receptionist_info = json_decode($row->receptionist_info);
            $row->item = DB::table('booking_items')->select('*')->where('booking_id','=',$row->id)->get();
            $row->client = Client::where('id',$row->uid)->get();
            $row->departure = Schedule::where('id',$row->schedule_id)->select('departure_warehouse')->get();
            $row->arrival = Schedule::where('id',$row->schedule_id)->select('destination_warehouse')->get();
        }
        if($data->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => "No Bookings",
                'data' => [] 
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Bookings',
                'data' => $data
            ], 201); 
        }
    }

// Funvtion to Get Client previous booking on particularr shipment company

    public function previousBooking(Request $request)
    {
        // return $request->all();
        $shipment = Schedule::where('id',$request->schedule_id)->first();
        // return $shipment;
        $schedule = Schedule::where('sid',$shipment->sid)->get();
        // return $schedule;
        $previousBooking = array();
        foreach($schedule as $row)
        {
            $row->booking = Booking::where('schedule_id',$row->id)->where('uid',$request->client_id)->get();
            foreach($row->booking as $res)
            {
                $res->pickup_review = json_decode($res->pickup_review);
                $res->receptionist_info = json_decode($res->receptionist_info);
            }
            if($row->booking != '[]')
            {
                array_push($previousBooking, $row->booking);
            }
        }
        // return $schedule;
        if($schedule->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => "No Other Bookings",
                'data' => [] 
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Other Bookings',
                'data' => $schedule
            ], 201); 
        }

    }
// Function to Show Confirmed Orders To Shipment Company

    public function confirmedOrders()
    {
        $this->checkToken();
        $schedule = DB::table('schedules')
                    ->join('bookings', 'schedules.id','=','bookings.schedule_id')
                    ->join('clients','bookings.uid','=','clients.id')
                    ->where('schedules.sid',$this->shipmentId)
                    ->where('bookings.status','Confirmed')
                    ->select('bookings.*','clients.*','bookings.status as booking_status','bookings.id as booking_id','schedules.id as schedules_id','schedules.status as schedules_status')->get();
        //    return $schedule;
        // $bookings_item = [];
        foreach($schedule as $book)
        {
            $booking_item = BookingItem::where('booking_id','=',$book->booking_id)->get();
            $book->booking_items = $booking_item;
        }
        $items = [];
            foreach($schedule as $data)
            {
                $obj = new \stdClass();
                $obj = json_decode($data->pickup_review);

                array_push($items, $obj);
            }

            foreach($schedule as $key=>$row)
            {
                if($key == 0)
                {
                    $row->pickup_review = $items[$key];
                }
                else
                {
                    $row->pickup_review = $items[$key];
                }
            }

            if($schedule->isEmpty())
            {
                return response()->json([
                    'status' => false,
                    'message' => "No Confirmed orders",
                    'data' => [] 
                ]);
            }
            else
            {
                return response()->json([
                    'status' => true,
                    'message' => 'All Confirmed orders',
                    'schedule' => $schedule
                ], 201); 
            }

    }

// Function To Accept or Reject Orders (Bookings)

    public function acceptOrders(Request $request)
    {
        if($request->status == 1)
        {
          $booking = Booking::where('id',$request->booking_id)->update(['accepted' => $request->status,'rejected' => 0,'status' => 'Accepted']);
        }
        else if($request->status == 0)
        {
          $booking = Booking::where('id',$request->booking_id)->update(['rejected' => 1, 'accepted' => 0,'status' => 'Cancelled','reason' => $request->reason]);


        $bookitem = DB::table('booking_category')->select('*')->where('booking_id','=',$request->booking_id)->get();
        foreach($bookitem as $key=>$itm)
        {
            
            $scheduleItem = ScheduleCategory::where('item_name',$itm->item_name)->where('schedule_id',$itm->schedule_id)->get();
                foreach($scheduleItem as $scItem)
                {
                    ScheduleCategory::where('item_name', $itm->item_name)->where('schedule_id',$itm->schedule_id)->update([
                        'available' => $scItem->available + $itm->quantity
                        ]);
                }

        }
          
        }
        $book = Booking::where('id',$request->booking_id)->first();
        if(!$book)
        {
            return response()->json([
                'status' => false,
                'message' => 'Failed'
            ], 201); 
        }
        else if($book->accepted == 1)
        {
            $notification = new Notification();
            $notification->msg = "Your booking is accepted.";
            $notification->title = "Great !!";
            $notification->uid = $book->uid;
            $notification->booking_id = $book->id;
            $notification->save();
            
            return response()->json([
                'status' => true,
                'message' => 'Booking Accepted',
                'data' => []
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Booking Rejected',
                'data' => []
            ], 201);
        }
    
    }

// Function to Get Schedule Data

    public function scheduleDetail(Request $request)
    {
        $data = Schedule::where('id',$request->scheduleId)->get();
        foreach($data as $value) {
            $value->item_type = json_decode($value->item_type);
        }
        if($data->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message'=>"Failed",
                'data' => []
            ],404);
        }
        else 
        {
            return response()->json([
                'status' => true,
                'message' => 'Details',
                'data' => $data
            ], 201); 
        }
    }

// Function to get Selected Client All Booking Information

    public function clientBookings(Request $request)
    {
        $this->checkToken();
        $data = DB::table('bookings')
                ->join('schedules','schedules.id','=','bookings.schedule_id')
                ->where('bookings.uid','=',$request->id)
                ->where('schedules.sid',$this->shipmentId)
                ->select('bookings.*')->get();
      

        foreach($data as $value) {
            $value->pickup_review = json_decode($value->pickup_review);
            $value->receptionist_info = json_decode($value->receptionist_info);
        }
        if($data->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message'=>"No More Bookings",
                'data' => []
            ],404);
        }
        else 
        {
            return response()->json([
                'status' => true,
                'message' => 'Bookings',
                'data' => $data
            ], 201); 
        }
    }

// Function to Forgot Password Api

    public function shipmentForgotPassword(Request $request)
    {

        $users = Shipment::where('email', '=', $request->input('email'))->first();
        // return $users;

        if ($users === null) {
            return response()->json([
                'status' => false,
                'message' => 'User Not Registered yet',
                'data' => []
            ]);
        } 
        else 
        {     
            $email = $request->email;
            $otp = rand(1000,9999);

            Shipment::where('email','=',$request->email)->update(['otp'=>$otp]);
    
            $maildata = [
                'title' => 'Your OTP For Forget Password is :'. $otp,
                'url' => 'https://www.positronx.io'
            ];
    
            Mail::to($email)->send(new SendDemoMail($maildata));
    
            return response()->json([
                'status' => true,
                'message' => 'Reset password OTP is sent on your email id',
                'data' => ['otp' => $otp]
            ]);
        }
    }


// Shipment Social Login Api 

    public function shipment_socialLogin(Request $request)
    {
        $check = Client::where('email',$request->email)->get();
        if(!$check->isEmpty())
        {
            $response = [
                'status' => false,
                'message' => 'Email Exist in Client',
                'data' => []
            ];

            return response()->json($response, 200);
        }
        else 
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|email', 
                'provider' => 'required|string',
                'social_token'    => 'required|string'
            ]);
            if ($validator->fails())
            {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ]);
            }

            $loginData = [];
            $loginData['name'] = $request->name;
            $loginData['lname'] = $request->lname;
            $loginData['phone'] = $request->phone;
            $loginData['email'] = $request->email;
            $loginData['roles'] = 1;
            $loginData['provider'] = $request->provider;
            if($request->provider == 'google')
            {
                $loginData['google_id'] = $request->social_token;
            }
            else
            {
                $loginData['facebook_id'] = $request->social_token;
            }

            // print_r($loginData);
            // die;
            
            $shipment = Shipment::where('email',$request->email)->where('roles','=','1')->first();
            // if(!$shipment || !Hash::check($request->password, $shipment->password))
            if(!isset($shipment->id)) 
            {
            $shipment = Shipment::create($loginData);
            } 

            $token = $shipment->createToken('my-app-token')->accessToken;

            $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;

            $response = [
                'status' => true,
                'message' => 'Login Successful',
                'data' => [$shipment]
            ];

            return response()->json($response, 200);
        }
        
      
    }

// Shipment Delete Profile Api

    public function deleteProfileShipment()
    {
        $this->checkToken();
        $headers = getallheaders();
        $check = DB::table('personal_access_tokens')->where('token',$headers['token'])->select('tokenable_id')->get();
        // $client = Client::find($this->userId);
        $client = Shipment::where('id', $this->shipmentId)->update(['status' => 'Deactivate'  ]);
        if(is_null($client)){
          return response([
              'status' => false,
              'message' => 'User Not Found',
              'data' => []
            ],404);
        }
        else {
            
            return response()->json([
                'status' => true,
                'message' => 'Profile Deactivated',
                'data' => []
            ] ,201);
        }
    }
  
// Function to Perform Search by Schedule Title by Shipment Company

    public function searchTitle(Request $request)
    {
        $this->checkToken();
        $stats = $request->stats;
        $title = $request->title;
        
        if($title == '' && $stats == '')
        {
            return response()->json([
                'status' => false,
                'message' => "No Result Found",
                'data' => []
            ]);
        }
        else if($stats == '')
        {
            $data = Schedule::where('title','LIKE','%'.$title.'%')->where('sid', $this->shipmentId)->orderBy('created_at','desc')->get();
            foreach($data as $value) {
                $value->item_type = json_decode($value->item_type);
            }
            $total = [];
            foreach($data as $key=>$value) 
            {
                $available_container = "0";
                $total_container = "0";
                $schedule_items = DB::table('schedule_items')
                    ->select('*')
                    ->where('schedule_id',$value->id)
                    ->get();
                //   return $schedule_items;
                    foreach($schedule_items as $key=>$sitem)
                    {
                        $available_container += $sitem->available;
                        $total_container += $sitem->item_number;
                        $sf = $sitem->item_id;
                        $pf = $sitem->available;
                        $price = ['category' => $sf,'available' => $pf];
                        array_push($total,$price);
                    }
            
                
                
                $value->available = $total;
                foreach($total as $pop)
                {
                    array_pop($total);
                }
                $value->total_container = $total_container;
                $value->available_container = $available_container;
            }
            foreach($data as $review)
            {
                $review->rating = Review::where('sid','=',$review->sid)->select('rating')->get();
                $count = 0;
                $cnt = 0;
                foreach($review->rating as $key=>$avg)
                {
                    $cnt += $avg->rating;
                    $count++;
                }
                if($count != 0)
                {
                    $review->rating = round($cnt/$count ,2 );
                }
                else
                {
                    $review->rating = 0;
                }
            }
            if(count($data) > 0)
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Matched Results.',
                    'data' => $data
                ]);
            }
            else{
                return response()->json([
                    'status' => false,
                    'message' => 'No Result Found.',
                    'data' => []
                ]);
            }
        }
        else if($title == '')
        {
            $data = Schedule::where('status',$stats)->where('sid', $this->shipmentId)->get();
            foreach($data as $value) {
                $value->item_type = json_decode($value->item_type);
            }
            $total = [];
            foreach($data as $key=>$value) 
            {
                $available_container = "0";
                $total_container = "0";
                $schedule_items = DB::table('schedule_items')
                    ->select('*')
                    ->where('schedule_id',$value->id)
                    ->get();
                //   return $schedule_items;
                    foreach($schedule_items as $key=>$sitem)
                    {
                        $available_container += $sitem->available;
                        $total_container += $sitem->item_number;
                        $sf = $sitem->item_id;
                        $pf = $sitem->available;
                        $price = ['category' => $sf,'available' => $pf];
                        array_push($total,$price);
                    }
            
                
                
                $value->available = $total;
                foreach($total as $pop)
                {
                    array_pop($total);
                }
                $value->total_container = $total_container;
                $value->available_container = $available_container;
            }
            foreach($data as $review)
            {
                $review->rating = Review::where('sid','=',$review->sid)->select('rating')->get();
                $count = 0;
                $cnt = 0;
                foreach($review->rating as $key=>$avg)
                {
                    $cnt += $avg->rating;
                    $count++;
                }
                if($count != 0)
                {
                    $review->rating = round($cnt/$count ,2 );
                }
                else
                {
                    $review->rating = 0;
                }
            }
            if(count($data) > 0)
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Matched Results.',
                    'data' => $data
                ]);
            }
            else{
                return response()->json([
                    'status' => false,
                    'message' => 'No Result Found.',
                    'data' => []
                ]);
            }
        }
        else 
        {
            $matchThese = ['title' => $title, 'status' => $stats];
            $data = Schedule::where($matchThese)->where('sid', $this->shipmentId)->get();
            // $data = Schedule::where('title','LIKE','%'.$title.'%')->where('sid', $this->shipmentId)->get();
            foreach($data as $value) {
                $value->item_type = json_decode($value->item_type);
            }
            $total = [];
            foreach($data as $key=>$value) 
            {
                $available_container = "0";
                $total_container = "0";
                $schedule_items = DB::table('schedule_items')
                    ->select('*')
                    ->where('schedule_id',$value->id)
                    ->get();
                //   return $schedule_items;
                    foreach($schedule_items as $key=>$sitem)
                    {
                        $available_container += $sitem->available;
                        $total_container += $sitem->item_number;
                        $sf = $sitem->item_id;
                        $pf = $sitem->available;
                        $price = ['category' => $sf,'available' => $pf];
                        array_push($total,$price);
                    }
            
                
                
                $value->available = $total;
                foreach($total as $pop)
                {
                    array_pop($total);
                }
                $value->total_container = $total_container;
                $value->available_container = $available_container;
            }
            foreach($data as $review)
            {
                $review->rating = Review::where('sid','=',$review->sid)->select('rating')->get();
                $count = 0;
                $cnt = 0;
                foreach($review->rating as $key=>$avg)
                {
                    $cnt += $avg->rating;
                    $count++;
                }
                if($count != 0)
                {
                    $review->rating = round($cnt/$count ,2 );
                }
                else
                {
                    $review->rating = 0;
                }
            }
            if(count($data) > 0)
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Matched Results.',
                    'data' => $data
                ]);
            }
            else{
                return response()->json([
                    'status' => false,
                    'message' => 'No Result Found.',
                    'data' => []
                ]);
            }
        }

    }

// Function to Perform Search By Shipment Employees

    public function searchTitles(Request $request)
    {
        $this->checkToken();
        $sid = Shipment::where('id',$this->shipmentId)->first();
        $stats = $request->stats;
        $title = $request->title;

        if($sid->roles == 2)
        {

            if($title == '' && $stats == '')
            {
                return response()->json([
                    'status' => false,
                    'message' => "No Result Found",
                    'data' => []
                ]);
            }
            else if($stats == '')
            {
                $data = Schedule::where('title','LIKE','%'.$title.'%')->where('sid', $sid->shipment_id)->orderBy('created_at','desc')->get();
                foreach($data as $value) {
                    $value->item_type = json_decode($value->item_type);
                }
                $total = [];
                foreach($data as $key=>$value) 
                {
                    $available_container = "0";
                    $total_container = "0";
                    $schedule_items = DB::table('schedule_items')
                        ->select('*')
                        ->where('schedule_id',$value->id)
                        ->get();
                    //   return $schedule_items;
                        foreach($schedule_items as $key=>$sitem)
                        {
                            $available_container += $sitem->available;
                            $total_container += $sitem->item_number;
                            $sf = $sitem->item_id;
                            $pf = $sitem->available;
                            $price = ['category' => $sf,'available' => $pf];
                            array_push($total,$price);
                        }
                
                    
                    
                    $value->available = $total;
                    foreach($total as $pop)
                    {
                        array_pop($total);
                    }
                    $value->total_container = $total_container;
                    $value->available_container = $available_container;
                }
                foreach($data as $review)
                {
                    $review->rating = Review::where('sid','=',$review->sid)->select('rating')->get();
                    $count = 0;
                    $cnt = 0;
                    foreach($review->rating as $key=>$avg)
                    {
                        $cnt += $avg->rating;
                        $count++;
                    }
                    if($count != 0)
                    {
                        $review->rating = round($cnt/$count ,2 );
                    }
                    else
                    {
                        $review->rating = 0;
                    }
                }
                if(count($data) > 0)
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Matched Results.',
                        'data' => $data
                    ]);
                }
                else{
                    return response()->json([
                        'status' => false,
                        'message' => 'No Result Found.',
                        'data' => []
                    ]);
                }
            }
            else if($title == '')
            {
                $data = Schedule::where('status',$stats)->where('sid', $sid->shipment_id)->orderBy('created_at','desc')->get();
                foreach($data as $value) {
                    $value->item_type = json_decode($value->item_type);
                }
                $total = [];
                foreach($data as $key=>$value) 
                {
                    $available_container = "0";
                    $total_container = "0";
                    $schedule_items = DB::table('schedule_items')
                        ->select('*')
                        ->where('schedule_id',$value->id)
                        ->get();
                    //   return $schedule_items;
                        foreach($schedule_items as $key=>$sitem)
                        {
                            $available_container += $sitem->available;
                            $total_container += $sitem->item_number;
                            $sf = $sitem->item_id;
                            $pf = $sitem->available;
                            $price = ['category' => $sf,'available' => $pf];
                            array_push($total,$price);
                        }
                
                    
                    
                    $value->available = $total;
                    foreach($total as $pop)
                    {
                        array_pop($total);
                    }
                    $value->total_container = $total_container;
                    $value->available_container = $available_container;
                }
                foreach($data as $review)
                {
                    $review->rating = Review::where('sid','=',$review->sid)->select('rating')->get();
                    $count = 0;
                    $cnt = 0;
                    foreach($review->rating as $key=>$avg)
                    {
                        $cnt += $avg->rating;
                        $count++;
                    }
                    if($count != 0)
                    {
                        $review->rating = round($cnt/$count ,2 );
                    }
                    else
                    {
                        $review->rating = 0;
                    }
                }
                if(count($data) > 0)
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Matched Results.',
                        'data' => $data
                    ]);
                }
                else{
                    return response()->json([
                        'status' => false,
                        'message' => 'No Result Found.',
                        'data' => []
                    ]);
                }
            }
            else 
            {
                $matchThese = ['title' => $title, 'status' => $stats];
                $data = Schedule::where($matchThese)->where('sid', $sid->shipment_id)->orderBy('created_at','desc')->get();
                // $data = Schedule::where('title','LIKE','%'.$title.'%')->where('sid', $sid->shipment_id)->get();
                foreach($data as $value) {
                    $value->item_type = json_decode($value->item_type);
                }
                $total = [];
                foreach($data as $key=>$value) 
                {
                    $available_container = "0";
                    $total_container = "0";
                    $schedule_items = DB::table('schedule_items')
                        ->select('*')
                        ->where('schedule_id',$value->id)
                        ->get();
                    //   return $schedule_items;
                        foreach($schedule_items as $key=>$sitem)
                        {
                            $available_container += $sitem->available;
                            $total_container += $sitem->item_number;
                            $sf = $sitem->item_id;
                            $pf = $sitem->available;
                            $price = ['category' => $sf,'available' => $pf];
                            array_push($total,$price);
                        }
                
                    
                    
                    $value->available = $total;
                    foreach($total as $pop)
                    {
                        array_pop($total);
                    }
                    $value->total_container = $total_container;
                    $value->available_container = $available_container;
                }
                foreach($data as $review)
                {
                    $review->rating = Review::where('sid','=',$review->sid)->select('rating')->get();
                    $count = 0;
                    $cnt = 0;
                    foreach($review->rating as $key=>$avg)
                    {
                        $cnt += $avg->rating;
                        $count++;
                    }
                    if($count != 0)
                    {
                        $review->rating = round($cnt/$count ,2 );
                    }
                    else
                    {
                        $review->rating = 0;
                    }
                }
                if(count($data) > 0)
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Matched Results.',
                        'data' => $data
                    ]);
                }
                else{
                    return response()->json([
                        'status' => false,
                        'message' => 'No Result Found.',
                        'data' => []
                    ]);
                }
            }

        }
        else if($sid->roles == 3)
        {

            if($title == '' && $stats == '')
            {
                return response()->json([
                    'status' => false,
                    'message' => "No Result Found",
                    'data' => []
                ]);
            }
            else if($stats == '')
            {
                $data = Schedule::where('title','LIKE','%'.$title.'%')->where('departure_warehouse', $this->shipmentId)->orderBy('created_at','desc')->get();
                foreach($data as $value) {
                    $value->item_type = json_decode($value->item_type);
                }
                $total = [];
                foreach($data as $key=>$value) 
                {
                    $available_container = "0";
                    $total_container = "0";
                    $schedule_items = DB::table('schedule_items')
                        ->select('*')
                        ->where('schedule_id',$value->id)
                        ->get();
                    //   return $schedule_items;
                        foreach($schedule_items as $key=>$sitem)
                        {
                            $available_container += $sitem->available;
                            $total_container += $sitem->item_number;
                            $sf = $sitem->item_id;
                            $pf = $sitem->available;
                            $price = ['category' => $sf,'available' => $pf];
                            array_push($total,$price);
                        }
                
                    
                    
                    $value->available = $total;
                    foreach($total as $pop)
                    {
                        array_pop($total);
                    }
                    $value->total_container = $total_container;
                    $value->available_container = $available_container;
                }
                foreach($data as $review)
                {
                    $review->rating = Review::where('sid','=',$review->sid)->select('rating')->get();
                    $count = 0;
                    $cnt = 0;
                    foreach($review->rating as $key=>$avg)
                    {
                        $cnt += $avg->rating;
                        $count++;
                    }
                    if($count != 0)
                    {
                        $review->rating = round($cnt/$count ,2 );
                    }
                    else
                    {
                        $review->rating = 0;
                    }
                }
                if(count($data) > 0)
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Matched Results.',
                        'data' => $data
                    ]);
                }
                else{
                    return response()->json([
                        'status' => false,
                        'message' => 'No Result Found.',
                        'data' => []
                    ]);
                }
            }
            else if($title == '')
            {
                $data = Schedule::where('status',$stats)->where('departure_warehouse', $this->shipmentId)->orderBy('created_at','desc')->get();
                foreach($data as $value) {
                    $value->item_type = json_decode($value->item_type);
                }
                $total = [];
                foreach($data as $key=>$value) 
                {
                    $available_container = "0";
                    $total_container = "0";
                    $schedule_items = DB::table('schedule_items')
                        ->select('*')
                        ->where('schedule_id',$value->id)
                        ->get();
                    //   return $schedule_items;
                        foreach($schedule_items as $key=>$sitem)
                        {
                            $available_container += $sitem->available;
                            $total_container += $sitem->item_number;
                            $sf = $sitem->item_id;
                            $pf = $sitem->available;
                            $price = ['category' => $sf,'available' => $pf];
                            array_push($total,$price);
                        }
                
                    
                    
                    $value->available = $total;
                    foreach($total as $pop)
                    {
                        array_pop($total);
                    }
                    $value->total_container = $total_container;
                    $value->available_container = $available_container;
                }
                foreach($data as $review)
                {
                    $review->rating = Review::where('sid','=',$review->sid)->select('rating')->get();
                    $count = 0;
                    $cnt = 0;
                    foreach($review->rating as $key=>$avg)
                    {
                        $cnt += $avg->rating;
                        $count++;
                    }
                    if($count != 0)
                    {
                        $review->rating = round($cnt/$count ,2 );
                    }
                    else
                    {
                        $review->rating = 0;
                    }
                }
                if(count($data) > 0)
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Matched Results.',
                        'data' => $data
                    ]);
                }
                else{
                    return response()->json([
                        'status' => false,
                        'message' => 'No Result Found.',
                        'data' => []
                    ]);
                }
            }
            else 
            {
                $matchThese = ['title' => $title, 'status' => $stats];
                $data = Schedule::where($matchThese)->where('departure_warehouse', $this->shipmentId)->orderBy('created_at','desc')->get();
                // $data = Schedule::where('title','LIKE','%'.$title.'%')->where('sid', $sid->shipment_id)->get();
                foreach($data as $value) {
                    $value->item_type = json_decode($value->item_type);
                }
                $total = [];
                foreach($data as $key=>$value) 
                {
                    $available_container = "0";
                    $total_container = "0";
                    $schedule_items = DB::table('schedule_items')
                        ->select('*')
                        ->where('schedule_id',$value->id)
                        ->get();
                    //   return $schedule_items;
                        foreach($schedule_items as $key=>$sitem)
                        {
                            $available_container += $sitem->available;
                            $total_container += $sitem->item_number;
                            $sf = $sitem->item_id;
                            $pf = $sitem->available;
                            $price = ['category' => $sf,'available' => $pf];
                            array_push($total,$price);
                        }
                
                    
                    
                    $value->available = $total;
                    foreach($total as $pop)
                    {
                        array_pop($total);
                    }
                    $value->total_container = $total_container;
                    $value->available_container = $available_container;
                }
                foreach($data as $review)
                {
                    $review->rating = Review::where('sid','=',$review->sid)->select('rating')->get();
                    $count = 0;
                    $cnt = 0;
                    foreach($review->rating as $key=>$avg)
                    {
                        $cnt += $avg->rating;
                        $count++;
                    }
                    if($count != 0)
                    {
                        $review->rating = round($cnt/$count ,2 );
                    }
                    else
                    {
                        $review->rating = 0;
                    }
                }
                if(count($data) > 0)
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Matched Results.',
                        'data' => $data
                    ]);
                }
                else{
                    return response()->json([
                        'status' => false,
                        'message' => 'No Result Found.',
                        'data' => []
                    ]);
                }
            }

        }
        else if($sid->roles == 4)
        {
            if($title == '' && $stats == '')
            {
                return response()->json([
                    'status' => false,
                    'message' => "No Result Found",
                    'data' => []
                ]);
            }
            else if($stats == '')
            {
                $data = Schedule::where('title','LIKE','%'.$title.'%')->where('destination_warehouse', $this->shipmentId)->orderBy('created_at','desc')->get();
                foreach($data as $value) {
                    $value->item_type = json_decode($value->item_type);
                }
                $total = [];
                foreach($data as $key=>$value) 
                {
                    $available_container = "0";
                    $total_container = "0";
                    $schedule_items = DB::table('schedule_items')
                        ->select('*')
                        ->where('schedule_id',$value->id)
                        ->get();
                    //   return $schedule_items;
                        foreach($schedule_items as $key=>$sitem)
                        {
                            $available_container += $sitem->available;
                            $total_container += $sitem->item_number;
                            $sf = $sitem->item_id;
                            $pf = $sitem->available;
                            $price = ['category' => $sf,'available' => $pf];
                            array_push($total,$price);
                        }
                
                    
                    
                    $value->available = $total;
                    foreach($total as $pop)
                    {
                        array_pop($total);
                    }
                    $value->total_container = $total_container;
                    $value->available_container = $available_container;
                }
                foreach($data as $review)
                {
                    $review->rating = Review::where('sid','=',$review->sid)->select('rating')->get();
                    $count = 0;
                    $cnt = 0;
                    foreach($review->rating as $key=>$avg)
                    {
                        $cnt += $avg->rating;
                        $count++;
                    }
                    if($count != 0)
                    {
                        $review->rating = round($cnt/$count ,2 );
                    }
                    else
                    {
                        $review->rating = 0;
                    }
                }
                if(count($data) > 0)
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Matched Results.',
                        'data' => $data
                    ]);
                }
                else{
                    return response()->json([
                        'status' => false,
                        'message' => 'No Result Found.',
                        'data' => []
                    ]);
                }
            }
            else if($title == '')
            {
                $data = Schedule::where('status',$stats)->where('destination_warehouse', $this->shipmentId)->orderBy('created_at','desc')->get();
                foreach($data as $value) {
                    $value->item_type = json_decode($value->item_type);
                }
                $total = [];
                foreach($data as $key=>$value) 
                {
                    $available_container = "0";
                    $total_container = "0";
                    $schedule_items = DB::table('schedule_items')
                        ->select('*')
                        ->where('schedule_id',$value->id)
                        ->get();
                    //   return $schedule_items;
                        foreach($schedule_items as $key=>$sitem)
                        {
                            $available_container += $sitem->available;
                            $total_container += $sitem->item_number;
                            $sf = $sitem->item_id;
                            $pf = $sitem->available;
                            $price = ['category' => $sf,'available' => $pf];
                            array_push($total,$price);
                        }
                
                    
                    
                    $value->available = $total;
                    foreach($total as $pop)
                    {
                        array_pop($total);
                    }
                    $value->total_container = $total_container;
                    $value->available_container = $available_container;
                }
                foreach($data as $review)
                {
                    $review->rating = Review::where('sid','=',$review->sid)->select('rating')->get();
                    $count = 0;
                    $cnt = 0;
                    foreach($review->rating as $key=>$avg)
                    {
                        $cnt += $avg->rating;
                        $count++;
                    }
                    if($count != 0)
                    {
                        $review->rating = round($cnt/$count ,2 );
                    }
                    else
                    {
                        $review->rating = 0;
                    }
                }
                if(count($data) > 0)
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Matched Results.',
                        'data' => $data
                    ]);
                }
                else{
                    return response()->json([
                        'status' => false,
                        'message' => 'No Result Found.',
                        'data' => []
                    ]);
                }
            }
            else 
            {
                $matchThese = ['title' => $title, 'status' => $stats];
                $data = Schedule::where($matchThese)->where('destination_warehouse', $this->shipmentId)->orderBy('created_at','desc')->get();
                // $data = Schedule::where('title','LIKE','%'.$title.'%')->where('sid', $sid->shipment_id)->get();
                foreach($data as $value) {
                    $value->item_type = json_decode($value->item_type);
                }
                $total = [];
                foreach($data as $key=>$value) 
                {
                    $available_container = "0";
                    $total_container = "0";
                    $schedule_items = DB::table('schedule_items')
                        ->select('*')
                        ->where('schedule_id',$value->id)
                        ->get();
                    //   return $schedule_items;
                        foreach($schedule_items as $key=>$sitem)
                        {
                            $available_container += $sitem->available;
                            $total_container += $sitem->item_number;
                            $sf = $sitem->item_id;
                            $pf = $sitem->available;
                            $price = ['category' => $sf,'available' => $pf];
                            array_push($total,$price);
                        }
                
                    
                    
                    $value->available = $total;
                    foreach($total as $pop)
                    {
                        array_pop($total);
                    }
                    $value->total_container = $total_container;
                    $value->available_container = $available_container;
                }
                foreach($data as $review)
                {
                    $review->rating = Review::where('sid','=',$review->sid)->select('rating')->get();
                    $count = 0;
                    $cnt = 0;
                    foreach($review->rating as $key=>$avg)
                    {
                        $cnt += $avg->rating;
                        $count++;
                    }
                    if($count != 0)
                    {
                        $review->rating = round($cnt/$count ,2 );
                    }
                    else
                    {
                        $review->rating = 0;
                    }
                }
                if(count($data) > 0)
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Matched Results.',
                        'data' => $data
                    ]);
                }
                else{
                    return response()->json([
                        'status' => false,
                        'message' => 'No Result Found.',
                        'data' => []
                    ]);
                }
            }
        }

    }

// Function to Perform search By Pickup Agent in Bookings

    public function pickupSearch(Request $request)
    {
        $this->checkToken();
        $stats = $request->stats;
        $title = $request->title;
        
        if($title == '' && $stats == '')
        {
            return response()->json([
                'status' => false,
                'message' => "No Result Found",
                'data' => []
            ]);
        }
        else if($stats == '')
        {
            $data = Booking::where('title','LIKE','%'.$title.'%')->where('pickupagent_id', $this->shipmentId)->orderBy('created_at','desc')->get();
            foreach($data as $value) {
                $value->pickup_review = json_decode($value->pickup_review);
            }
            foreach($data as $key=>$value) 
            {
                $booking_items = DB::table('booking_items')
                    ->select('*')
                    ->where('booking_id',$value->id)
                    ->get();

                    foreach($booking_items as $sitem)
                    {
                        $sitem->item_image = json_decode($sitem->item_image);
                    }
            }
            if(count($data) > 0)
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Matched Results.',
                    'data' => $data
                ]);
            }
            else{
                return response()->json([
                    'status' => false,
                    'message' => 'No Result Found.',
                    'data' => []
                ]);
            }
        }
        else if($title == '')
        {
            $data = Booking::where('status',$stats)->where('pickupagent_id', $this->shipmentId)->get();
            foreach($data as $value) {
                $value->pickup_review = json_decode($value->pickup_review);
            }
            foreach($data as $key=>$value) 
            {
                $booking_items = DB::table('booking_items')
                    ->select('*')
                    ->where('booking_id',$value->id)
                    ->get();

                    foreach($booking_items as $sitem)
                    {
                        $sitem->item_image = json_decode($sitem->item_image);
                    }
            }
            if(count($data) > 0)
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Matched Results.',
                    'data' => $data
                ]);
            }
            else{
                return response()->json([
                    'status' => false,
                    'message' => 'No Result Found.',
                    'data' => []
                ]);
            }
        }
        else 
        {
            $matchThese = ['title' => $title, 'status' => $stats];
            $data = Booking::where($matchThese)->where('pickupagent_id', $this->shipmentId)->get();
            foreach($data as $value) {
                $value->pickup_review = json_decode($value->pickup_review);
            }
            foreach($data as $key=>$value) 
            {
                $booking_items = DB::table('booking_items')
                    ->select('*')
                    ->where('booking_id',$value->id)
                    ->get();

                    foreach($booking_items as $sitem)
                    {
                        $sitem->item_image = json_decode($sitem->item_image);
                    }
            }
            if(count($data) > 0)
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Matched Results.',
                    'data' => $data
                ]);
            }
            else{
                return response()->json([
                    'status' => false,
                    'message' => 'No Result Found.',
                    'data' => []
                ]);
            }
        }

    }

// Function to View Market Place Bookings By Shipment

    public function shipmentMarketPlace()
    {
        $this->checkToken();
        $data = Marketp::where('sid',$this->shipmentId)->orderBy('created_at','desc')->get();
        foreach($data as $value) {
            $value->category = json_decode($value->category);
            $value->client = Client::where('id',$value->uid)->first();
            $value->marketplace_bookingid = MarketBooking::where('mid',$value->id)->get();
        }
        if(count($data) == 0 )
        {
            return response()->json([
                'status' => false,
                'message' => 'No Accepted Bookings',
                'data' => [],
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Your Accepted Market Place Bookings',
                'data' => $data
            ], 201); 
        }
    }

// Function to Send Proposal After Accepting Market Place Booking

    public function sendProposal(Request $request)
    {
        $this->checkToken();
        $validator = Validator::make($request->all(), [
            "uid" => "required",
            "mid" => "required",
            "proposal" => "required",
            "departure_id" => "required",
            "arrival_id" => "required"
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
                $proposal = new Proposal();
                $proposal->uid = $request->uid;
                $proposal->sid = $this->shipmentId;
                $proposal->mid = $request->mid;
                $proposal->proposals = $request->proposal;
                $proposal->type = $request->type;
                $proposal->pickupfee = $request->pickupfee;
                $proposal->shipping_price = $request->shipping_price;
                $proposal->tax = $request->tax;
                $proposal->status = "sent"; 
                $proposal->departure_id = $request->departure_id;
                $proposal->arrival_id = $request->arrival_id;
                $proposal->save();
        }
        return response()->json([
            'status' => true,
            'message' => 'Successfully Sent Proposal',
            'data' => [$proposal]
        ], 201); 
    }

// Function getting all bookings on shipment company schedules with status confirmed

    public function confirmedBookings()
    {
        $this->checkToken();
        $databooking=array();
        $schedule = Schedule::where('sid',$this->shipmentId)->orderBy('created_at','desc')->get();

        $time = DB::table('timer')->orderBy('created_at','desc')->first();

    

        $timerhr = '+'.$time->hours.' hour';
        foreach($schedule as $value)
        {
            $value->item_type = json_decode($value->item_type);
            $booking = Booking::where('schedule_id',$value->id)->where('status','=','Confirmed')->where('accepted','=',0)->where('rejected','=',0)->orderBy('created_at','desc')->get();



            foreach($booking as $book)
            {
                $databooking[]=$book;

                

                $book->created_at .' '.$timerhr;

                $book->new_expire_date = date('Y-m-d H:i:s', strtotime($book->created_at .' '.$timerhr));
                $book->schedule = Schedule::where('id',$book->schedule_id)->orderBy('created_at','desc')->get();
                $book->pickup_review = json_decode($book->pickup_review);
                $book->receptionist_info = json_decode($book->receptionist_info);
                $book->booking_item =  DB::table('booking_items')->select('*')->where('booking_id','=',$book->id)->get();
                foreach($book->booking_item as $row)
                {
                    $row->item_name = json_decode($row->item_name);
                    $row->item_image = json_decode($row->item_image);
                }
                $book->client = Client::where('id',$book->uid)->orderBy('created_at','desc')->get();
            }
        }
        if(isset($schedule->booking))
        {
            return response()->json([
                'status' => false,
                'message' => 'No Confirmed Bookings',
                'data' => []
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Confirmed Bookings',
                'data' => $databooking
            ], 201); 
        }
    }

// Function getting all bookings on shipment company schedules with status accepted

    public function shipmentOrders()
    {
        $this->checkToken();
        $databooking=array();
        $schedule = Schedule::where('sid',$this->shipmentId)->orderBy('created_at','desc')->get();
        foreach($schedule as $value)
        {
            $value->item_type = json_decode($value->item_type);
            $booking = Booking::where('schedule_id',$value->id)->where('accepted','=',1)->orderBy('created_at','desc')->get();
            foreach($booking as $book)
            {
                $databooking[]=$book;
                $book->schedule = Schedule::where('id',$book->schedule_id)->get();
                $book->pickup_review = json_decode($book->pickup_review);
                $book->receptionist_info = json_decode($book->receptionist_info);
                $book->booking_item =  DB::table('booking_items')->select('*')->where('booking_id','=',$book->id)->get();
                foreach($book->booking_item as $row)
                {
                    $row->item_name = json_decode($row->item_name);
                    $row->item_image = json_decode($row->item_image);
                }
                $book->client = Client::where('id',$book->uid)->first();
            }
        }
        if(isset($schedule->booking))
        {
            return response()->json([
                'status' => false,
                'message' => 'No Accepted Bookings',
                'data' => []
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Accepted Bookings',
                'data' => $databooking
            ], 201); 
        }
    }

// Function getting all bookings with status done (Completed)

    public function doneBookings()
    {
        $this->checkToken();
        $databooking=array();
        $schedule = Schedule::where('sid',$this->shipmentId)->orderBy('created_at','desc')->get();
        foreach($schedule as $value)
        {
            $value->item_type = json_decode($value->item_type);
            $value->booking = Booking::where('schedule_id',$value->id)->where('status','=','Completed')->get();
            foreach($value->booking as $book)
            {
                $databooking[]=$book;
                $book->pickup_review = json_decode($book->pickup_review);
                $book->receptionist_info = json_decode($book->receptionist_info);
                $book->booking_item =  DB::table('booking_items')->select('*')->where('booking_id','=',$book->id)->get();
                $book->client = Client::where('id',$book->uid)->first();
            }
        }
        if(isset($schedule->booking))
        {
            return response()->json([
                'status' => false,
                'message' => 'No Completed Bookings',
                'data' => []
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Completed Bookings',
                'data' => $databooking
            ], 201); 
        }
    }

// Function to get market place details by itd id

    public function marketDetails(Request $request)
    {
        $booking = Marketp::where('id', $request->market_id)->get();
        foreach($booking as $book)
        {
            $client = Client::where('id',$book->uid)->get();
            $book->client = $client;
            $book->category = json_decode($book->category);
            $book->item_image = json_decode($book->item_image);
        }

        if(!$booking)
        {
            return response()->json([
                'status' => false,
                'message'=>"Not Found",
                'data' => []
            ],404);
        }
        else 
        {
            return response()->json([
                'status' => true,
                'message' => 'Details',
                'data' => $booking
            ], 201); 
        }
    }

// Departure Manager List Show

    public function departureManager()
    {
        $this->checkToken();
        $agent = Shipment::where('shipment_id',$this->shipmentId)->where('roles',3)->get();
        if($agent->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No Departure Manager',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Departure Manager',
                'data' => $agent
            ], 201); 
        }
        
    }

// Arrival Manager List Show

    public function arrivalManager()
    {
        $this->checkToken();
        $agent = Shipment::where('shipment_id',$this->shipmentId)->where('roles',4)->get();
        if($agent->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No Arrival Manager',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Arrival Manager',
                'data' => $agent
            ], 201); 
        }

    }

// Departure Warehouse Manager Dashboard Api
    
    public function departureDashboard()
    {
        $this->checkToken();
        $data = Schedule::where('departure_warehouse',$this->shipmentId)->orderBy('created_at','desc')->get();
        $total = [];
        foreach($data as $key=>$row)
        {
            // $row->item_type = json_decode($row->item_type);
            $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
                ->select('*')
                ->where('schedule_id',$row->id)
                ->get();
                
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_number;
                    $sf = $sitem->item_id;
                    $pf = $sitem->available;
                    $icon = $sitem->icon;
                    $price = ['category' => $sf,'available' => $pf,'icon' => $icon];
                    array_push($total,$price);
                }
            $row->available = $total;
            foreach($total as $pop)
            {
                array_pop($total);
            }
            $row->total_container = $total_container;
            $row->available_container = $available_container;
        }
        foreach($data as $value)
        {
            $value->bookings = Booking::where('schedule_id',$value->id)->where('accepted','=',1)->get();
            foreach($value->bookings as $res)
            {
                $res->pickup_review = json_decode($res->pickup_review);
                $res->pickup_itemimage = json_decode($res->pickup_itemimage);
                $res->pickup_itemimage1 = json_decode($res->pickup_itemimage1);
                $res->departure_image = json_decode($res->departure_image);
                $res->arrival_image = json_decode($res->arrival_image);
                $res->receptionist_info = json_decode($res->receptionist_info);
                $res->booking = BookingItem::where('booking_id',$res->id)->get();
                foreach($res->booking as $row)
                {
                    $row->item_name = json_decode($row->item_name);
                    $row->item_image = json_decode($row->item_image);
                }
            }
            $value->item_type = json_decode($value->item_type);
        }
        
        if($data->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No Schedules Yet',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Schedules',
                'data' => $data
            ], 201); 
        }
    }

// Deparure manager assign Pickup agent to bookings

    public function assignAgent(Request $request)
    {
        $this->checkToken();
        $data = Booking::where('id',$request->booking_id)->first();
        if($data->pickupagent_id == NULL)
        {
            Booking::where('id',$request->booking_id)->update(['pickupagent_id' => $request->pickupagent_id,'status' => 'assigned to agent']);

            $book = Booking::where('id', $request->booking_id)->first();
            $ship = Schedule::where('id', $book->schedule_id)->first();
            $notification = new Notification();
            $notification->msg = "Pickup Agent has assigned to ". $request->booking_id ." booking ";
            $notification->title = "Notification !!";
            $notification->sid = $ship->sid;
            $notification->booking_id = $request->booking_id;
            $notification->save();

            $notification = new Notification();
            $notification->msg = "Pickup Agent has assigned to your booking ";
            $notification->title = "Notification !!";
            $notification->uid = $book->uid;
            $notification->booking_id = $request->booking_id;
            $notification->save();

            $notification = new Notification();
            $notification->msg = "You Have Assigned New booking ";
            $notification->title = "Notification !!";
            $notification->sid = $book->pickupagent_id;
            $notification->booking_id = $request->booking_id;
            $notification->save();
            
        
            return response()->json([
                'status' => true,
                'message' => 'Pickup Agent Assigned Successfully',
                'data' => []
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Pickup Agent Already Assigned',
                'data' => []
            ], 201); 
        }

    }

// Pickup Agent Accept & Reject Booking 

    public function pickupAccept(Request $request)
    {
        $this->checkToken();
        if($request->status == 1)
        {
        $booking = Booking::where('id',$request->booking_id)->update(['pickupagent_id' => $this->shipmentId,'pickup_accept' => 1]);
        }
        else if($request->status == 0)
        {
        $booking = Booking::where('id',$request->booking_id)->update(['pickup_accept' => '', 'status'=> 'Accepted' ,'pickupagent_id' => 0,'pickup_reason' => $request->reason]);
        }
        $book = Booking::where('id',$request->booking_id)->first();
        $ship = Schedule::where('id',$book->schedule_id)->first();
        if(!$book)
        {
            return response()->json([
                'status' => false,
                'message' => 'Failed'
            ], 201); 
        }
        else if($book->pickupagent_id != 0)
        {
            $notification = new Notification();
            $notification->msg = "Pickup Agent has assigned to your booking ";
            $notification->title = "Notification !!";
            $notification->uid = $book->uid;
            $notification->booking_id = $request->booking_id;
            $notification->save();
            
            return response()->json([
                'status' => true,
                'message' => 'Booking Accepted',
                'data' => []
            ], 201); 
        }
        else
        {
            $notification = new Notification();
            $notification->msg = "Pickup Agent has Rejected your booking ";
            $notification->title = "Notification !!";
            $notification->sid = $ship->departure_warehouse;
            $notification->booking_id = $request->booking_id;
            $notification->save();

            return response()->json([
                'status' => true,
                'message' => 'Booking Rejected',
                'data' => []
            ], 201);
        }
    }

// Pickup Agent Accept & Reject Market Bookings 

    public function pickupAcceptMarket(Request $request)
    {
        $this->checkToken();
        if($request->status == 1)
        {
        $booking = MarketBooking::where('mid',$request->market_id)->update(['pickupagent_id' => $this->shipmentId,'pickup_accept' => 1]);
        }
        else if($request->status == 0)
        {
        $booking = MarketBooking::where('mid',$request->market_id)->update(['pickup_accept' => '' ,'pickupagent_id' => 0,'pickup_reason' => $request->reason]);
        }
        $book = MarketBooking::where('mid',$request->market_id)->first();
        // $ship = Schedule::where('id',$book->schedule_id)->first();
        if(!$book)
        {
            return response()->json([
                'status' => false,
                'message' => 'Failed'
            ], 201); 
        }
        else if($book->pickupagent_id != 0)
        {
            $notification = new Notification();
            $notification->msg = "Pickup Agent has assigned to your Market Place booking ";
            $notification->title = "Notification !!";
            $notification->uid = $book->uid;
            $notification->market_id = $request->market_id;
            $notification->save();
            
            return response()->json([
                'status' => true,
                'message' => 'Booking Accepted',
                'data' => []
            ], 201); 
        }
        else
        {
            $notification = new Notification();
            $notification->msg = "Pickup Agent has Rejected your booking ";
            $notification->title = "Notification !!";
            $notification->sid = $book->departure_id;
            $notification->market_id = $request->market_id;
            $notification->save();

            return response()->json([
                'status' => true,
                'message' => 'Booking Rejected',
                'data' => []
            ], 201);
        }
    }

// Pickup Agent Dashboard Api

    public function pickupDashboard()
    {
        $this->checkToken();
        $data = Booking::where('pickupagent_id', $this->shipmentId)->orderBy('created_at','desc')->get();
        foreach($data as $res)
        {
            $res->pickup_review = json_decode($res->pickup_review);
            $res->pickupagent_id = (int)$res->pickupagent_id;
            $res->pickup_itemimage = json_decode($res->pickup_itemimage);
            $res->pickup_itemimage1 = json_decode($res->pickup_itemimage1);
            $res->receptionist_info = json_decode($res->receptionist_info);
            $res->booking = BookingItem::where('booking_id',$res->id)->get();
            foreach($res->booking as $row)
            {
                $row->item_name = json_decode($row->item_name);
                $row->item_image = json_decode($row->item_image);
            }
            $shipment = Schedule::where('id',$res->schedule_id)->first();
            if(isset($shipment))
            {
                $res->shipmentId = $shipment->sid;
                $res->arrival = $shipment->destination_warehouse;
                $res->departure = $shipment->departure_warehouse;
            }

        //     $manager = Shipment::where('id',$this->shipmentId)->first();
        //     $book->departure = Shipment::where('id',$manager->departure_warehouse)->get();
        //     $book->arrival = Shipment::where('id',$manager->destination_warehouse)->get();
        //     $book->pickup = Shipment::where('id',$book->pickupagent_id)->get();
            $res->client = Client::where('id',$res->uid)->where('roles','=','1c')->get();
        }
        if($data->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No Assigned Bookings',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Assigned Bookings',
                'data' => $data
            ], 201); 
        }

    }

// Function getting all bookings on Departure Warehouse Manager Dashboard schedules with status accepted

    public function departureOrders()
    {
        $this->checkToken();
        $databooking=array();
        $data = Shipment::where('id', $this->shipmentId)->first();
        $schedule = Schedule::where('sid',$data->shipment_id)->where('departure_warehouse', $this->shipmentId)->orderBy('created_at','desc')->get();
        foreach($schedule as $value)
        {
            $value->item_type = json_decode($value->item_type);
            $booking = Booking::where('schedule_id',$value->id)->where('accepted','=',1)->orderBy('created_at','desc')->get();
            foreach($booking as $book)
            {
                $databooking[]=$book;
                $book->pickupagent_id = (int)$book->pickupagent_id;
                $book->schedule = Schedule::where('id',$book->schedule_id)->get();
                $book->pickup_review = json_decode($book->pickup_review);
                $book->receptionist_info = json_decode($book->receptionist_info);
                $book->pickup_itemimage = json_decode($book->pickup_itemimage);
                $book->pickup_itemimage1 = json_decode($book->pickup_itemimage1);
                $book->departure_image = json_decode($book->departure_image);
                $book->arrival_image = json_decode($book->arrival_image);
                $book->receptionist_image = json_decode($book->receptionist_image);
                $book->booking_item =  DB::table('booking_items')->select('*')->where('booking_id','=',$book->id)->get();
                foreach($book->booking_item as $row)
                {
                    $row->item_image = json_decode($row->item_image);
                }
                $book->client = Client::where('id',$book->uid)->first();
            }
        }
        if(isset($schedule->booking))
        {
            return response()->json([
                'status' => false,
                'message' => 'No Accepted Bookings',
                'data' => []
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Accepted Bookings',
                'data' => $databooking
            ], 201); 
        }
    }

// Function getting all bookings on Departure Warehouse Manager Dashboard schedules with status accepted

    public function arrivalOrders()
    {
        $this->checkToken();
        $databooking=array();
        $data = Shipment::where('id', $this->shipmentId)->first();
        $schedule = Schedule::where('sid',$data->shipment_id)->where('destination_warehouse', $this->shipmentId)->orderBy('created_at','desc')->get();
        foreach($schedule as $value)
        {
            $value->item_type = json_decode($value->item_type);
            $booking = Booking::where('schedule_id',$value->id)->where('accepted','=',1)->orderBy('created_at','desc')->get();
            foreach($booking as $book)
            {
                $databooking[]=$book;
                $book->pickupagent_id = (int)$book->pickupagent_id;
                $book->schedule = Schedule::where('id',$book->schedule_id)->get();
                $book->pickup_review = json_decode($book->pickup_review);
                $book->receptionist_info = json_decode($book->receptionist_info);
                $book->pickup_itemimage = json_decode($book->pickup_itemimage);
                $book->pickup_itemimage1 = json_decode($book->pickup_itemimage1);
                $book->departure_image = json_decode($book->departure_image);
                $book->arrival_image = json_decode($book->arrival_image);
                $book->receptionist_image = json_decode($book->receptionist_image);
                $book->booking_item =  DB::table('booking_items')->select('*')->where('booking_id','=',$book->id)->get();
                $book->client = Client::where('id',$book->uid)->first();
            }
        }
        if(isset($schedule->booking))
        {
            return response()->json([
                'status' => false,
                'message' => 'No Accepted Bookings',
                'data' => []
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Accepted Bookings',
                'data' => $databooking
            ], 201); 
        }
    }

// Function for getting all employees of shipment Company

    public function shipmentEmployees()
    {
        $this->checkToken();
        $data = Shipment::where('shipment_id','=',$this->shipmentId)->get();
        if($data->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No Employees Yet',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Employees',
                'data' => $data
            ], 201); 
        }
    }

// Function For Creating Items With category

    public function addItems(Request $request)
    {
        $this->checkToken();
        $validator = Validator::make($request->all(), [
            "item_name" => "required",
            "category" => "required",
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
            // $name = $request->input('item_name');
            $name = json_decode($request->item_name);
            $category = $request->input('category');
            $sid = $this->shipmentId;
            $items = array();
            foreach($name as $key=>$item)
            {
                $obj = new \stdClass();
                $obj = $item;
        
                array_push($items, $obj);
            }
            $cat =  json_encode($items);
            DB::insert('insert into item_master (item_name,category,sid) values(?, ?, ?)',[$cat, $category, $sid]);

            return response()->json([
                'status' => true,
                'message' => 'Item Successfully Saved',
                'data' => []
            ], 201);    
        }
    }

// Function to Remove items 

    public function removeItems(Request $request)
    {
        $this->checkToken();
        DB::table('item_master')->where('id', $request->id)->where('sid',$this->shipmentId)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Item Successfully Deleted',
            'data' => []
        ], 201);
    }

// Function to Edit Items

    public function editItems(Request $request)
    {
        $this->checkToken();
        $data = DB::table('item_master')->where('id', $request->category_id)->where('sid',$this->shipmentId)->update(['item_name' => '', 'category' => '']);
        $items = array();
        $name = json_decode($request->item_name);
        foreach($name as $key=>$item)
        {
            $obj = new \stdClass();
            $obj = $item;
    
            array_push($items, $obj);
        }
        $cat =  json_encode($items);
        DB::table('item_master')->where('id', $request->category_id)->where('sid',$this->shipmentId)->update(['item_name' => $cat,'category' => $request->input('category') ]);

        return response()->json([
            'status' => true,
            'message' => 'Item Successfully Edited',
            'data' => []
        ], 201);  

        // foreach($data as $row)
        // {
        //     $items = $row->item_name;
        //     $items = json_decode($items);
            // unset($items[1]);
            // return json_decode($items);
            //delete element in array by value 
            // if (($key = array_search($request->item_name, $items)) !== false) {
            //     unset($items[$key]);
            //     DB::table('item_master')->where('id', $request->category_id)->where('sid',$this->shipmentId)->update(['item_name' => $items]);
            //     return response()->json([
            //         'status' => true,
            //         'message' => 'Item Successfully Removed',
            //         'data' => []
            //     ], 201);
            // }
            // else if (($key = array_search($request->item_name, $items)) !== true)
            // {
            //     array_push($items, $request->item_name);
            //     DB::table('item_master')->where('id', $request->category_id)->where('sid',$this->shipmentId)->update(['item_name' => $items]);
            //     return response()->json([
            //         'status' => true,
            //         'message' => 'Item Successfully Added',
            //         'data' => []
            //     ], 201);
            // }
        // }
        
    }

// Function for Creating Warehouse by Shipment Company

    public function createWarehouse(Request $request)
    {
        $this->checkToken();
        $validator = Validator::make($request->all(), [
            "warehouse" => "required",
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
            $warehouse = new Warehouse();
            $warehouse->warehouse = $request->warehouse;
            $warehouse->sid = $this->shipmentId;
            $warehouse->save();

            return response()->json([
                'status' => true,
                'message' => 'Successfully Saved Warehouse',
                'data' => []
            ], 201);
        }

    }

// Function For View All Warehouse of Shipment Company by Itself

    public function viewWarehouse()
    {
        $this->checkToken();
        $data = Warehouse::where('sid', $this->shipmentId)->get();
        if($data->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No Warehouse yet',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Warehouses',
                'data' => $data
            ], 201); 
        }
    }

// Function to assign manager to Warehouse By Shipment company

    public function assignManager(Request $request)
    {
        $this->checkToken();
        $data = Warehouse::where('id',$request->warehouse_id)->where('sid',$this->shipmentId)->first();
        if($data->manager_id == 0)
        {
            Warehouse::where('id',$request->warehouse_id)->update(['manager_id' => $request->manager_id]);
            return response()->json([
                'status' => true,
                'message' => 'Manager Assigned Successfully',
                'data' => []
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Manager Already Assigned',
                'data' => []
            ], 201); 
        }

    }

// Function to Get Shipment Stats in Dashboard

    public function shipmentStats()
    {
        $this->checkToken();
       // return $this->shipmentId;
        $schedules = DB::table('schedules')->where('sid',$this->shipmentId)->count();
        $shipment = Schedule::where('sid',$this->shipmentId)->get();
        $client = 0;
        $arr = array();
        foreach($shipment as $row)
        {
            $result = DB::table('bookings')->where('schedule_id',$row->id)->groupBy('schedule_id')->distinct('uid')->count();
            array_push($arr,$result);
        }
        $client += array_sum($arr);
        
        $closed = DB::table('schedules')->where('sid',$this->shipmentId)->where('status','=','closed')->count();
        $open = DB::table('schedules')->where('sid',$this->shipmentId)->where('status','=','Open')->count();
        $inProgress = DB::table('schedules')->where('sid',$this->shipmentId)->where('status','=','InProgress')->count();
        $packed = DB::table('schedules')->where('sid',$this->shipmentId)->where('status','=','Packed')->count();


        $data = ['total_schedules' => $schedules,'closed_schedules' => $closed,'packed_schedules' => $packed,'open_schedules' => $open, 'inprogress_schedules' => $inProgress,'total_client' => $client];

        // $data = ['total_schedules' => $schedules,'closed_schedules' => $closed,'open_schedules' => $open, 'completed_schedules' => $completed,'total_client' => $client];

        if($schedules == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Scheuled Shipment',
                'data' => []
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Statistics',
                'data' => [$data]
            ], 201); 
        }
        
    }

// Function to Get Pickup Agent Stats in Dashboard

    public function pickupStats()
    {
        $this->checkToken();
        $pickup_orders = Booking::where('pickupagent_id', $this->shipmentId)->count();
        $pending_orders = Booking::where('pickupagent_id', $this->shipmentId)->where('status','=','going to pickup')->count();
        $progress_orders = Booking::where('pickupagent_id', $this->shipmentId)->where('status','=','pickup done')->count();
        $completed_orders = Booking::where('pickupagent_id', $this->shipmentId)->where('status','=','delivered to warehouse')->count();
        $assign_orders = Booking::where('pickupagent_id', $this->shipmentId)->where('status','=','assigned to agent')->count();


        $data = ['pickup_orders' => $pickup_orders,'going_to_pickup' => $pending_orders,'pickup_done' => $progress_orders,'assign_orders' => $assign_orders, 'completed_orders' => $completed_orders];

        $data1 = ['pickup_orders' => 0,'going_to_pickup' => 0,'pickup_done' => 0, 'completed_orders' => 0, 'assign_orders' => 0];

        if($pickup_orders == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Bookings',
                'data' => [$data1]
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Statistics',
                'data' => [$data]
            ], 201); 
        }
        
    }

// Function to Get Departure Dashboard Stats in Dashboard

    public function departureStats()
    {
        $this->checkToken();
        $schedules = DB::table('schedules')->where('departure_warehouse',$this->shipmentId)->count();
        $shipment = Schedule::where('departure_warehouse',$this->shipmentId)->get();
        $client = 0;
        $arr = array();
        foreach($shipment as $row)
        {
            $result = DB::table('bookings')->where('schedule_id',$row->id)->groupBy('schedule_id')->distinct('uid')->count();
            array_push($arr,$result);
        }
        $client += array_sum($arr);
        
        $closed = DB::table('schedules')->where('departure_warehouse',$this->shipmentId)->where('status','=','closed')->count();
        $open = DB::table('schedules')->where('departure_warehouse',$this->shipmentId)->where('status','=','Open')->count();
        $inprogress = DB::table('schedules')->where('departure_warehouse',$this->shipmentId)->where('status','=','inProgress')->count();
        $packed = DB::table('schedules')->where('departure_warehouse',$this->shipmentId)->where('status','=','Packed')->count();

        $data = ['total_schedules' => $schedules,'closed_schedules' => $closed,'open_schedules' => $open, 'inprogress_schedules' => $inprogress,'packed_schedules' => $packed,'total_client' => $client];

        if($schedules == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Scheuled Shipment',
                'data' => []
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Statistics',
                'data' => [$data]
            ], 201); 
        }
    }

// Function to Get Arrival Dashboard Stats in Dashboard

    public function arrivalStats()
    {
        $this->checkToken();
        $schedules = DB::table('schedules')->where('destination_warehouse',$this->shipmentId)->count();
        $shipment = Schedule::where('destination_warehouse',$this->shipmentId)->get();
        $client = 0;
        $arr = array();
        foreach($shipment as $row)
        {
            $result = DB::table('bookings')->where('schedule_id',$row->id)->groupBy('schedule_id')->distinct('uid')->count();
            array_push($arr,$result);
        }
        $client += array_sum($arr);
        
        $closed = DB::table('schedules')->where('destination_warehouse',$this->shipmentId)->where('status','=','closed')->count();
        $open = DB::table('schedules')->where('destination_warehouse',$this->shipmentId)->where('status','=','Open')->count();
        $inProgress = DB::table('schedules')->where('destination_warehouse',$this->shipmentId)->where('status','=','InProgress')->count();
        $packed = DB::table('schedules')->where('destination_warehouse',$this->shipmentId)->where('status','=','Packed')->count();


        $data = ['total_schedules' => $schedules,'closed_schedules' => $closed,'packed_schedules' => $packed,'open_schedules' => $open, 'inprogress_schedules' => $inProgress,'total_client' => $client];

        if($schedules == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Scheuled Shipment',
                'data' => []
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Statistics',
                'data' => [$data]
            ], 201); 
        }
    }
    
// Function to Change status by shipment Company & Employees (Bookings)

    public function changeStatus(Request $request)
    {
        $this->checkToken();
        $data = Shipment::find($this->shipmentId);
        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'You are Not authorized',
                'data' => []
            ]);
        }
        else if($data->roles == 5)
        {
           // Booking::where('id',$request->booking_id)->update(['status' => $request->booking_status,'pickup_itemimage' => $request->pickup_itemimage,'pickup_comment' => $request->comment]);
            
            if($request->booking_status == 'going to pickup')
            {
                Booking::where('id',$request->booking_id)->update(['status' => $request->booking_status,'pickup_comment' => $request->comment]);
                $book = Booking::where('id',$request->booking_id)->first();
                $ship = Schedule::where('id', $book->schedule_id)->first();
                $notification = new Notification();
                $notification->msg = "going to pickup your ". $request->booking_id ." booking ";
                $notification->title = "Notification !!";
                $notification->uid = $book->uid;
                $notification->booking_id = $request->booking_id;
                $notification->save();

                $notification = new Notification();
                $notification->msg = "going to pickup ". $request->booking_id ." booking ";
                $notification->title = "Notification !!";
                $notification->sid = $ship->sid;
                $notification->booking_id = $request->booking_id;
                $notification->save();
            }
            else if($request->booking_status == 'pickup done')
            {
                Booking::where('id',$request->booking_id)->update(['status' => $request->booking_status,'pickup_itemimage' => $request->pickup_itemimage,'pickup_comment' => $request->comment]);
                $book = Booking::where('id',$request->booking_id)->first();
                $ship = Schedule::where('id', $book->schedule_id)->first();
                $notification = new Notification();
                $notification->msg = "Pickup done to your ". $request->booking_id ." booking ";
                $notification->title = "Notification !!";
                $notification->uid = $book->uid;
                $notification->booking_id = $request->booking_id;
                $notification->save();

                $notification = new Notification();
                $notification->msg = "Pickup done to ". $request->booking_id ." booking ";
                $notification->title = "Notification !!";
                $notification->sid = $ship->sid;
                $notification->booking_id = $request->booking_id;
                $notification->save();
            }
            else if($request->booking_status == 'delivered to Warehouse')
            {
                Booking::where('id',$request->booking_id)->update(['status' => $request->booking_status,'pickup_itemimage1' => $request->pickup_itemimage,'pickup_comment1' => $request->comment]);
                $book = Booking::where('id',$request->booking_id)->first();
                $ship = Schedule::where('id', $book->schedule_id)->first();
                $notification = new Notification();
                $notification->msg = "Your Item With Booking id ". $request->booking_id ." Delivered to Warehouse ";
                $notification->title = "Notification !!";
                $notification->uid = $book->uid;
                $notification->booking_id = $request->booking_id;
                $notification->save();

                $notification = new Notification();
                $notification->msg = "Item With Booking id ". $request->booking_id  ." Delivered to Warehouse";
                $notification->title = "Notification !!";
                $notification->sid = $ship->sid;
                $notification->booking_id = $request->booking_id;
                $notification->save();

                $notification = new Notification();
                $notification->msg = "Item With Booking id ". $request->booking_id  ." Delivered to Warehouse";
                $notification->title = "Notification !!";
                $notification->sid = $ship->departure_warehouse;
                $notification->booking_id = $request->booking_id;
                $notification->save();
            }
            $data = Booking::where('id',$request->booking_id)->select('status','pickup_itemimage','pickup_comment')->get();
            foreach($data as $row)
            {
                $row->pickup_itemimage = json_decode($row->pickup_itemimage);
                $row->pickup_itemimage1 = json_decode($row->pickup_itemimage1);
            }
            return response()->json([
                'status' => true,
                'message' => 'Status Changed',
                'data' => $data
            ], 201); 
        }
        else if($data->roles == 3)
        {
            $book = Booking::where('id',$request->booking_id)->first();
            $schedule = Schedule::where('id',$book->schedule_id)->first();
            if($request->booking_status == '')
            {
                Schedule::where('id',$schedule->id)->update(['status' => $request->schedule_status]);
            }
            else if($request->schedule_status == '')
            {
                Booking::where('id',$request->booking_id)->update(['status' => $request->booking_status,'departure_image' => $request->pickup_itemimage,'departure_comment' => $request->comment]);
            }
            else
            {
                Booking::where('id',$request->booking_id)->update(['status' => $request->booking_status,'departure_image' => $request->pickup_itemimage,'departure_comment' => $request->comment]);
                Schedule::where('id',$schedule->id)->update(['status' => $request->schedule_status]);
            }
            $data = Booking::where('id',$request->booking_id)->select('status','departure_image','departure_comment')->get();
            foreach($data as $row)
            {
                $row->departure_image = json_decode($row->departure_image);
                $row->pickup_itemimage = json_decode($row->pickup_itemimage);
                $row->pickup_itemimage1 = json_decode($row->pickup_itemimage1);
            }
            $arr = ['booking' => $data, 'schedule' => Schedule::where('id',$schedule->id)->select('status')->first()];
            return response()->json([
                'status' => true,
                'message' => 'Status Changed',
                'data' => [$arr]
            ], 201); 
        }
        else if($data->roles == 4)
        {
            $book = Booking::where('id',$request->booking_id)->first();
            $schedule = Schedule::where('id',$book->schedule_id)->first();
            if($request->booking_status == '')
            {
                Schedule::where('id',$schedule->id)->update(['status' => $request->schedule_status]);
            }
            else if($request->schedule_status == '')
            {
                Booking::where('id',$request->booking_id)->update(['status' => $request->booking_status,'arrival_image' => $request->pickup_itemimage,'arrival_comment' => $request->comment]);
            }
            else
            {
                Booking::where('id',$request->booking_id)->update(['status' => $request->booking_status,'arrival_image' => $request->pickup_itemimage,'arrival_comment' => $request->comment]);
                Schedule::where('id',$schedule->id)->update(['status' => $request->schedule_status]);
            }
            $data = Booking::where('id',$request->booking_id)->select('status','arrival_image','arrival_comment')->get();
            foreach($data as $row)
            {
                $row->departure_image = json_decode($row->departure_image);
                $row->arrival_image = json_decode($row->arrival_image);
                $row->pickup_itemimage = json_decode($row->pickup_itemimage);
                $row->pickup_itemimage1 = json_decode($row->pickup_itemimage1);
            }
            $arr = ['booking' => $data, 'schedule' => Schedule::where('id',$schedule->id)->select('status')->first()];
            return response()->json([
                'status' => true,
                'message' => 'Status Changed',
                'data' => [$arr]
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Unexpected Error',
                'data' => []
            ], 201); 
        }

    }

// Function to get arrival dashboard manager Dashboard

    public function arrivalDashboard()
    {
        $this->checkToken();
        $data = Schedule::where('destination_warehouse',$this->shipmentId)->orderBy('created_at','desc')->get();
        $total = [];
        foreach($data as $key=>$row)
        {
            $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
                ->select('*')
                ->where('schedule_id',$row->id)
                ->get();
                
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_number;
                    $sf = $sitem->item_id;
                    $pf = $sitem->available;
                    $icon = $sitem->icon;
                    $price = ['category' => $sf,'available' => $pf,'icon' => $icon];
                    array_push($total,$price);
                }
            $row->available = $total;
            foreach($total as $pop)
            {
                array_pop($total);
            }
            $row->total_container = $total_container;
            $row->available_container = $available_container;
        }
        foreach($data as $value)
        {
            $value->bookings = Booking::where('schedule_id',$value->id)->where('accepted','=',1)->get();
            foreach($value->bookings as $res)
            {
                $res->pickup_review = json_decode($res->pickup_review);
                $res->pickup_itemimage = json_decode($res->pickup_itemimage);
                $res->pickup_itemimage1 = json_decode($res->pickup_itemimage1);
                $res->departure_image = json_decode($res->departure_image);
                $res->arrival_image = json_decode($res->arrival_image);
                $res->receptionist_info = json_decode($res->receptionist_info);
                $res->booking = BookingItem::where('booking_id',$res->id)->get();
                foreach($res->booking as $row)
                {
                    $row->item_name = json_decode($row->item_name);
                    $row->item_image = json_decode($row->item_image);
                }
            }
            $value->item_type = json_decode($value->item_type);
        }
        
        if($data->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No Schedules Yet',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Schedules',
                'data' => $data
            ], 201); 
        }
    }

// Function to get Clients by Searching client name in Shipment Panel

    public function clientnameSearch(Request $request)
    {
        $this->checkToken();
       // return $this->shipmentId;
        $name = $request->name;

        if($name == '')
        {
            return response()->json([
                'status' => false,
                'message' => "NO Result Found",
                'data' => []
            ]);
        }
        else
        {
            $c = Client::find($this->shipmentId);

            if(isset($c))
            {
                $sData = Shipment::where('id',$this->shipmentId)->select('roles')->first(); 
                // print_r($sData);
                $data = Client::where('name','LIKE','%'.$name.'%')->get();
                $data1 = Shipment::where('name','LIKE','%'.$name.'%')->orWhere('companyname','LIKE','%'.$name.'%')->where('roles','!=',$sData->roles)->get();
                $arr = ['client_results' => $data , 'shipment_result' => $data1];
                if(count($data) > 0 || count($data1) > 0)
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Matched Results.',
                        'data' => [$arr]
                    ]);
                }

                else{
                    return response()->json([
                        'status' => false,
                        'message' => 'No Result Found.',
                        'data' => []
                    ]);
                }
            }
            else
            {
                //$cData = Client::where('id',$this->shipmentId)->select('roles')->first(); 
                $data = Client::where('name','LIKE','%'.$name.'%')->get();
                $data1 = Shipment::where('name','LIKE','%'.$name.'%')->orWhere('companyname','LIKE','%'.$name.'%')->get();
                $arr = ['client_results' => $data , 'shipment_result' => $data1];
                if(count($data) > 0 || count($data1) > 0)
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Matched Results.',
                        'data' => [$arr]
                    ]);
                }

                else{
                    return response()->json([
                        'status' => false,
                        'message' => 'No Result Found.',
                        'data' => []
                    ]);
                }
            }
            
        }
    }

// Function for deactivating Receptionist profile

    public function deactivateProfileEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "employee_id" => "required",
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
            if($request->status == 0)
            {
                $client = Shipment::where('id', $request->employee_id)->update(['status' => 'Deactivate'  ]);
                if(is_null($client)){
                return response([
                    'status' => false,
                    'message' => 'User Not Found',
                    'data' => []
                    ],404);
                }
                else {
                    
                    return response()->json([
                        'status' => true,
                        'message' => 'Profile Deactivated',
                        'data' => []
                    ] ,201);
                }
            }
            else
            {
                $client = Shipment::where('id', $request->employee_id)->update(['status' => ''  ]);
                if(is_null($client)){
                return response([
                    'status' => false,
                    'message' => 'User Not Found',
                    'data' => []
                    ],404);
                }
                else {
                    
                    return response()->json([
                        'status' => true,
                        'message' => 'Profile Activated',
                        'data' => []
                    ] ,201);
                }
            }
        }

    }

// Function For Login All

    public function loginAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email',
            // 'roles' => 'required'
            // 'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        // $roles = $request->input('roles');

        //ie();

        $cliente = Client::where('email',$request->email)->get();
        $clientu = Client::where('username',$request->email)->get();
        // return $clientu;

        if ($validator->fails()) 
        {  
            echo $username = $request->email;
            $shipment = Shipment::where('username', '=', $username)->where('password',$password)->first();
            if (!$shipment) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, please check Username' ,'data' => []]);
            }
            if (!($password == $shipment->password)) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, Wrong password. Try again .','data' => []]);
            }
            if($shipment->status == "Deactivate") {
                return response()->json(['status'=>false, 'message' => 'Login Fail, Your Account is Deactivated','data' => []]);
            }
            if($shipment->roles == 1){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Shipment Company', 'data' => [$shipment] ]);
            }
            if($shipment->roles == 2){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Accountant', 'data' => [$shipment] ]);
            }
            if($shipment->roles == 3){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Departure Warehouse Manager', 'data' => [$shipment] ]);
            }
            if($shipment->roles == 4){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Arrival Warehouse Manager', 'data' => [$shipment] ]);
            }
            if($shipment->roles == 5){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Pickup Agent', 'data' => [$shipment] ]);
            }       
            $response = [
                'status' => false,
                'message' => 'Not an Employee',
                'data' => []
            ];
            return response()->json($response, 200);

        }
        else if(count($cliente) == 0 && count($clientu) == 0)
        {
            //return 'hierrr';
            $shipment = Shipment::where('email', '=', $email)->first();
            if (!$shipment) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, please check Email' ,'data' => []]);
            }
            if (!($password == $shipment->password)) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, Wrong password. Try again ','data' => []]);
            }
            if($shipment->status == "Deactivate") {
                return response()->json(['status'=>false, 'message' => 'Login Fail, Your Account is Deactivated','data' => []]);
            }
            if($shipment->roles == 1){

                $cdate = strtotime(date('Y-m-d'));
                $edate = strtotime($shipment->expire_date);
                if($cdate > $edate){
                        return response()->json(['status'=>false, 'message' => 'Login Fail, Your Subscription plan expire','data' => [$shipment]]);
                }else{
                        $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Shipment Company', 'data' => [$shipment] ]);
                }
                
            }
            if($shipment->roles == 2){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Accountant', 'data' => [$shipment] ]);
            }
            if($shipment->roles == 3){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Departure Warehouse Manager', 'data' => [$shipment] ]);
            }
            if($shipment->roles == 4){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Arrival Warehouse Manager', 'data' => [$shipment] ]);
            }
            if($shipment->roles == 5){
                $token = $shipment->createToken('my-app-token')->accessToken;
                $shipment->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
                return response()->json(['status'=>true,'message'=>'Login Successful Pickup Agent', 'data' => [$shipment] ]);
            }       
            $response = [
                'status' => false,
                'message' => 'Not an Employee',
                'data' => []
            ];
            return response()->json($response, 200);
        }
        else if(count($cliente) == 0 )
        {
            $username = $request->email;
            $client = Client::where('username', '=', $username)->first();
            if (!$client) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, please check Username' ,'data' => []]);
            }
            if (!($password == $client->password)) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, Wrong password. Try again or click ‘Forgot password’ to reset it.','data' => []]);
            }
            if($client->status == "Deactivate") {
                return response()->json(['status'=>false, 'message' => 'Login Fail, Your Account is Deactivated','data' => []]);
            }
            if($client->roles == '1c'){
                $token = $client->createToken('my-app-token')->accessToken;
                $client->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
               return response()->json(['status'=>true,'message'=>'Login Successful Client', 'data' => [$client] ]);
            }
            if($client->roles == '2r'){
                $token = $client->createToken('my-app-token')->accessToken;
                $client->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
               return response()->json(['status'=>true,'message'=>'Login Successful Receptionist', 'data' => [$client] ]);
            }
            $response = [
                'status' => false,
                'message' => 'Not an user',
                'data' => []
            ];
            return response()->json($response, 200);
        }
        else if(count($clientu) == 0)
        {
            $client = Client::where('email', '=', $request->email)->first();
            if (!$client) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, please check Email' ,'data' => []]);
            }
            if (!($password == $client->password)) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, Wrong password. Try again or click ‘Forgot password’ to reset it.','data' => []]);
            }
            if($client->status == "Deactivate") {
                return response()->json(['status'=>false, 'message' => 'Login Fail, Your Account is Deactivated','data' => []]);
            }
            if($client->roles == '1c'){
                $token = $client->createToken('my-app-token')->accessToken;
                $client->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
               return response()->json(['status'=>true,'message'=>'Login Successful Client', 'data' => [$client] ]);
            }
            if($client->roles == '2r'){
                $token = $client->createToken('my-app-token')->accessToken;
                $client->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
               return response()->json(['status'=>true,'message'=>'Login Successful Receptionist', 'data' => [$client] ]);
            }
            $response = [
                'status' => false,
                'message' => 'Not an user',
                'data' => []
            ];
            return response()->json($response, 200);
        }


    }

// Deparure manager assign Pickup agent to bookings

    public function assignAgentMarket(Request $request)
    {
        $this->checkToken();
        $data = MarketBooking::where('mid',$request->market_id)->first();
        // return $request->pickupagent_id;
        if($data->pickupagent_id == 0)
        {
            MarketBooking::where('mid',$request->market_id)->update(['pickupagent_id' => $request->pickupagent_id,'status' => 'assigned to agent']);

            $book = MarketBooking::where('mid', $request->market_id)->first();
            // return $request->market_id;
            // $ship = Schedule::where('id', $book->schedule_id)->first();
            $notification = new Notification();
            $notification->msg = "Pickup Agent has assigned to ". $request->market_id ." booking ";
            $notification->title = "Notification !!";
            $notification->sid = $book->sid;
            $notification->booking_id = $request->market_id;
            $notification->save();

            $notification = new Notification();
            $notification->msg = "Pickup Agent has assigned to your Market Place booking ";
            $notification->title = "Notification !!";
            $notification->uid = $book->uid;
            $notification->booking_id = $request->market_id;
            $notification->save();
        
            return response()->json([
                'status' => true,
                'message' => 'Pickup Agent Assigned Successfully',
                'data' => []
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Pickup Agent Already Assigned',
                'data' => []
            ], 201); 
        }

    }

// Function to Forgot Passowrd for all panels

    public function allForgotPassword(Request $request)
    {

        $users = Shipment::where('email', '=', $request->input('email'))->first();
        // return $users;

        if ($users === null) {
            $users = Client::where('email', '=', $request->input('email'))->first();
            // return $users;
    
            if ($users === null) {
                return response()->json([
                    'status' => false,
                    'message' => 'User Not Registered yet',
                    'data' => []
                ]);
            } 
            else 
            {     
                $email = $request->email;
                $otp = rand(1000,9999);
    
                Client::where('email','=',$request->email)->update(['otp'=>$otp]);
        
                $maildata = [
                    'title' => 'Your OTP For Forget Password is :'. $otp,
                    'url' => 'https://www.positronx.io'
                ];
        
                Mail::to($email)->send(new SendDemoMail($maildata));
        
                return response()->json([
                    'status' => true,
                    'message' => 'Reset password OTP is sent on your email id',
                    'data' => ['otp' => $otp]
                ]);
            }
        } 
        else 
        {     
            $email = $request->email;
            $otp = rand(1000,9999);

            Shipment::where('email','=',$request->email)->update(['otp'=>$otp]);
    
            $maildata = [
                'title' => 'Your OTP For Forget Password is :'. $otp,
                'url' => 'https://www.positronx.io'
            ];
    
            Mail::to($email)->send(new SendDemoMail($maildata));
    
            return response()->json([
                'status' => true,
                'message' => 'Reset password OTP is sent on your email id',
                'data' => ['otp' => $otp]
            ]);
        }
    }

// Function to Change status by shipment Company & Employees (Market Place Bookings)

    public function changeStatusMarket(Request $request)
    {
        $this->checkToken();
        $data = Shipment::find($this->shipmentId);
        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'You are Not authorized',
                'data' => []
            ]);
        }
        else if($data->roles == 5)
        {
            // MarketBooking::where('mid',$request->market_id)->update(['status' => $request->market_status,'pickup_itemimage' => $request->pickup_itemimage]);
            
            if($request->market_status == 'going to pickup')
            {
                MarketBooking::where('mid',$request->market_id)->update(['status' => $request->market_status,'pickup_comment' => $request->comment]);
                $book = MarketBooking::where('mid',$request->market_id)->first();
                $notification = new Notification();
                $notification->msg = "going to pickup your ". $request->market_id ." Market Place booking ";
                $notification->title = "Notification !!";
                $notification->uid = $book->uid;
                $notification->market_id = $request->market_id;
                $notification->save();

                $notification = new Notification();
                $notification->msg = "going to pickup ". $request->market_id ." Market Place booking ";
                $notification->title = "Notification !!";
                $notification->sid = $book->sid;
                $notification->market_id = $request->market_id;
                $notification->save();
            }
            else if($request->market_status == 'pickup done')
            {
                MarketBooking::where('mid',$request->market_id)->update(['status' => $request->market_status,'pickup_itemimage' => $request->pickup_itemimage,'pickup_comment' => $request->comment]);
                $book = MarketBooking::where('mid',$request->market_id)->first();
                $notification = new Notification();
                $notification->msg = "Pickup done to your ". $request->market_id ." Market Place booking ";
                $notification->title = "Notification !!";
                $notification->uid = $book->uid;
                $notification->market_id = $request->market_id;
                $notification->save();

                $notification = new Notification();
                $notification->msg = "Pickup done to ". $request->market_id ." Market Place booking ";
                $notification->title = "Notification !!";
                $notification->sid = $book->sid;
                $notification->market_id = $request->market_id;
                $notification->save();
            }
            else if($request->market_status == 'delivered to Warehouse')
            {
                MarketBooking::where('mid',$request->market_id)->update(['status' => $request->market_status,'pickup_itemimage1' => $request->pickup_itemimage,'pickup_comment1' => $request->comment]);
                $book = MarketBooking::where('mid',$request->market_id)->first();
                $notification = new Notification();
                $notification->msg = "Your Item With Booking id ". $request->market_id ." Delivered to Warehouse ";
                $notification->title = "Notification !!";
                $notification->uid = $book->uid;
                $notification->market_id = $request->market_id;
                $notification->save();

                $notification = new Notification();
                $notification->msg = "Item With Booking id ". $request->market_id  ." Delivered to Warehouse";
                $notification->title = "Notification !!";
                $notification->sid = $book->sid;
                $notification->market_id = $request->market_id;
                $notification->save();

                $notification = new Notification();
                $notification->msg = "Item With Booking id ". $request->market_id  ." Delivered to Warehouse";
                $notification->title = "Notification !!";
                $notification->sid = $book->departure_id;
                $notification->market_id = $request->market_id;
                $notification->save();
            }
            $data = MarketBooking::where('mid',$request->market_id)->select('status','pickup_itemimage','pickup_comment')->get();
            foreach($data as $row)
            {
                $row->pickup_itemimage = json_decode($row->pickup_itemimage);
                $row->pickup_itemimage1 = json_decode($row->pickup_itemimage1);
            }
            return response()->json([
                'status' => true,
                'message' => 'Status Changed',
                'data' => $data
            ], 201); 
        }
        else if($data->roles == 3)
        {
            $book = MarketBooking::where('mid',$request->market_id)->first();
            if($request->market_status == '')
            {
                Schedule::where('id',$schedule->id)->update(['status' => $request->schedule_status]);
            }
            else if($request->schedule_status)
            {
                MarketBooking::where('mid',$request->market_id)->update(['status' => $request->market_status,'departure_image' => $request->pickup_itemimage,'departure_comment' => $request->comment]);
            }
            else
            {
                MarketBooking::where('mid',$request->market_id)->update(['status' => $request->market_status,'departure_image' => $request->pickup_itemimage,'departure_comment' => $request->comment]);
                // Schedule::where('id',$schedule->id)->update(['status' => $request->schedule_status]);
            }
            $data = MarketBooking::where('mid',$request->market_id)->select('status','departure_image','departure_comment')->get();
            foreach($data as $row)
            {
                $row->departure_image = json_decode($row->departure_image);
                $row->arrival_image = json_decode($row->arrival_image);
                $row->pickup_itemimage = json_decode($row->pickup_itemimage);
                $row->pickup_itemimage1 = json_decode($row->pickup_itemimage1);
            }
            $arr = ['Marketbooking' => $data];
            return response()->json([
                'status' => true,
                'message' => 'Status Changed',
                'data' => $data
            ], 201); 
        }
        else if($data->roles == 4)
        {
            $book = MarketBooking::where('mid',$request->market_id)->first();
            $schedule = Schedule::where('id',$book->schedule_id)->first();
            if($request->market_status == '')
            {
                Schedule::where('id',$schedule->id)->update(['status' => $request->schedule_status]);
            }
            else if($request->schedule_status == '')
            {
                MarketBooking::where('mid',$request->market_id)->update(['status' => $request->market_status,'arrival_image' => $request->pickup_itemimage,'arrival_comment' => $request->comment]);
            }
            else
            {
                MarketBooking::where('mid',$request->market_id)->update(['status' => $request->market_status,'arrival_image' => $request->pickup_itemimage,'arrival_comment' => $request->comment]);
                // Schedule::where('id',$schedule->id)->update(['status' => $request->schedule_status]);
            }
            $data = MarketBooking::where('mid',$request->market_id)->select('status','arrival_image','arrival_comment')->get();
            foreach($data as $row)
            {
                $row->departure_image = json_decode($row->departure_image);
                $row->arrival_image = json_decode($row->arrival_image);
                $row->pickup_itemimage = json_decode($row->pickup_itemimage);
                $row->pickup_itemimage1 = json_decode($row->pickup_itemimage1);
            }
            $arr = ['Marketbooking' => Marketbooking::where('id',$request->market_id)->select('status')->first()];
            return response()->json([
                'status' => true,
                'message' => 'Status Changed',
                'data' => $data
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Unexpected Error',
                'data' => []
            ], 201); 
        }

    }

// Function to check Shipment company had connect it's stripe account or not

    public function checkStripe()
    {
        $this->checkToken();
        $data = Shipment::where('id',$this->shipmentId)->first();
        if($data->account_id == NULL)
        {
            return response()->json([
                'status' => false,
                'message' => 'Stripe Account Not Connected',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Stripe Account Connected',
                'data' => []
            ]);
        }
    }




}
        
        