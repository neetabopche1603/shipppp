<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shipment;
use App\Models\Dropoff;
use App\Models\Item;
use App\Models\Schedule;
use App\Models\ScheduleItem;
use App\Models\ScheduleCategory;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\BookingCategory;
use App\Models\Marketp;
use App\Models\Card;
use App\Models\Client;
use App\Models\Coupon;
use App\Models\Notification;
use App\Models\Advertisment;
use App\Models\Proposal;
use App\Models\MarketBooking;
use App\Models\Setting;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use App\Mail\SendDemoMail;
use App\Mail\SendLoginMail;
use App\Mail\SendOtpMail;
use Mail;
use App\Models\Review;

class UserController extends Controller
{

    protected $userId;

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
                $this->userId = $check->tokenable_id;
            }
        }else{
            return response()->json(['success'=>false,'data'=>array(),'message'=>'token blanked'], 401);
            die();
        }
    }


    public function __construct(Request $request)
    {
        // $x = new \stdClass();
        // $headers = getallheaders();
        // if(isset($headers['token']))
        // {
        //     // $check = User::where('api_token',$headers['token'])->first();
        //     $check = DB::table('personal_access_tokens')->where('token',$headers['token'])->select('tokenable_id')->get();
            
        //     if(!isset($check[0]->id))
        //     {
        //         return response()->json(['success'=>false,'data'=>$x,'message'=>'token mis matched'], 401);
        //         die();
        //     }else{
        //         $this->userId = $check[0]->id;
        //     }
        // }else{
        //     return response()->json(['success'=>false,'data'=>array(),'message'=>'token blanked'], 401);
        //     die();
        // }
    }

// Function For Creating Client Api to table with {Role = 1}

    public function registerClient(Request $request)
    {

        // VALIDATION OF Client DETAILS 

        $validator = Validator::make($request->all(), [
            "name" => "required|regex:/^[a-zA-Z]+$/u|max:255",
            // "lname" => "regex:/^[a-zA-Z]+$/u|max:255",
            "email" => 'required|email|unique:clients,email|unique:shipments,email|regex:/^.+@.+$/i', 
            "password" => "required",
            "phone" => "required|min:10|numeric",
            "username" => "required|regex:/^[a-zA-Z0-9-]+$/u|max:255|unique:clients,username|unique:shipments,username"
            // "country" => "required",
            // "address" => "required",
            // "file" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
                $filename = '';
                if($request->hasfile('file'))
                {
                    $image = $request->file('file');
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $filename);   
                    
                    $client = new Client();
                    $client->name = $request->name;
                    $client->lname = $request->lname;
                    $client->email = $request->email;
                    // $client->password = $request->password;
                    $client->password = $request->password;
                    $client->phone = $request->phone;
                    $client->username = $request->username;
                    $client->country_code = $request->country_code;
                    $client->country = $request->country;
                    $client->address = $request->address;
                    $client->status = 'Not Approve';
                    $client->roles = "1c";
                    $client->profileimage = $filename;
                    $client->type = "clients";
                    $client->save();
                }
                else if(!$request->hasfile('file'))
                {
                    $client = new Client();
                    $client->name = $request->name;
                    $client->lname = $request->lname;
                    $client->email = $request->email;
                    $client->password = $request->password;
                    $client->phone = $request->phone;
                    $client->username = $request->username;
                    $client->country_code = $request->country_code;
                    $client->country = $request->country;
                    $client->address = $request->address;
                    $client->status = 'Not Approve';
                    $client->roles = "1c";
                    $client->profileimage = $request->file;
                    $client->type = "clients";
                    $client->save();
                }
                else
                {
                    $client = new Client();
                    $client->name = $request->name;
                    $client->lname = $request->lname;
                    $client->email = $request->email;
                    $client->password = $request->password;
                    $client->phone = $request->phone;
                    $client->username = $request->username;
                    $client->country_code = $request->country_code;
                    $client->country = $request->country;
                    $client->address = $request->address;
                    $client->status = 'Not Approve';
                    $client->roles = "1c";
                    $client->profileimage = '';
                    $client->type = "clients";
                    $client->save();
                }
                
            }

        $otp = rand(1000,9999);

        Client::where('email','=',$request->email)->update(['otp'=>$otp]);
        $maildata = [

                'title' => 'Your OTP is : '. $otp,
                            ];
    
        Mail::to($request->email)->send(new SendOtpMail($maildata));

        $token = $client->createToken('my-app-token')->accessToken;

        $client->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;

        $notification = new Notification();
        $notification->msg = "Great to have you on board.";
        $notification->title = "Welcome !!";
        $notification->uid = $client->id;
        $notification->save();

        return response()->json([
            'status' => true,
            'message' => 'Client successfully Registered',
            'data' => [$client]
        ], 201); 
    } 

// Login Function Client Api {Role = 1}

    public function login(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'email' => 'email',
            // 'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        if ($validator->fails()) {  
       
            $username = $request->email;
            $client = Client::where('username', '=', $username)->where('roles','=','1c')->first();
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
             $client = Client::where('email', '=', $email)->where('roles','=','1c')->first();
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

// RESET Password Api For Clients And Recepitonist

    public function resetPassword(Request $request)
    {
        $this->checkToken();
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
        $cliente = Client::where('email',$request->email)->get();

        if(count($cliente) > 0)
        {
            $client = Client::where('email', $request->email)->update([
                'password' => $request->password,
                'otp' => ''
            ]);
        }
        else
        {
            $client = Shipment::where('email', $request->email)->update([
                'password' => $request->password,
                'otp' => ''
            ]);
        }
   
   
        if(!$client)
        {
            return response([
                'status' => false,
                'message' => "Something Went Wrong",
                'data'=>[]
            ],404);
        }
   
        $response = [
            'status' => true,
            'message' => 'Password Reset Successful',
            'data' => []
        ];
   
        return response($response, 200);
   
    }






// Client Add Receptionist to table with {Role = 2}

    public function addReceptionist(Request $request)
    {
        // VALIDATION OF Receptionist DETAILS 
        $this->checkToken();
        $validator = Validator::make($request->all(), [
            "name" => "required|regex:/^[a-zA-Z]+$/u|max:255",
            "lname" => "regex:/^[a-zA-Z]+$/u|max:255",
            "email" => 'required|email|unique:clients,email|regex:/^.+@.+$/i', 
            "phone" => "required|min:10|numeric",
            "country" => "required|max:255",
            "address" => "required|max:255",
            "password" => "required",
            // "client_id" => "required",
            "file" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
                    $filename = '';
                    if($request->hasfile('file'))
                    {
                        $image = $request->file('file');
                        $filename = time() . '.' . $image->getClientOriginalExtension();
                        $image->move($destinationPath, $filename);
                        
                        $client = new Client();
                        $client->name = $request->name;
                        $client->lname = $request->lname;
                        $client->email = $request->email;
                        $client->phone = $request->phone;
                        $client->country = $request->country;
                        $client->address = $request->address;
                        $client->password = $request->password;
                        $client->roles = "2r";
                        $client->profileimage = $filename;
                        $client->client_id = $this->userId;
                        $client->type = "receptionist";
                        $client->save();
                    }
                    else if(!$request->hasfile('file'))
                    {
                        $client = new Client();
                        $client->name = $request->name;
                        $client->lname = $request->lname;
                        $client->email = $request->email;
                        $client->phone = $request->phone;
                        $client->country = $request->country;
                        $client->address = $request->address;
                        $client->password = $request->password;
                        $client->roles = "2r";
                        $client->profileimage = $request->file;
                        $client->client_id = $this->userId;
                        $client->type = "receptionist";
                        $client->save();
                    }
                    else
                    {
                        $client = new Client();
                        $client->name = $request->name;
                        $client->lname = $request->lname;
                        $client->email = $request->email;
                        $client->phone = $request->phone;
                        $client->country = $request->country;
                        $client->address = $request->address;
                        $client->password = $request->password;
                        $client->roles = "2r";
                        $client->profileimage = '';
                        $client->client_id = $this->userId;
                        $client->type = "receptionist";
                        $client->save();
                    }
                    // if($request->hasfile('file'))
                    // {
                    //     $image = $request->file('file');
                    //     $filename = time() . '.' . $image->getClientOriginalExtension();
                    //     $image->move($destinationPath, $filename);
                    // }
                    //     $client = new Client();
                    //     $client->name = $request->name;
                    //     $client->lname = $request->lname;
                    //     $client->email = $request->email;
                    //     $client->phone = $request->phone;
                    //     $client->country = $request->country;
                    //     $client->address = $request->address;
                    //     $client->password = $request->password;
                    //     $client->roles = "2r";
                    //     $client->profileimage = $filename;
                    //     $client->client_id = $request->client_id;
                    //     $client->save();
                    
            }
        // return $client;

        if($client->roles == '2r')
        {
            $notification = new Notification();
            $notification->msg =  "Receptionist has been added ";
            $notification->title = "Great !!";
            $notification->uid = $this->userId;
            $notification->save();
        }

        $maildata = [
                'email' => 'Email : '. $request->email,
                'password' => 'Password : '. $request->npassword,
                'url' => 'https://shipment.netlify.app/#/login'
            ];
    
            Mail::to($request->email)->send(new SendLoginMail($maildata));

        

        $token = $client->createToken('my-app-token')->accessToken;

        $client->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;

        return response()->json([
            'status' => true,
            'message' => 'Receptionist successfully Added',
            'data' => [$client]
        ], 201); 
    
    }
   
// Receptionist Login {Role = 2}

    public function loginReceptionist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => []
            ]);
        }
        $client = Client::where('email',$request->email)->where('password', $request->password)->where('roles','=','2r')->first();
        if(!$client)
        {
            return response([
                'status'=>false,
                'message'=>"Wrong Credentials Given"
            ],404);
        }

        $token = $client->createToken('my-app-token')->accessToken;

        $client->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;

        $response = [
            'status' => true,
            'message' => 'Login Successful',
            'data' => [$client]
        ];

        return response()->json($response, 200);
    }


// Function to View Client's Receptionist

    public function viewReceptionist()
    {
        $this->checkToken();
        $receptionist = Client::where('client_id',$this->userId)->where('roles','=','2r')->get();
        // $receptionist = Client::where('client_id',$userId)->where('roles','=','2r')->get();
        // return $receptionist;
        if(count($receptionist) == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No receptionist',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'receptionist Details',
            'data' => $receptionist
        ], 201); 
       
    }


// Client Profile Details Show Api 

    public function clientProfile()
    {
        $this->checkToken();
        $client = Client::where('id',$this->userId)->where('roles','=','1c')->first();
        if($client)
        {
            return response()->json([
                'status' => true,
                'message' => 'Client Details',
                'data' => [$client]
            ], 201); 
        }
        return response()->json([
            'status' => false,
            'message' => 'Not a Client',
            'data' => []
        ]);
         
    }

// Client Profile Update 

    public function updateClientProfile(Request $request)
    {  
        $this->checkToken();
        $validator = Validator::make($request->all(), [
            "name" => "regex:/^[a-zA-Z]+$/u|max:255",
            // "lname" => "regex:/^[a-zA-Z]+$/u|max:255",
            "phone" => "min:10|numeric",
            // "password" => "min:6 | max:18",
            // "file" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            "username" => "required|regex:/^[a-zA-Z0-9-]+$/u|max:255"
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
                $filename = '';
                if($request->hasfile('file'))
                {
                    $image = $request->file('file');
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $filename);
                    
                    $client =  Client::where('email','=', $request->email)->update(['name' => $request->name ,
                                            'lname' => $request->lname ,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                            "username" => $request->username,
                                            "country_code" => $request->country_code,
                                            'roles' => "1c",
                                            'language' => $request->language,
                                            'about_me' => $request->about_me,
                                            'profileimage' =>  $filename
                                        ]);
                }
                else if(!$request->hasfile('file'))
                {
                    $client =  Client::where('email','=', $request->email)->update(['name' => $request->name ,
                                            'lname' => $request->lname ,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                            "username" => $request->username,
                                            "country_code" => $request->country_code,
                                            'roles' => "1c",
                                            'language' => $request->language,
                                            'about_me' => $request->about_me,
                                            'profileimage' => $request->file
                    ]);
                }
                else
                {
                    $client = Client::where('email','=', $request->email)->update(['name' => $request->name ,
                                            'lname' => $request->lname ,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                            "username" => $request->username,
                                            "country_code" => $request->country_code,
                                            'roles' => "1c",
                                            'language' => $request->language,
                                            'about_me' => $request->about_me,
                                            'profileimage' => ''
                    ]);
                }
            }

            
                $data = Client::where('email','=', $request->email)->get();
                if($client)
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Profile successfully Updated',
                        'data' => $data
                    ], 201); 
                }
                return response()->json([
                    'status' => false,
                    'message' => 'Not a Client',
                    'data' => []
                ]);
                    // return response()->json([
                    //     'status' => true,
                    //     'message' => 'Profile successfully Updated',
                    //     'data' => $client
                    // ], 201);
    }

// Client Delete Profile Api

    public function deleteProfile()
    {
        $this->checkToken();
        $headers = getallheaders();
        $check = DB::table('personal_access_tokens')->where('token',$headers['token'])->select('tokenable_id')->get();
        // $client = Client::find($this->userId);
        $client = Client::where('id', $this->userId)->update(['status' => 'Deactivate'  ]);
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

// Receptionist Get Profile Api

    public function receptionistProfile()
    {
        $this->checkToken();
        $receptionist = Client::where('id',$this->userId)->where('roles','=','2r')->first();
        if($receptionist)
        {
            return response()->json([
                'status' => true,
                'message' => 'Receptionist Details',
                'data' => [$receptionist]
            ], 201); 
        }
        return response()->json([
            'status' => false,
            'message' => 'Not a Receptionist',
            'data' => []
        ], 201);
    }

// Update Receptionist Profile

    public function updateReceptionistProfile(Request $request)
    {  
        $this->checkToken();
        $validator = Validator::make($request->all(), [
            "name" => "regex:/^[a-zA-Z]+$/u|max:255",
            "lname" => "regex:/^[a-zA-Z]+$/u|max:255",
            "phone" => "min:10|numeric",
            // "password" => "min:6 | max:18",
            // "file" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
                $filename = '';
                if($request->hasfile('file'))
                {
                    $image = $request->file('file');
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $filename);
                    
                    $receptionist = Client::where('email','=', $request->email)->update(['name' => $request->name ,
                                            'lname' => $request->lname ,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                            'roles' => '2r',
                                            'language' => $request->language,
                                            'about_me' => $request->about_me,
                                            'profileimage' =>  $filename
                                        ]);
                }
                else if(!$request->hasfile('file'))
                {
                    $receptionist = Client::where('email','=', $request->email)->update(['name' => $request->name ,
                                            'lname' => $request->lname ,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                            'roles' => '2r',
                                            'language' => $request->language,
                                            'about_me' => $request->about_me,
                                            'profileimage' => $request->file
                    ]);
                }
                else
                {
                    $receptionist = Client::where('email','=', $request->email)->update(['name' => $request->name ,
                                            'lname' => $request->lname ,
                                            'phone' => $request->phone ,
                                            'country' => $request->country ,
                                            'address' => $request->address ,
                                            'roles' => '2r',
                                            'language' => $request->language,
                                            'about_me' => $request->about_me,
                                            'profileimage' => ''
                    ]);
                }
            }

                $data = Client::where('email','=', $request->email)->get();
                if($receptionist)
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Profile successfully Updated',
                        'data' => $data
                    ], 201); 
                }
                return response()->json([
                    'status' => false,
                    'message' => 'Not a Client',
                    'data' => []
                ]);

                // $receptionist = Client::where('email','=', $request->email)->get();
                //     return response()->json([
                //         'status' => true,
                //         'message' => 'Profile successfully Updated',
                //         'data' => [$receptionist]
                //     ], 201);
    }

// Client Drop Off Api

    public function clientDropoff(Request $request)
    {
        $this->checkToken();
        $validator = Validator::make($request->all(), [
            "uid" => "required|numeric",
            "pickup_location" => "required|max:255",
            "pickup_date" => "required",
            "pickup_time" => 'required', 
            "distance" => "required",
            "estimate" => "required"
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
                $dropoff = new Dropoff();
                $dropoff->uid = $request->uid;
                $dropoff->pickup_location = $request->pickup_location;
                $dropoff->pickup_date = $request->pickup_date;
                $dropoff->pickup_time = $request->pickup_time;
                $dropoff->distance = $request->distance;
                $dropoff->estimate = $request->estimate;
                $dropoff->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Pickup Successfully Scheduled',
                'data' => [$dropoff]
            ], 201); 

    }

// Client Items Store Api

    public function clientItem(Request $request)
    {
        $this->checkToken();
        $image = array();
        if($file = $request->file('item_image')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                $destinationPath =  public_path('/image');
                $image_url = $destinationPath.$image_full_name;
                $file->move($destinationPath,$image_full_name);
                $image[] = $image_full_name;
            }
            Item::insert([
                'uid' => $request->uid,
                'mid' => $request->mid,
                'booking_id' => $request->booking_id,
                'items' => $request->items,
                'item_image' => json_encode($image),
                'category' => $request->category,
                'booking_attribute' => $request->booking_attribute,
                'booking_price' => $request->booking_price,
                'note' => $request->note,
                'description' => $request->description,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Item Successfully Saved'
            ], 201);       
        }
    }

// booking Items Show APi 

    public function viewBookingItem()
    {
        $this->checkToken();
        $item = DB::table('bookings')
                    ->join('items', 'bookings.id','=','items.booking_id')
                    ->select('items.*')
                    ->where('bookings.uid',$this->userId)
                    ->get();
                    // return $item;
        $booking = Booking::where('uid',$this->userId)->first();
        $booking->booking_items = $item;
        if($item->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No Items'
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Your Items',
                'data' => $booking
            ], 201); 
        }        
        
    }

// Client Items Store Api

    // public function clientItem(Request $request)
    // {
    //     //Validation 

    //     $validator = Validator::make($request->all(), [
    //         "uid" => "numeric",
    //         // "file" => 'image|mimes:jpeg,png,jpg,gif,svg',
    //         "description" => "",
    //     ]);
    //         if ($validator->fails()) 
    //         {
    //             return response()->json($validator->errors()->toJson(), 400);
    //         }
    //         else
    //         {
                
    //             if($request->hasfile('file'))
    //             {
    //                 $item_image = [];
    //                 $destinationPath = public_path('/image');
    //                 foreach ($request->file('file') as $file) 
    //                 {
    //                     $name =  time() . '.' . $file->getClientOriginalExtension();
    //                     $file->move($destinationPath, $name);
    //                     $item_image[] = $name;                
    //                 }
    //                     // store image file into directory and db
    //                     $item = new Item();
    //                     $item->uid = $request->uid;
    //                     $item->description = $request->description;
    //                     $item->image = json_encode($item_image);
    //                     $item->save(); 
                    
    //                     return response()->json([
    //                         'message' => 'Item Successfully Saved',
    //                         'item' => $item
    //                     ], 201);
    //             }
    //         }
    // }

// Display Client Items Api

    public function displayItem()
    {
        $this->checkToken();
        $item = Item::where('uid',$this->userId)->get();
        if($item->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No Items'
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Your Items',
                'data' => $item
            ], 201); 
        }
        return response()->json([
            'status' => false,
            'message' => 'No Review'
        ], 201); 
        // return "No Review";

    }

// Add Booking Details Api

    public function bookingAdd(Request $request)
    {
        //Validation 
        $this->checkToken();
        $validator = Validator::make($request->all(), [
            // "uid" => "required|numeric",
            // "booking_date" => "required",
            // "booking_type" => "required",
            // "from" => "required",
            // "to" => "required",
            // "shipment_company" => "required",
            // "status" => "required",
            // "pickup_review" => "required",
            "schedule_id" => "required"
        ]);
            if ($validator->fails()) 
            {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ]);
            }
            else
            {
                $schedule = Schedule::where('id',$request->schedule_id)->first();
                $shipment = Shipment::where('id',$schedule->sid)->first();
                $rid = Client::where('email',$request->receptionist_email)->first();    
                $time = DB::table('timer')->orderBy('created_at','desc')->first();

                $booking = new Booking();
                $booking->uid = $this->userId;
                $booking->title = $request->title;
                $booking->booking_date = Carbon::now();
                $booking->arrival_date = $schedule->arrival_date;
                $booking->booking_type = $schedule->shipment_type;
                $booking->from = $schedule->from;
                $booking->to = $schedule->to;
                $booking->shipment_company = $shipment->companyname;
                $booking->status = "Not Confirmed";
                $booking->schedule_id = $request->schedule_id;
                $booking->transaction_id = $request->transaction_id;
                $booking->card_type = $request->card_type;
               
                $pickup = [];
                    $obj = new \stdClass();
                    $obj->pickup_type = $request->pickup_type;
                    $obj->pickup_location = $request->pickup_location;
                    $obj->pickup_date = $request->pickup_date;
                    $obj->pickup_time = $request->pickup_time;
                    $obj->pickup_distance = $request->pickup_distance;
                    $obj->pickup_estimate = $request->pickup_estimate;
                    array_push($pickup, $obj);

                $booking->pickup_review = json_encode($pickup);

                    $info = [];
                    $obj = new \stdClass();
                    $obj->receptionist_name = $request->receptionist_name;
                    $obj->receptionist_email = $request->receptionist_email;
                    $obj->receptionist_phone = $request->receptionist_phone;
                    $obj->receptionist_address = $request->receptionist_address;
                    $obj->receptionist_country = $request->receptionist_country;
                    array_push($info, $obj);

                $booking->receptionist_info = json_encode($info);
                $booking->receptionist_id = $rid->id;
                $booking->save();
            }

            // $booking->pickup_review = json_decode($booking->pickup_review);
            // $booking->receptionist_info = json_decode($booking->receptionist_info);
            // return $booking;

            $items = json_decode($request->input('items'));
            // $items = $request->input('items');
            // return $items;
            // return count($items);
         
            foreach($items as $key=>$item)
            {
                // return json_encode($item->item_image); die;
               
                    $book = new BookingItem();
                    $book->uid = $this->userId;
                    $book->category = $item->category_id;
                    $book->item_name = $item->item_name; 
                    $book->item_image = $item->item_image;
                    $book->quantity = $item->quantity;
                    $book->description = $item->description;
                    $book->booking_id = $booking->id;
                    $book->schedule_id = $booking->schedule_id;
                    $book->save();

                    $itemCategory = json_decode($item->item_name);
                    // $itemCategory = $item->item_name;
                    // return $itemCategory;
                    // return count($itemCategory);
                    // return $itemCategory;
                    foreach($itemCategory as $key=>$res)
                    {
                        // return $res;
                        $books = new BookingCategory();
                        $books->uid = $this->userId;
                        $books->category_id = $book->id;
                        $books->booking_id = $booking->id;
                        $books->item_name = $res->itemname; 
                        $books->quantity = $res->qty;
                        $books->pickupfee = $res->pickupfee;
                        $books->shippingfee = $res->shippingFee;
                        $books->schedule_id = $booking->schedule_id;
                        $books->save(); 
                    }
            }
            $bookitem = DB::table('booking_items')->select('*')->where('booking_id','=',$booking->id)->get();
            $booking->pickup_review = json_decode($booking->pickup_review);
            $booking->receptionist_info = json_decode($booking->receptionist_info);
            $booking->item_type = $bookitem;
           

                // array_push($item_array, $obj);
            // $booking->items = json_decode($booking->booking_item);
            // return $bookitem;
            // foreach($bookitem as $bitem)
            // {
            //     $available += $bitem->quantity;
            // }
            // $item = DB::table('booking_items')
            //                 ->join('schedule_items','schedule_items.item_id','=','booking_items.category')
            //                 ->where('schedule_items.schedule_id',$bookitem->schedule_id)
            //                 ->select('schedule_items.*','booking_items.*')
            //                 ->get();
            //                 return $items;
            
            // foreach($bookitem as $key=>$itm)
            // {
                
            //     $scheduleItem = ScheduleItem::where('item_id',$itm->category)->where('schedule_id',$itm->schedule_id)->get();
            //         foreach($scheduleItem as $scItem)
            //         {
            //             ScheduleItem::where('item_id', $itm->category)->where('schedule_id',$itm->schedule_id)->update([
            //                 'available' => $scItem->available - $itm->quantity
            //                 ]);
            //         }
                // return $scheduleItem;
                // return $itm->quantity;
                // ScheduleItem::where('item_id',$itm['category'])->update([
                //                                                         'available' => $scheduleItem->item_number - $itm->quantity
                //                                                         ]);
            // }

            // $scheduleItem = ScheduleItem::where('schedule_id',$booking->schedule_id)->first();
            
            //         ScheduleItem::where('schedule_id',$booking->schedule_id)->update([
            //                                                         'available' => $scheduleItem->item_number - $available
            //                                                         ]);
            
            // $notification = new Notification();
            // $notification->msg = "You missed booking process in mid, please complete it.";
            // $notification->title = "Booking incomplete !!";
            // $notification->uid = $this->userId;
            // $notification->booking_id = $booking->id;
            // $notification->save();
            
            return response()->json([
                'status' => true,
                'message' => 'Booking Successful But Not Confirmed',
                'payment_link' => 'http://44.194.48.17/stripe',
                'data' => [$booking]
            ], 201); 
    
    }

// Function To Update Transaction Id And Status In Booking Done

    public function updateBooking1(Request $request)
    {
        $time = DB::table('timer')->orderBy('created_at','desc')->first();
        $book =  Booking::where('id',$request->id)->first();
        $date = $book->created_at;
        $carbon_date = Carbon::parse($date);
        $carbon_date->addHours($time->hours);
        //return $carbon_date;
        $booking = Booking::where('id',$request->id)->update(['transaction_id' => $request->transaction_id,
                                                              'status' => 'Confirmed',
                                                              'card_type' => $request->card_type,
                                                              'total_amount' => $request->total_amount,
                                                              'expired_at' => $carbon_date]);
        

        $bookitem = DB::table('booking_category')->select('*')->where('booking_id','=',$request->id)->get();

        return $bookitem;

        //die();
        foreach($bookitem as $key=>$itm)
        {
            
            //echo "1";

            DB::enableQueryLog();
            $scheduleItem = ScheduleCategory::where('category_id',$itm->category_id)->where('item_name',$itm->item_name)->get();
            print_r(DB::getQueryLog());

            //print_r($scheduleItem);
                foreach($scheduleItem as $scItem)
                {
                    

                    ScheduleCategory::where('category_id',$itm->category_id)
                    ->where('item_name',$itm->item_name)->update([
                        'available' => $scItem->available - $itm->quantity
                        ]);

                    
                }

        }


        die();




        $coupon_code = $request->coupon_code;
        if(!is_null($coupon_code))
        {
            $this->checkToken();
            $coupon_code = $request->coupon_code;
            $amount = $request->amount;
            $uid = array();                             // Create Array to store userId
            array_push($uid,$this->userId);             // Push UserId in Array
            $data = Coupon::where('coupon_code',$coupon_code)->where('status',1)->first();
            // return json_decode($data->users);
            if(!$data)
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Coupon is Expired',
                    'data' => []
                ]);
            }
            else if($data->once == 1)
            {
                if(!in_array($this->userId,json_decode($data->users)))  // Check Usser Id is Present Or Not. if Not Present then Update
                {
                    $uid = json_decode($data->users);
                    array_push($uid,$this->userId);
                    Coupon::where('coupon_code',$coupon_code)->update(['users' => json_encode($uid)]);
                    if($data->coupon_type == "percentage")
                    {
                        $percentage = ($data->coupon_amount/100) * $amount;
                        $final_amount = $amount - $percentage;
                    }
                    else if($data->coupon_type == "fix_amount")
                    {
                        $final_amount = $amount - $data->coupon_amount;
                    }        
                    $data->final_amount = $final_amount;
            
                        // return response()->json([
                        //     'status' => true,
                        //     'message' => 'Coupon Details',
                        //     'data' => [$data]
                        // ], 201); 
                }
                else if(in_array($this->userId,json_decode($data->users)))
                {
                    // return response()->json([
                    //     'status' => false,
                    //     'message' => 'Coupon can only be used once',
                    //     'data' => []
                    // ]);
                }
                
            }
            else
            {
                if($data->coupon_type == "percentage")
                {
                    $percentage = ($data->coupon_amount/100) * $amount;
                    $final_amount = $amount - $percentage;
                }
                else if($data->coupon_type == "fix_amount")
                {
                    $final_amount = $amount - $data->coupon_amount;
                }        
                $data->final_amount = $final_amount;

                    // return response()->json([
                    //     'status' => true,
                    //     'message' => 'Coupon Details',
                    //     'data' => [$data]
                    // ], 201); 
            }
        }
            $book = Booking::where('id',$request->id)->first();
            $sche = Schedule::where('id', $book->schedule_id)->first();
            $user = Client::where('id', $book->uid)->first();
            $notification = new Notification();
            $notification->msg = "You have recieve a new booking ";
            $notification->title = "Booking request received !!";
            $notification->sid = $sche->sid;
            $notification->booking_id = $request->id;
            $notification->save();

            $notification = new Notification();
            $notification->msg = $user->name . "has assigned you as receptionist ";
            $notification->title = "Great !!";
            $notification->uid = $book->receptionist_id;
            $notification->booking_id = $request->id;
            $notification->save();

            $notification = new Notification();
            $notification->msg = "Payment successfully done ";
            $notification->title = "Success !!";
            $notification->uid = $book->uid;
            $notification->booking_id = $request->id;
            $notification->save();

        if(!$booking)
        {
            return response()->json([
                'status' => false,
                'message' => 'Failed'
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Booking Confirmed',
                'data' => []
            ], 201); 
        }
       
    }



    public function updateBooking(Request $request)
    {
        $time = DB::table('timer')->orderBy('created_at','desc')->first();
        $book =  Booking::where('id',$request->id)->first();
        $date = $book->created_at;
        $carbon_date = Carbon::parse($date);
        $carbon_date->addHours($time->hours);
        //return $carbon_date;
        $booking = Booking::where('id',$request->id)->update(['transaction_id' => $request->transaction_id,
                                                              'status' => 'Confirmed',
                                                              'card_type' => $request->card_type,
                                                              'total_amount' => $request->total_amount,
                                                              'expired_at' => $carbon_date]);
        

        $bookitem = DB::table('booking_category')->select('*')->where('booking_id','=',$request->id)->get();
        foreach($bookitem as $key=>$itm)
        {
            
            $scheduleItem = ScheduleCategory::where('item_name',$itm->item_name)->where('schedule_id',$itm->schedule_id)->get();
                foreach($scheduleItem as $scItem)
                {
                    ScheduleCategory::where('item_name', $itm->item_name)->where('schedule_id',$itm->schedule_id)->update([
                        'available' => $scItem->available - $itm->quantity
                        ]);
                }

        }




        $coupon_code = $request->coupon_code;
        if(!is_null($coupon_code))
        {
            $this->checkToken();
            $coupon_code = $request->coupon_code;
            $amount = $request->amount;
            $uid = array();                             // Create Array to store userId
            array_push($uid,$this->userId);             // Push UserId in Array
            $data = Coupon::where('coupon_code',$coupon_code)->where('status',1)->first();
            // return json_decode($data->users);
            if(!$data)
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Coupon is Expired',
                    'data' => []
                ]);
            }
            else if($data->once == 1)
            {
                if(!in_array($this->userId,json_decode($data->users)))  // Check Usser Id is Present Or Not. if Not Present then Update
                {
                    $uid = json_decode($data->users);
                    array_push($uid,$this->userId);
                    Coupon::where('coupon_code',$coupon_code)->update(['users' => json_encode($uid)]);
                    if($data->coupon_type == "percentage")
                    {
                        $percentage = ($data->coupon_amount/100) * $amount;
                        $final_amount = $amount - $percentage;
                    }
                    else if($data->coupon_type == "fix_amount")
                    {
                        $final_amount = $amount - $data->coupon_amount;
                    }        
                    $data->final_amount = $final_amount;
            
                        // return response()->json([
                        //     'status' => true,
                        //     'message' => 'Coupon Details',
                        //     'data' => [$data]
                        // ], 201); 
                }
                else if(in_array($this->userId,json_decode($data->users)))
                {
                    // return response()->json([
                    //     'status' => false,
                    //     'message' => 'Coupon can only be used once',
                    //     'data' => []
                    // ]);
                }
                
            }
            else
            {
                if($data->coupon_type == "percentage")
                {
                    $percentage = ($data->coupon_amount/100) * $amount;
                    $final_amount = $amount - $percentage;
                }
                else if($data->coupon_type == "fix_amount")
                {
                    $final_amount = $amount - $data->coupon_amount;
                }        
                $data->final_amount = $final_amount;

                    // return response()->json([
                    //     'status' => true,
                    //     'message' => 'Coupon Details',
                    //     'data' => [$data]
                    // ], 201); 
            }
        }
            $book = Booking::where('id',$request->id)->first();
            $sche = Schedule::where('id', $book->schedule_id)->first();
            $user = Client::where('id', $book->uid)->first();
            $notification = new Notification();
            $notification->msg = "You have recieve a new booking ";
            $notification->title = "Booking request received !!";
            $notification->sid = $sche->sid;
            $notification->booking_id = $request->id;
            $notification->save();

            $notification = new Notification();
            $notification->msg = $user->name . "has assigned you as receptionist ";
            $notification->title = "Great !!";
            $notification->uid = $book->receptionist_id;
            $notification->booking_id = $request->id;
            $notification->save();

            $notification = new Notification();
            $notification->msg = "Payment successfully done ";
            $notification->title = "Success !!";
            $notification->uid = $book->uid;
            $notification->booking_id = $request->id;
            $notification->save();

        if(!$booking)
        {
            return response()->json([
                'status' => false,
                'message' => 'Failed'
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Booking Confirmed',
                'data' => []
            ], 201); 
        }
       
    }


// FUnction to List Transaction

    public function listTransaction()
    {
        $this->checkToken();
        $transaction = Booking::where('uid',$this->userId)->select('id','booking_type','transaction_id','card_type','total_amount','created_at')->where('status','=','Confirmed')->orderBy('created_at','desc')->get(); 
        if($transaction->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No transactions'
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Your transactions',
                'data' => $transaction
            ], 201); 
        }
    }

// Function to List Transaction Market Bookings

    public function listTransactionMarket()
    {
        $this->checkToken();
        $transaction = MarketBooking::where('uid',$this->userId)->select('mid','transaction_id','card_type','total_amount','created_at')->where('transaction_id','!=',NULL)->orderBy('created_at','desc')->get(); 
        if($transaction->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No transactions'
            ], 201); 
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Your transactions',
                'data' => $transaction
            ], 201); 
        }
    }

// Function to Do Calculation In Booking Api Page

    public function calculation(Request $request)
    {
        $this->checkToken();

        // $data = Schedule::where('id',$request->schedule_id)->first();

        $data = ScheduleCategory::where('id',$request->item_id)->where('item_name',$request->item_name)->first();
     
        // $available = ScheduleItem::where('schedule_id',$request->schedule_id)->where('item_id',$request->category)->first();
        // return $available->available;
        // $category = [];
        // foreach(json_decode($data->item_type) as $item)
        // {
        //     if($request->category == $item->category_name)
        //     {
        //         $sf = $item->shipping_fee;
        //         $pf = $item->pickup_fee;
        //         $icon = $item->icon;
        //         $quantity = $available->available;
        //         $price = ['shipping_fee' => $sf,'pickup_fee' => $pf,'available'=>$quantity,'icon' => $icon];
        //         array_push($category,$price);

        //             return response()->json([
        //                 'status' => true,
        //                 'message' => 'Prices',
        //                 'data' => $category
        //             ], 201);
        //     }  
        // }
        if(isset($data))
        {
            return response()->json([
                'status' => true,
                'message' => 'Prices',
                'data' => [$data]
            ]);
        }
            return response()->json([
                'status' => false,
                'message' => 'No Items',
                'data' => []
            ]);
    }

// Function to get Schedule Category Item List

    public function scheduleCategory(Request $request)
    {
        // $data = Schedule::where('id',$request->schedule_id)->first();
        $data = ScheduleItem::where('schedule_id',$request->schedule_id)->get();
        $items = array();
        foreach($data as $row)
        {
            $row->item = ScheduleCategory::where('category_id',$row->id)->get();
            foreach($row->item as $res)
            {
                // $itms = ['item_name' => $res->item_name,'key' => false];
                // array_push($items, $itms);
                $res->key = false;
            }
            
        }

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Items',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Categories',
                'data' => $data
            ], 201);
        }
    }

// Function to Save Booking Item in Database

    // public function saveBookingItem(Request $request)
    // {
    //     $this->checkToken();
    //     $validator = Validator::make($request->all(), [
    //         "uid" => "required|numeric",
    //         "category" => "required",
    //         "item_name" => "required",
    //         // "description" => "required",
    //         "booking_id" => "required"
    //     ]);
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => $validator->errors()->first(),
    //                 'data' => []
    //             ]);
    //         }
    //         else
    //         {
    //             $image = array();
    //             if($file = $request->file('item_image')){
    //                 foreach($file as $file){
    //                     $image_name = md5(rand(1000,10000));
    //                     $ext = strtolower($file->getClientOriginalExtension());
    //                     $image_full_name = $image_name.'.'.$ext;
    //                     $destinationPath =  public_path('/image');
    //                     $image_url = $destinationPath.$image_full_name;
    //                     $file->move($destinationPath,$image_full_name);
    //                     $image[] = $image_full_name;
    //                 }
                
    //                 $bookingItem = new BookingItem();
    //                 $bookingItem->uid = $request->uid;
    //                 $bookingItem->category = $request->category;
    //                 $bookingItem->item_name = $request->item_name;
    //                 $bookingItem->item_image = json_encode($image);
    //                 $bookingItem->description = $request->description;
    //                 $bookingItem->quantity = $request->quantity;
    //                 $bookingItem->booking_id = $request->booking_id;
    //                 $bookingItem->save();

    //                 return response()->json([
    //                     'status' => true,
    //                     'message' => 'Booking Item Successfully Added'
    //                     ], 201);

    //             }
    //         }
    // }

//  Display Booking Details to client Api

    public function viewBooking()
    {
        $this->checkToken();
        $data = Booking::where('uid',$this->userId)->where('status','!=','Not Confirmed')->orderBy('created_at','desc')->get();
        foreach($data as $value) {
            $value->pickup_review = json_decode($value->pickup_review);
            $value->receptionist_info = json_decode($value->receptionist_info);

            $value->pickupagent_id = (int)$value->pickupagent_id;
        }
        foreach($data as $book)
        {
            $book->item = DB::table('booking_items')->select('*')->where('booking_id','=',$book->id)->get();
            foreach($book->item as $value)
            {
                $value->item_name = json_decode($value->item_name);
                $value->item_image = json_decode($value->item_image);
            }
            $manager = Schedule::where('id',$book->schedule_id)->first();
            if(isset($manager))
            {
                $book->sid = $manager->sid;
                $book->departure = Shipment::where('id',$manager->departure_warehouse)->get();
                $book->arrival = Shipment::where('id',$manager->destination_warehouse)->get();
                $book->pickup = Shipment::where('id',$book->pickupagent_id)->get();
                $book->receptionist = Client::where('id',$book->receptionist_id)->where('roles','=','2r')->get();
            }
        }
            // return $data[0]['pickup_review'];
                // $pickupBookings = [];
                // $dropoffBookings = [];
                // foreach($data as $key=>$book)
                // {
                //     if($book->pickup_review == 'dropoff')
                //     {
                //         $booking = Booking::where('uid',$this->userId)
                //                         ->where('pickup_review','=','dropoff')->get();
                //         array_push($dropoffBookings, $booking);
                //         return response()->json([
                //             'status' => true,
                //             'message' => 'Your Bookings',
                //             'data' => $booking
                //         ], 201); 
                //     }
                //     else if($book->pickup_review == 'pickup'){
                //         $bookings = DB::table('bookings') 
                //                 ->join('dropoffs', 'bookings.uid','=','dropoffs.uid')
                //                 ->select('bookings.*','dropoffs.*')
                //                 ->where('bookings.uid',$this->userId)
                //                 ->where('bookings.pickup_review','=','pickup')->get();
                //         array_push($dropoffBookings, $bookings);
                //         return response()->json([
                //             'status' => true,
                //             'message' => 'Your Bookings',
                //             'data' => $bookings
                //         ], 201); 
                //     }

                //     else
                //     {
                //         return response()->json([
                //             'status' => true,
                //             'message' => 'Your Bookings',
                //             'data' => $bookings + $booking
                //         ], 201); 
                //     }
                // }
                // $allBookings = array_merge($dropoffBookings,$pickupBookings);
                // $allBookings = $dropoffBookings + $pickupBookings ;
                // return $booking;
                // if(!$allBookings)
                // {
                //     return response()->json([
                //         'status' => false,
                //         'message' => 'No Bookings',
                //         'data' => [],
                //     ], 201); 
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
                'message' => 'Your Bookings',
                'data' => $data
            ], 201); 
        }
    }

// Receptionist Dashboard API

    public function receptionistDashboard()
    {
        $this->checkToken();
        $data = Booking::where('receptionist_id',$this->userId)->where('status','!=','Not Confirmed')->orderBy('created_at','desc')->get();
        if(count($data) > 0)
        {
            foreach($data as $value) {
                $value->pickup_review = json_decode($value->pickup_review);
                $value->receptionist_info = json_decode($value->receptionist_info);
                $value->pickupagent_id = (int)$value->pickupagent_id;
            }
            foreach($data as $book)
            {
                $book->item = DB::table('booking_items')->select('*')->where('booking_id','=',$book->id)->get();
                foreach($book->item as $row)
                {
                    $row->item_name = json_decode($row->item_name);
                    $row->item_image = json_decode($row->item_image);
                }
            }
            foreach($data as $row)
            {
                $schedule = Schedule::where('id',$row->schedule_id)->orderBy('created_at','desc')->first();
                if(isset($schedule))
                {
                $row->arrival = Shipment::where('id',$schedule->destination_warehouse)->orderBy('created_at','desc')->get();
                $row->departure = Shipment::where('id',$schedule->departure_warehouse)->orderBy('created_at','desc')->get();
                }
            }
        }
        if(count($data) == 0)
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
                'message' => 'Your Bookings',
                'data' => $data
            ], 201); 
        }
    }
 
// Market Place Api

    public function marketPlace(Request $request)
    {
        $this->checkToken();
        // VALIDATION OF Market Place DETAILS 

        $validator = Validator::make($request->all(), [
            "title" => "required",
            "category" => "required",
            // "booking_attribute" => 'required', 
            "pickup_location" => "required",
            "dropoff_location" => "required",
            "delivery_days" => "required",
            // "items" => "required|max:255",
            "booking_price" => "required|max:255",
            "dropoff" => "required",
            // "item_image" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            // "description" => "required",
            // "needs" => "required",
            // "status" => "required",
        ]);
        // $item_image =  array();
        $image = array();
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ]);
            }
            else
            {
                if($request->hasfile('item_image'))
                {
                    foreach($request->file('item_image') as $file){
                        $image_name = md5(rand(1000,10000));
                        $ext = strtolower($file->getClientOriginalExtension());
                        $image_full_name = $image_name.'.'.$ext;
                        $destinationPath =  public_path('/image');
                        $image_url = $destinationPath.$image_full_name;
                        $file->move($destinationPath,$image_full_name);
                        $image[] = $image_full_name;
                    }
                
                    $market = new Marketp();
                    $market->title = $request->title;
                    $size = $request->category;
                    $items = [];
                    foreach($size as $key=>$item)
                    {
                        $obj = new \stdClass();
                        $obj->category = $item['categoryItem'];
                        $obj->booking_attribute = $item['booking_attribute'];
                        $obj->quantity = $item['quantity'];
                        $obj->icon = $item['icon'];
                
                        array_push($items, $obj);
                    }
                
                    $market->category = json_encode($items);
                    $market->pickup_location = $request->pickup_location;
                    $market->dropoff_location = $request->dropoff_location;
                    $market->delivery_days = $request->delivery_days;
                    $market->items = $request->items;
                    $market->booking_price = $request->booking_price;
                    $market->dropoff = $request->dropoff;
                    $market->item_image = json_encode($image);
                    $market->description = $request->description;
                    $market->needs = $request->needs;
                    $market->status = $request->status;
                    $market->uid = $this->userId;
                    $market->booking_date = $request->booking_date;
                    $market->save();
                }
                else
                {
                    $market = new Marketp();
                    $market->title = $request->title;
                    $size = $request->category;
                    // return count($size);
                    $items = [];
                    if(is_array($size))
                    {
                        foreach($size as $key=>$item)
                        {
                            $obj = new \stdClass();
                            $obj->category = $item['categoryItem'];
                            $obj->booking_attribute = $item['booking_attribute'];
                            $obj->quantity = $item['quantity'];
                            $obj->icon = $item['icon'];
                    
                            array_push($items, $obj);
                        }
                        $market->category = json_encode($items);
                    }
                    else
                    {
                        $market->category = $request->category;
                    }
                    $market->pickup_location = $request->pickup_location;
                    $market->dropoff_location = $request->dropoff_location;
                    $market->delivery_days = $request->delivery_days;
                    $market->items = $request->items;
                    $market->booking_price = $request->booking_price;
                    $market->dropoff = $request->dropoff;
                    $market->item_image = $request->item_image;
                    $market->description = $request->description;
                    $market->needs = $request->needs;
                    $market->status = $request->status;
                    $market->uid = $this->userId;
                    $market->booking_date = $request->booking_date;
                    $market->save();
                }
            }

            $notification = new Notification();
            $notification->msg = "You have added a new market place booking";
            $notification->title = "Success !!";
            $notification->uid = $this->userId;
            $notification->market_id = $market->id;
            $notification->save();

            $user = Client::where('id',$this->userId)->first();
            $notification = new Notification();
            $notification->msg = "You have recived new request from ".$user->name ;
            $notification->title = "New post recieved !!";
            $notification->uid = $this->userId;
            $notification->market_id = $market->id;
            $notification->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully Placed Booking',
            'data' => [$market]
        ], 201); 
    }

// Function to View Market Place Bookings By User

    public function viewMarketPlace()
    {
        $this->checkToken();
        $data = Marketp::where('uid',$this->userId)->orderBy('created_at','desc')->get();
        foreach($data as $value) {
            $value->category = json_decode($value->category);
            $value->proposal = Proposal::where('mid',$value->id)->get();
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

// Function to view Accepted Market Place Booking

    public function viewAcceptedMarket()
    {
        $this->checkToken();
        $data = MarketBooking::join('marketps','marketps.id','=','market_bookings.mid')
                              ->where('market_bookings.uid',$this->userId)
                              ->orWhere('market_bookings.receptionist_id',$this->userId)
                              ->orderBy('market_bookings.created_at','desc')
                              ->select('marketps.*','market_bookings.status as market_status','market_bookings.*')
                              ->get();
        foreach($data as $row)
        {
            $row->item_image = json_decode($row->item_image);
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

// Save Card Details Api

    public function saveCard(Request $request)
    {
        // VALIDATION OF Card DETAILS 
        $this->checkToken();
        $validator = Validator::make($request->all(), [
            // "uid" => "required",
            "name" => "required|max:255",
            "expire" => "required|max:255",
            "card_number" => "required|numeric",
            "cvv" => "required | max:3 | numeric",
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
                    $card = new Card();
                    $card->uid = $this->userId;
                    $card->name = $request->name;
                    $card->card_number = $request->card_number;
                    $card->expire = $request->expire;
                    $card->cvv = $request->cvv;
                    $card->save();
            }
        // return $card;

        return response()->json([
            'status' => true,
            'message' => 'Card successfully Added',
            'data' => [$card]
        ], 201);
    }

// Display Saved Cards to User

    public function viewCard()
    {
        $this->checkToken();
        $card = Card::where('uid',$this->userId)->orderBy('created_at','desc')->get();
        if($card->isEmpty())
        {
            return response()->json([
                'status' => true,
                'message' => 'No Saved Cards',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Your Cards',
                'data' => $card
            ], 201); 
        }
    }

// Function to Forgot Password Api

    public function forgotPassword(Request $request)
    {
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

    // public function sendDemoMail(Request $request)
    // {
    //     $email = $request->email;
    //     $otp = rand(1000,9999);
   
    //     $maildata = [
    //         'title' => 'Your OTP For Forget Password is :'. $otp,
    //         'url' => 'https://www.positronx.io'
    //     ];
  
    //     Mail::to($email)->send(new SendDemoMail($maildata));
   
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Mail has been sent successfully',
    //         'data' => [$otp]
    //     ]);
    // }

// Function to Perform Search By From & To Location

    public function search(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        if($from == '' && $to == '')
        {
            return response()->json([
                'status' => false,
                'message' => "NO Result Found",
                'data' => []
            ]);
        }
        else if($to == '')
        {
            $data = Schedule::where('from' , $from)->get();
            foreach($data as $value) {
                $value->item_type = json_decode($value->item_type);
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
        else if($from == '')
        {
            $data = Schedule::where('to' , $to)->get();
            foreach($data as $value) {
                $value->item_type = json_decode($value->item_type);
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
            $matchThese = ['from' => $from, 'to' => $to];
            $data = Schedule::where($matchThese)->get();
            foreach($data as $value) {
                $value->item_type = json_decode($value->item_type);
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

// Function to Perform Search By Date

    public function searchDate(Request $request)
    {
        $date = $request->date;

        if($date == '')
        {
            return response()->json([
                'status' => false,
                'message' => "NO Result Found",
                'data' => []
            ]);
        }
        else
        {
            $data = Schedule::where('departure_date','LIKE','%'.$date.'%')->get();
            foreach($data as $value) {
                $value->item_type = json_decode($value->item_type);
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

// Function to Perform Search By All The Data

    public function searchall(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $title = $request->title;
        $date = $request->date;
        $sdate = $request->arrivaldate;

        if($from == '' && $to == '' && $title == '' && $date == '' && $sdate == '')
        {
            return response()->json([
                'status' => false,
                'message' => "NO Result Found",
                'data' => []
            ]);
        }
        else if($from != '' && $to == '' && $title == '' && $date == '' && $sdate == '')
        {
            $data = DB::table('schedules')
            ->join('shipments','schedules.sid','=','shipments.id')
            ->select('schedules.*','shipments.companyname')
            ->where('schedules.permission_status','Approved')
            ->where('schedules.status','Open')
            ->where('schedules.from',$from)
            ->orderBy('schedules.created_at','desc')
            ->get();   
            $items = [];
            foreach($data as $row)
            {
                $obj = new \stdClass();
                $obj = json_decode($row->item_type);

                array_push($items, $obj);
            }                     
         $total = [];
         foreach($data as $key=>$value) {
            if($key == 0)
            {
                $value->item_type = json_decode($items[$key]);
            }
            else
            {
                $value->item_type = json_decode($items[$key]);
            }
                 $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from == '' && $to != '' && $title == '' && $date == '' && $sdate == '')
        {
            $data = DB::table('schedules')
                ->join('shipments','schedules.sid','=','shipments.id')
                ->select('schedules.*','shipments.companyname')
                ->where('schedules.permission_status','Approved')
                ->where('schedules.status','Open')
                ->where('schedules.to',$to)
                ->orderBy('schedules.created_at','desc')
                ->get();   
                $items = [];
                foreach($data as $row)
                {
                    $obj = new \stdClass();
                    $obj = json_decode($row->item_type);

                    array_push($items, $obj);
                }                     
             $total = [];
             foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                 $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from == '' && $to == '' && $title != '' && $date == '' && $sdate == '' )
        {
            // $data = Schedule::where('title','LIKE','%'.$title.'%')->get();
            $data = DB::table('schedules')
                ->join('shipments','schedules.sid','=','shipments.id')
                ->select('schedules.*','shipments.companyname')
                ->where('schedules.permission_status','Approved')
                ->where('schedules.status','Open')
                ->where('schedules.title','LIKE','%'.$title.'%')
                ->orWhere('shipments.companyname','LIKE','%'.$title.'%')
                ->orderBy('schedules.created_at','desc')
                ->get(); 
          //  dd($data);  
                $items = [];
                foreach($data as $row)
                {
                    $obj = new \stdClass();
                    $obj = json_decode($row->item_type);

                    array_push($items, $obj);
                }                     
             $total = [];
             foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                 $available_container = "0";
                 $total_container = "0";
          

                $schedule_items = DB::table('schedule_items')
                ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
                ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
                ->where('schedule_items.schedule_id',$value->id)
                ->get();
              // return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
                    $sf = $sitem->item_id;
                    $pf = $sitem->available;
                    $price = ['category' => $sf,'available' => $pf];
                    array_push($total,$price);
                }
        
            
            
            $value->available = $total;
          //  dd($total);
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
        else if($from == '' && $to == '' && $title == '' && $date != '' && $sdate == '' )
        {
            // $data = Schedule::where('departure_date','LIKE','%'.$date.'%')->get();
            $data = DB::table('schedules')
                ->join('shipments','schedules.sid','=','shipments.id')
                ->select('schedules.*','shipments.companyname')
                ->where('schedules.permission_status','Approved')
                ->where('schedules.status','Open')
                ->where('schedules.departure_date',$date)
                ->orderBy('schedules.created_at','desc')
                ->get();   
                $items = [];
                foreach($data as $row)
                {
                    $obj = new \stdClass();
                    $obj = json_decode($row->item_type);

                    array_push($items, $obj);
                }                     
             $total = [];
             foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
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
        else if($from == '' && $to == '' && $title == '' && $date == '' && $sdate != '' )
        {
            // $data = Schedule::where('departure_date','LIKE','%'.$date.'%')->get();
            $data = DB::table('schedules')
                ->join('shipments','schedules.sid','=','shipments.id')
                ->select('schedules.*','shipments.companyname')
                ->where('schedules.permission_status','Approved')
                ->where('schedules.status','Open')
                ->where('schedules.arrival_date',$sdate)
                ->orderBy('schedules.created_at','desc')
                ->get();   
                $items = [];
                foreach($data as $row)
                {
                    $obj = new \stdClass();
                    $obj = json_decode($row->item_type);

                    array_push($items, $obj);
                }                     
             $total = [];
             foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                 $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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

        else if($from != '' && $to != '' && $title == '' && $date == '' && $sdate == '' )
        {
            $matchThese = ['from' => $from, 'to' => $to];
            $data = DB::table('schedules')
                ->join('shipments','schedules.sid','=','shipments.id')
                ->select('schedules.*','shipments.companyname')
                ->where('schedules.permission_status','Approved')
                ->where('schedules.status','Open')
                ->where('schedules.from',$from)
                ->where('schedules.to',$to)
                ->orderBy('schedules.created_at','desc')
                ->get();   
                $items = [];
                foreach($data as $row)
                {
                    $obj = new \stdClass();
                    $obj = json_decode($row->item_type);

                    array_push($items, $obj);
                }                     
             $total = [];
             foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                 $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from != '' && $to != '' && $title == '' && $date != '' && $sdate == '' )
        {
            // $data = Schedule::where('departure_date','LIKE','%'.$date.'%')->get();
            $data = DB::table('schedules')
                ->join('shipments','schedules.sid','=','shipments.id')
                ->select('schedules.*','shipments.companyname')
                ->where('schedules.permission_status','Approved')
                ->where('schedules.status','Open')
                ->where('schedules.arrival_date',$sdate)
                ->orderBy('schedules.created_at','desc')
                ->get();   
                $items = [];
                foreach($data as $row)
                {
                    $obj = new \stdClass();
                    $obj = json_decode($row->item_type);

                    array_push($items, $obj);
                }                     
             $total = [];
             foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                 $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from != '' && $to != '' && $title != '' && $date == '' && $sdate == '' )
        {
            $matchThese = ['from' => $from, 'to' => $to];
            $data = DB::table('schedules')
                ->join('shipments','schedules.sid','=','shipments.id')
                ->select('schedules.*','shipments.companyname')
                ->where('schedules.permission_status','Approved')
                ->where('schedules.status','Open')
                ->where('schedules.from',$from)
                ->where('schedules.to',$to)
                ->orderBy('schedules.created_at','desc')
                ->get();   
                $items = [];
                foreach($data as $row)
                {
                    $obj = new \stdClass();
                    $obj = json_decode($row->item_type);

                    array_push($items, $obj);
                }                     
             $total = [];
             foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
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
        else if($from != '' && $to == '' && $title != '' && $date != '' && $sdate == '' )
        {
            $matchThese = ['from' => $from, 'departure_date' => $date, 'title' => $title];
            $data = DB::table('schedules')
            ->join('shipments','schedules.sid','=','shipments.id')
            ->select('schedules.*','shipments.companyname')
            ->where('schedules.permission_status','Approved')
            ->where('schedules.status','Open')
            ->where('schedules.from',$from)
            ->where('schedules.title',$title)
            ->where('schedules.departure_date',$date)
            ->orWhere('shipments.companyname','LIKE','%'.$title.'%')
            ->orderBy('schedules.created_at','desc')
            ->get();   
            $items = [];
            foreach($data as $row)
            {
                $obj = new \stdClass();
                $obj = json_decode($row->item_type);

                array_push($items, $obj);
            }                     
         $total = [];
            foreach($data as $key=>$value) {
            if($key == 0)
            {
                $value->item_type = json_decode($items[$key]);
            }
            else
            {
                $value->item_type = json_decode($items[$key]);
            }
                 $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from == '' && $to != '' && $title != '' && $date != '' && $sdate == '' )
        {
            $matchThese = ['to' => $to, 'departure_date' => $date, 'title' => $title];
            $data = DB::table('schedules')
                ->join('shipments','schedules.sid','=','shipments.id')
                ->select('schedules.*','shipments.companyname')
                ->where('schedules.permission_status','Approved')
                ->where('schedules.status','Open')
                ->where('schedules.to',$to)
                ->where('schedules.title',$title)
                ->where('schedules.departure_date',$date)
                ->orWhere('shipments.companyname','LIKE','%'.$title.'%')
                ->orderBy('schedules.created_at','desc')
                ->get();   
                $items = [];
                foreach($data as $row)
                {
                    $obj = new \stdClass();
                    $obj = json_decode($row->item_type);

                    array_push($items, $obj);
                }                     
                $total = [];
                foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                 $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from == '' && $to != '' && $title == '' && $date != '' && $sdate != '' )
        {
            $matchThese = ['to' => $to, 'departure_date' => $date];
            $data = DB::table('schedules')
                ->join('shipments','schedules.sid','=','shipments.id')
                ->select('schedules.*','shipments.companyname')
                ->where('schedules.permission_status','Approved')
                ->where('schedules.status','Open')
                ->where('schedules.to',$to)
                ->where('schedules.departure_date',$date)
                ->where('schedules.arrival_date',$sdate)
                ->orderBy('schedules.created_at','desc')
                ->get();   
                $items = [];
                foreach($data as $row)
                {
                    $obj = new \stdClass();
                    $obj = json_decode($row->item_type);

                    array_push($items, $obj);
                    }                     
                $total = [];
                foreach($data as $key=>$value) {
                    if($key == 0)
                    {
                        $value->item_type = json_decode($items[$key]);
                    }
                    else
                    {
                        $value->item_type = json_decode($items[$key]);
                    }
                 $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from != '' && $to == '' && $title != '' && $date == '' && $sdate != '' )
        {
            $matchThese = ['from' => $from, 'title' => $title];
            $data = DB::table('schedules')
            ->join('shipments','schedules.sid','=','shipments.id')
            ->select('schedules.*','shipments.companyname')
            ->where('schedules.permission_status','Approved')
            ->where('schedules.status','Open')
            ->where('schedules.from',$from)
            ->where('schedules.title',$title)
            ->where('schedules.arrival_date',$sdate)
            ->orWhere('shipments.companyname','LIKE','%'.$title.'%')
            ->orderBy('schedules.created_at','desc')
            ->get();   
            $items = [];
            foreach($data as $row)
            {
                $obj = new \stdClass();
                $obj = json_decode($row->item_type);

                array_push($items, $obj);
            }                     
            $total = [];
            foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
            {
                $value->item_type = json_decode($items[$key]);
            }
                 $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from != '' && $to == '' && $title == '' && $date == '' && $sdate != '' )
        {
            $matchThese = ['from' => $from, 'sdate' => $sdate];
            $data = DB::table('schedules')
            ->join('shipments','schedules.sid','=','shipments.id')
            ->select('schedules.*','shipments.companyname')
            ->where('schedules.permission_status','Approved')
            ->where('schedules.status','Open')
            ->where('schedules.from',$from)
            ->where('schedules.arrival_date',$sdate)
            ->orderBy('schedules.created_at','desc')
            ->get();   
            $items = [];
            foreach($data as $row)
            {
                $obj = new \stdClass();
                $obj = json_decode($row->item_type);

                array_push($items, $obj);
            }                     
            $total = [];
            foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
            {
                $value->item_type = json_decode($items[$key]);
            }
                 $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from == '' && $to == '' && $title != '' && $date != '' && $sdate == '' )
        {
            $matchThese = ['departure_date' => $date, 'title' => $title];
            $data = DB::table('schedules')
                ->join('shipments','schedules.sid','=','shipments.id')
                ->select('schedules.*','shipments.companyname')
                ->where('schedules.permission_status','Approved')
                ->where('schedules.status','Open')
                ->where('schedules.title',$title)
                ->where('schedules.departure_date',$date)
                ->orWhere('shipments.companyname','LIKE','%'.$title.'%')
                ->orderBy('schedules.created_at','desc')
                ->get();   
                $items = [];
                foreach($data as $row)
                {
                    $obj = new \stdClass();
                    $obj = json_decode($row->item_type);

                    array_push($items, $obj);
                }                     
             $total = [];
            foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                 $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from == '' && $to == '' && $title != '' && $date == '' && $sdate != '' )
        {
            $matchThese = ['departure_date' => $date, 'title' => $title];
            $data = DB::table('schedules')
                ->join('shipments','schedules.sid','=','shipments.id')
                ->select('schedules.*','shipments.companyname')
                ->where('schedules.permission_status','Approved')
                ->where('schedules.status','Open')
                ->where('schedules.title',$title)
                ->where('schedules.arrival_date',$sdate)
                ->orderBy('schedules.created_at','desc')
                ->get();   
                $items = [];
                foreach($data as $row)
                {
                    $obj = new \stdClass();
                    $obj = json_decode($row->item_type);

                    array_push($items, $obj);
                }                     
             $total = [];
            foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                 $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from == '' && $to == '' && $title == '' && $date != '' && $sdate != '' )
        {
            $matchThese = ['departure_date' => $date, 'arrival_date' => $sdate];
            $data = DB::table('schedules')
                ->join('shipments','schedules.sid','=','shipments.id')
                ->select('schedules.*','shipments.companyname')
                ->where('schedules.permission_status','Approved')
                ->where('schedules.status','Open')
                ->where('schedules.departure_date',$date)
                ->where('schedules.arrival_date',$sdate)
                ->orderBy('schedules.created_at','desc')
                ->get();   
                $items = [];
                foreach($data as $row)
                {
                    $obj = new \stdClass();
                    $obj = json_decode($row->item_type);

                    array_push($items, $obj);
                }                     
             $total = [];
            foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                 $available_container = "0";
            $total_container = "0";
            $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from != '' && $to != '' && $title == '' && $date == '' && $sdate != '' )
        {
            $matchThese = ['from' => $from, 'to' => $to];
            $data = DB::table('schedules')
            ->join('shipments','schedules.sid','=','shipments.id')
            ->select('schedules.*','shipments.companyname')
            ->where('schedules.permission_status','Approved')
            ->where('schedules.status','Open')
            ->where('schedules.from',$from)
            ->where('schedules.to',$to)
            ->where('schedules.arrival_date',$sdate)
            ->orderBy('schedules.created_at','desc')
            ->get();   
            $items = [];
            foreach($data as $row)
            {
                $obj = new \stdClass();
                $obj = json_decode($row->item_type);

                array_push($items, $obj);
            }                     
            $total = [];
            foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                 $available_container = "0";
                $total_container = "0";
                $schedule_items = DB::table('schedule_items')
                ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
                ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
                ->where('schedule_items.schedule_id',$value->id)
                ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from != '' && $to != '' && $title != '' && $date == '' && $sdate != '' )
        {
            $matchThese = ['from' => $from, 'to' => $to];
            $data = DB::table('schedules')
            ->join('shipments','schedules.sid','=','shipments.id')
            ->select('schedules.*','shipments.companyname')
            ->where('schedules.permission_status','Approved')
            ->where('schedules.status','Open')
            ->where('schedules.from',$from)
            ->where('schedules.to',$to)
            ->where('schedules.title',$title)
            ->where('schedules.arrival_date',$sdate)
            ->orderBy('schedules.created_at','desc')
            ->get();   
            $items = [];
            foreach($data as $row)
            {
                $obj = new \stdClass();
                $obj = json_decode($row->item_type);

                array_push($items, $obj);
            }                     
            $total = [];
            foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                 $available_container = "0";
                $total_container = "0";
                $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from == '' && $to != '' && $title != '' && $date != '' && $sdate != '' )
        {
            $matchThese = ['from' => $from, 'to' => $to];
            $data = DB::table('schedules')
            ->join('shipments','schedules.sid','=','shipments.id')
            ->select('schedules.*','shipments.companyname')
            ->where('schedules.permission_status','Approved')
            ->where('schedules.status','Open')
            ->where('schedules.departure_date',$date)
            ->where('schedules.to',$to)
            ->where('schedules.title',$title)
            ->where('schedules.arrival_date',$sdate)
            ->orderBy('schedules.created_at','desc')
            ->get();   
            $items = [];
            foreach($data as $row)
            {
                $obj = new \stdClass();
                $obj = json_decode($row->item_type);

                array_push($items, $obj);
            }                     
            $total = [];
            foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                 $available_container = "0";
                $total_container = "0";
                $schedule_items = DB::table('schedule_items')
            ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
            ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
            ->where('schedule_items.schedule_id',$value->id)
            ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from == '' && $to == '' && $title != '' && $date != '' && $sdate != '' )
        {
            $matchThese = ['from' => $from, 'to' => $to];
            $data = DB::table('schedules')
            ->join('shipments','schedules.sid','=','shipments.id')
            ->select('schedules.*','shipments.companyname')
            ->where('schedules.permission_status','Approved')
            ->where('schedules.status','Open')
            ->where('schedules.departure_date',$date)
            ->where('schedules.title',$title)
            ->where('schedules.arrival_date',$sdate)
            ->orderBy('schedules.created_at','desc')
            ->get();   
            $items = [];
            foreach($data as $row)
            {
                $obj = new \stdClass();
                $obj = json_decode($row->item_type);

                array_push($items, $obj);
            }                     
            $total = [];
            foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                 $available_container = "0";
                $total_container = "0";
                $schedule_items = DB::table('schedule_items')
                ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
                ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
                ->where('schedule_items.schedule_id',$value->id)
                ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
        else if($from == '' && $to != '' && $title != '' && $date == '' && $sdate != '' )
        {
            $matchThese = ['from' => $from, 'to' => $to];
            $data = DB::table('schedules')
            ->join('shipments','schedules.sid','=','shipments.id')
            ->select('schedules.*','shipments.companyname')
            ->where('schedules.permission_status','Approved')
            ->where('schedules.status','Open')
            ->where('schedules.to',$to)
            ->where('schedules.title',$title)
            ->where('schedules.arrival_date',$sdate)
            ->orderBy('schedules.created_at','desc')
            ->get();   
            $items = [];
            foreach($data as $row)
            {
                $obj = new \stdClass();
                $obj = json_decode($row->item_type);

                array_push($items, $obj);
            }                     
            $total = [];
            foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                 $available_container = "0";
                $total_container = "0";
                $schedule_items = DB::table('schedule_items')
                ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
                ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
                ->where('schedule_items.schedule_id',$value->id)
                ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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
            $matchThese = ['from' => $from, 'to' => $to, 'title' => $title, 'departure_date' => $date, 'arrival_date' => $sdate];
            // $data = Schedule::where($matchThese)->get();
            $data = DB::table('schedules')
                ->join('shipments','schedules.sid','=','shipments.id')
                ->select('schedules.*','shipments.companyname')
                ->where('schedules.permission_status','Approved')
                ->where('schedules.status','Open')
                ->where('schedules.from',$from)
                ->where('schedules.to',$to)
                ->where('schedules.title',$title)
                ->where('schedules.departure_date',$date)
                ->where('schedules.arrival_date',$sdate)
                ->orWhere('shipments.companyname','LIKE','%'.$title.'%')
                ->orderBy('schedules.created_at','desc')
                ->get();   
                $items = [];
                foreach($data as $row)
                {
                    $obj = new \stdClass();
                    $obj = json_decode($row->item_type);

                    array_push($items, $obj);
                }                     
             $total = [];
             foreach($data as $key=>$value) {
                if($key == 0)
                {
                    $value->item_type = json_decode($items[$key]);
                }
                else
                {
                    $value->item_type = json_decode($items[$key]);
                }
                // $value->item_type = json_decode($value->item_type);
                 $available_container = "0";
                $total_container = "0";
                $schedule_items = DB::table('schedule_items')
                ->join('schedule_category','schedule_category.category_id','=','schedule_items.id')
                ->select('schedule_category.*','schedule_items.item_id','schedule_items.icon')
                ->where('schedule_items.schedule_id',$value->id)
                ->get();
            //   return $schedule_items;
                foreach($schedule_items as $key=>$sitem)
                {
                    $available_container += $sitem->available;
                    $total_container += $sitem->item_quantity;
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

// socialLogin

    public function client_socialLogin(Request $request)
    {
        $check = Shipment::where('email',$request->email)->get();
        if(!$check->isEmpty())
        {
            $response = [
                'status' => false,
                'message' => 'Email Exist in Shipment',
                'data' => []
            ];

            return response()->json($response, 200);
        }
        else 
        {

            $validator = Validator::make($request->all(), [
                // 'name' => 'required|min:3',
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
            $loginData['provider'] = $request->provider;
            $loginData['status'] = "Not Approve";
            $loginData['roles'] = '1c';
            $loginData['type'] = "clients";
            if($request->provider == 'google')
            {
                $loginData['google_id'] = $request->social_token;
            }
            else
            {
                $loginData['facebook_id'] = $request->social_token;
            }
            
            $client = Client::where('email',$request->email)->where('roles','=','1c')->first();
            
            if(!isset($client->id)) 
            {
            $client = Client::create($loginData);
            } 

            $token = $client->createToken('my-app-token')->accessToken;

            $client->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;

            $response = [
                'status' => true,
                'message' => 'Login Successful',
                'data' => [$client]
            ];

            return response()->json($response, 200);

        }
        
      
    }

// Funciton to Get All COupons

    public function allCoupons()
    {
        $data = Coupon::where('status',1)->get();
        if($data->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No Coupons',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'All Coupons',
                'data' => $data
            ], 201); 
        }

    }

// Function to Check Coupon

    public function checkCoupon(Request $request)
    {

        $this->checkToken();
        //print_r($this->userId);

        $coupon_code = $request->coupon_code;
        $amount = $request->amount;
        $uid = array();                             // Create Array to store userId
        array_push($uid,$this->userId);             // Push UserId in Array
        $data = Coupon::where('coupon_code',$coupon_code)->where('status',1)->first();
        // return json_decode($data->users);
        if($data->status == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'Coupon is Expired',
                'data' => []
            ]);
        }
        else if($data->once == 1)
        {
            if(!in_array($this->userId,json_decode($data->users)))  // Check Usser Id is Present Or Not. if Not Present then Update
            {
                $uid = json_decode($data->users);
                array_push($uid,$this->userId);
                Coupon::where('coupon_code',$coupon_code)->update(['users' => json_encode($uid)]);
                if($data->coupon_type == "percentage")
                {
                    $percentage = ($data->coupon_amount/100) * $amount;
                    $final_amount = $amount - $percentage;
                    $discount = $percentage;
                }
                else if($data->coupon_type == "fix_amount")
                {
                    $final_amount = $amount - $data->coupon_amount;
                    $discount = $data->coupon_amount;
                }        
                $data->final_amount = (string) $final_amount;

                $booking = Booking::where('id',$request->order_id)->update(['discount' => $discount]);
        
                    return response()->json([
                        'status' => true,
                        'message' => 'Coupon Details',
                        'data' => [$data]
                    ], 201); 
            }
            else if(in_array($this->userId,json_decode($data->users)))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Coupon can only be used once',
                    'data' => []
                ]);
            }
            
        }

    }

// Function to Get Receptionist Details BY Email

    public function receptionistDetails(Request $request)
    {
        $email = $request->email;
        $data = Client::where('email',$email)->first();
        if($data == '')
        {
            return response()->json([
                'status' => false,
                'message' => 'No Receptionist',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Receptinist Information',
                'data' => $data
            ], 201); 
        }
    }

// Function to Change Status of booking by Recepitonist

    public function receptionistStatus(Request $request)
    {
        $this->checkToken();
        $data = Client::find($this->userId);
        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'You are Not authorized',
                'data' => []
            ]);
        }
        else
        {
            Booking::where('id',$request->booking_id)->update(['status' => $request->booking_status,'receptionist_image' => $request->receptionist_image,'receptionist_comment' => $request->comment]);
            $data = Booking::where('id',$request->booking_id)->select('status','receptionist_image','receptionist_comment')->get();
            foreach($data as $row)
            {
                $row->receptionist_image = json_decode($row->receptionist_image);
            }
            return response()->json([
                'status' => true,
                'message' => 'Status Changed',
                'data' => $data
            ], 201); 
        }
    }

// Function For get Shipment COmpany by Searching its name and companyname in Client Panel

    public function shipmentnameSearch(Request $request)
    {
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
            $data = Shipment::where('name','LIKE','%'.$name.'%')->orWhere('companyname','LIKE','%'.$name.'%')->get();
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

// Function for viewing all adverstisment uploaded by super admin

    public function viewAdvertisment()
    {
        $data = Advertisment::all();
        foreach($data as $row)
        {
            $row->image = 'http://44.194.48.17//advertisment/'.$row->image;
        }
        if(count($data) == 0)
        {
            return response()->json([
                'status' => false,
                'message' => "NO Advertisment",
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Advertisment.',
                'data' => $data
            ]);
        }
    }

// Function for viewing all proposals by shipment company to client

    public function viewProposal()
    {
        $this->checkToken();
        $data = Proposal::where('uid',$this->userId)->get();
        foreach($data as $row)
        {
            $row->shipment = Shipment::where('id',$row->sid)->get();
            $row->market = Marketp::where('id',$row->mid)->get();
        }
        if(count($data) == 0)
        {
            return response()->json([
                'status' => false,
                'message' => "NO Proposals",
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Proposals.',
                'data' => $data
            ]);
        }
    }

// Function for deactivating Receptionist profile

    public function deactivateProfileReceptionist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "receptionist_id" => "required",
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
                $client = Client::where('id', $request->receptionist_id)->where('roles','=','2r')->update(['status' => 'Deactivate'  ]);
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
                $client = Client::where('id', $request->receptionist_id)->where('roles','=','2r')->update(['status' => ''  ]);
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

// Function to acceptProposal by shipment company

    public function acceptProposal(Request $request)
    {
        $this->checkToken();
        Marketp::where('id',$request->mid)->update([
            'sid' => $request->sid,
            'status' => 'accepted',
            'booking_price' => $request->final_amount
        ]);

        $proposal = Proposal::where('id',$request->proposal_id)->first();

        $market = new MarketBooking();
        $market->uid = $this->userId;
        $market->mid = $request->mid;
        $market->sid = $request->sid;
        $market->receptionist_id = $request->receptionist_id;
        $market->departure_id = $proposal->departure_id;
        $market->arrival_id = $proposal->arrival_id;
        $market->status = 'accepted';
        $market->save();

        $user = Client::where('id',$this->userId)->first();
        $market = Marketp::where('id',$request->mid)->first();
        $notification = new Notification();
        $notification->msg = "Your Proposal is accepted by ".$user->name." For ".$market->title." Market Place Booking";
        $notification->title = "Proposal Accepted !!";
        $notification->sid = $request->sid;
        $notification->market_id = $market->id;
        $notification->save();

        return response()->json([
            'status' => true,
            'message' => 'Accepted Proposal',
            'data' => [['marketbooking_id' => $market->id]]
        ] ,201); 

    }

// Function to Get Receptionist Stats

    public function receptionistStats()
    {
        $this->checkToken();
        $total_orders = Booking::where('receptionist_id', $this->userId)->count();
        $assign_orders = Booking::where('receptionist_id', $this->userId)->where('status','=','assigned to agent')->count();
        $deliver_to_departure = Booking::where('receptionist_id', $this->userId)->where('status','=','delivered to warehouse')->count();
        $deliver_to_arrival = Booking::where('receptionist_id', $this->userId)->where('status','=','delivered to arrival')->count();
        $completed = Booking::where('receptionist_id', $this->userId)->where('status','=','received by receptionist')->count();

        $data = ['total_orders' => $total_orders,'assign_orders' => $assign_orders,'deliver_to_departure' => $deliver_to_departure,'deliver_to_arrival' => $deliver_to_arrival, 'completed_orders' => $completed];

        $data1 = ['total_orders' => 0,'assign_orders' => 0,'deliver_to_departure' => 0, 'deliver_to_arrival' => 0,'completed_orders' =>0];

        if($total_orders == 0)
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

// Function to Perform search By Receptionist in Bookings

    public function receptionistSearch(Request $request)
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
            $data = Booking::where('title','LIKE','%'.$title.'%')->where('receptionist_id', $this->userId)->orderBy('created_at','desc')->get();
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
            $data = Booking::where('status',$stats)->where('receptionist_id', $this->userId)->orderBy('created_at','desc')->get();
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
            $data = Booking::where($matchThese)->where('receptionist_id', $this->userId)->orderBy('created_at','desc')->get();
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

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => []
            ]);
        }

        if($request->type == 'client'){

            $response = Client::where('email',$request->email)->where('otp', $request->otp)->first();

        }else{
            $response = Shipment::where('email',$request->email)->where('otp', $request->otp)->first();
        }

        
        if(!$response)
        {
            return response([
                'status'=>false,
                'message'=>"Wrong OTP"
            ],404);
        }

    

        $response = [
            'status' => true,
            'message' => 'Otp Verify Successful',
        ];

        return response()->json($response, 200);
    }



    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => []
            ]);
        }


        $otp = rand(1000,9999);

        if($request->type == 'client'){

            Client::where('email','=',$request->email)->update(['otp'=>$otp]);

        }else{
            Shipment::where('email','=',$request->email)->update(['otp'=>$otp]);
        }

        $maildata = [

                'title' => 'Your OTP is : '. $otp,

                            ];
    
        Mail::to($request->email)->send(new SendOtpMail($maildata));

        $response = [
            'status' => true,
            'message' => 'Otp Send Successful On Registered Mailid.',
        ];

        return response()->json($response, 200);
    }


    public function settings()
    {       
        $data = Setting::where('id',1)->first();
        return response()->json([
                'status' => true,
                'message' => 'Retrive Data',
                'data' => $data
            ], 201); 
        
    }


    public function getTimer()
    {       
        $time = DB::table('timer')->orderBy('created_at','desc')->first();
        return response()->json([
                'status' => true,
                'message' => 'Retrive Data',
                'data' => $time
            ], 201); 
        
    }


    // public function subscriptionPlanList1()
    // {
    //     $this->checkToken();
    //     $data = DB::table('subscriptionplans')->select('*')->get();
    //     //echo "<pre>";        print_r($data);

    //      $plan_obj =array();

    //     foreach ($data as $list) {
    //             $plan_obj[$list->id]["id"] =  $list->id;
    //             $plan_obj[$list['id']]["name"] =  $list->name;
    //             $plan_obj[$list['id']]["price"] =  $list->price;
    //             $plan_obj[$list['id']]["duration"] =  $list->duration;
    //             $plan_obj[$list['id']]["description"] =  json_decode($list->description);
    //     } 


    //       // foreach ($data as $list) {
    //     //         $plan_obj[$list['id']]["id"] =  $list->id;
    //     //         $plan_obj[$list['id']]["name"] =  $list->name;
    //     //         $plan_obj[$list['id']]["price"] =  $list->price;
    //     //         $plan_obj[$list['id']]["duration"] =  $list->duration;
    //     //         $plan_obj[$list['id']]["description"] =  json_decode($list->description);
    //     // } 



        
    //      // echo "<pre>";   print_r($plan_obj);  die();

    //     $plan_array=[];
    //     foreach ($plan_obj as $key => $value) {
    //         array_push($plan_array,$value);
    //     }

    //     return response()->json([
    //             'status' => true,
    //             'message' => 'Plan List',
    //             'data' => $plan_array
    //         ], 200);
    // }

    // public function subscriptionPlanList()
    // {
    //     $this->checkToken();
    //     $data = DB::table('subscriptionplans')->select('id','name','price','duration','description','plan_features')->whereNull('deleted_at')->get();
    //     foreach ($data as $key => $value) {
    //         $value->description = json_decode($value->description);
    //     }
    //     //echo "<pre>";        print_r($item);
    //     return response()->json([
    //             'status' => true,
    //             'message' => 'Plan List',
    //             'data' => $data
    //         ], 200);
    // }


    public function subscriptionPlanList()
    {
        $this->checkToken();
        $data = DB::table('subscriptionplans')->select('id','name','price','duration','description','plan_features')->whereNull('deleted_at')->get()->toArray();

        //echo "<pre>";
        $data = json_decode(json_encode($data), true);
        //print_r($data);
        //die();

        $plan_obj = array();

            foreach ($data as $plan) {
                        
                        if(!isset($plan_obj[$plan['id']])){

                            $plan_obj[$plan['id']] = [];
                            $plan_obj[$plan['id']]["id"] = $plan['id'];
                            $plan_obj[$plan['id']]["name"] = $plan['name'];
                            $plan_obj[$plan['id']]["price"] = $plan['price'];
                            $plan_obj[$plan['id']]["duration"] = $plan['duration'];
                            //$plan_obj[$plan['id']]["description"] = json_decode($plan['description']);
                            $plan_obj[$plan['id']]["description"] = [];
                           
                        }

                        if(!empty($plan['plan_features'])){

                            $pf=explode(",",$plan['plan_features']);

                            foreach ($pf as $cplan) {

                                $fdata = DB::table('planfeatures')->select('name')->where('id','=',$cplan)->first();

                                $plan_obj[$plan['id']]["description"][] = array(
                                    'feature' => $fdata->name, 
                                );
                            
                        }

                        }

                        
                    }

                    $plan_array=[];
                    foreach ($plan_obj as $key => $value) {
                        array_push($plan_array,$value);
                    }
        
        return response()->json([
                'status' => true,
                'message' => 'Plan List',
                'data' => $plan_array
            ], 200);
    }

}