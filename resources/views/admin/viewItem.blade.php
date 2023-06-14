@extends('admin.master')


@section('content')

<div class="white-box col-md-12">
        <div class="table-responsive">
            <table class="table text-nowrap"  id="usersTable">
                <thead>
                    <tr>
                        <th class="border-top-0" style="width:5%;">#</th>
                        <th class="border-top-0" style="width:16%;">Category</th>
                        <th class="border-top-0" style="width:16%;">Item Name</th>
                        <th class="border-top-0" style="width:5%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($data))
                         @foreach($data as $key=>$row)
                            <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $row->category_name }}</td>
                            <td>{{ $row->item_name }}</td>
                            <td> 
                            <form method="POST" action="{{ route('admin.deleteItem', $row->id) }}">
                                @csrf
                                <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a>
                                  &nbsp<a data-toggle="modal" data-target="#updateItemModal" id="{{ $row->id }}" class="Itemmodal"><i class="fas fa-edit"></i>
                                </a>
                            </form>
                            </td>
                            </tr>
                        @endforeach
                    @endif    
                </tbody>
                
            </table>
        </div>
</div>  



<div class="modal fade" id="updateItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="float:left !important; " id="myModalLabel">Update Items</h5>
        <button type="button" style="float:right !important;" class="btn btn-info text-white btn-sm rounded-circle" id="closeItemmodal" data-dismiss="modal">X</button>
     
      </div>
      <div class="modal-body">
        <form action="/updateItem" method="POST">
            @csrf
            <input type="hidden" name="id" id="ids" value=""> 
            <label for="email" class="mt-3">Item Name</label>
            <input type="text" name="item_name" class="form-control" id="item_name" value="">
            <!-- <label for="name" class="mt-3">Category</label>
            <input type="text" name="category" class="form-control" id="category" value=""> -->
            <label class="control-label col-sm-2" for="title">Category:</label>
                    <div class="col-sm-12"> 
                        <select class="form-control" name="category" id="category" data-parsley-required="true">
                          <option value="">Select Category</option>
                            @foreach ($category as $row) 
                            {
                              <option value="{{ $row->id }}">{{ $row->category_name }}</option>
                            }
                            @endforeach
                        </select>  
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