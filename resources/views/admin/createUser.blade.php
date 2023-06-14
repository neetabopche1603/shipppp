@extends('admin.master')


@section('content')


<style>
    .contact{
		padding: 4%;
		height: 400px;
	}
	.col-md-3{
		background: #ff9b00;
		padding: 4%;
		border-top-left-radius: 0.5rem;
		border-bottom-left-radius: 0.5rem;
	}
	.contact-info{
		margin-top:10%;
	}
	.contact-info img{
		margin-bottom: 15%;
	}
	.contact-info h2{
		margin-bottom: 10%;
	}
	.col-md-9{
		background: #fff;
		padding: 3%;
		border-top-right-radius: 0.5rem;
		border-bottom-right-radius: 0.5rem;
	}
	.contact-form label{
		font-weight:600;
	}
	.contact-form button{
		background: #25274d;
		color: #fff;
		font-weight: 600;
		width: 25%;
	}
	.contact-form button:focus{
		box-shadow:none;
	}
</style>

<div class="container">
    <div class="row">
		<div class="col-md-3">
			<div class="contact-info">
				<img src="https://image.ibb.co/kUASdV/contact-image.png" alt="image"/>
				<h2>Create User</h2>
			</div>
		</div>
		<div class="col-md-9">
            <form action="/createUser" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="contact-form">
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="title">First Name:</label>
                    <div class="col-sm-10">          
                        <input type="text" class="form-control" id="name" placeholder="Enter First Name" name="name">
                        @error('name')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                        @enderror
                    </div>
                    </div>
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="title">Last Name:</label>
                    <div class="col-sm-10">          
                        <input type="text" class="form-control" id="lname" placeholder="Enter Last Name" name="lname">
                        @error('lname')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                        @enderror
                    </div>
                    </div>
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="emial">Email:</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email">
                        @error('email')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                        @enderror
                    </div>
                    </div>
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="password">Password:</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password">
                        @error('password')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                        @enderror
                    </div>
                    </div>
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="phone">Phone:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="phone" placeholder="Enter Phone" name="phone">
                        @error('phone')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                        @enderror
                    </div>
                    </div>
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="address">Address:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="address" placeholder="Enter Address" name="address">
                        @error('address')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                        @enderror
                    </div>
                    </div>
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="address">Country:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="country" placeholder="Enter Country" name="country">
                        @error('country')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                        @enderror
                    </div>
                    </div>
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="profile">Select Profile Pic:</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control" id="file" placeholder="Select Image" name="file">
                        @error('file')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                        @enderror
                    </div>
                    </div>
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="permissions">Select Permission:</label>
                    <div class="col-sm-10">
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="permission[]" value="1" type="checkbox" id="flexSwitchCheckDefault" checked>
                        <label class="form-check-label" for="clientmanagement">Client Management</label>
                        </div>
                        <div class="form-check form-switch">
                        <input class="form-check-input" name="permission[]" value="2" type="checkbox" id="flexSwitchCheckChecked">
                        <label class="form-check-label" for="shipmentmanagement">Shipment Management</label>
                        </div>
                        <div class="form-check form-switch">
                        <input class="form-check-input" name="permission[]" value="3" type="checkbox" id="flexSwitchCheckDisabled">
                        <label class="form-check-label" for="bookingmanagement">Booking Management</label>
                        </div>
                        <div class="form-check form-switch">
                        <input class="form-check-input" name="permission[]" value="4" type="checkbox" id="flexSwitchCheckCheckedDisabled">
                        <label class="form-check-label" for="adsmanagement">Advertisment Management</label>
                        </div>
                        <div class="form-check form-switch">
                        <input class="form-check-input" name="permission[]" value="5" type="checkbox" id="flexSwitchCheckCheckedDisabled">
                        <label class="form-check-label" for="scheudlemanagement">Schedule Management</label>
                        </div>
                        
                    </div>
                    </div>
                    <div class="form-group">        
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Submit</button>
                    </div>
                    </div>
                </div>
            </form>    
		</div>
	</div> 
     
</div>





@endsection