@extends('layouts.master')


@section('content')

<div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">All Shipment Companies</h4>
            </div>
        </div>
    </div>

    <div class="white-box">
        <div class="table-responsive">
            <table class="table text-nowrap" id="shipmentTable">
                <thead>
                    <tr>
                        <th class="border-top-0">#</th>
                        <th class="border-top-0">First Name</th>
                        <th class="border-top-0">Email</th>
                        <th class="border-top-0">Phone</th>
                        <th class="border-top-0">Company Name</th>
                        <th class="border-top-0">Country</th>
                        <th class="border-top-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                         @foreach($data as $row)
                            <tr>
                            <td tabindex="0" aria-controls="DataTables_Table_0"  aria-label="Job Title: activate to sort column ascending">{{ $row->id }}</td>
                            <td tabindex="0" aria-controls="DataTables_Table_0"  aria-label="Job Title: activate to sort column ascending">{{ $row->name }}</td>
                            <td tabindex="0" aria-controls="DataTables_Table_0"  aria-label="Job Title: activate to sort column ascending">{{ $row->email }}</td>
                            <td tabindex="0" aria-controls="DataTables_Table_0"  aria-label="Job Title: activate to sort column ascending">{{ $row->phone }}</td>
                            <td tabindex="0" aria-controls="DataTables_Table_0"  aria-label="Job Title: activate to sort column ascending">{{ $row->companyname }}</td>
                            <td tabindex="0" aria-controls="DataTables_Table_0"  aria-label="Job Title: activate to sort column ascending">{{ $row->country }}</td>
                            <td> 
                            <form method="POST" action="{{ route('subadmin.subAdminDeleteShipment', $row->id) }}">
                                @csrf
                                <a href="" class="show_confirm"><i class="fa fa-trash-alt"></i></a> </form>
                            </td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection