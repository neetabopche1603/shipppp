<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shipment | Dashboard</title>
  <link rel="icon" href="{{ asset('dist/img/favicon.png ') }}/favicon.png">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <link rel="canonical" href="https://www.wrappixel.com/templates/ample-admin-lite/" />
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('plugins/images/favicon.png') }}">
  <!-- Custom CSS -->
  <link href="{{ asset('plugins/bower_components/chartist/dist/chartist.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css') }}">
  <!-- Custom CSS -->
  <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">

  <style>
      .btm 
      {
        position: fixed;
        bottom: 0;
        width: 100%;
        height: 2.5rem;
        background: #F0F0F0;
        padding: 15px;
        padding-left: 10px;
      }

      .image-upload>input {
        display: none;
        }

       #itemImage
       {
        display: flex;
        overflow: hidden;
        overflow-x: scroll;
       } 
       .left-sidebar li .submenu{ 
          list-style: none; 
          margin: 0; 
          padding: 0; 
          padding-left: 2rem; 
          padding-right: 1rem;
        }
  </style>
  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <!-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="" alt="AdminLTELogo" height="60" width="60">
  </div> -->

  <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

  <!-- Navbar -->
  <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin6">
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand text-dark" href="/dashboard">
                       SHIPMENT
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none"
                        href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                   
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav ms-auto d-flex align-items-center">

                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class=" in">
                            <form role="search" class="app-search d-none d-md-block me-3">
                                <input type="text" placeholder="Search..." class="form-control mt-0">
                                <a href="" class="active">
                                    <i class="fa fa-search"></i>
                                </a>
                            </form>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li>
                              <!-- <a class="profile-pic" href="#">
                                <img src="plugins/images/users/varun.jpg" alt="user-img" width="36"
                                    class="img-circle"><span class="text-white font-medium">{{ Auth::user()->name }}</span></a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                          document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                    </form>
                                      </div> -->
                                      

                              <div class="dropdown">
                                  <a class="dropdown-toggle profile-pic" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                  <!-- <img src="plugins/images/users/varun.jpg" alt="user-img" width="36" class="img-circle"> -->
                                  @foreach($img[0] as $image)
                                  <img class="img-circle" width="36" src="{{ asset('profile/'.  $image  )}}" >
                                  @endforeach
                                  {{ Auth::user()->name }}
                                  </a>
                                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a  class="dropdown-item" href="/subAdminProfiles">Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"
                                          onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                          {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                        </form>
                                    </li>
                                  </ul>
                              </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
  </header>

  
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="left-sidebar" data-sidebarbg="skin6">
              <!-- Sidebar scroll-->
              <div class="scroll-sidebar">
                  <!-- Sidebar navigation-->
                  <nav class="sidebar-nav">
                    <!-- <div class="info">
                      <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                    </div> -->
                      <ul id="sidebarnav">
                          <!-- User Profile-->
                          
                          <!-- <li class="sidebar-item pt-2">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/module"
                                  aria-expanded="false">
                                  <i class="fas fa-chart-line" aria-hidden="true"></i>
                                  <span class="hide-menu">Dashboard</span>
                              </a>
                          </li> -->
                          <?php $role = json_decode(Auth::user()->roles); ?>
                          @if(in_array(1,$role))
                          <li class="sidebar-item ">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/subAdminClients"
                                  aria-expanded="false">
                                  <i class="fas fa-users" aria-hidden="true"></i>
                                  <span class="hide-menu">Clients</span>
                              </a>
                          </li>
                          @endif
                          @if(in_array(2,$role))
                          <li class="sidebar-item ">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/subAdminShipment"
                                  aria-expanded="false">
                                  <i class="far fa-building" aria-hidden="true"></i>
                                  <span class="hide-menu">Shipment Company</span>
                              </a>
                          </li>
                          @endif
                         
                          @if(in_array(5,$role))
                          <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/subAdminScheduleShipment"
                                  aria-expanded="false">
                                  <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                  <span class="hide-menu">Shipment Schedule</span>
                              </a>
                          </li>
                          @endif

                          @if(in_array(3,$role))
                          <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/subAdminViewBooking"
                                  aria-expanded="false">
                                  <i class="far fa-bookmark" aria-hidden="true"></i>
                                  <span class="hide-menu">Bookings</span>
                              </a>
                          </li>
                          @endif
                          @if(in_array(4,$role))
                          <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#"
                                  aria-expanded="true">
                                  <i class="fas fa-sitemap" aria-hidden="true"></i>
                                  <span class="hide-menu">Advertisment Managment</span>
                              </a>
                              <ul class="submenu collapse" style="background: #142a39;">
                                <li><a class="sidebar-link text-white" href="/subAdminViewAdsManagement">Create Advertisment</a></li>
                                <li><a class="sidebar-link text-white" href="/subAdminViewAdvertisment">View Advertisments </a></li>
                              </ul>
                          </li>
                          @endif
                          <!-- <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#"
                                  aria-expanded="true">
                                  <i class="fa fa-sitemap" aria-hidden="true"></i>
                                  <span class="hide-menu">User Managment</span>
                              </a>
                              <ul class="submenu collapse" style="background: #142a39;">
                                <li><a class="sidebar-link text-white" href="/createUsers">Create User</a></li>
                                <li><a class="sidebar-link text-white" href="/viewUsers">View Users </a></li>
                              </ul>
                          </li> -->
                          <!-- <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#"
                                  aria-expanded="false">
                                  <i class="fas fa-sitemap" aria-hidden="true"></i>
                                  <span class="hide-menu">Item Managment</span>
                              </a>
                          </li>
                          <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#"
                                  aria-expanded="false">
                                  <i class="fa fa-columns" style="margin-left: 10px;margin-right: 4px;" aria-hidden="true"></i>
                                  <span class="hide-menu">Subcription Management</span>
                              </a>
                          </li>
                          <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href=""
                                  aria-expanded="false">
                                  <i class="fa fa-info-circle" aria-hidden="true"></i>
                                  <span class="hide-menu">Setting</span>
                              </a>
                          </li> -->
                      </ul>

                  </nav>
                  <!-- End Sidebar navigation -->
              </div>
              <!-- End Sidebar scroll-->
  </aside>
 

 <!-- Content -->
<div class="page-wrapper">

  @yield('content')

  <footer class="main-footer btm">
    <strong>Copyright &copy; 2021 <a href="#">Shipment.com</a>.</strong>
    All rights reserved.
  </footer>

</div>


<!-- Footer  -->


 

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


</div>

<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{ asset('bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/app-style-switcher.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('js/waves.js') }}"></script>
<!--Menu sidebar -->
<script src="{{ asset('js/sidebarmenu.js') }}"></script>
<!--Custom JavaScript -->
<script src="{{ asset('js/custom.js') }}"></script>
<!--This page JavaScript -->
<!--chartis chart-->
<script src="{{ asset('plugins/bower_components/chartist/dist/chartist.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
<script src="{{ asset('js/pages/dashboards/dashboard1.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#clientTable').DataTable();
        $('#shipmentTable').DataTable();
        $('#advertismentTable').DataTable();
        $('#usersTable').DataTable();
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">

     $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to delete this record?`,
              text: "If you delete this, it will be gone forever.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              form.submit();
            }
          });
      });
  
</script>

<script>
  $("#pop").on("click", function() {
    $('#imagepreview').attr('src', $('#imageresource').attr('src')); // here asign the image to the modal when the user click the enlarge link
    $('#imagemodal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
  });

  $(".Imodal").on("click", function() {

    var id = $(this).attr('id');
    $.ajax({
        url: "/viewBookingDetails/"+id,
        type: "GET",
        data: {"id": id},
        success: function (data) {
          $("#itemImage").html("");
            console.log(data);
            console.log(data.image);
            var json = JSON.parse(data.image);
 
                for (i in json)
                {   
                   $("#itemImage").append("<img src='image/" + json[i] + "' width='300'>"); 
                }
                
              
            $('#desc').val(data.description);
            $('#bookedby').val(data.name);
            $('#itemmodal').modal('show'); 
        }
    });
    event.stopImmediatePropagation();
    event.preventDefault();
    return false;
   
  });

  $(".Bmodal").on("click", function() {

        var id = $(this).attr('id');
        $.ajax({
            url: "/clientBook/"+id,
            type: "GET",
            data: {"id": id},
            success: function (data) {
              console.log(data);
                $('#bdate').val(data.booking_date);
                $('#btype').val(data.booking_type);
                $('#from').val(data.from);
                $('#to').val(data.to);
                $('#scompany').val(data.shipment_company);
                $('#bookingModal').modal('show'); 
            }
        });
        event.stopImmediatePropagation();
        event.preventDefault();
        return false;

  });

  // View Schedule Shipment Details Modal   

  $(".Smodal").on("click", function() {

    var id = $(this).attr('id');
    $.ajax({
        url: "/subAdminViewScheduleDetails/"+id,
        type: "GET",
        data: {"id": id},
        success: function (data) {
          // $("#docsImage").html("");
            console.log(data);
            // console.log(data.image);
            // var json = JSON.parse(data.image);

            //     for (i in json)
            //     {   
            //       $("#docsImage").append("<img src='image/" + json[i] + "' width='300'>"); 
            //     }
      
            $('#scompany').val(data.companyname);
            $('#shipmentmodal').modal('show'); 
        }
    });
    event.stopImmediatePropagation();
    event.preventDefault();
    return false;

  });

  $('#closemodal').click(function() {
      $('#imagemodal').modal('hide');
  });

  $('#closeItemmodal').click(function() {
      $('#itemmodal').modal('hide');
  });

  $('#closeBmodal').click(function() {
      $('#bookingModal').modal('hide');
  });

  $('#closeSmodal').click(function() {
      $('#shipmentmodal').modal('hide');
  });
  
</script>


</body>
</html>

