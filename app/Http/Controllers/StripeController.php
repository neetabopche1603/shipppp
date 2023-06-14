<?php
 namespace App\Http\Controllers;

   

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Marketp;
use App\Models\MarketBooking;
use App\Models\BookingItem;
use DB;
use App\Models\Item;
use App\Models\Schedule;
use App\Models\ScheduleItem;
use App\Models\Client;
use App\Models\Notification;
use App\Models\Shipment;
use App\Models\SubscriptionDetail;
use App\Models\ScheduleCategory;
use Carbon\Carbon;
use Session;

// use Stripe;
use Stripe\OAuth;
// use Stripe\Stripe;
use Stripe;
// use Stripe\OAuth;
// use Stripe\Stripe;
use Stripe\StripeClient;

   

class StripeController extends Controller

{

    private $stripe;
    // public function __construct()
    // {
    //     $this->stripe = new StripeClient(config('stripe.api_keys.secret_key'));
    //     Stripe::setApiKey(config('stripe.api_keys.secret_key'));
    // }

    public function index()
    {
        $queryData = [
            'response_type' => 'code',
            'client_id' => config('stripe.client_id'),
            'scope' => 'read_write',
            'redirect_uri' => config('stripe.redirect_uri')
        ];
        $connectUri = config('stripe.authorization_uri').'?'.http_build_query($queryData);
        return view('index', compact('connectUri'));
    }

    public function redirect(Request $request)
    {
        $token = $this->getToken($request->code);
        if(!empty($token['error'])) {
            $request->session()->flash('danger', $token['error']);
            return response()->redirectTo('/');
        }
        $connectedAccountId = $token->stripe_user_id;
        $account = $this->getAccount($connectedAccountId);
        if(!empty($account['error'])) {
            $request->session()->flash('danger', $account['error']);
            return response()->redirectTo('/');
        }
        return view('account', compact('account'));
    }

    /**

     * success response method.

     *

     * @return \Illuminate\Http\Response

     */

    public function stripe()

    {
        // $data = Booking::where('id',$_GET['order_id'])->first();
        if($_GET['type'] == 'booking'){

            $data = DB::table('booking_category')->where('booking_id',$_GET['order_id'])->get();
            $total = 0;
            foreach ($data as $key => $value) {
                $val = $value->quantity * $value->shippingfee;
                $res = $val + $value->pickupfee;
                $total += $res;
            }

            $bdata = DB::table('bookings')->where('id',$_GET['order_id'])->first();
            $sdata = DB::table('settings')->where('id',1)->first();
            $total =  $total  -  $bdata->discount +  $sdata->tax;

        }else if($_GET['type'] == 'subscription'){

            $data = DB::table('subscriptionplans')->where('id',$_GET['order_id'])->first();
            $total = $data->price;

        }else{

            $data = DB::table('marketps')->where('id',$_GET['order_id'])->first();
            $total = $data->booking_price;
        }
        //echo $_GET['order_id']; // dd($total);
        return view('stripe',compact('total'));

    }


    public function stripemob()

    {
        // $data = Booking::where('id',$_GET['order_id'])->first();
        if($_GET['type'] == 'booking'){

            $data = DB::table('booking_category')->where('booking_id',$_GET['order_id'])->get();
            $total = 0;
            foreach ($data as $key => $value) {
                $val = $value->quantity * $value->shippingfee;
                $res = $val + $value->pickupfee;
                $total += $res;
            }

            $bdata = DB::table('bookings')->where('id',$_GET['order_id'])->first();
            $sdata = DB::table('settings')->where('id',1)->first();
            $total =  $total  -  $bdata->discount +  $sdata->tax;

        }else if($_GET['type'] == 'subscription'){

            $data = DB::table('subscriptionplans')->where('id',$_GET['order_id'])->first();
            $total = $data->price;

        }else{

            $data = DB::table('marketps')->where('id',$_GET['order_id'])->first();
            $total = $data->booking_price;
        }
        //echo $_GET['order_id']; // dd($total);
        return view('stripemob',compact('total'));

    }

  

    /**

     * success response method.

     *

     * @return \Illuminate\Http\Response

     */

    public function stripePost(Request $request)

    {
        // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        // $customer = \Stripe\Customer::create([
        //     'email' => 'email@example.com',
        //     'name' => 'test'
        // ]);
        // $paymentIntent = \Stripe\PaymentIntent::create([
        //     'amount' => 100 * 10,
        //     'currency' => 'usd',
        //     'payment_method_types' => ['card'],
        //     'customer' => $customer->id,
        //     'description' => 'Shipment Booking Payment',
        //     'payment_method_data' => [
        //         'type' => 'card',
        //         'card' => [
        //             'token' => $request->stripeToken
        //         ]
        //     ],
        // ]);

        // $transfer = \Stripe\Transfer::create(array(
        //      "amount" => 100 * 10,
        //      "currency" => "usd",
        //      "destination" => "acct_1KwQWbBTwiUripAy",
        //      "transfer_group" => "ORDER_95"
        // ));

        // Session::flash('success', 'Payment successful!');

        // return back();
        // die;
        //echo "<pre>";
        //dd($request);

        
        $id = $request->order_id;
        $total_amount = $request->amount;
        $shipuid = $request->uid;
        // $transaction_id = '1234';
        $card_type = 'debit';
        if($request->type == 'booking')
        {
            $book = Booking::where('id',$id)->first();
            $user = Client::where('id',$book->uid)->first();


            $des = 'Shipment Booking Payment';
        }else if($request->type == 'subscription')
        {
            
            $user = Shipment::where('id',$shipuid)->first();
            //print_r($user);
            $des = 'Subscription plan Payment';

            $planDetails = DB::table('subscriptionplans')->where('id',$id)->first();

            $planPrice = $planDetails->price;
            $planName = $planDetails->name;
            $planInterval = $planDetails->intervals;
            $planId = $planDetails->stripe_plan_id;
            $tableId = $planDetails->id;

        }
        else
        {
            $book = MarketBooking::where('mid',$id)->first();
            // dd($book);
            $user = Client::where('id',$book->uid)->first();
            $des = 'Shipment Booking Payment';
        }
        try {
            // dd(config('stripe.api_keys.secret_key'));
            $stripe = new \Stripe\StripeClient(config('stripe.api_keys.secret_key'));

            if($request->type == 'booking')
            {
            $customer = $stripe->customers->create([
                // 'email' => 'email@example.com',
                'email' => $user->email,
                'name' => $user->name
                // 'name' => 'test'
            ]);
            $payemntid = $stripe->paymentIntents->create([
                'amount' => 100 * $total_amount,
                'currency' => env('CURRENCY'),
                'payment_method_types' => ['card'],
                'customer' => $customer->id,
                'description' => $des,
                'payment_method_data' => [
                    'type' => 'card',
                    'card' => [
                        'token' => $request->stripeToken
                    ]
                ]
            ]);
            //dd($payemntid);
            $transaction_id = $payemntid->id;
        }else if($request->type == 'subscription'){
            $address = array("country" => "India", "line1" => 56/2);
            // $address = array("country" => $request->country, "line1" => $request->line1); 

            $customer=  $stripe->customers->create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone'=> $user->phone,
                    'source' => $request->stripeToken,
                    'address' => $address,
                ]);

                $pay=$stripe->paymentMethods->attach(
                    $customer->default_source,
                    ['customer' => $customer->id]
                );
                $stripe->customers->update(
                    $customer->id
                );

                   $subscription=$stripe->subscriptions->create([
                    'customer' => $customer->id,
                    'items' => [
                      ['plan' => $planId],
                    ],
                  ]);
                 // echo "<pre>";print_r($subscription);die;
                $subscriptions2=$stripe->subscriptions->retrieve(
                    $subscription->id,
                    []
                );
                
                //echo "<pre>"; print_r($subscriptions2); die();

                $addDetails = SubscriptionDetail::create([
                    'subscription_id' => $subscriptions2->id,
                    'stripe_customer_id' => $subscriptions2->customer,
                    'stripe_plan_id' => $subscriptions2->plan->id,
                    'payer_email' => $user->email,
                    'plan_interval' => $planInterval,
                    'plan_type' => $planName,
                    'plan_id' => $tableId,
                    'user_id' => $user->id,
                    'amount' =>  $planPrice,
                    'currency' => env('CURRENCY'),
                    'plan_start_date' => date('Y-m-d H:i:s',$subscriptions2->current_period_start),
                    'plan_end_date' => date('Y-m-d H:i:s',$subscriptions2->current_period_end),
                    'status' => $subscriptions2->status,
                    'txn_id' => $subscriptions2->latest_invoice,
                    'invoice_id' => $subscriptions2->latest_invoice,
                ]);


        }else{

            $customer = $stripe->customers->create([
                // 'email' => 'email@example.com',
                'email' => $user->email,
                'name' => $user->name
                // 'name' => 'test'
            ]);
            $payemntid = $stripe->paymentIntents->create([
                'amount' => 100 * $total_amount,
                'currency' => env('CURRENCY'),
                'payment_method_types' => ['card'],
                'customer' => $customer->id,
                'description' => $des,
                'payment_method_data' => [
                    'type' => 'card',
                    'card' => [
                        'token' => $request->stripeToken
                    ]
                ]
            ]);
            //dd($payemntid);
            $transaction_id = $payemntid->id;

        }

   
            //dd($paymentresponse);
            if($request->type == 'booking')
            {
               $this->updateBooking($id,$transaction_id,$card_type,$total_amount);
            }else if($request->type == 'subscription')
            {
               
                $data = DB::table('subscriptionplans')->where('id',$id)->first();
                $duration = $data->duration;
                $edate = date('Y-m-d', strtotime(' +'.$duration.' day'));
                $resdata = Shipment::where('id',$shipuid)->update(['plan_id' => $id, 'expire_date' => $edate]);

               //echo $resdata;
            }
            else
            {
                MarketBooking::where('mid',$id)->update(['transaction_id' => $transaction_id, 'card_type' => $card_type,
                'total_amount' => $total_amount]);
            }

            $response = response()->json(['success'=>true,'message'=>'payment done successfully'], 200);
            // return response()->json(['success'=>true,'message'=>'payment done successfully'], 200);

        } catch(\Exception $e) {
             return response()->json(
                    [
                        'success'=>false,
                        'message'=> $e->getMessage()
                    ], 200
            );
        }
 

        Session::flash('success', 'Payment successful!');
        return back();

    }

    public function payCompany($id)
    {
        return view('stripe');
    }

    public function updateBooking($id,$transaction_id,$card_type,$total_amount)
    {
        $time = DB::table('timer')->orderBy('created_at','desc')->first();
        $book =  Booking::where('id',$id)->first();
        $date = $book->created_at;
        $carbon_date = Carbon::parse($date);
        $carbon_date->addHours($time->hours);

        $booking = Booking::where('id',$id)->update(['transaction_id' => $transaction_id,
                                                              'status' => 'Confirmed',
                                                              'card_type' => $card_type,
                                                              'total_amount' => $total_amount,
                                                          'expired_at' => $carbon_date]);

        $bookitem = DB::table('booking_category')->select('*')->where('booking_id','=',$id)->get();
        if(count($bookitem) > 0)
        {
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
            
            $coupon_code = isset($coupon_code) ? $coupon_code : '';
            if(is_null($coupon_code))
            {
                $this->checkToken();
                $coupon_code = $coupon_code;
                $amount = $amount;
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
                $book = Booking::where('id',$id)->first();
                $sche = Schedule::where('id', $book->schedule_id)->first();
                $user = Client::where('id', $book->uid)->first();
                $notification = new Notification();
                $notification->msg = "You have recieve a new booking ";
                $notification->title = "Booking request received !!";
                $notification->sid = $sche->sid;
                $notification->booking_id = $id;
                $notification->save();

                $notification = new Notification();
                $notification->msg = $user->name . "has assigned you as receptionist ";
                $notification->title = "Great !!";
                $notification->uid = $book->receptionist_id;
                $notification->booking_id = $id;
                $notification->save();

                $notification = new Notification();
                $notification->msg = "Payment successfully done ";
                $notification->title = "Success !!";
                $notification->uid = $book->uid;
                $notification->booking_id = $id;
                $notification->save();
        }
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

}