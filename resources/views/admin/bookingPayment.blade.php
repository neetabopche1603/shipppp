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
            <thead>
                <tr>
                    <th class="border-top-0">#</th>
                    <th class="border-top-0">Client Name</th>
                    <th class="border-top-0">Booking Date</th>
                    <th class="border-top-0">From</th>
                    <th class="border-top-0">To</th>
                    <th class="border-top-0">Shipment Company</th>
                    <th class="border-top-0">Transaction ID</th>
                    <th class="border-top-0">Status</th>
                    <th class="border-top-0">Total Amount</th>
                    <th class="border-top-0">Action</th>
                </tr>
            </thead>
            <tbody>
                     @foreach($data as $key=>$row)
                        <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->booking_date }}</td>
                        <td>{{ $row->from }}</td>
                        <td>{{ $row->to }}</td>
                        <td>{{ $row->shipment_company }}</td>
                        <td>{{ $row->transaction_id }}</td>
                        <td>{{ $row->status }}</td>
                        <td>{{ $row->total_amount }}</td>
                        <td> 
                        <a href="" class="btn btn-info btn-sm text-white">Pay</a>
                        </td>
                        </tr>
                    @endforeach
            </tbody>
            
        </table>
    </div>
</div>


@endsection