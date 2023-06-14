@extends('admin.master')


@section('content')
<style>
    .q {
        display: inline-block;
        position: relative;
        width: 70%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: top;
        transition: inherit;
        }
</style>

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">All Support Queries</h4>
            </div>
        </div>
    </div>

    <div class="white-box">
        <div class="table-responsive">
            

          <table class="table text-nowrap" style="table-layout: fixed;" id="supportTable">
            <div class="d-flex justify-content-center">
                  <select id="mySelectQuery" class="form-check">
                      <option value="">All</option>
                      <option value="new">New</option>
                      <option value="pending">pending</option>
                      <option value="inprogress">In Progress</option>
                      <option value="resolved">Resolved</option>
                  </select>
            </div>
                <thead>
                    <tr>
                        <th class="border-top-0" style="width:5%;">#</th>
                        <th class="border-top-0" style="width:16%;">Name</th>
                        <th class="border-top-0" style="width:16%;">Date</th>
                        <th class="border-top-0" style="">Role</th>
                        <th class="border-top-0" style="width:14%;">Status</th>
                        <th class="border-top-0">Query</th>
                    </tr>
                </thead>
                <tbody>
                         @foreach($data as $row)
                            <tr>
                              <td>{{ $row->id }}</td>
                            <!-- <td>
                            <div class="col-md-5"><a href="/clientDetailss/{{ $row->name }}" ><input type="text" class="form-control" name="name" value="{{ $row->name }}" style="color:blue; cursor:pointer; text-decoration:underline;"></a></div>
                            </td> -->
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->date }}</td>
                            <td>
                                <?php 
                                if($row->roles == '1c')
                                { $value = 'Client'; echo $value; echo "<br>";  }
                                if($row->roles == '2r')
                                { $value = 'Receptionist'; echo $value; echo "<br>";  }
                                if($row->roles == '1')
                                { $value = 'Shipment Company'; echo $value; echo "<br>";  }
                                if($row->roles == '2')
                                { $value = 'Accountant'; echo $value; echo "<br>"; }
                                if($row->roles == '3')
                                { $value = 'Departure WareHouse Manager'; echo $value; echo "<br>"; }     
                                if($row->roles == '4')
                                { $value = 'Arrival WareHouse Manager'; echo $value; echo "<br>"; }    
                                if($row->roles == '5')
                                { $value = 'Pickup Agent'; echo $value; echo "<br>"; }    
                                ?>  
                            </td>
                            <td>
                              {{ $row->status }}
                            <!-- <a data-toggle="modal" data-target="#supportModal" id="{{ $row->id }}" class="Supportmodal"> &nbsp; <i class="fas fa-edit"></i></a> -->
                            </td>
                            <td>
                                <p class="q">{{ $row->query }}</p>
                                <!-- <a data-toggle="modal" href="#myModalQ" id="{{ $row->id }}" class="btn btn-info btn-sm text-white viewQ">View Query</a> -->
                                <a href="/queryDetails/{{ $row->id }}" class="btn btn-info btn-sm text-white"> Details </a>
                            </td>
                            </tr>
                        @endforeach
                </tbody>
                
          </table>
        </div>
    </div>  



<!--  Full Query Modal -->

<div class="modal1 fade" id="myModalQ" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style=" height: 308px !important;
          width: 500px !important;">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Query Modal</h5>
          <button type="button" class="btn btn-info text-white rounded-circle" id="closeBmodal" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body">
                <p>Query:</p>
                <textarea class="form-control" id="querys" rows='5' cols="45">
                </textarea>
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div>     

<!-- Status Of Queries Modal  -->

<!-- <div class="modal1 fade" id="supportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style=" height: 308px !important;
          width: 500px !important;">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Query Status</h5>
          <button type="button" class="btn btn-info text-white rounded-circle" id="closeQmodal" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body">
        <form action="/updateQueryStatus" method="POST">
          @csrf
          <input type="hidden" id="id" name="id" value="">
          <label for="status" class="mt-3">Current Query Status</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="pending" value="pending">
            <label class="form-check-label" for="exampleRadios1">
                Pending
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="inprogress" value="inprogress">
            <label class="form-check-label" for="exampleRadios3">
              In Progress
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="resolved" value="resolved">
            <label class="form-check-label" for="exampleRadios2">
              Resolved
            </label>
          </div>
            <div class="col-sm-offset-2 d-flex justify-content-center mb-3 col-sm-10 my-3">
                <button type="submit" class="btn btn-info text-white">Change Status</button>
            </div>
        </form>
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div>    -->



@endsection
