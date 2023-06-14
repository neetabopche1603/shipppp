@extends('admin.master')


@section('content')

<div class="row d-flex justify-content-start pt-5">

    <label class="form-check-label ms-5" for="exampleRadios3">
                <h3>* Query</h3>
    </label>
    <div class="col-10">
    ​<textarea id="txtArea" name="comment" class="ms-5 me-5" rows="10" cols="95">{{ $data->query }}</textarea>
    </div>
</div>

<div class="row d-flex justify-content-start mt-5 pt-5">
        <div class="col-md-3 col-lg-3 col-sm-10 border-right ms-5 card shadow p-3 mb-5 bg-white rounded">  
            <form action="/updateQueryStatus" method="POST">
            @csrf
            <input type="hidden" name="qid" value="{{ $data->id }}">
            <label for="status" class="mt-3"><h3>Current Query Status</h3></label>
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
            <div class="form-check">
                <label class="form-check-label" for="exampleRadios3">
                <h5>* Reason</h5>
                </label>
                ​<textarea id="txtArea" name="comment" rows="3" cols="25"></textarea>
            </div>
                <div class="col-sm-offset-2 d-flex justify-content-center mb-3 col-sm-10 my-3">
                    <button type="submit" class="btn btn-info text-white">Change Status</button>
                </div>
            </form>
        </div>
        <div class="col-md-7 col-lg-7 col-sm-10 border-right table-responsive">  
            <table class="table text-nowrap bg-white" id="queryStatusTable">
                    <thead>
                        <tr>
                            <th class="border-top-0">#</th>
                            <th class="border-top-0">Status</th>
                            <th class="border-top-0">Date</th>
                            <th class="border-top-0">Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                          @foreach($querytDetails as $row)
                          @php
                            $temp = explode(' ',$row->created_at);
                          @endphp
                          <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->status }}</td>
                            <td>{{ $temp[0] }}</td>
                            <td>{{ $row->comment }}</td>
                          </tr>
                          @endforeach
                    </tbody>
                
            </table>
        </div>

    </div>



@endsection