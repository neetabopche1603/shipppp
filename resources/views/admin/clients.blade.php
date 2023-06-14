@extends('admin.master')


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
            <div class="d-flex justify-content-center">
                  <select id="mySelect" class="form-check">
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
                        <th class="border-top-0">Last Name</th>
                        <th class="border-top-0">Email</th>
                        <th class="border-top-0">Phone</th>
                        <th class="border-top-0">Country Code</th>
                        <th class="border-top-0">Country</th>
                        <th class="border-top-0">Address</th>
                        <th class="border-top-0">Status</th>
                        <th class="border-top-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                         @foreach($data as $key=>$row)
                            <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->lname }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->phone }}</td>
                            <td>+{{ $row->country_code }}</td>
                            <td>{{ $row->country }}</td>
                            <td>{{ $row->address }}</td>
                            <td> {{ $row->status }}
                              <!-- <a data-toggle="modal" data-target="#bookingModal" id="{{ $row->id }}" class="Bmodal"> &nbsp; <i class="fas fa-edit"></i></a> -->
                            </td>
                            <!-- <td><a href="/changeClientStatus">{{ $row->status }}</a></td> -->
                            <td> 
                              <a href="/clientDetails/{{ $row->id }}" class="btn btn-info btn-sm text-white"> Details </a>
                              <!-- <form method="POST" action="/clientDetails">
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

<div class="modal1 fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style=" height: 308px !important;
          width: 500px !important;">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Client Status</h5>
          <button type="button" class="btn btn-info btn-sm text-white rounded-circle" id="closeBmodal" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body">
        <!-- <form action="/updateStatus" method="POST">
          @csrf
          <input type="hidden" id="email" name="email" value="">
          <label for="status" class="mt-3">Current Status</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="clientA" value="Approved">
            <label class="form-check-label" for="exampleRadios1">
              Approve
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="clientNA" value="Not Approve">
            <label class="form-check-label" for="exampleRadios2">
              Not Approve
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="clientB" value="Block">
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



@endsection