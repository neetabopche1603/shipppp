@extends('admin.master')


@section('content')
<style>
  table {
      width: 100%;
      overflow-x: hidden;
      table-layout: fixed;
  }
  .ellipsis span{
    display: inline-block;
        position: relative;
        width: 52%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: top;
        transition: inherit;
  }
  .ellipsis span:hover {
		position: absolute;
		word-break: break-all;
		background-color:yellow;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		border: 1px solid rgba(0,0,0,0.1);
	}
</style>

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">All Scheduled Shipment</h4>
            </div>
        </div>
    </div>

    <div class="white-box">
        <div class="table-responsive">
            <table class="table " id="scheduleTable">
                <div class="d-flex justify-content-center">
                      <select id="mySelectSchedule" class="form-check">
                          <option value="">All</option>
                          <option value="Open">Open</option>
                          <option value="InProgress">In Progress</option>
                          <option value="close">Closed</option>
                      </select>
                </div>
                <thead>
                    <tr>
                        <th class="border-top-0" style="width:5%;">#</th>
                        <th class="border-top-0" style="width:13%;">Shipment Type</th>
                        <th class="border-top-0" style="width:13%;">From</th>
                        <th class="border-top-0" style="width:13%;">To</th>
                        <th class="border-top-0" style="width:13%;">Departure Date</th>
                        <th class="border-top-0" style="width:26%;">Destination Warehouse</th>
                        <th class="border-top-0" style="width:10%;">Shipment Company</th>
                        <!-- <th class="border-top-0">Shipping Fee</th>
                        <th class="border-top-0">Pickup Fee</th> -->
                        <th class="border-top-0"style="width:8%;">Status</th>
                        <th class="border-top-0" style="width:5%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                         @foreach($data as $key=>$row)
                            <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $row->shipment_type }}</td>
                            <td>{{ $row->from }}</td>
                            <td>{{ $row->to }}</td>
                            <td>{{ $row->departure_date }}</td>
                            <td>{{ $row->arrival_address }}</td>
                            <td>{{ $row->companyname }}</td>
                            <td>{{ $row->status }}</td>
                            <td> 
                            <a href="/scheduleShipmentDetails/{{ $row->id }}" class="btn btn-info btn-sm text-white"> Details </a>
                          
                            </td>
                            </tr>
                        @endforeach
                </tbody>
                
            </table>
        </div>
    </div>


<div class="modal fade" id="shipmentmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Scheduled Shipment Details</h5>
      </div>
      <div class="modal-body">
        

          <div id="docsImage">
            
          </div>  
          
        
        <label for="description" class="mt-3">Shipment Company</label>
        <input type="text" name="description" class="form-control" id="scompany" value="" Disabled>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info text-white" id="closeSmodal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Scheduled Shipment Details</h5>
      </div>
      <div class="modal-body">
      
      <form action="/scheduleStatus" method="POST">
        @csrf
        &nbsp; <input type="hidden" id="stats" name="stats" value="">
        <input type="radio" id="accept" name="status" value="accept">
        <label for="accept">Accept</label><br>
        <input type="radio" id="reject" name="status" value="reject">
        <label for="reject">Reject</label><br>
        <div class="col-sm-offset-2 d-flex justify-content-center mb-3 col-sm-10">
            <button type="submit" class="btn btn-info">Update Status</button>
        </div>
      </form>
  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info text-white" id="closeSTmodal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> -->



@endsection