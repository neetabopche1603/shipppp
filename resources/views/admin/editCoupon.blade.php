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
				<h2>Create Coupons</h2>
				<h4>We would love to hear from you !</h4>
			</div>
		</div>
		<div class="col-md-9">
            <form action="/updateCoupons" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" value="{{ $data->id }}" name="id">
                <div class="contact-form">
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="title">Coupon Name:</label>
                    <div class="col-sm-10">          
                        <input type="text" class="form-control" id="coupon_name" placeholder="Enter Coupon Name" name="coupon_name"  value="{{ $data->coupon_name }}">
                    </div>
                    </div>
                    @error('coupon_name')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                    @enderror
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="title">Coupon Code:</label>
                    <div class="col-sm-10">          
                        <input type="text" class="form-control" id="coupon_code" placeholder="Enter Coupon Code" name="coupon_code"  value="{{ $data->coupon_code }}">
                    </div>
                    </div>
                    @error('coupon_code')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                    @enderror
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="description">Description:</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="coupon_description" placeholder="Describe Your Coupon" rows="5" id="coupon_description">{{ $data->coupon_description }}</textarea>
                    </div>
                    </div>
                    @error('coupon_description')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                    @enderror
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="type">Coupon Type:</label>
                    <div class="col-sm-10">
						<select class="form-select" name="coupon_type" aria-label="Default select example">
							<option selected>Select Coupon Type</option>
							<option value="percentage" {{ $data->coupon_type == "percentage" ? 'selected="selected"' : '' }}>Percentage</option>
							<option value="fix_amount" {{ $data->coupon_type == "fix_amount" ? 'selected="selected"' : '' }}>Fix Amount</option>
						</select>
					</div>
                    </div>
                    @error('coupon_type')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                    @enderror
					<div class="form-group">
                    <label class="control-label col-sm-2" for="amount">Amount:</label>
                    <div class="col-sm-10">          
                        <input type="text" class="form-control" id="coupon_amount" placeholder="Enter Coupon Amount" name="coupon_amount" value="{{ $data->coupon_amount }}">
                    </div>
                    </div>
                    @error('coupon_amount')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                    @enderror
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="permissions">Select Status:</label>
                    <div class="col-sm-10">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="active" value="1" {{ ($data->status=="1")? "checked" : "" }} >
                        <label class="form-check-label" for="clientmanagement">Active</label>
                        </div>
                    </div>
                    </div>
                    <div class="form-group">        
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Update</button>
                    </div>
                    </div>
                </div>
            </form>    
		</div>
	</div> 
     
</div>


@endsection 