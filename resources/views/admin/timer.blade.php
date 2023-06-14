@extends('admin.master')


@section('content')


    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Set Timer For Booking</h4>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="d-flex justify-content-center">
                <form action="/setTimer" method="POST">
                @csrf
                    <div class="form-group">
                        <label class="control-label col-sm-6" for="title">Set Time</label>
                        <div class="col-sm-10">          
                            <input type="text" class="form-control" id="time" placeholder="Enter Hours" name="time">
                            @error('time')
                            <small id="usercheck" style="color: red;" >
                                {{$message}}
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">        
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default text-dark">Submit</button>
                        </div>
                    </div>
                
                </form>
            </div>
        </div>
    </div>



@endsection