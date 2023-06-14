@extends('layouts.master')


@section('content')

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">All Clients</h4>
            </div>
        </div>
    </div>

    <div class="white-box">
        <div class="table-responsive">
            <table class="table text-nowrap" id="clientTable">
                <thead>
                    <tr>
                        <th class="border-top-0">#</th>
                        <th class="border-top-0">First Name</th>
                        <th class="border-top-0">Last Name</th>
                        <th class="border-top-0">Email</th>
                        <th class="border-top-0">Phone</th>
                        <th class="border-top-0">Country</th>
                        <th class="border-top-0">Address</th>
                        <th class="border-top-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                         @foreach($data as $row)
                            <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->lname }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->phone }}</td>
                            <td>{{ $row->country }}</td>
                            <td>{{ $row->address }}</td>
                            <td> 
                            <!-- <form method="POST" action="{{ route('admin.deleteClient', $row->id) }}">
                                @csrf
                                <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a>  &nbsp; -->
                                 <a data-toggle="modal" data-target="#bookingModal" id="{{ $row->id }}" class="Bmodal"><i class="fa fa-eye"></i></a>
                            <!-- </form> -->
                            </td>
                            </tr>
                        @endforeach
                </tbody>
                
            </table>
        </div>
    </div>

<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Client Bookings</h5>
      </div>
      <div class="modal-body">
        <label for="description" class="mt-3">Booking Date</label>
        <input type="text" name="booking_date" class="form-control" id="bdate" value="" Disabled>
        <label for="booked_by" class="mt-3">Booking Type</label>
        <input type="text" name="booking_type" class="form-control" id="btype" value="" Disabled>
        <label for="description" class="mt-3">From</label>
        <input type="text" name="from" class="form-control" id="from" value="" Disabled>
        <label for="booked_by" class="mt-3">To</label>
        <input type="text" name="to" class="form-control" id="to" value="" Disabled>
        <label for="description" class="mt-3">Shipment Company</label>
        <input type="text" name="Shipment_company" class="form-control" id="scompany" value="" Disabled>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info text-white" id="closeBmodal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>    



@endsection