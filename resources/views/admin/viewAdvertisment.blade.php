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
                            <td><img class="" width="150px" height="100px" src="{{ asset('advertisment/'. $row->image) }}"/></td>
                            <td>{{ $row->description }}</td>
                            <td> 
                            <form method="POST" action="{{ route('admin.deleteAdvertisment', $row->id) }}">
                                @csrf
                                <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a>  &nbsp;
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