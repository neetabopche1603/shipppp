@extends('admin.master')


@section('content')
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- @if( ! empty($data[0])) -->
<?php $data = isset($data[0]) ? $data[0] : []  ?>
<?php $shipment = isset($shipment[0]) ? $shipment[0] : []  ?>
<!-- @else

@endif -->
<div class="container-fluid rounded bg-white">
    <div class="row">     

        <form action="updateMarketPlace" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-lg-10 col-sm-10" style="margin-left: 10%;">
                    <div class="py-5 card shadow-lg p-3 mb-5 bg-white rounded ">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right font-weight-bold">Market Place Basic Details</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6"><label class="labels">Title</label><input type="text" class="form-control" name="title" value="{{ $data->title }}"></div>
                                <div class="col-md-6"><label class="labels">Booking Date</label><input type="text" class="form-control" name="date" value="{{ $data->booking_date }}"></div>
                                <!-- -->
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6"><label class="labels">Client :-</label><a href="/clientDetails/{{ $data->uid }}" ><input type="text" class="form-control" name="name" value="{{ $data->name }}" style="color:blue;  cursor:pointer; text-decoration:underline;" readonly></a></div>
                                <div class="col-md-6"><label class="labels">Shipment Company :-</label><a href="/shipmentDetails/{{ $data->sid }}" ><input type="text" class="form-control" name="companyname" value="{{ isset($shipment->companyname) ? $shipment->companyname : 'Not Accepted' }}" style="color:blue; cursor:pointer; text-decoration:underline;" readonly></a></div>
                                <!-- <div class="col-md-6"><label class="labels">Client :-</label><a href="/clientDetails/{{ $data->uid }}" style="line-heeight:10px;">{{ $data->name }}</a></div> -->
                                <!-- <div class="col-md-6"><label class="labels">Shipment Company :-</label><input type="text" class="form-control" name="companyname" value="{{ isset($shipment->companyname) ? $shipment->companyname : 'Not Accepted' }}"></div> -->
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6"><label class="labels">Pickup Location</label><input type="text" class="form-control" name="pickup_location" value="{{ $data->pickup_location }}"></div>
                                <div class="col-md-6"><label class="labels">DropOff Location</label><input type="text" class="form-control" value="{{ $data->dropoff_location }}" name="dropoff_location"></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12 mt-2"><label class="labels">Delivery Days</label><input type="text" class="form-control" name="delivery_days" value="{{ $data->delivery_days }}" ></div>
                            </div>

                    </div>
                </div>

               
                <div class="col-lg-10 col-sm-10" style="margin-left: 10%;">
                    @php $item = json_decode($data->category); @endphp
                    @foreach($item as $res)
                        <div class=" py-5 card shadow-lg p-3 mb-5 bg-white rounded">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right font-weight-bold">Market Place Item Details</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <div class="col-md-12 mb-3" ><label class="labels"> Item Category</label><input type="text" class="form-control" name="category" value="{{ $res->categoryItem }}" ></div>
                                    @foreach($res->booking_attribute as $attr)
                                    <div class="col-md-12 mb-3" ><label class="labels">Booking Attribute</label><input type="text" class="form-control" name="booking_attribute" value="{{ $attr}}"></div>
                                    @endforeach
                                    <div class="col-md-12 mb-3" ><label class="labels">No. of Items</label><input type="text" class="form-control" name="items" value="{{ $res->quantity }}"></div>
                                    <div class="col-md-12 mb-3" ><label class="labels">Note</label><input type="text" class="form-control" name="needs" value="{{ $data->needs }}"></div>
                                    <div class="col-md-12 mb-3" ><label class="labels">Customer Budget</label><input type="text" class="form-control" name="booking_price" value="{{ $data->booking_price }}"></div>
                                    <div class="col-md-12 mb-3" ><label class="labels">Description</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description">{{ $data->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label class="labels"> Item Images</label>
                                    <div
                                        id='carouselExampleIndicatorsM'
                                        class='carousel slide'
                                        data-ride='carousel'
                                        >
                                        <ol class='carousel-indicators'>
                                        <li
                                            data-target='#carouselExampleIndicatorsM'
                                            data-slide-to='0'
                                            class='active'
                                            ></li>
                                        <li
                                            data-target='#carouselExampleIndicatorsM'
                                            data-slide-to='1'
                                            ></li>
                                        <li
                                            data-target='#carouselExampleIndicatorsM'
                                            data-slide-to='2'
                                            ></li>
                                        </ol>
                                        <div class='carousel-inner'>
                                        @php $image = json_decode($data->item_image);
                                        @endphp
                                                @foreach($image as $key=>$row)
                                                    @if($key == 0)
                                                    <div class='carousel-item active'>
                                                        <!-- <img class='img-size img-responsive' src="{{ asset('image/'. $row) }}" /> -->
                                                        <img class='img-size img-responsive' src="{{ $row }}" />
                                                    </div>
                                                    @else
                                                    <div class='carousel-item'>
                                                        <!-- <img class='img-size' src="{{ asset('image/'. $row) }}" /> -->
                                                        <img class='img-size' src="{{ $row }}" />
                                                    </div>
                                                    @endif
                                                @endforeach


                                        </div>
                                        
                                        <a
                                        class='carousel-control-prev'
                                        href='#carouselExampleIndicatorsM'
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
                                        href='#carouselExampleIndicatorsM'
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
                    @endforeach
                </div>

               

                
                <div class="col-lg-10 col-sm-10 mb-5" style="margin-left: 10%;">
                

                 
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

