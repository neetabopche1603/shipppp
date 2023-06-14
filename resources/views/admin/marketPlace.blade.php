@extends('admin.master')


@section('content')


    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Market Place</h4>
            </div>
        </div>
    </div>

    <div class="white-box">
        <div class="table-responsive">
            

            <table class="table text-nowrap" id="marketTable">
            <div class="d-flex justify-content-center">
                  <select id="mySelectM" class="form-check">
                      <option value="">All</option>
                      <option value="created">Created Only</option>
                      <option value="accepted">Accepted</option>
                      <option value="delivered">Delivered</option>
                  </select>
            </div>
                <thead>
                    <tr>
                        <th class="border-top-0">#</th>
                        <th class="border-top-0">Client</th>
                        <th class="border-top-0">Shipment Company</th>
                        <th class="border-top-0">Booking Date</th>
                        <th class="border-top-0">Status</th>
                        <th class="border-top-0">Transaction ID</th>
                        <th class="border-top-0">Total Amount</th>
                        <th class="border-top-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                         @foreach($data as $key=>$row)
                   
                            <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->companyname }}</td>
                            <td>{{ $row->booking_date }}</td>
                            <td> {{ $row->status }}
                                <!-- <a data-toggle="modal" data-target="#marketModal" id="{{ $row->id }}" class="Mmodal"> &nbsp; <i class="fas fa-edit"></i></a> -->
                            </td>
                            <td>{{ $row->transaction_id }}</td>
                            <td>{{ $row->total_amount }}</td>
                            <!-- <td><a href="/changeClientStatus">{{ $row->status }}</a></td> -->
                            <td> 
                              <a href="/marketDetails/{{ $row->id }}" class="btn btn-info btn-sm text-white">Details</a>
                              {{-- <a href="" class="btn btn-danger btn-sm text-white">Pay</a> --}}
                              <!-- <form method="POST" action="/marketDetails">
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



<div class="modal fade" id="marketModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Client Booking Status</h5>
          <button type="button" class="btn btn-info text-white rounded-circle" id="closeBmodal" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body">
        <form action="/updateClientMarketStatus" method="POST">
          @csrf
          <input type="hidden" id="idm" name="id" value="">
          <label for="status" class="mt-3">Current Status</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="clientA" value="created">
            <label class="form-check-label" for="exampleRadios1">
              Created
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="clientNA" value="accepted">
            <label class="form-check-label" for="exampleRadios2">
            Accept
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="clientB" value="delivered">
            <label class="form-check-label" for="exampleRadios3">
            Delivered
            </label>
          </div>
            <div class="col-sm-offset-2 d-flex justify-content-center mb-3 col-sm-10 my-3">
                <button type="submit" class="btn btn-info">Change Status</button>
            </div>
        </form>
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div> 


@endsection