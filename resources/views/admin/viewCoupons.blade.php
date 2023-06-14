@extends('admin.master')


@section('content')

<div class="white-box col-md-12">
        <div class="table-responsive">
            <table class="table text-nowrap"  id="usersTable">
                <thead>
                    <tr>
                        <th class="border-top-0" style="width:5%;">#</th>
                        <th class="border-top-0" style="width:8%;">Title</th>
                        <th class="border-top-0" style="width:12%;">Coupon Code</th>
                        <th class="border-top-0" style="width:16%;">description</th>
                        <!-- <th class="border-top-0" style="width:16%;">Type</th>
                        <th class="border-top-0" style="width:16%;">Amount</th> -->
                        <th class="border-top-0" style="width:6%;">Status</th>
                        <th class="border-top-0" style="width:5%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($data))
                         @foreach($data as $key=>$row)
                            <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $row->coupon_name }}</td>
                            <td>{{ $row->coupon_code }}</td>
                            <td>{{ $row->coupon_description }}</td>
                            <!-- <td>{{ $row->coupon_type}}</td>
                            <td>{{ $row->coupon_amount }}</td> -->
                            @if($row->status == 0)
                            <td>Deactive</td>
                            @else
                            <td>Active</td>
                            @endif
                            <td> 
                            <form method="POST" action="{{ route('admin.deleteCoupon', $row->id) }}">
                                @csrf
                                <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a>  &nbsp<a  href="/updateCoupon/{{ $row->id }}" ><i class="fas fa-edit"></i></a>
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