@extends('admin.master')


@section('content')

<div class="white-box col-md-12">
        <div class="table-responsive">
            <table class="table text-nowrap"  id="usersTable">
                <thead>
                    <tr>
                        <th class="border-top-0" style="width:5%;">#</th>
                        <th class="border-top-0" style="width:16%;">Icon</th>
                        <th class="border-top-0" style="width:16%;">Category Name</th>
                        <th class="border-top-0" style="width:5%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($data))
                         @foreach($data as $key=>$row)
                            <tr>
                            <td>{{ $key+1 }}</td>
                            <td><img class="rounded-circle zoom" width="40px" height="40px" src="{{ asset('image/'. $row->icon) }}"/></td>
                            <td>{{ $row->category_name }}</td>
                            <td> 
                            <form method="POST" action="{{ route('admin.deleteCategory', $row->id) }}">
                                @csrf
                                <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a></a>
                            </form>
                            </td>
                            </tr>
                        @endforeach
                    @endif    
                </tbody>
                
            </table>
        </div>
</div>  


@endsection