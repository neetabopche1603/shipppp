@extends('admin.master')


@section('content')

<div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">All Shipment Companies</h4>
            </div>
        </div>
    </div>

    <div class="white-box">
        <div class="table-responsive">
            <table class="table text-nowrap" id="shipmentTable">
            <div class="d-flex justify-content-center">
                  <select id="mySelectShipment" class="form-check">
                      <option value="">All</option>
                      <option value="Approved">Approved</option>
                      <option value="Not Approve">Not Aproved</option>
                      <option value="Deactivate">Deactivate</option>
                  </select>
            </div>
                <thead>
                    <tr>
                        <th class="border-top-0">#</th>
                        <th class="border-top-0">First Name</th>
                        <th class="border-top-0">Email</th>
                        <th class="border-top-0">Phone</th>
                        <th class="border-top-0">Company Name</th>
                       <!--  <th class="border-top-0">Country Code</th> -->
                        <th class="border-top-0">Country</th>
                        <th class="border-top-0">Status</th>
                        <th class="border-top-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                         @foreach($data as $key=>$row)
                            <tr>
                            <td tabindex="0" aria-controls="DataTables_Table_0"  aria-label="Job Title: activate to sort column ascending">{{ $key+1 }}</td>
                            <td tabindex="0" aria-controls="DataTables_Table_0"  aria-label="Job Title: activate to sort column ascending">{{ $row->name }}</td>
                            <td tabindex="0" aria-controls="DataTables_Table_0"  aria-label="Job Title: activate to sort column ascending">{{ $row->email }}</td>
                            <td tabindex="0" aria-controls="DataTables_Table_0"  aria-label="Job Title: activate to sort column ascending">{{ $row->phone }}</td>
                            <td tabindex="0" aria-controls="DataTables_Table_0"  aria-label="Job Title: activate to sort column ascending">{{ $row->companyname }}</td>
                            <!-- <td tabindex="0" aria-controls="DataTables_Table_0"  aria-label="Job Title: activate to sort column ascending">+{{ $row->country_code }}</td> -->
                            <td tabindex="0" aria-controls="DataTables_Table_0"  aria-label="Job Title: activate to sort column ascending">{{ $row->country }}</td>
                            <td tabindex="0" aria-controls="DataTables_Table_0"  aria-label="Job Title: activate to sort column ascending">{{ $row->status }}
                              <!-- <a data-toggle="modal" data-target="#shipmentsModal" id="{{ $row->id }}" class="SCmodal"> &nbsp; <i class="fas fa-edit"></i></a> -->
                            </td>
                            <td> 
                            <a href="/shipmentDetails/{{ $row->id }}" class="btn btn-info btn-sm text-white"> Details </a>
                            <!-- <form method="POST" action="{{ route('admin.deleteShipment', $row->id) }}">
                                @csrf
                                <!-- <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a>  &nbsp; <a data-toggle="modal" data-target="#shipmentcompanymodal" id="{{ $row->id }}" class="SCmodal"><i class="fa fa-eye"></i></a> -->
                            <!-- </form> --> 
                              <!-- <form method="POST" action="/shipmentDetails">
                                  @csrf -->
                                  <!-- <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a>  &nbsp; -->
                                  <!-- <input type="hidden" name="id" value="{{ $row->id }}">
                                  <button type="submit" class="btn btn-info btn-sm text-white">Details</button>
                              </form> -->
                            </td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>


<div class="modal1 fade" id="shipmentsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style=" height: 308px !important;
          width: 500px !important;">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Shipment Status</h5>
          <button type="button" class="btn btn-info text-white rounded-circle" id="closeBmodal1" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body">
        <!-- <form action="/updateStatusShipment" method="POST">
          @csrf
          <input type="hidden" id="emails" name="email" value="">
          <input type="hidden" id="ids" name="sid" value="">
          <label for="status" class="mt-3">Current Status</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="shipmentA" value="Approved">
            <label class="form-check-label" for="exampleRadios1">
              Approve
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="shipmentNA" value="Not Approve">
            <label class="form-check-label" for="exampleRadios2">
              Not Approve
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="shipmentB" value="Block">
            <label class="form-check-label" for="exampleRadios3">
              Block
            </label>
          </div>
            <div class="col-sm-offset-2 d-flex justify-content-center mb-3 col-sm-10 my-3">
                <button type="submit" class="btn btn-info text-white">Change Status</button>
            </div>
        </form> -->
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div> 


<!-- <div class="modal fade" id="shipmentcompanymodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Certificate</h5>
      </div>
      <div class="modal-body"> -->
        <!-- <img src="{{ asset('image/'. $row->docs) }}" id="imagepreview" style="width: 100%; height: 100%;" > -->
        <!-- <div id="docsImage">
            <img src="{{url('/image/17.png')}}" style="width: 100%; height: 100%;" alt="">
            <img src="{{url('/image/18.png')}}" style="width: 100%; height: 100%;" alt="">
            <img src="{{url('/image/19.png')}}" style="width: 100%; height: 100%;" alt="">
        </div> 

        <form action="/scheduleStatus" class="mt-4" method="POST">
          @csrf
          &nbsp; <input type="hidden" id="stats" name="stats" value="">
          <input type="radio" id="accept" name="status" value="Approved">
          <label for="accept">Approve</label><br>
          <input type="radio" id="reject" name="status" value="Not Approved">
          <label for="reject">Reject</label><br>
          <div class="col-sm-offset-3 d-flex justify-content-center mb-3 col-sm-10">
              <button type="submit" class="btn btn-info">Update Status</button>
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info text-white" id="closeSCmodal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> -->

@endsection