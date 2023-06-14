@extends('admin.master')


@section('content')

<div class="white-box col-md-12">
        <div class="table-responsive">
            <table class="table text-nowrap"  id="usersTable">
                <thead>
                    <tr>
                        <th class="border-top-0" style="width:5%;">#</th>
                        <th class="border-top-0" style="width:8%;">Interval</th>
                        <th class="border-top-0" style="width:8%;">Plan Name</th>
                        <th class="border-top-0" style="width:12%;">Price</th>
                        <!-- <th class="border-top-0" style="width:16%;"></th> -->
                        <th class="border-top-0" style="width:5%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($data))
                         @foreach($data as $key=>$row)
                            <tr>
                            <td>{{ $key+1 }}</td>
                            <td><?php 
                            if($row->intervals=="free"){
                                echo "Free";
                             }else if($row->intervals=="month"){
                                echo "Monthly";
                            }else if($row->intervals=="year"){
                                echo "Yearly";
                            }else if($row->intervals=="semiannual"){
                                echo "Half Yearly";
                            }else if($row->intervals=="quarter"){
                                echo "Quarterly";
                            }else{
                                echo "Free";
                            }
                             ?></td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->price }}</td>
                            <!-- <td>{{ $row->plan_featues }}</td> -->
                           
                            <td> 
                            <form method="POST" action="{{ route('admin.deletePlan', $row->id) }}">
                                @csrf
                                <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a>  &nbsp<a  href="/updatePlan/{{ $row->id }}" ><i class="fas fa-edit"></i></a>
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