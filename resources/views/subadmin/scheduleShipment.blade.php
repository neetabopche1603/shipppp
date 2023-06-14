@extends('layouts.master')


@section('content')

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">All Scheduled Shipment</h4>
            </div>
        </div>
    </div>

    <div class="white-box">
        <div class="table-responsive">
            <table class="table text-nowrap" id="clientTable">
                <thead>
                    <tr>
                        <th class="border-top-0">#</th>
                        <th class="border-top-0">Shipment Type</th>
                        <th class="border-top-0">From</th>
                        <th class="border-top-0">To</th>
                        <th class="border-top-0">Departure Date</th>
                        <th class="border-top-0">Destination Warehouse</th>
                        <th class="border-top-0">Item Type</th>
                        <th class="border-top-0">Shipping Fee</th>
                        <th class="border-top-0">Pickup Fee</th>
                        <!-- <th class="border-top-0">Status</th> -->
                        <th class="border-top-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                         @foreach($data as $row)
                            <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->shipment_type }}</td>
                            <td>{{ $row->from }}</td>
                            <td>{{ $row->to }}</td>
                            <td>{{ $row->departure_date }}</td>
                            <td>{{ $row->destination_warehouse }}</td>
                            <td>{{ $row->item_type }}</td>
                            <td>{{ $row->shipping_fee }}</td>
                            <td>{{ $row->pickup_fee }}</td>
                            <!-- <td>{{ $row->status }}</td> -->
                            <td> 
                            <form method="POST" action="{{ route('subadmin.subAdminDeleteSchedule', $row->id) }}">
                                @csrf
                                <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a> &nbsp; <a data-toggle="modal" data-target="#shipmentmodal" id="{{ $row->sid }}" class="Smodal"><i class="fa fa-eye"></i></a>
                               
                            </form>
                            </td>
                            </tr>
                        @endforeach
                </tbody>
                
            </table>
        </div>
    </div>


<div class="modal fade" id="shipmentmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Scheduled Shipment Details</h5>
      </div>
      <div class="modal-body">
        

          <div id="docsImage">
            
          </div>  
          
        
        <label for="description" class="mt-3">Shipment Company</label>
        <input type="text" name="description" class="form-control" id="scompany" value="" Disabled>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info text-white" id="closeSmodal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Scheduled Shipment Details</h5>
      </div>
      <div class="modal-body">
      
      <form action="/scheduleStatus" method="POST">
        @csrf
        &nbsp; <input type="hidden" id="stats" name="stats" value="">
        <input type="radio" id="accept" name="status" value="accept">
        <label for="accept">Accept</label><br>
        <input type="radio" id="reject" name="status" value="reject">
        <label for="reject">Reject</label><br>
        <div class="col-sm-offset-2 d-flex justify-content-center mb-3 col-sm-10">
            <button type="submit" class="btn btn-info">Update Status</button>
        </div>
      </form>
  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info text-white" id="closeSTmodal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> -->



@endsection