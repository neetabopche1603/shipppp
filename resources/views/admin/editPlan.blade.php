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
            <form action="/updatePlans" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" value="{{ $data->id }}" name="id">
                <div class="contact-form">


                    <div class="form-group">
                    <label class="control-label col-sm-2" for="type">Interval:</label>
                    <div class="col-sm-10">
                        <select class="form-select" name="interval" aria-label="Default select example">
                            <option selected value="">Select Interval</option>
                            <option value="free" {{ $data->intervals == "free" ? 'selected="selected"' : '' }}>Free</option>
                            <option value="month" {{ $data->intervals == "month" ? 'selected="selected"' : '' }}>Monthly</option>
                           <!--  <option value="quarter" {{ $data->intervals == "quarter" ? 'selected="selected"' : '' }}>Quarterly</option>
                            <option value="semiannual" {{ $data->intervals == "semiannual" ? 'selected="selected"' : '' }}>Half Yearly</option> -->
                            <option value="year" {{ $data->intervals == "year" ? 'selected="selected"' : '' }}>Yearly</option>
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
                        
                        <input type="text" class="form-control" id="name" placeholder="Enter Plan Name" name="name"  value="{{ $data->name }}">
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
                        <input type="number" class="form-control" id="price" placeholder="Enter Price" name="price"  value="{{ $data->price}}">
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
                            <?php //echo $data->plan_features;?>
                        <label><input type="checkbox" disabled="" value="{{ isset($row->id) ? $row->id : '' }}" name="plan_features[]" {{ isset($row->id) && isset($data->plan_features) ? in_array($row->id, explode(',', $data->plan_features)) ? 'checked' : '' : '' }}>&nbsp;&nbsp;&nbsp;&nbsp;{{ $row->name }}

                        </label>
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
                        <button type="submit" class="btn btn-default">Update</button>
                    </div>
                    </div>
                </div>
            </form>    
		</div>
	</div> 
     
</div>


@endsection 