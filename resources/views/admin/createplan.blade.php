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
				<h2>Create Plans</h2>
				<h4>We would love to hear from you !</h4>
			</div>
		</div>
		<div class="col-md-9">
            <form action="/createPlan" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="contact-form">


                    <div class="form-group">
                    <label class="control-label col-sm-2" for="type">Interval:</label>
                    <div class="col-sm-10">
                        <select class="form-select" name="interval" aria-label="Default select example">
                            <option selected value="">Select Interval</option>
                            <option value="free">Free</option>
                            <option value="month">Monthly</option>
                           <!--  <option value="quarter">Quarterly</option>
                            <option value="semiannual">Half Yearly</option> -->
                            <option value="year">Yearly</option>
                        </select>
                    </div>
                    </div>
                    @error('interval')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                    @enderror

                    <div class="form-group">
                    <label class="control-label col-sm-2" for="title">Plan Name:</label>
                    <div class="col-sm-10">          
                        <input type="text" class="form-control" id="name" placeholder="Enter Plan Name" name="name"  value="{{old('name')}}">
                    </div>
                    </div>
                    @error('name')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                    @enderror
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="title">Price:</label>
                    <div class="col-sm-10">          
                        <input type="number" class="form-control" id="price" placeholder="Enter Price" name="price"  value="{{old('price')}}">
                    </div>
                    </div>
                    @error('price')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                    @enderror
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="description">Plan Features:</label>
                    <div class="col-sm-10">
                        @foreach($sfdata as $key=>$row)
                        <div class="checkbox">
                        <label><input type="checkbox" checked="" disabled="" value="{{ $row->id }}" name="plan_features[]">&nbsp;&nbsp;&nbsp;&nbsp;{{ $row->name }}</label>
                        </div>

                        @endforeach
                    </div>
                    </div>
                    <!-- @error('coupon_description')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                    @enderror -->
					
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