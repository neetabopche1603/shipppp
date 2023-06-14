@extends('admin.master')


@section('content')
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<?php $sname = isset($sname[0]) ? $sname[0] : []  ?>

@if( ! empty($book[0]))
<?php $book = $book[0]  ?>
@else
@endif
<div class="container-fluid rounded bg-white">
    <div class="row">     

        <form action="updateBooking" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-lg-10 col-sm-10" style="margin-left: 10%;">
                    <div class="py-5 card shadow-lg p-3 mb-5 bg-white rounded ">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right font-weight-bold">Booking Basic Details</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6"><label class="labels">Title</label><input type="text" class="form-control" name="title" value="{{ $book->booking_type }}"></div>
                                <div class="col-md-6"><label class="labels">Booking Date</label><input type="text" class="form-control" name="date" value="{{ $book->booking_date }}"></div>
                                <!-- -->
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6"><label class="labels">Shipment Company :-</label><a href="/shipmentDetails/{{ $sname->id }}" ><input type="text" class="form-control" name="companyname" value="{{ isset($sname->companyname) ? $sname->companyname:'Not Accepted' }}" style="color:blue; cursor:pointer; text-decoration:underline;" readonly></a></div>
                                <!-- <div class="col-md-6"><label class="labels">Client :-</label><input type="text" class="form-control" name="name" value="{{ $book->name }}"></div> -->
                                <div class="col-md-6"><label class="labels">Client :-</label><a href="/clientDetails/{{ $book->uid }}" ><input type="text" class="form-control" name="name" value="{{ $book->name }}" style="color:blue; cursor:pointer; text-decoration:underline;" readonly></a></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6"><label class="labels">From :-</label><input type="text" class="form-control" name="pickup_location" value="{{ $book->from }}"></div>
                                <div class="col-md-6"><label class="labels">To :-</label><input type="text" class="form-control" value="{{ $book->to }}" name="dropoff_location"></div>
                            </div>

                    </div>
                </div>
                @foreach($datas as $data)
                @php
                        $itemName = array();
                        if(is_array(json_decode($data->item_name)))
                        {
                            foreach(json_decode($data->item_name) as $res)
                            {
                                array_push($itemName,$res->itemname);
                            }
                        }
                        $items = implode(',',$itemName);
                    @endphp
                <div class="col-lg-10 col-sm-10" style="margin-left: 10%;">
                    <div class=" py-5 card shadow-lg p-3 mb-5 bg-white rounded">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right font-weight-bold">Booking Item Details</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <div class="col-md-12 mb-3" ><label class="labels"> Item Category</label><input type="text" class="form-control" name="category" value="{{ $data->category }}" ></div>
                                    <div class="col-md-12 mb-3" ><label class="labels">Item Name</label><input type="text" class="form-control" name="booking_attribute" value="{{ $items }}"></div>
                                    <div class="col-md-12 mb-3" ><label class="labels">No. of Items</label><input type="text" class="form-control" name="items" value="{{ $data->quantity }}"></div>
                                    
                                    <div class="col-md-12 mb-3" ><label class="labels">Description</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description">{{ $data->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                <label class="labels"> Item Images</label>
                                    <div
                                        id='carouselExampleIndicatorsI'
                                        class='carousel slide'
                                        data-ride='carousel'
                                        >
                                        <ol class='carousel-indicators'>
                                        <li
                                            data-target='#carouselExampleIndicatorsI'
                                            data-slide-to='0'
                                            class='active'
                                            ></li>
                                        <li
                                            data-target='#carouselExampleIndicatorsI'
                                            data-slide-to='1'
                                            ></li>
                                        <li
                                            data-target='#carouselExampleIndicatorsI'
                                            data-slide-to='2'
                                            ></li>
                                        </ol>
                                        <div class='carousel-inner'>
                                        @if(json_decode($data->item_image) != [])
                                                @foreach(json_decode($data->item_image) as $key=>$row)
                                                    @if($key == 0)
                                                    <div class='carousel-item active'>
                                                        <img class='img-size img-responsive' src="{{ $row }}" />
                                                    </div>
                                                    @else
                                                    <div class='carousel-item'>
                                                        <img class='img-size' src="{{ $row }}" />
                                                    </div>
                                                    @endif
                                                @endforeach
                                        @else
                                            <div class='text-center'>
                                                <img class='img-size img-responsive' style="height:320px; width:500px;" src="{{ asset('image/noimg.png') }}" />
                                            </div>
                                        @endif

                                        </div>
                                        
                                        <a
                                        class='carousel-control-prev'
                                        href='#carouselExampleIndicatorsI'
                                        role='button'
                                        data-slide='prev'
                                        >
                                        <span class='carousel-control-prev-icon'
                                                aria-hidden='true'
                                                ></span>
                                        <span class='sr-only'>Previous</span>
                                        </a>
                                        <a
                                        class='carousel-control-next'
                                        href='#carouselExampleIndicatorsI'
                                        role='button'
                                        data-slide='next'
                                        >
                                        <span
                                                class='carousel-control-next-icon'
                                                aria-hidden='true'
                                                ></span>
                                        <span class='sr-only'>Next</span>
                                        </a>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="mt-5 text-center">
                                <!-- <button class="btn btn-primary" type="submit" value="submit">Update Details</button> -->
                            </div>
                    </div>
                </div>
                @endforeach
                @php 
                    $pickup = json_decode($book->pickup_review);
                @endphp
                <div class="col-lg-10 col-sm-10 mb-5" style="margin-left: 10%;">
                    <div class=" py-5 card shadow-lg p-3 mb-5 bg-white rounded">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right font-weight-bold">Pickup Details</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="col-md-12 mb-3" ><label class="labels">Pickup Type</label><input type="text" class="form-control" name="category" value="{{ $pickup[0]->pickup_type }}" ></div>
                                    <div class="col-md-12 mb-3" ><label class="labels">Pickup Location</label><input type="text" class="form-control" name="booking_attribute" value="{{ $pickup[0]->pickup_location }}"></div>
                                    <div class="col-md-12 mb-3" ><label class="labels">Pickup Date</label><input type="text" class="form-control" name="items" value="{{ $pickup[0]->pickup_date }}"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12 mb-3" ><label class="labels">Pickup Time</label><input type="text" class="form-control" name="category" value="{{ $pickup[0]->pickup_time }}" ></div>
                                    <div class="col-md-12 mb-3" ><label class="labels">Pickup Distance</label><input type="text" class="form-control" name="booking_attribute" value="{{ $pickup[0]->pickup_distance }}"></div>
                                    <div class="col-md-12 mb-3" ><label class="labels">Pickup Estimate</label><input type="text" class="form-control" name="items" value="{{ $pickup[0]->pickup_estimate }}"></div>
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
