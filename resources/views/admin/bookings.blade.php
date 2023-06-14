@extends('admin.master')


@section('content')

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">All Bookings</h4>
            </div>
        </div>
    </div>

    <div class="white-box">
        <div class="table-responsive">
            <table class="table text-nowrap" id="BookingTable">
                <div class="d-flex justify-content-center">
                      <select id="mySelectBooking" class="form-check">
                          <option value="">All</option>
                          <option value="Accepted">Accepted</option>
                          <option value="going to pickup">Going To Pickup</option>
                          <option value="pickup done">Pickup Done</option>
                          <option value="assign to agent">Assign to Agent</option>
                          <option value="pickup item received">Pickup Item Received</option>
                          <option value="delivered to receptionist">Delivered to Receptionist</option>
                          <option value="item received">Item Received</option>
                      </select>
                </div>
                <div class="d-flex justify-content-end">
                  <a href="/set-timer" class="btn btn-primary">Set Timer</a>
                </div>
                <thead>
                    <tr>
                        <th class="border-top-0">#</th>
                        <th class="border-top-0">Client Name</th>
                        <th class="border-top-0">Booking Date</th>
                        <th class="border-top-0">Booking Type</th>
                        <th class="border-top-0">From</th>
                        <th class="border-top-0">To</th>
                        <th class="border-top-0">Shipment Company</th>
                        <th class="border-top-0">Status</th>
                        <th class="border-top-0">Transaction ID</th>
                        <th class="border-top-0">Total Amount</th>
                        <!-- <th class="border-top-0">Pickup/Dropoff</th> -->
                        <th class="border-top-0">Pickup Type</th>
                        <th class="border-top-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                         @foreach($data as $key=>$row)
                            <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->booking_date }}</td>
                            <td>{{ $row->booking_type }}</td>
                            <td>{{ $row->from }}</td>
                            <td>{{ $row->to }}</td>
                            <td>{{ $row->shipment_company }}</td>
                            <td>{{ $row->status }}</td>
                            <td>{{ $row->transaction_id }}</td>
                            <td>{{ $row->total_amount }}</td>
                            @php  
                              $pickup = json_decode($row->pickup_review);
                            @endphp
                            <!-- <td>{{ $row->pickup_review }}</td> -->
                            <td>{{ $pickup[0]->pickup_type }}</td>
                            <td> 
                            <!-- <form method="POST" action="{{ route('admin.deleteBooking', $row->id) }}">
                                @csrf
                                <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a>
                            </form> -->
                            <a href="/bookingDetails/{{ $row-> id }}" class="btn btn-info btn-sm text-white">Details</a>
                            {{-- <a href="/payCompany/{{ $row->id }}" class="btn btn-danger btn-sm text-white">Pay</a> --}}
                            <!-- <form method="POST" action="/bookingDetails">
                                  @csrf
                                  <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a>  &nbsp;
                                  <input type="hidden" name="id" value="{{ $row->id }}">
                                  <button type="submit" class="btn btn-info btn-sm text-white">Details</button>
                            </form> -->
                            </td>
                            </tr>
                        @endforeach
                </tbody>
                
            </table>
        </div>
    </div>

<!-- 
<div class="modal fade" id="itemmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Booking Items</h5>
      </div>
      <div class="modal-body">
        

          <div id="itemImage">
            
          </div>  
          
        
        <label for="description" class="mt-3">Description</label>
        <input type="text" name="description" class="form-control" id="desc" value="" Disabled>
        <label for="booked_by" class="mt-3">Booked By</label>
        <input type="text" name="booked_by" class="form-control" id="bookedby" value="" Disabled>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info text-white" id="closeItemmodal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
 -->




@endsection