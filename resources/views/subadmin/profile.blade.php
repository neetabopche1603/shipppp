@extends('layouts.master')


@section('content')
<?php $data = $data[0]  ?>
<div class="container rounded bg-white">
    <div class="row">
                <div class="col-md-3 border-right">  
        <form action="/subAdminUpdateProfile" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="image-upload d-flex flex-column align-items-center text-center p-3 py-5">
                    <label for="file-input">
                        <img class="rounded-circle mt-5" width="200px" height="200px" src="{{ asset('profile/'. $data->profileimage) }}"/>
                        <span>
                        <button type="button" id="pop" class="btn btn-info mt-3 btn-sm" data-toggle="modal" data-target="#imagemodal">
                             View Full Photo
                        </button>
                        </span>
                    </label>
                    <input id="file-input" name="file" type="file" />
                </div>
                        <!-- <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"><span class="font-weight-bold">{{ Auth::user()->name }}</span><span class="text-black-50">{{ Auth::user()->email }}</span><span> </span></div> -->
                </div>
                <div class="col-md-5 border-right">
                    <div class="p-3 py-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right">Profile Settings</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6"><label class="labels">Name</label><input type="text" class="form-control" name="name" value="{{ $data->name }}"></div>
                                <div class="col-md-6"><label class="labels">Surname</label><input type="text" class="form-control" value="{{ $data->lname }}" name="lname"></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12"><label class="labels">Mobile Number</label><input type="text" class="form-control" name="phone" value="{{ $data->phone }}"></div>
                                <div class="col-md-12 mt-2"><label class="labels">Email ID</label><input type="email" class="form-control" name="email" value="{{ $data->email }}" readonly></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6"><label class="labels">Country</label><input type="text" class="form-control" name="country" value="{{ $data->country }}"></div>
                                <div class="col-md-6"><label class="labels">Address</label><input type="text" class="form-control" value="{{ $data->address }}" name="address"></div>
                            </div>
                            <div class="mt-5 text-center">
                                <button class="btn btn-primary" type="submit" value="submit">Save Profile</button>
                            </div>
                    </div>
                </div>
        </form>
                <div class="col-md-3 py-5 border-right">
                    <form action="/subAdminResetAdminPassword" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12"><label class="labels">Email ID</label><input type="email" class="form-control" name="email" value="{{ $data->email }}" readonly="true"></div>
                        <div class="col-md-12 mt-2"><label class="labels">Enter New Password</label><input type="password" class="form-control" name="password" placeholder="Enter New Password"></div>
                        <div class="mt-3 text-center">
                             <button class="btn btn-danger" type="submit">Reset Password</button>
                        </div>
                    </form>
                </div>
    </div>
</div>

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Image Preview</h5>
      </div>
      <div class="modal-body">
        <img src="{{ asset('profile/'. $data->profileimage) }}" id="imagepreview" style="width: 100%; height: 100%;" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="closemodal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>




@endsection