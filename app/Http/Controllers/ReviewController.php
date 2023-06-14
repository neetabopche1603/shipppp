<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Client;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
use DB;

class ReviewController extends Controller
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
   
// Function to post Client Review Api With {Role=1} 1 for Client

    public function clientReview(Request $request)
    {
        $this->checkToken();
        $validator = Validator::make($request->all(), [
            "rating" => "required",
            // "recommend" => "required",
            // "comment" => "required",
            // "file" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' 
        ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            else
            {
                $review = new Review();
                $review->uid = $this->userId;
                $review->rating = $request->rating;
                $review->recommend = $request->recommend;
                $review->comment = $request->comment;
                $review->sid = $request->sid;
                $image = array();
                if($request->hasfile('file'))
                {
                    foreach($file as $file){
                        $image_name = md5(rand(1000,10000));
                        $ext = strtolower($file->getClientOriginalExtension());
                        $image_full_name = $image_name.'.'.$ext;
                        $destinationPath =  public_path('/image');
                        $image_url = $destinationPath.$image_full_name;
                        $file->move($destinationPath,$image_full_name);
                        $image[] = $image_full_name;
                    }
                }
                $review->image = json_encode($image);
                $review->save();
            }

        return response()->json([
            'status' => true,
            'message' => 'Review Added Successfully',
            'data' => [$review]
        ], 201);
    }

// Function to Display All Feedbacks to Client with user name Api

    public function displayClientReview(Request $request)
    {
        $this->checkToken();
        // $review = DB::table('reviews')
        //     ->join('clients', 'reviews.uid', '=', 'clients.id')
        //     ->select('reviews.*', 'clients.name')
        //     ->get();
        $review = DB::table('reviews')
            ->join('shipments', 'reviews.sid', '=', 'shipments.id')
            ->select('reviews.*','shipments.companyname')
            ->where('reviews.sid',$request->sid)
            ->orderBy('created_at', 'DESC')
            ->get();
        foreach($review as $rev)
        {
            $rev->clientname = Client::where('id',$rev->uid)->select('name','profileimage')->get();
        }
        if($review->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No Reviews',
                'data' => []
            ]); 
        }
        return response()->json([
            'status' => true,
            'message' => 'All Reviews',
            'data' => $review
        ], 201); 
        
    }

// Function to Send Responsee on Client Review

    public function clientReviewResponse(Request $request)
    {
        $this->checkToken();

        $response = Review::where('id',$request->review_id)->update(['response' => $request->response]);

        if(!$response)
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
            'message' => 'response Sent',
            'data' => []
            ], 201); 
        }
    }

// Function to post Shipment Review Api {Role=2} 2 for Shipment Company

    public function shipmentReview(Request $request)
    {
        $this->checkToken();
        $validator = Validator::make($request->all(), [
            "rating" => "required",
            "recommend" => "required",
            "comment" => "required"
        ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            else
            {
                $review = new Review();
                $review->sid = $this->userId;
                $review->uid = $request->uid;
                $review->rating = $request->rating;
                $review->recommend = $request->recommend;
                $review->comment = $request->comment;
                $image = array();
                if($request->hasfile('file'))
                {
                    foreach($file as $file){
                        $image_name = md5(rand(1000,10000));
                        $ext = strtolower($file->getClientOriginalExtension());
                        $image_full_name = $image_name.'.'.$ext;
                        $destinationPath =  public_path('/image');
                        $image_url = $destinationPath.$image_full_name;
                        $file->move($destinationPath,$image_full_name);
                        $image[] = $image_full_name;
                    }
                }
                $review->image = json_encode($image);
                $review->save();
            }

        return response()->json([
            'status' => true,
            'message' => 'Review Added Successfully',
            'data' => [$review]
        ], 201);
    }

// Function to Display All Feedbacks to Shipment Api

    public function displayShipmentReview()
    {
        $this->checkToken();
        $review = DB::table('reviews')
            ->join('shipments', 'reviews.sid', '=', 'shipments.id')
            ->select('reviews.*','shipments.name','shipments.profileimage')
            ->where('reviews.sid',$this->userId)
            ->get();
  
        if($review->isEmpty())
        {
            return response()->json([
                'status' => false,
                'message' => 'No Reviews',
                'data' => []
            ]); 
        }
        return response()->json([
            'status' => true,
            'message' => 'All Reviews',
            'data' => $review
        ], 201);
    }    

// Function to Post Rating & Review On Blogs

    public function clientReviewBlog(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "bid" => "required",
            "rating" => "required",
            "reviews" => "required",
        ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            else
            {
                Blog::where('id', $request->bid)->update(['reviews' => $request->reviews ,
                                            'client_id' => $request->client_id,
                                            'rating' => $request->rating ,
                                           ]);
                $blog = Blog::all()->where('id',$request->bid);
            }

        return response()->json([
            'status' => true,
            'message' => 'Review Added Successfully',
            'data' => $blog
        ], 201);
    }


}
