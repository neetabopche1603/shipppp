<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Client;
use App\Models\Notification;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_totals =  DB::select("SELECT
        sum(case when roles = 1 then 1 else 0 end) AS users
        FROM clients");
        $approved_users = DB::select("SELECT 
        sum(case when status = 'Approved' then 1 else 0 end) AS approvedusers
        FROM clients");
        $notapprove_user = DB::select("SELECT 
        sum(case when status = 'Not Approve' then 1 else 0 end) AS notapproveusers
        FROM clients");
        $blocked_user = DB::select("SELECT 
        sum(case when status = 'Deactivate' then 1 else 0 end) AS blockedusers
        FROM clients");
        $shipment_totals = DB::select("SELECT
        sum(case when roles = 1 then 1 else 0 end) AS companies
        FROM shipments");
        $approved_shipment = DB::select("SELECT 
        sum(case when status = 'Approved' then 1 else 0 end) AS approvedshipment
        FROM shipments");
        $notapprove_shipment = DB::select("SELECT 
        sum(case when status = 'Not Approve' then 1 else 0 end) AS notapproveshipment
        FROM shipments");
        $blocked_shipment = DB::select("SELECT 
        sum(case when status = 'Deactivate' then 1 else 0 end) AS blockedshipment
        FROM shipments");
        $order_totals = DB::select("SELECT
        sum(case when id = 0 then 0 else 1 end) AS orders
        FROM bookings");
        $approved_booking = DB::select("SELECT
        sum(case when status = 'Confirmed' then 1 else 0 end) AS approvedbooking
        FROM bookings");
        $notapprove_booking = DB::select("SELECT
        sum(case when status = 'Not Confirmed' then 1 else 0 end) AS notapprovedbooking
        FROM bookings");
        $penidng_booking = DB::select("SELECT
        sum(case when status = 'pending' then 1 else 0 end) AS pendingbooking
        FROM bookings");
        $agent_totals = DB::select("SELECT
        sum(case when roles = 5 then 1 else 0 end) AS agents
        FROM shipments");
   
        if (isset($user_totals[0]) && isset($shipment_totals[0]) && isset($order_totals[0]) && isset($agent_totals[0])) {
            $data['users'] =  $user_totals[0]->users;
            $data['approvedusers'] = $approved_users[0]->approvedusers;
            $data['notapproveusers'] = $notapprove_user[0]->notapproveusers;
            $data['blockedusers'] = $blocked_user[0]->blockedusers;
            $data['companies'] =  $shipment_totals[0]->companies;
            $data['approvedshipment'] = $approved_shipment[0]->approvedshipment;
            $data['notapproveshipment'] = $notapprove_shipment[0]->notapproveshipment;
            $data['blockedshipment'] = $blocked_shipment[0]->blockedshipment;
            $data['orders'] =  $order_totals[0]->orders;
            $data['approvedbooking'] = $approved_booking[0]->approvedbooking;
            $data['notapprovedbooking'] = $notapprove_booking[0]->notapprovedbooking;
            $data['pendingbooking'] = $penidng_booking[0]->pendingbooking;
            $data['agents'] =  $agent_totals[0]->agents;
        } else {
            $data['users'] =  '00';
            $data['companies'] =  '00';
            $data['orders'] =  '00';
            $data['agents'] =  '00';
        }
        $image = User::select('profileimage')->where('is_admin','=',1)->get();
        $img = json_decode($image);

// Chart JS Function

        $user = DB::table('users')->where('is_admin','=',NULL)->get('roles')->toArray();
        $datas = [];
        foreach($user as $row)
        {       
            $datas[] = json_decode($row->roles);
        }
        
        $datas1 = [];
        
            foreach($datas as $v){
                if($v != NULL)
                {
                $datas1 = array_merge($datas1, $v);
                }
            }
    
            $roleArray = [1,2,3,4,5];
            $roleValues = [];
            $arrayCount = 0;
    
            foreach($roleArray as $role) {
                $arrayCount = 0;
                foreach($datas1 as $v)
                {   
                    if($v == $role){
                        $arrayCount++;
                    }
                }
                array_push($roleValues, $arrayCount);
            }

            
        $new = Notification::where('is_admin','=',1)->where('status','=',0)->orderBy('created_at', 'desc')->count();


        $noti = Notification::where('is_admin','=',1)->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.dashboard', compact('data','img','roleValues','new','noti'));

    }

    

    

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }

}
