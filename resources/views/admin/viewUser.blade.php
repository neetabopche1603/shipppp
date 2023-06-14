@extends('admin.master')


@section('content')

<div class="white-box col-md-12">
        <div class="table-responsive">
            <table class="table text-nowrap"  id="usersTable">
                <thead>
                    <tr>
                        <th class="border-top-0" style="width:5%;">#</th>
                        <th class="border-top-0" style="width:16%;">Profile Pic</th>
                        <th class="border-top-0" style="width:16%;">Name</th>
                        <th class="border-top-0" style="width:16%;">Email</th>
                        <!-- <th class="border-top-0" style="width:16%;">Phone</th>  -->
                        <th class="border-top-0" style="width:16%;">Address</th>
                        <th class="border-top-0" style="width:16%;">Country</th>
                        <th class="border-top-0" style="width:16%;">Permissions</th>
                        <th class="border-top-0" style="width:5%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($data))
                         @foreach($data as $row)
                            <tr>
                            <td>{{ $row->id }}</td>
                            <td><img class="rounded-circle zoom" width="40px" height="40px" src="{{ asset('profile/'. $row->profileimage) }}"/></td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->email }}</td>
                            <!-- <td>{{ $row->phone }}</td> -->
                            <td>{{ $row->address }}</td>
                            <td>{{ $row->country }}</td>
                            <td>
                                @if($row->roles != '' && $row->roles != null && $row->roles != 'null' && $row->roles != NULL && is_array(json_decode($row->roles)))
                                    @foreach(json_decode($row->roles) as $value)
                                        <?php 
                                        if($value == 1)
                                        { $value = 'Client Management'; echo $value; echo "<br>";  }
                                        if($value == 2)
                                        { $value = 'Shipment Management'; echo $value; echo "<br>";  }
                                        if($value == 3)
                                        { $value = 'Booking Management'; echo $value; echo "<br>";  }
                                        if($value == 4)
                                        { $value = 'Advertisment Management'; echo $value; echo "<br>"; }
                                        if($value == 5)
                                        { $value = 'Schedule Shipment Management'; echo $value; echo "<br>"; }     
                                        ?>  
                                    @endforeach
                                @endif
                            </td>
                            <!-- <td>{{ $row->permission }}</td> -->
                            <td> 
                            <form method="POST" action="{{ route('admin.deleteUser', $row->id) }}">
                                @csrf
                                <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a>  &nbsp<a data-toggle="modal" data-target="#updateUserModal" id="{{ $row->id }}" class="Umodal"><i class="fas fa-edit"></i></a>
                            </form>
                            </td>
                            </tr>
                        @endforeach
                    @endif    
                </tbody>
                
            </table>
        </div>
</div>  



<div class="modal fade" id="updateUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="float:left !important; " id="myModalLabel">Update Users</h5>
        <button type="button" style="float:right !important;" class="btn btn-info text-white btn-sm rounded-circle" id="closeUmodal" data-dismiss="modal">X</button>
     
      </div>
      <div class="modal-body">
        <form action="/updateUser" method="POST">
            @csrf
            <label for="email" class="mt-3">Email</label>
            <input type="text" name="email" class="form-control" id="email" value="" readonly>
            <label for="name" class="mt-3">Name</label>
            <input type="text" name="name" class="form-control" id="name" value="">
            <label for="phone" class="mt-3">Phone</label>
            <input type="text" name="phone" class="form-control" id="phone" value="">
            <label for="address" class="mt-3">Address</label>
            <input type="text" name="address" class="form-control" id="address" value="">
            <label for="country" class="mt-3">Country</label>
            <input type="text" name="country" class="form-control" id="country" value="">
            <label for="permission" class="mt-3">Permissions</label>
            
                <div class="form-check form-switch">
                <input class="form-check-input" name="permission[]" value="1" type="checkbox" id="clientM">
                <label class="form-check-label" for="clientmanagement">Client Management</label>
                </div>
                <div class="form-check form-switch">
                <input class="form-check-input" name="permission[]" value="2" type="checkbox" id="shipmentM">
                <label class="form-check-label" for="shipmentmanagement">Shipment Management</label>
                </div>
                <div class="form-check form-switch">
                <input class="form-check-input" name="permission[]" value="3" type="checkbox" id="bookingM">
                <label class="form-check-label" for="bookingmanagement">Booking Management</label>
                </div>
                <div class="form-check form-switch">
                <input class="form-check-input" name="permission[]" value="4" type="checkbox" id="adsM">
                <label class="form-check-label" for="adsmanagement">Advertisment Management</label>
                </div>
                <div class="form-check form-switch">
                <input class="form-check-input" name="permission[]" value="5" type="checkbox" id="scheduleM">
                <label class="form-check-label" for="scheudlemanagement">Schedule Management</label>
                </div>

            <div class="d-flex justify-content-center mt-3 col-12">
                <button type="submit" class="btn btn-info text-white btn-sm">Update</button>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        </div>
    </div>
  </div>
</div>  



@endsection