@extends('admin.master')


@section('content')

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">All Transactions</h4>
            </div>
        </div>
    </div>

    <div class="white-box">
        <div class="table-responsive">
            <table class="table text-nowrap" id="BookingTable">
                
                
                <thead>
                    <tr>
                        <th class="border-top-0">#</th>
                        <th class="border-top-0">Name</th>
                        <th class="border-top-0">Email</th>
                        <th class="border-top-0">Phone</th>
                        <th class="border-top-0">Interval</th>
                        <th class="border-top-0">Plan Name</th>
                        <th class="border-top-0">Amount</th>
                        <th class="border-top-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                         @foreach($data as $key=>$row)
                            <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $row->name }} {{ $row->lname }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->phone }}</td>
                            <td>{{ $row->plan_interval }}</td>
                            <td>{{ $row->plan_type }}</td>
                            <td>{{ $row->amount }}</td>
                            

                    
                            <td> 
                            <!-- <form method="POST" action="{{ route('admin.deleteBooking', $row->id) }}">
                                @csrf
                                <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a>
                            </form> -->

                             <a href="/viewTransactionDetails/{{ $row-> id }}" class="btn btn-info btn-sm text-white">Details</a>
                            
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