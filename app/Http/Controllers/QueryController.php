<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Notification;
use App\Models\ScheduleCategory;
use Carbon\Carbon;
use App\Models\Query;
use App\Mail\SendPlanExpireMail;
use Mail;


class QueryController extends Controller
{

// Function to post Client Review Api With {Role=1} 1 for Client

    public function clientQuery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "querys" => "required",
            "date" => "required",
        ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            else
            {
                $querys = new Query();
                $querys->name = $request->name;
                $querys->query = $request->querys;
                $querys->date = $request->date;
                $querys->uid = $request->uid;
                $querys->sid = $request->sid;
                $querys->status = "new";
                $querys->save();
            }

        return response()->json([
            'message' => 'Query Added Successfully',
            'querys' => $querys
        ], 201);
    }

// Function to post Client Review Api With {Role=1} 1 for Client

    public function shipmentQuery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "querys" => "required",
            "date" => "required",
        ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            else
            {
                $querys = new Query();
                $querys->name = $request->name;
                $querys->query = $request->querys;
                $querys->date = $request->date;
                $querys->uid = $request->uid;
                $querys->sid = $request->sid;
                $querys->status = "new";
                $querys->save();
            }

        return response()->json([
            'message' => 'Query Added Successfully',
            'querys' => $querys
        ], 201);
    }

// Api to accept image and return URl

    public function imageUrl(Request $request)
    {
            $destinationPath = public_path('/image');
            if($request->hasfile('file'))
            {
                $image = $request->file('file');
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($image->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                $destinationPath =  public_path('/image');
                $image_url = $destinationPath.$image_full_name;
                $image->move($destinationPath,$image_full_name);
                $img = $image_full_name;

                DB::table('imageurl')->insert(
                    ['url' => $img]
                );
                $image = 'http://44.194.48.17//image/'.$img;

            return response()->json([
                'status' => true,
                'message' => 'Image Url',
                'data' => [['image' => $image]]
            ], 201); 

            }
    }

// Function to Check Cron Job

    public function cronJob()
    {
        // $book = Booking::where('created_at', '>=', Carbon::now()->subDay())->where('status','=','Confirmed')->get();
        // $book = Booking::olderThanOneDay()->get();

        $time = DB::table('timer')->orderBy('created_at','desc')->first();
        // $book = Booking::where('created_at', '<=', Carbon::now()->subHours($time->hours))->where('accepted','=',0)->get();
        //$book = Booking::where('expired_at', '>=', Carbon::now())->where('accepted','=',0)->get();
        $book = Booking::where('expired_at', '<=', Carbon::now())->where('accepted','=',0)->where('rejected','=',0)->get();
        foreach($book as $row)
        {
            if($row->accepted != 1)
            {
                Booking::where('id',$row->id)->update(['rejected' => 1, 'accepted' => 0,'status' => 'Cancelled']);

                $bookitem = DB::table('booking_category')->select('*')->where('booking_id','=',$row->id)->get();
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
        }
        return response()->json([
            'status' => true,
            'message' => 'Executed',
            'data' => []
        ], 201);
    }

    public function cronJob1()
    {
        

        $time = DB::table('timer')->orderBy('created_at','desc')->first();
        

        echo Carbon::now();
        $book = Booking::where('expired_at', '<=', Carbon::now())->where('accepted','=',0)->get();

        print_r($book);
        // foreach($book as $row)
        // {
        //     if($row->accepted != 1)
        //     {
        //         Booking::where('id',$row->id)->update(['rejected' => 1, 'accepted' => 0,'status' => 'Cancelled']);
        //     }
        // }
        return response()->json([
            'status' => true,
            'message' => 'Executed',
            'data' => []
        ], 201);
    }

// Function to Change Schedule Status

    public function checkSchedule()
    {
        $data =  Schedule::where('departure_date','<=', Carbon::now())->get();
        // $data =  Schedule::where('departure_date','>=', Carbon::yesterday())->get();
        foreach($data as $row)
        {
            Schedule::where('id',$row->id)->update(['status'=>'inProgress']);
        }

    }

// Function to send notification 1 hour before its cancelled

    public function sendAlert()
    {
        $data = Booking::olderThanHours()->get();
 
        foreach($data as $row)
        {
            $not = Notification::where('booking_id',$row->id)->get();
            if(count($not) == 0)
            {
                $ship = Schedule::where('id', $row->schedule_id)->first();
                $notification = new Notification();
                $notification->msg = "You have 60 mins left for requested booking ";
                $notification->title = "Alert  !!";
                $notification->sid = $ship->sid;
                $notification->booking_id = $row->id;
                $notification->save();
                Notification::where('booking_id',$row->id)->update(['once'=>1]);
            }
            
        }

    }

// Function to Pack Schedule when its availabilty 0

    public function schedulePack()
    {
        $data = Schedule::all();
        $available_container = "0";
        $total_container = "0";
        $total = [];
        foreach($data as $row)
        {
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
                    $price = ['available' => $pf];
                    array_push($total,$pf);
                }
            
            $row->available = $total;
            $row->length = count($total);
            $row->zeroCount = count(array_keys($total, "0"));
            if($row->length == $row->zeroCount)
            {
                Schedule::where('id',$row->id)->update(['status' => 'Packed']);
            }
            foreach($total as $pop)
            {
                array_pop($total);
            }
        }
        // return $data;/
    }

// Function to Remove Booking in 24 Hours if Not Confirmed

    public function removeBooking()
    {
        $data = Booking::where('status','=','Not Confirmed')->delete();
    }



    public function checkplanExpire()
    {
        
                $cdate = strtotime(date('Y-m-d'));
                $shipmentlist = DB::table('shipments')
                ->select('*')
                ->where('roles',1)
                ->get();
                foreach($shipmentlist as $key=>$list)
                {
                    $list->id;
                    echo $list->email;
                    $list->expire_date;  
                    $edate  = strtotime($list->expire_date);
                    $datediff = $edate - $cdate;
                    $days = round($datediff / (60 * 60 * 24));
                    //echo 'userid-'.$list->email.'---'.round($datediff / (60 * 60 * 24));
                    //echo "<br>";
                    if($days == 3)
                    {
                        $maildata = ['title' => 'Your Subscription Plan expire in : '. date('d/m/Y',strtotime($list->expire_date)),
                            ];
                            Mail::to($list->email)->send(new SendPlanExpireMail($maildata));

                    }
                    
                }
            
    }

}