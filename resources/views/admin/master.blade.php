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
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
  <!-- Custom CSS -->
  <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">

  <style>
    .zoom {
  /* padding: 50px;
  background-color: green; */
  transition: transform .2s;
  /* width: 200px;
  height: 200px;
  margin: 0 auto; */
}

.badge {
  position: absolute;
  top: -10px;
  right: -10px;
  padding: 5px 10px;
  border-radius: 50%;
  background-color: red;
  color: white;
}

.zoom:hover {
  -ms-transform: scale(3.5); /* IE 9 */
  -webkit-transform: scale(3.5); /* Safari 3-8 */
  transform: scale(3.5); 
}
    .order-card {
    color: #fff;
}

.bg-c-blue {
    background: linear-gradient(45deg,#4099ff,#73b4ff);
}

.bg-c-green {
    background: linear-gradient(45deg,#2ed8b6,#59e0c5);
}

.bg-c-yellow {
    background: linear-gradient(45deg,#FFB64D,#ffcb80);
}

.bg-c-pink {
    background: linear-gradient(45deg,#FF5370,#ff869a);
}


.card {
    border-radius: 5px;
    -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    border: none;
    margin-bottom: 30px;
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}

.card .card-block {
    padding: 25px;
}

.order-card i {
    font-size: 26px;
}

.f-left {
    float: left;
}

.f-right {
    float: right;
}
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
       #itemImage1
       {
        display: flex;
        overflow: hidden;
        overflow-x: scroll;
       } 
       #itemImage2
       {
        display: flex;
        overflow: hidden;
        overflow-x: scroll;
       } 
       #itemImage3
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

        .dataTables_filter{
          float: right;
        }
        .pagination
        {
          float:right;
        }
        .img-size{
          /* 	padding: 0;
            margin: 0; */
            height: 600px;
            width: 100%;
            background-size: cover;
            overflow: hidden;
          }

        .carousel-control-prev-icon {
          background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23009be1' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E");
          width: 30px;
          height: 48px;
        }
        .carousel-control-next-icon {
          background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23009be1' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E");
          width: 30px;
          height: 48px;
        }
        .modal-content {
            height: 100%;
            width: 100%;
          }
        .modal-content1 {
          height: 308px !important;
          width: 500px !important;
        }
          
        .modal-open .modal.modal-center {
          display: flex!important;
          align-items: center!important;
          .modal-dialog {
              flex-grow: 1;
          }
        }
        .modal-open .modal {

        }
        .modal {
              position: fixed;
              top: 0;
              left: 0;
              z-index: 1050;
              display: none;
              width: 100%;
              height: 100%;
              overflow: hidden;
              outline: 0;
          }

          .modal1 {
              position: absolute;
              top: 0;
              left: -1px;
              z-index: 1050;
              display: none;
              width: 75%!important;
              height: 35%!important;
              overflow: visible;
              outline: 0;
          }

          .dropdown {
              display:inline-block;
              margin-left:20px;
            }


          .glyphicon-bell {
            
              font-size:1.5rem;
            }

          .notifications {
            min-width:420px; 
            }
            
            .notifications-wrapper {
              overflow:auto;
                max-height:250px;
              }
              
          .menu-title {
              color:#ff7788;
              font-size:1.3rem;
                display:inline-block;
                }
          
          .glyphicon-circle-arrow-right {
                margin-left:10px;     
            }
            
            
          .notification-heading, .notification-footer  {
            padding:10px 10px;
                }
                
                  
          .dropdown-menu.divider {
            margin:5px 0;          
            }

          .item-title {
            
          font-size:1.3rem;
          color:#000;
              
          }

          .notifications a.content {
          text-decoration:none;
          background:#ccc;

          }
              
          .notification-item {
          padding:10px;
          margin:5px;
          background:#fff0f0;
          border-radius:4px;
          }
          .notification-item1 {
          padding:10px;
          margin:5px;
          background:#ff8a8a;
          border-radius:4px;
          }

          .item-info {
              color: black;
              margin-bottom: 15px;
              margin-left: 15px;
          }


  </style>
  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  @php 
    use App\Models\Notification;
    $new = Notification::where('is_admin','=',1)->where('status','=',0)->orderBy('created_at', 'desc')->count();
    $noti = Notification::where('is_admin','=',1)->orderBy('created_at', 'desc')->paginate(10);
  @endphp
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
                          <div class="dropdown">
                            <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-bs-toggle="dropdown" aria-expanded="false" data-target="#" href="#">
                              <i class="fa fa-bell" style="font-size:24px; color:white;"></i>
                              <span class="badge">{{ $new }}</span>
                            </a>
                            
                            <ul class="dropdown-menu notifications" role="menu" aria-labelledby="dLabel">
                              
                              <div class="notification-heading"><h4 class="menu-title">Notifications</h4>
                              </div>
                              <li class="divider"></li>
                              <div class="notifications-wrapper">
                                <a class="content" href="#" id="noti">
                                  @if(count($noti) > 0)
                                    @foreach($noti as $row)
                                      @if($row->status == 0)
                                        <div class="notification-item1">
                                          <h4 class="item-title">{{ $row->title }}</h4>
                                          <h5 class="item-info">{{ $row->msg }}</h5>
                                        </div>
                                      @else
                                        <div class="notification-item">
                                          <h4 class="item-title">{{ $row->title }}</h4>
                                          <h5 class="item-info">{{ $row->msg }}</h5>
                                        </div>
                                      @endif
                                    @endforeach
                                  @else
                                        <div class="notification-item">
                                          <h5 class="item-info">No Notifications...</h5>
                                        </div>
                                  @endif
                                </a>

                              </div>
                              <li class="divider"></li>
                              <div class="notification-footer"><a href="{{ route('admin.mark') }}"><h6>Mark As Read</h6></a>
                              </div>
                              <!-- <div class="notification-footer"><h4 class="menu-title">View all<i class="glyphicon glyphicon-circle-arrow-right"></i></h4></div> -->
                            </ul>
                            
                          </div>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav ms-auto d-flex align-items-center">
                          
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class=" in me-5">

                     



                              <!-- <div class="dropdown">
                                  <a class="dropdown-toggle profile-pic" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                  <i class="fa fa-bell" style="font-size:24px; color:white;"></i>
                                  </a>
                                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a  class="dropdown-item" href="/profiles">Profile</a></li>
                                   
                                  </ul>
                              </div> -->
                        
                            <!-- <form role="search" class="app-search d-none d-md-block me-3">
                                <input type="text" placeholder="Search..." class="form-control mt-0">
                                <a href="" class="active">
                                    <i class="fa fa-search"></i>
                                </a>
                            </form> -->
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
                                  <img class="img-circle" width="36" height="32" src="{{ asset('image/'.  $image  )}}" >
                                  @endforeach
                                  {{ Auth::user()->name }}
                                  </a>
                                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a  class="dropdown-item" href="{{route('admin.profile')}}">Profile</a></li>
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
                          <li class="sidebar-item pt-2">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.dashboard')}}"
                                  aria-expanded="false">
                                  <i class="fas fa-chart-line" aria-hidden="true"></i>
                                  <span class="hide-menu">Dashboard</span>
                              </a>
                          </li>
                          <li class="sidebar-item ">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.clients')}}"
                                  aria-expanded="false">
                                  <i class="fas fa-users" aria-hidden="true"></i>
                                  <span class="hide-menu">Clients</span>
                              </a>
                          </li>
                          <li class="sidebar-item ">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.shipment')}}"
                                  aria-expanded="false">
                                  <i class="far fa-building" aria-hidden="true"></i>
                                  <span class="hide-menu">Shipment Company</span>
                              </a>
                          </li>
                          <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.marketPlace')}}"
                                  aria-expanded="false">
                                  <i class="fa fa-columns" aria-hidden="true"></i>
                                  <span class="hide-menu">Market Place</span>
                              </a>
                          </li>
                          <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.scheduleShipment')}}"
                                  aria-expanded="false">
                                  <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                  <span class="hide-menu">Shipment Schedule</span>
                              </a>
                          </li>
                          <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.viewBooking')}}"
                                  aria-expanded="false">
                                  <i class="far fa-bookmark" aria-hidden="true"></i>
                                  <span class="hide-menu">Bookings</span>
                              </a>
                          </li>
                          <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#"
                                  aria-expanded="true">
                                  <i class="fas fa-sitemap" aria-hidden="true"></i>
                                  <span class="hide-menu">Advertisment Managment</span>
                              </a>
                              <ul class="submenu collapse" style="background: #142a39;">
                                <li><a class="sidebar-link text-white" href="{{route('admin.viewAdsManagement')}}">Create Advertisment</a></li>
                                <li><a class="sidebar-link text-white" href="{{route('admin.viewAdvertisment')}}">View Advertisments </a></li>
                              </ul>
                          </li>
                          <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#"
                                  aria-expanded="true">
                                  <i class="fa fa-sitemap" aria-hidden="true"></i>
                                  <span class="hide-menu">SubAdmin Managment</span>
                              </a>
                              <ul class="submenu collapse" style="background: #142a39;">
                                <li><a class="sidebar-link text-white" href="{{route('admin.createUsers')}}">Create SubAdmin</a></li>
                                <li><a class="sidebar-link text-white" href="{{route('admin.viewUser')}}">View SubAdmin </a></li>
                              </ul>
                          </li>
                          {{-- <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#"
                                  aria-expanded="true">
                                  <!-- <i class="fas fa-sitemap" aria-hidden="true"></i> -->
                                  <i class="fas fa-tasks"></i>
                                  <span class="hide-menu">Items Managment</span>
                              </a>
                              <ul class="submenu collapse" style="background: #142a39;">
                                <li><a class="sidebar-link text-white" href="/createItems">Create Item</a></li>
                                <li><a class="sidebar-link text-white" href="/viewItems">View Items </a></li>
                              </ul>
                          </li> --}}
                          {{-- <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#"
                                  aria-expanded="true">
                                  <!-- <i class="fas fa-sitemap" aria-hidden="true"></i> -->
                                  <i class="fas fa-tasks"></i>
                                  <span class="hide-menu">Category Managment</span>
                              </a>
                              <ul class="submenu collapse" style="background: #142a39;">
                                <li><a class="sidebar-link text-white" href="/createCategory">Create Category</a></li>
                                <li><a class="sidebar-link text-white" href="/viewCategory">View Category</a></li>
                              </ul>
                          </li> --}}
                          <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#"
                                  aria-expanded="false">
                                  <i class="fas fa-barcode"></i>
                                  <span class="hide-menu">Manage Coupons</span>
                              </a>
                              <ul class="submenu collapse" style="background: #142a39;">
                                <li><a class="sidebar-link text-white" href="{{route('admin.createCoupons')}}">Create Coupons</a></li>
                                <li><a class="sidebar-link text-white" href="{{route('admin.viewCoupons')}}">View Couponss </a></li>
                              </ul>
                          </li>

                          <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#"
                                  aria-expanded="false">
                                  <i class="fas fa-barcode"></i>
                                  <span class="hide-menu">Manage Plans</span>
                              </a>
                              <ul class="submenu collapse" style="background: #142a39;">
                                <li><a class="sidebar-link text-white" href="/createPlans">Create Plans</a></li>
                                <li><a class="sidebar-link text-white" href="/viewPlans">View Plans </a></li>
                              </ul>
                          </li>


                          <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#"
                                  aria-expanded="false">
                                  <i class="fas fa-barcode"></i>
                                  <span class="hide-menu">Manage Transactions</span>
                              </a>
                              <ul class="submenu collapse" style="background: #142a39;">
                                
                                <li><a class="sidebar-link text-white" href="/viewTransactions">View Transactions </a></li>
                              </ul>
                          </li>


                          {{-- <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#"
                                  aria-expanded="true">
                                  <i class="fab fa-blogger" aria-hidden="true"></i>
                                  <span class="hide-menu">Payments</span>
                              </a>
                              <ul class="submenu collapse" style="background: #142a39;">
                                <li><a class="sidebar-link text-white" href="/bookingPayment">Booking Payment</a></li>
                                <li><a class="sidebar-link text-white" href="/marketPayment">Market Place Payment </a></li>
                              </ul>
                          </li>  --}}
                          <li class="sidebar-item">
                              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/viewSupport"
                                  aria-expanded="false">
                                  <i class="fa fa-info-circle" aria-hidden="true"></i>
                                  <span class="hide-menu">Support</span>
                              </a>
                          </li>
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

<script src="{{ asset('js/chart.js') }}"></script>
<script src="{{ asset('js/canvasjs.min.js') }}"></script>
<script src="{{ asset('js/canvasjs.react.js') }}"></script>
<script src="{{ asset('js/jquery.canvasjs.min.js') }}"></script>
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
chartis chart
<script src="{{ asset('plugins/bower_components/chartist/dist/chartist.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/pages/dashboards/dashboard1.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
		<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        // $('#clientTable').DataTable();
        // $('#shipmentTable').DataTable();
        $('#advertismentTable').DataTable();
        $('#scheduleTable').DataTable();
        // $('#marketTable').DataTable();
        $('#usersTable').DataTable();
        // $('#supportTable').DataTable();
        // $('#clientStatusTable').DataTable();

// Clients Table Filter

        dataTable = $("#clientTable").DataTable({
            "columnDefs": [
                  {
                      "targets": [8],
                      "visible": true
                  }
              ]
            
          });

        $('#mySelect').on('change', function(e){
          var status = $(this).val();
          $('#mySelect').val(status)
          console.log(status)
          dataTable.column(8).search(status).draw();
        });

// Shipment DataTable Filter

        dataTableS = $("#shipmentTable").DataTable({
            "columnDefs": [
                  {
                      "targets": [7],
                      "visible": true
                  }
              ]
            
          });

        $('#mySelectShipment').on('change', function(e){
          var status = $(this).val();
          $('#mySelectShipment').val(status)
          console.log(status)
          dataTableS.column(7).search(status).draw();
        });
  
    });

// MarketPlace Table Filter

        // dataTableM = $("#marketTable").DataTable({
        //     "columnDefs": [
        //           {
        //               "targets": [7],
        //               "visible": true
        //           }
        //       ]
            
        //   });
        dataTableM = $('#marketTable').DataTable();

        $('#mySelectM').on('change', function(e){
          var status = $(this).val();
          $('#mySelectM').val(status)
          console.log(status)
          dataTableM.column(4).search(status).draw();
        });

// Query Table Filter 

        dataTableQ = $('#supportTable').DataTable();

        $('#mySelectQuery').on('change', function(e){
          var status = $(this).val();
          $('#mySelectQuery').val(status)
          console.log(status)
          dataTableQ.column(4).search(status).draw();
        });

// Schedule Table Filter

        dataTableSc = $('#scheduleTable').DataTable();

        $('#mySelectSchedule').on('change', function(e){
          var status = $(this).val();
          $('#mySelectSchedule').val(status)
          console.log(status)
          dataTableSc.column(7).search(status).draw();
        });



// Booking Table Filter

        dataTableB = $('#BookingTable').DataTable();

        $('#mySelectBooking').on('change', function(e){
          var status = $(this).val();
          $('#mySelectBooking').val(status)
          console.log(status)
          dataTableB.column(7).search(status).draw();
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

  $("#pop1").on("click", function() {
    $('#imagepreview').attr('src', $('#imageresource').attr('src')); // here asign the image to the modal when the user click the enlarge link
    $('#imagemodal1').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
  });

  $("#pop2").on("click", function() {
    $('#imagepreview').attr('src', $('#imageresource').attr('src')); // here asign the image to the modal when the user click the enlarge link
    $('#imagemodal2').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
  });

  $("#pop3").on("click", function() {
    $('#imagepreview').attr('src', $('#imageresource').attr('src')); // here asign the image to the modal when the user click the enlarge link
    $('#imagemodal3').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
  });





// View Booking Details Modal 

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

// View Client Booking Details Modal   

  $(".Bmodal").on("click", function() {

      var id = $(this).attr('id');
        $.ajax({
            url: "/changeClientStatus/"+id,
            type: "GET",
            data: {"id": id},
            success: function (data) {
              // console.log(data);
                $('#email').val(data.email);
                $('#status').val(data.status);
                if(data.status == 'Approved')
                {
                  $('#clientA').prop('checked', true);
                }
                if(data.status == 'Not Approve')
                {
                  $('#clientNA').prop('checked', true);
                }
                if(data.status == 'Block')
                {
                  $('#clientB').prop('checked', true);
                }
                $('#bookingModal').modal('show'); 
            }
        });
        event.stopImmediatePropagation();
        event.preventDefault();
        return false;

  });

// Chnage Market Place Status Modal   

  $(".Mmodal").on("click", function() {

    var id = $(this).attr('id');
      $.ajax({
          url: "/changeClientMarketStatus/"+id,
          type: "GET",
          data: {"id": id},
          success: function (data) {
            // console.log(data);
              $('#idm').val(data.id);
              if(data.status == 'created')
              {
                $('#clientA').prop('checked', true);
              }
              if(data.status == 'accepted')
              {
                $('#clientNA').prop('checked', true);
              }
              if(data.status == 'delivered')
              {
                $('#clientB').prop('checked', true);
              }
              $('#marketModal').modal('show'); 
          }
      });
      event.stopImmediatePropagation();
      event.preventDefault();
      return false;

  });

// Update User Details Modal 

  $(".Umodal").on("click", function() {

    var id = $(this).attr('id');
    $.ajax({
        url: "/updateUserDetails/"+id,
        type: "GET",
        data: {"id": id},
        success: function (data) {
          console.log(JSON.parse(data.roles));
            $('#email').val(data.email);
            $('#name').val(data.name);
            $('#lname').val(data.lname);
            $('#email').val(data.email);
            $('#phone').val(data.phone);
            $('#address').val(data.address);
            $('#country').val(data.country);
            var roles = JSON.parse(data.roles);
            console.log(roles);

            if(roles.includes('1')) {
                $('#clientM').prop('checked', true);
            }
            if(roles.includes('2')) {
                $('#shipmentM').prop('checked', true);
            }
            if(roles.includes('3')) {
                $('#bookingM').prop('checked', true);
            }
            if(roles.includes('4')) {
                $('#adsM').prop('checked', true);
            }
            if(roles.includes('5')) {
                $('#scheduleM').prop('checked', true);
            }

            $('#updateUserModal').modal('show'); 
        }
    });
    event.stopImmediatePropagation();
    event.preventDefault();
    return false;

  });

// Update Item Details Modal 

    $(".Itemmodal").on("click", function() {

    var id = $(this).attr('id');
    $.ajax({
        url: "/updateItemDetails/"+id,
        type: "GET",
        data: {"id": id},
        success: function (data) {
           console.log(data[0]);
            $('#ids').val(data[0].id);
            $('#item_name').val(data[0].item_name);
            $('#category').val(data[0].category);
            // $("#category select").val(data[0].category).change();
            
            $('#updateItemModal').modal('show'); 
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
          url: "/viewScheduleDetails/"+id,
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

// View Shipment Company Status Modal 

  $(".SCmodal").on("click", function() {

    var id = $(this).attr('id');
        $.ajax({
            url: "/changeShipmentStatus/"+id,
            type: "GET",
            data: {"id": id},
            success: function (data) {
              console.log(data);
                $('#emails').val(data.email);
                $('#ids').val(data.id);
                $('#status').val(data.status);
                if(data.status == 'Approved')
                {
                  $('#shipmentA').prop('checked', true);
                }
                if(data.status == 'Not Approve')
                {
                  $('#shipmentNA').prop('checked', true);
                }
                if(data.status == 'Block')
                {
                  $('#shipmentB').prop('checked', true);
                }
                $('#shipmentsModal').modal('show'); 
            }
        });
        event.stopImmediatePropagation();
        event.preventDefault();


        return false;
    
    
        // var id = $(this).attr('id');
    // $.ajax({
    //     url: "/viewShipmentDocs/"+id,
    //     type: "GET",
    //     data: {"id": id},
    //     success: function (data) {
    //       // $("#docsImage").html("");
    //       console.log(data[0]);
    //       console.log(data[0].docs);

    //       // $("#docsImage").append("<img src='image/" + data[0].docs + "' width='100%'>"); 
          
    //         $('#stats').val(data[0].id);
    //         $('#shipmentcompanymodal').modal('show'); 
    //     }
    // });
    // event.stopImmediatePropagation();
    // event.preventDefault();
    // return false;

  });

// View Reviews & Rating For Blogs Modal

  $(".Rmodal").on("click", function() {

    var id = $(this).attr('id');
    $.ajax({
        url: "/viewReviewDetails/"+id,
        type: "GET",
        data: {"id": id},
        success: function (data) {
          $("#star").html("");
            console.log(data); 
            console.log(data.rating);
               
                  while(data.rating>0)
                  {
                    if(data.rating >0.5){
                         $("#star").append("<i class='fas fa-star'></i>");    
                    }
                    else{
                         $("#star").append("<i class='fas fa-star-half'></i>");    
                    }
                    data.rating--; 
                  }        
              
            $('#rating').val(data.rating);
            $('#review').val(data.reviews);
            $('#user').val(data.name);
            $('#reviewModal').modal('show'); 
      }
    });
    event.stopImmediatePropagation();
    event.preventDefault();
    return false;

  });

// View Query Modal 

  $('.viewQ').on("click", function() {
    var id = $(this).attr('id');
    console.log(id);
    $.ajax({
            url: "/viewQuery/"+id,
            type: "GET",
            data: {"id": id},
            success: function (data) {
              console.log(data[0].query);
                $('#querys').val(data[0].query);
                $('#myModalQ').modal('show');
            }
        });
        event.stopImmediatePropagation();
        event.preventDefault();


        return false;
  });

// Change Support Query Status Modal

  $(".Supportmodal").on("click", function() {

  var id = $(this).attr('id');
    $.ajax({
        url: "/changeQueryStatus/"+id,
        type: "GET",
        data: {"id": id},
        success: function (data) {
          // console.log(data);
            $('#id').val(data.id);
            $('#status').val(data.status);
            if(data.status == 'pending')
            {
              $('#pending').prop('checked', true);
            }
            if(data.status == 'resolved')
            {
              $('#resolved').prop('checked', true);
            }
            if(data.status == 'inprogress')
            {
              $('#inprogress').prop('checked', true);
            }
            $('#supportModal').modal('show'); 
        }
    });
    event.stopImmediatePropagation();
    event.preventDefault();
    return false;

  });

  $('#closeQmodal').click(function() {
      $('#supportModal').modal('hide');
  });

  $('#closemodal').click(function() {
      $('#imagemodal').modal('hide');
  });

  $('#closemodal1').click(function() {
      $('#imagemodal1').modal('hide');
  });

  $('#closemodal2').click(function() {
      $('#imagemodal2').modal('hide');
  });

  $('#closemodal3').click(function() {
      $('#imagemodal3').modal('hide');
  });

  $('#closemodal4').click(function() {
      $('#imagemodal4').modal('hide');
  });

  $('#closeItemmodal').click(function() {
      $('#itemmodal').modal('hide');
  });

  $('#closeBmodal').click(function() {
      $('#bookingModal').modal('hide');
      $('#marketModal').modal('hide');
      $('#myModalQ').modal('hide');
  });

  $('#closeBmodal1').click(function() {
      $('#shipmentsModal').modal('hide');
  });

  $('#closeItemmodal').click(function() {
      $('#updateItemModal').modal('hide'); 
  });

  $('#closeUmodal').click(function() {
            $('#clientM').prop('checked', false);
            $('#shipmentM').prop('checked', false);
            $('#bookingM').prop('checked', false);
            $('#adsM').prop('checked', false);
            $('#scheduleM').prop('checked', false);
      $('#updateUserModal').modal('hide');
  });

  $('#closeSmodal').click(function() {
      $('#shipmentmodal').modal('hide');
  });

  $('#closeSCmodal').click(function() {
      $('#shipmentcompanymodal').modal('hide');
  });

  $('#closeSTmodal').click(function() {
      $('#statusModal').modal('hide');
  });

  $('#closeRmodal').click(function() {
      $('#reviewModal').modal('hide');
  });

// Chart JS Script


  @stack('scripts');
  @stack('scriptsB');


  $(document).ready(function(){

    setTimeout(() => {
      $('.carousel').carousel();
    }, 3000);
  });


  // setInterval(function()
  //   {
       
  //       $.ajax({
  //           url: '{{route('admin.getNotification')}}',
  //           type: 'GET',
  //           data: {
  //          },
  //           success:function(data)
  //           {
  //               $('#noti').html(data.msg); 
  //               // console.log(data);
  //           }
  //       });
  //   }, 1000);

  


</script>


</body>
</html>

