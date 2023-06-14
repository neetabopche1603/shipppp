<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Broadcast;
use App\Models\Client;
use App\Models\Shipment;
use App\Models\Schedule;
use DB;

class NotificationController extends Controller
{

    public function checkToken() 
    {
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
    
// Function for getting Client Notifications

    public function clientNotification()
    {
        $this->checkToken();
        $data = Notification::where('uid',$this->userId)->orderBy('created_at','desc')->get();
        foreach($data as $res)
        {
            Notification::where('uid',$this->userId)->update(['status' => 1]);
        }
        if(count($data) == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Notifications',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Notifications',
                'data' => $data
            ], 201); 
        }
    }

// Function for getting Client Notifications

    public function clientNotificationCount()
    {
        $this->checkToken();
        $data = Notification::where('uid',$this->userId)->where('status','=',0)->count();
        if($data == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Notifications',
                'data' => [0]
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Notifications',
                'data' => $data
            ], 201); 
        }
    }

// Function for Getting Shipment Notifications

    public function shipmentNotification()
    {
        $this->checkToken();
        $data = Notification::where('sid',$this->userId)->orderBy('created_at','desc')->get();
        // return $this->userId;
        foreach($data as $res)
        {
            Notification::where('sid',$this->userId)->update(['status' => 1]);
        }
        if(count($data) == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Notifications',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Notifications',
                'data' => $data
            ], 201); 
        }
    }

// Function for Getting Shipment Notifications

    public function shipmentNotificationCount()
    {
        $this->checkToken();
        $data = Notification::where('sid',$this->userId)->where('status','=',0)->count();
        if($data == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Notifications',
                'data' => [0]
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Notifications',
                'data' => $data
            ], 201); 
        }
    }

// Function to send All shipment company Notification

    public function allShipmentNotification()
    {
        $data = Notification::where('market_id','!=',0)->get();
        if(count($data) == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Notifications',
                'data' => []
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Notifications',
                'data' => [$data]
            ], 201); 
        }
    }

// Function to Clear Client Side Notification

    public function clientClear()
    {
        $this->checkToken();
        $data = Notification::where('uid',$this->userId)->delete();
        return response()->json([
            'status' => true,
            'message' => 'All Notification Cleared',
            'data' => []
        ], 201);
    }

// Function to Clear Shipment Side Notification

    public function shipmentClear()
    {
        $this->checkToken();
        $data = Notification::where('sid',$this->userId)->delete();
        return response()->json([
            'status' => true,
            'message' => 'All Notification Cleared',
            'data' => []
        ], 201);
    }

// Function to Send Broadcast message 

    public function broadcast(Request $request)
    {
        $this->checkToken();
        $broadcast = new Broadcast();
        $broadcast->schedule_id = $request->schedule_id;
        $broadcast->sid =  $this->userId;
        $broadcast->users = $request->users;
        $broadcast->title = $request->title;
        $broadcast->schedule_title = $request->schedule_title;
        $broadcast->message	 = $request->message;
        $broadcast->save();

        foreach(json_decode($broadcast->users) as $u)
        {
            
            $name = Client::where('id', $u->uid)->select('name')->first();
            if(isset($name))
            {
                $u->name = $name->name;
                $notification = new Notification();
                $notification->msg = "You Have a New BroadCast";
                $notification->title = "Notification !!";
                $notification->uid = $u->uid;
                $notification->schedule_id = $request->schedule_id;
                $notification->save();
            }
            else
            {
                $name = Shipment::where('id', $u->uid)->select('name')->first();
                $u->name = $name->name;
                $notification = new Notification();
                $notification->msg = "You Have a New BroadCast";
                $notification->title = "Notification !!";
                $notification->sid = $u->uid;
                $notification->schedule_id = $request->schedule_id;
                $notification->save();
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Broadcast Created',
            'data' => [$broadcast]
        ], 201);
        
    }

// Function to get Broadcast message 

    public function getClientBroadcast(Request $request)
    {
        $this->checkToken();

        $schedule = Schedule::where('id',$request->schedule_id)->first();
        if(isset($this->userId))
        {
            $shipment = Broadcast::where('sid',$this->userId)->get();
            if(!isset($schedule))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Error',
                    'data' => []
                ], 201);
            }
            else if(count($shipment) > 0)
            {
                // return $this->userId;
                $bd = Broadcast::where('sid',$this->userId)->where('schedule_id',$request->schedule_id)->get();
                
                foreach($bd as $row)
                {
                    $row->users = json_decode($row->users);
                    $ship = Shipment::where('id', $this->userId)->select('profileimage')->first();
                    $row->shipmentprofile = $ship->profileimage;
                    // $row->users = Client::where('id', $row->users['uid'])->get();
                    foreach($row->users as $u)
                    {
                        
                        $name = Client::where('id', $u->uid)->select('name')->first();
                        if(isset($name))
                        {
                            $u->name = $name->name;
                        }
                        else
                        {
                            $name = Shipment::where('id', $u->uid)->select('name')->first();
                            $u->name = $name->name;
                        }
                        $profile = Client::where('id', $u->uid)->select('profileimage')->first();
                        if(isset($profile))
                        {
                            $u->profile = $profile->profileimage;
                        }
                        else
                        {
                            $profile = Shipment::where('id', $u->uid)->select('profileimage')->first();
                            $u->profile = $profile->profileimage;
                        }
                    }
                }
    
                return response()->json([
                    'status' => true,
                    'message' => 'Your Broadcasts',
                    'data' => $bd
                ], 201);
    
            }
        }

        else
        {
            $data = Broadcast::where('schedule_id',$request->schedule_id)->select('sid','title','schedule_title','message','created_at')->get();
            foreach($data as $row)
            {
                $ship = Shipment::where('id', $row->sid)->select('profileimage')->first();
                $row->shipmentprofile = $ship->profileimage;
            }
            return response()->json([
                'status' => true,
                'message' => 'Broadcasts',
                'data' => $data
            ], 201);
        }



    }


}
