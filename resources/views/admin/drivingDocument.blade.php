<!doctype html>

    <html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Download | Document</title>
    </head>
    <body>
      
        <?php $data = $data[0]  ?>
        <h1> {{ $data->companyname }} Driving Licence Documents</h1>
            
            @if(is_array($data->driving_licence))
            
            @foreach ($data->driving_licence as $row)

           <div class="container-fluid">
            <!-- <img class='img-size img-responsive' src="{{ asset('image/'. $row) }}" style="max-width:100%!important;" /> -->
            <img class='img-size img-responsive' src="{{ $row }}" style="max-width:100%!important;" />
           </div>
            
            <!-- <img class='img-size img-responsive' src="{{ public_path('image/'.$row) }}" /> -->

            @endforeach
            @elseif(!is_array($data->driving_licence))
                <div class="container-fluid">
                    <img class='img-size img-responsive' src="{{ $data->driving_licence }}" style="max-width:100%!important; min-width:100%!important;"  />
                </div>
            @else
               <div class="container-fluid">
                   <h1 class="text-center">No Document Uploaded !!</h1>
               </div>
            @endif


    <style>
    /*css*/
    </style>
    <br>
   
     </body>
</html>