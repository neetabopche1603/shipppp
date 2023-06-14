<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['subscription_id','stripe_customer_id','stripe_plan_id','payer_email','plan_interval','plan_type','plan_id','user_id','amount','currency','plan_start_date','plan_end_date','status','txn_id','invoice_id'];
    protected $hidden = [

    ];
}
