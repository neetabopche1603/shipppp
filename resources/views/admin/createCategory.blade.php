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
				<h2>Create Items category</h2>
			</div>
		</div>
		<div class="col-md-9">
            <form action="/createCategories" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="contact-form">
                    <div class="form-group">
                    <label class="control-label col-sm-2" for="title">Category Name:</label>
                    <div class="col-sm-10">          
                        <input type="text" class="form-control" value="{{ old('category_name') }}" id="category_name" placeholder="Enter Item Name" name="category_name">
                        @error('category_name')
                        <small id="usercheck" style="color: red;" >
                            {{$message}}
                        </small>
                        @enderror
                    </div>
                    </div>
					<div class="form-group">
                    <label class="control-label col-sm-2" for="title">Category Icon:</label>
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