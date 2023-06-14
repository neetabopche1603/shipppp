@extends('admin.master')


@section('content')
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<?php $data = $data[0]  ?>
<div class="container-fluid rounded bg-white">
    <div class="row">
            <div class="col-md-4 col-xl-4">
                <div class="card bg-c-blue order-card">
                    <div class="card-block text-center">
                        <h3 class="m-b-20">I-9 Documents</h3>
                        
                        <!-- <button class="btn btn-secondary text-white" id="pop1" data-toggle="modal" data-target="#imagemodal1">View Document</button> -->
                        <a class="btn btn-secondary text-white" href="/generatepdf/{{ $data->id }}">Download Document</a>
                      

                    </div>
                </div>
            </div>
            
            <div class="col-md-4 col-xl-4">
                <div class="card bg-c-green order-card">
                    <div class="card-block text-center">
                        <h3 class="m-b-20">Tax Documents</h3>
                        
                        <!-- <button class="btn btn-success text-white" id="pop2" data-toggle="modal" data-target="#imagemodal2">View Document</button> -->
                        <a class="btn btn-success text-white" href="/generatepdft/{{ $data->id }}">Download Document</a>

                    </div>
                </div>
            </div>
            
            <div class="col-md-4 col-xl-4">
                <div class="card bg-c-yellow order-card">
                    <div class="card-block text-center">
                        <h3 class="m-b-20"> Driving Licence</h3>

                        <!-- <button class="btn btn-primary text-white"  id="pop3" data-toggle="modal" data-target="#imagemodal3">View Document</button> -->
                        <a class="btn btn-primary text-white" href="/generatepdfd/{{ $data->id }}">Download Document</a>

                    </div>
                </div>
            </div>
    
    </div>
    <div class="row">
                <div class="col-md-5 border-right">  
        <form action="/updateShipmentProfile" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="image-upload d-flex flex-column align-items-center text-center p-3 py-5">
                    <label for="file-input">
                        <!-- <img class="rounded-circle mt-5" width="200px" height="200px" src="{{ asset('image/'. $data->profileimage) }}" alt = "Profile Pic"/> -->
                        <img class="rounded-circle mt-5" width="200px" height="200px" src="{{ $data->profileimage }}" alt = "Profile Pic"/>
                        <span>
                        
                        </span>
                    </label>
                        <button type="button" id="pop" class="btn btn-info mt-3 btn-sm text-white" data-toggle="modal" data-target="#imagemodal">
                             View Full Photo
                        </button>
                    <input id="file-input" name="file" type="file" />
                     
                </div>
                <div class="d-flex flex-column ms-5">
                        <label for="totalbooking"><h4>Total Scheduled Shipment :- {{ $book }} </h4></label>
                        <label for="total Items"><h4>Total Pending Shipments :-  </h4></label>
                        <label for="completed shipment"><h4>Total Completed Shipments :- </h4></label>
                </div>
                        <!-- <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"><span class="font-weight-bold">{{ Auth::user()->name }}</span><span class="text-black-50">{{ Auth::user()->email }}</span><span> </span></div> -->
                </div>
                <div class="col-md-5 border-right">
                    <div class="p-3 py-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right">Shipment Details</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6"><label class="labels">First Name</label><input type="text" class="form-control" name="name" value="{{ $data->name }}"></div>
                                <div class="col-md-6"><label class="labels">Last Name</label><input type="text" class="form-control" value="{{ $data->lname }}" name="lname"></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12"><label class="labels">Mobile Number</label><input type="text" class="form-control" name="phone" value="{{ $data->phone }}"></div>
                                <div class="col-md-12 mt-2"><label class="labels">Email ID</label><input type="text" class="form-control" name="email" value="{{ $data->email }}" readonly></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6"><label class="labels">Country</label><input type="text" class="form-control" name="country" value="+{{ $data->country_code }} {{ $data->country }}"></div>
                                <div class="col-md-6"><label class="labels">Address</label><input type="text" class="form-control" value="{{ $data->address }}" name="address"></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6"><label class="labels">Shipment Bio</label><input type="text" class="form-control" name="about_me" value="{{ $data->about_me }}"></div>
                                <div class="col-md-6"><label class="labels">Language</label><input type="text" class="form-control" value="{{ $data->language }}" name="language"></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6"><label class="labels">Company Name</label><input type="text" class="form-control" name="companyname" value="{{ $data->companyname }}"></div>
                                <div class="col-md-6"><label class="labels">Annual Shipment</label><input type="text" class="form-control" value="{{ $data->annualshipment }}" name="annualshipment"></div>
                            </div>
                            <div class="mt-5 text-center">
                                <button class="btn btn-primary" type="submit" value="submit">Update Details</button>
                            </div>
                    </div>
                </div>
        </form>
    </div>

    <hr>
    <div class="row d-flex justify-content-start">
        <div class="col-md-4 col-lg-4 col-sm-10 border-right ms-5 card shadow p-3 mb-5 bg-white rounded">    
            <form action="/updateStatusShipment" method="POST">
            @csrf
            <input type="hidden" id="email" name="email" value="{{ $data->email }}">
            <input type="hidden" id="ids" name="sid" value="{{ $data->id }}">
            <label for="status" class="mt-3"><h3>Change Status</h3></label>
            <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="shipmentA" value="Approved" {{ ($data->status=="Approved")? "checked" : "" }}>
            <label class="form-check-label" for="exampleRadios1">
              Approve
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="shipmentNA" value="Not Approve" {{ ($data->status=="Not Approve")? "checked" : "" }}>
            <label class="form-check-label" for="exampleRadios2">
              Not Approve
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="shipmentB" value="Deactivate" {{ ($data->status=="Deactivate")? "checked" : "" }}>
            <label class="form-check-label" for="exampleRadios3">
            Deactivate
            </label>
            <!-- <input class="form-check-input" type="radio" name="status" id="shipmentB" value="Block" {{ ($data->status=="Block")? "checked" : "" }}>
            <label class="form-check-label" for="exampleRadios3">
              Block
            </label> -->
          </div>
            <div class="form-check">
                <label class="form-check-label" for="exampleRadios3">
                <h3>* Reason</h3>
                </label>
                ​<textarea id="txtArea" name="comment" rows="5" cols="35"></textarea>
            </div>
                <div class="col-sm-offset-2 d-flex justify-content-center mb-3 col-sm-10 my-3">
                    <button type="submit" class="btn btn-info text-white">Change Status</button>
                </div>
            </form>
        </div>
        <div class="col-md-7 col-lg-7 col-sm-10 border-right table-responsive">  
            <table class="table text-nowrap" id="clientStatusTable">
                    <thead>
                        <tr>
                            <th class="border-top-0">#</th>
                            <th class="border-top-0">Status</th>
                            <th class="border-top-0">Date & Time</th>
                            <th class="border-top-0">Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                          @foreach($shipmentDetails as $key=>$row)
                            @php
                              $temp = explode(' ',$row->created_at);
                            @endphp
                            <tr>
                              <td>{{ ($key+1) }}</td>
                              <td>{{ $row->status }}</td>
                              <td>{{ $temp[0] }}</td>
                              <td>{{ $row->comment }}</td>
                            </tr>
                          @endforeach
                    </tbody>
                
            </table>
        </div>

    </div>
    

</div>

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Image Preview</h5>
        <button type="button" class="btn btn-secondary btn-sm rounded-circle" id="closemodal" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body">
        <div class="col-md-12 col-lg-12 col-sm-12">
        <!-- <img src="{{ asset('image/'. $data->profileimage) }}" id="imagepreview" style="max-width: 100%; min-width:100%; height: 100%;" > -->
        <img src="{{ $data->profileimage }}" id="imagepreview" style="max-width: 100%; min-width:100%; height: 100%;" >
        </div>
        
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>




@endsection



