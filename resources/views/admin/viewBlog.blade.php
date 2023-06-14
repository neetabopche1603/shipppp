@extends('admin.master')


@section('content')

<div class="white-box col-md-12">
        <div class="table-responsive">
            <table class="table text-nowrap" id="advertismentTable">
                <thead>
                    <tr>
                        <th class="border-top-0">#</th>
                        <th class="border-top-0">Title</th>
                        <th class="border-top-0">Image</th>
                        <th class="border-top-0">Description</th>
                        <th class="border-top-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($data))
                         @foreach($data as $row)
                            <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->title }}</td>
                            <td><img class="" width="150px" height="100px" src="{{ asset('blog/'. $row->image) }}"/></td>
                            <td>{{ $row->description }}</td>
                            <td> 
                            <form method="POST" action="{{ route('admin.deleteBlog', $row->id) }}">
                                @csrf
                                <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a>  &nbsp;<a data-toggle="modal" data-target="#reviewModal" id="{{ $row->id }}" class="Rmodal"><i class="fa fa-eye"></i></a>
                            </form>
                            </td>
                            </tr>
                        @endforeach
                    @endif    
                </tbody>
                
            </table>
        </div>
</div>  


<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Booking Items</h5>
      </div>
      <div class="modal-body">
        
        <label for="rating" class="mt-3">Rating</label>
      
        <p>
            <div class="overlay" id="star" style="position: relative;top: -22px; color:#FF9529;">

            </div> 
        </p>
        <label for="review" class="mt-3">Review</label>
        <input type="text" name="review" class="form-control" id="review" value="" Disabled>
        <label for="user" class="mt-3">User</label>
        <input type="text" name="user" class="form-control" id="user" value="" Disabled>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info text-white" id="closeRmodal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


@endsection