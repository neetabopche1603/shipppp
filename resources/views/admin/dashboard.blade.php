@extends('admin.master')


@section('content')
  <!-- Content Wrapper. Contains page content -->
<style>

  .wh-hei {
    height: 200px !important;
}
.white-box .box-title {
    font-weight: 700;
    line-height: 30px;
    font-size: 13px;
}

</style>
        <div class="container-fluid">
           <!-- ============================================================== -->
           <!-- Three charts -->
           <!-- ============================================================== -->
           <div class="row justify-content-center">
              <div class="col-lg-3 col-md-3">
                <div class="white-box wh-hei analytics-info">
                    <h3 class="box-title">
                       <a href="/clients" class="text-dark"> Total Clients </a>
                       <span style="float: right;" class="counter text-success">
                       @if($data)
                       {{ $data['users'] }}
                       @else
                       {{ 0 }}
                       @endif</span>
                    </h3>
                    <h3 class="box-title">
                       <a href="/clients" class="text-dark"> Total Approved Clients </a>
                       <span style="float: right;" class="counter text-success">
                       @if($data)
                       {{ $data['approvedusers'] }}
                       @else
                       {{ 0 }}
                       @endif</span>
                    </h3>
                    <h3 class="box-title">
                       <a href="/clients" class="text-dark"> Total Not Approved Clients </a>
                       <span style="float: right;" class="counter text-success">
                       @if($data)
                       {{ $data['notapproveusers'] }}
                       @else
                       {{ 0 }}
                       @endif</span>
                    </h3>
                    <h3 class="box-title">
                       <a href="/clients" class="text-dark"> Total Deactive Clients </a>
                       <span style="float: right;" class="counter text-success">
                       @if($data)
                       {{ $data['blockedusers'] }}
                       @else
                       {{ 0 }}
                       @endif</span>
                    </h3>
                </div>
              </div>
              <div class="col-lg-3 col-md-3">
                <div class="white-box wh-hei analytics-info">
                    <h3 class="box-title">
                       <a href="/shipment" class="text-dark"> Total Shipment Companies </a>
                       <span style="float: right;" class="counter text-success">
                       @if($data)
                       {{ $data['companies'] }}
                       @else
                       {{ 0 }}
                       @endif</span>
                    </h3>
                    <h3 class="box-title">
                       <a href="/shipment" class="text-dark"> Total Approved Companies </a>
                       <span style="float: right;" class="counter text-success">
                       @if($data)
                       {{ $data['approvedshipment'] }}
                       @else
                       {{ 0 }}
                       @endif</span>
                    </h3>
                    <h3 class="box-title">
                       <a href="/shipment" class="text-dark"> Total NotApproved Companies </a>
                       <span style="float: right;" class="counter text-success">
                       @if($data)
                       {{ $data['notapproveshipment'] }}
                       @else
                       {{ 0 }}
                       @endif</span>
                    </h3>
                    <h3 class="box-title">
                       <a href="/shipment" class="text-dark"> Total Deactive Companies </a>
                       <span style="float: right;" class="counter text-success">
                       @if($data)
                       {{ $data['blockedshipment'] }}
                       @else
                       {{ 0 }}
                       @endif</span>
                    </h3>
                </div>
              </div>
              <div class="col-lg-3 col-md-3">
                <div class="white-box wh-hei analytics-info">
                    <h3 class="box-title">
                       <a href="/viewBooking" class="text-dark"> Total Bookings </a>
                       <span style="float: right;" class="counter text-success">
                       @if($data)
                       {{ $data['orders'] }}
                       @else
                       {{ 0 }}
                       @endif</span>
                    </h3>
                    <h3 class="box-title">
                       <a href="/viewBooking" class="text-dark"> Total Confirmed Bookings </a>
                       <span style="float: right;" class="counter text-success">
                       @if($data)
                       {{ $data['approvedbooking'] }}
                       @else
                       {{ 0 }}
                       @endif</span>
                    </h3>
                    <h3 class="box-title">
                       <a href="/viewBooking" class="text-dark"> Total Not Confirmed Bookings </a>
                       <span style="float: right;" class="counter text-success">
                       @if($data)
                       {{ $data['notapprovedbooking'] }}
                       @else
                       {{ 0 }}
                       @endif</span>
                    </h3>
                    <h3 class="box-title">
                       <a href="/viewBooking" class="text-dark"> Total Penidng Booking </a>
                       <span style="float: right;" class="counter text-success">
                       @if($data)
                       {{ $data['pendingbooking'] }}
                       @else
                       {{ 0 }}
                       @endif</span>
                    </h3>
                </div>
              </div>
              <div class="col-lg-3 col-md-3">
                <div class="white-box wh-hei analytics-info">
                    <h3 class="box-title">
                        <a href="#" class="text-dark" style="cursor: context-menu;"> Total Pickup Agent </a>
                        <span style="float: right;" class="counter text-success">
                        @if($data)
                        {{ $data['agents'] }}
                        @else
                        {{ 0 }}
                        @endif</span>
                    </h3>
                    <h3 class="box-title">
                        <a href="#" class="text-dark" style="cursor: context-menu;"> Total Assigned Agents </a>
                        <span style="float: right;" class="counter text-success">
                        @if($data)
                        {{ $data['agents'] }}
                        @else
                        {{ 0 }}
                        @endif</span>
                    </h3>
                    <h3 class="box-title">
                        <a href="#" class="text-dark" style="cursor: context-menu;"> Total Free Agents </a>
                        <span style="float: right;" class="counter text-success">
                        @if($data)
                        {{ $data['agents'] }}
                        @else
                        {{ 0 }}
                        @endif</span>
                    </h3>
                </div>
              </div>
            </div>
        </div>
<!-- </div> -->
<!-- ============================================================== -->
<!-- PRODUCTS YEARLY SALES -->
<!-- ============================================================== -->
<div class="row">
   <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
      <div class="white-box">
         <h3 class="box-title">Total Users (Sub Admin)</h3>
         <div class="d-flex justify-content-center">
            <div class="col-lg-10 col-md-12  col-sm-12">
               <canvas id="myChart" aria-label="chart" role="img"></canvas>
            </div>
         </div>
         <!-- <canvas id="myChart" aria-label="chart" role="img" style="height:100px !important;"></canvas> -->
         <!-- <div class="container"> -->
         <!-- <div id="chartContainer" style="height: 400px; width: 100%"></div> -->
         <!-- </div> -->
         <!-- <div id="ct-visits" style="height: 405px;">
            <div class="chartist-tooltip" style="top: -17px; left: -12px;"><span
                    class="chartist-tooltip-value">6</span>
            </div>
            </div> -->
      </div>
   </div>
</div>
<!-- ============================================================== -->
<!-- RECENT SALES -->
<!-- ============================================================== -->


  @endsection

    @push('scripts')
        var ctx = document.getElementById("myChart").getContext("2d");

        var myChart = new Chart(ctx, {
            type:"bar",
            data:{
                labels:
                [
                    "Client Management",
                    "Shipment Management",
                    "Booking Management",
                    "Ads Management",
                    "Schedules Management"
                ],
                
                datasets:[
                    {
                        data:{{ json_encode($roleValues) }},
                        label: "Sub Admins",
                        backgroundColor:["red","blue","pink","green","gold","black"],
                        borderColor: ["black"],
                        borderWidth:1,
                    },
                ],
            },

        });
    @endpush
