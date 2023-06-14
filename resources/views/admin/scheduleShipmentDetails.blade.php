@extends('admin.master')


@section('content')
<?php $data = isset($data[0]) ? $data[0] : []  ?>
<?php $client = isset($client[0]) ? $client[0] : []  ?>
<div class="col-lg-10 col-sm-10" style="margin-left: 10%;">
    <div class="py-5 card shadow-lg p-3 mb-5 bg-white rounded ">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-right font-weight-bold">Schedule Shipment Details</h4>
            </div>
            <div class="row mt-2">
                 <div class="col-md-6"><label class="labels">Shipment Company :-</label><a href="/shipmentDetails/{{ $data->sid }}" ><input type="text" class="form-control" name="companyname" value="{{ isset($data->companyname) ? $data->companyname : 'No Company Name' }}" style="color:blue; cursor:pointer; text-decoration:underline;" readonly></a></div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6"><label class="labels">From</label><input type="text" class="form-control" name="from" value="{{ $data->from }}"></div>
                <div class="col-md-6"><label class="labels">To</label><input type="text" class="form-control" value="{{ $data->to }}" name="to"></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6"><label class="labels">Date :-</label><input type="text" class="form-control" name="departure_date" value="{{ $data->departure_date }}"></div>
                <div class="col-md-6"><label class="labels">Status :-</label><input type="text" class="form-control" name="status" value="{{ $data->shipmentStatus }}"></div>
            </div>
            <!-- <div class="row mt-3">
                <div class="col-md-6"><label class="labels">Availabilty</label><input type="text" class="form-control" value="{{ $avalible }}" name="availability"></div>
                <div class="col-md-6"><label class="labels">Reserved Space</label><input type="text" class="form-control" value="3" name="reserved"></div>
            </div> -->
            @php $item = json_decode($data->item_type) @endphp
           
            <div class="row mt-5">
            <hr>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-right font-weight-bold">Item Availabilty </h4>
            </div>
            <table class="table text-nowrap" id="acailabilityTable">
                <thead>
                    <tr>
                        <th class="border-top-0">#</th>
                        <th class="border-top-0">Category</th>
                        <th class="border-top-0">Item</th>
                        <!-- <th class="border-top-0">Availbility</th>
                        <th class="border-top-0">Reserved</th> -->
                        <th class="border-top-0">Shipping Fee</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(json_decode($item) as $key=>$result)
                    @php
                        $itemName = array();
                        if(is_array(json_decode($result->item_type)))
                        {
                            foreach(json_decode($result->item_type) as $res)
                            {
                                array_push($itemName,$res->name);
                            }
                        }
                        $items = implode(',',$itemName);
                    @endphp
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $result->category_name }}</td>
                        <td>{{ $items }}</td>
                        <td>{{ $result->shipping_fee }}</td>
                        <td></td>
                    </tr>
                    @endforeach 
                    <!-- @if (is_array(json_decode($data->item_type)) || is_object($data->item_type)) -->
                        <!-- @endif -->
                    <!-- <tr>
                        <th class="border-top-0">#</th>
                        <th class="border-top-0">Category</th>
                        <th class="border-top-0">Item</th>
                        <th class="border-top-0">Availbility</th>
                        <th class="border-top-0">Reserved</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($avalible as $result)
                        <tr>
                            <td>{{ $result->id }}</td>
                            <td>{{ $result->category_name }}</td>
                            <td>{{ $result->item_name }}</td>
                            <td>{{ $result->available }}</td>
                            <td>{{ $result->item_number - $result->available }}</td>
                            <td></td>
                        </tr>
                    @endforeach    -->
                </tbody>
                
            </table>
            </div>

    </div>
</div>

                   

<div class="white-box col-md-12">
        <div class="table-responsive">
            <?php $i = 1; ?>
            <table class="table text-nowrap" id="scheduleClientTable">
                <thead>
                    <tr>
                        <th class="border-top-0">#</th>
                        <th class="border-top-0">Profile Pic</th>
                        <th class="border-top-0">Name</th>
                        <th class="border-top-0">Email</th>
                        <th class="border-top-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $key=>$res)
                    <tr>
                        <td>{{ ($key+1) }}</td>
                        <td>
                            @if($res->client_data != null)
                                <img class="rounded-circle zoom" id="{{ $res->client_data->id }}" width="45px" height="45px" src="{{ $res->client_data->profileimage }}" />
                            </td>
                            <td>
                                <div class="col-md-6"><a href="/clientDetails/{{ $res->client_data->id }}" >{{ $res->client_data->name }}</a></div>
                            </td>
                            <td>{{ $res->client_data->email }}</td>
                            <td>
                                <a href="/bookingDetails/{{ $res->id }}" class="btn btn-info btn-sm text-white">Booking Detail</a>
                            </td>
                        </tr>
                        @endif  
                        @endforeach
                      
                </tbody>
                
            </table>
        </div>
</div>







@endsection