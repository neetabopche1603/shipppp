@extends('admin.master')

@section('content')
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<div class="container-fluid rounded bg-white">
    <div class="row">     

        <form action="" method="POST" enctype="multipart/form-data">
                @csrf
               <!--  <div class="col-lg-10 col-sm-10" style="margin-left: 10%;">
                    <div class="py-5 card shadow-lg p-3 mb-5 bg-white rounded ">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right font-weight-bold">Transactions Details</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6"><label class="labels">Name</label><input type="text" class="form-control" name="title" value="{{ isset($data->name) ? $data->name:'' }} {{ isset($data->lname) ? $data->lname:'' }}" readonly=""></div>
                                <div class="col-md-6"><label class="labels">Email</label><input type="text" class="form-control" name="email" value="{{ isset($data->email) ? $data->email:'' }}" readonly=""></div>
                                
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6"><label class="labels">Shipment Company :-</label><input type="text" class="form-control" name="companyname" value="" style="color:blue; cursor:pointer; text-decoration:underline;" readonly></div>


                                
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6"><label class="labels">From :-</label><input type="text" class="form-control" name="pickup_location" value=""></div>
                                <div class="col-md-6"><label class="labels">To :-</label><input type="text" class="form-control" value="" name="dropoff_location"></div>
                            </div>

                    </div>
                </div> -->
          
        
                <div class="col-lg-10 col-sm-10 mb-5" style="margin-left: 10%;">
                    <div class=" py-5 card shadow-lg p-3 mb-5 bg-white rounded">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right font-weight-bold">Company Details</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="col-md-12 mb-3" ><label class="labels">Name</label><input type="text" class="form-control" name="category" value="{{ isset($data->name) ? $data->name:'' }} {{ isset($data->lname) ? $data->lname:'' }}" readonly=""></div>
                                    
                                    <div class="col-md-12 mb-3" ><label class="labels">Phone No</label><input type="text" class="form-control" name="items" value="{{ isset($data->phone) ? $data->phone:'' }}" readonly=""></div>

                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12 mb-3" ><label class="labels">Email</label><input type="text" class="form-control" name="booking_attribute" value="{{ isset($data->email) ? $data->email:'' }}" readonly=""></div>
                                    <div class="col-md-12 mb-3" ><label class="labels">Company Name</label><input type="text" class="form-control" name="booking_attribute" value="{{ isset($data->companyname) ? $data->companyname:'' }}" readonly=""></div>

                                </div>

                            </div>
                    </div>
                </div>


                <div class="col-lg-10 col-sm-10 mb-5" style="margin-left: 10%;">
                    <div class=" py-5 card shadow-lg p-3 mb-5 bg-white rounded">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right font-weight-bold">Subscription Details</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="col-md-12 mb-3" ><label class="labels">Plan Name</label><input type="text" class="form-control" name="category" value="{{ isset($data->plan_type) ? $data->plan_type:'' }} " readonly=""></div>
                                    
                                    <div class="col-md-12 mb-3" ><label class="labels">Amount</label><input type="text" class="form-control" name="items" value="{{ isset($data->amount) ? $data->amount:'' }}" readonly=""></div>


                                    <div class="col-md-12 mb-3" ><label class="labels">Plan End</label><input type="text" class="form-control" name="booking_attribute" value="{{ isset($data->plan_end_date) ? date('d/m/Y',strtotime($data->plan_end_date)):'' }}" readonly=""></div>

                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12 mb-3" ><label class="labels">Interval</label><input type="text" class="form-control" name="items" value="{{ isset($data->plan_interval) ? $data->plan_interval:'' }}" readonly=""></div>
                                    <div class="col-md-12 mb-3" ><label class="labels">Plan Start</label><input type="text" class="form-control" name="booking_attribute" value="{{ isset($data->plan_start_date) ? date('d/m/Y',strtotime($data->plan_start_date)):'' }}" readonly=""></div>

                                </div>

                            </div>
                    </div>
                </div>
        </form>
    </div>
    <div class="row">
        <div class="col-md-5 border-right ms-5">  
                
        </div>
        <div class="col-md-5 border-right">  
            
        </div>

    </div>
</div>



@endsection
