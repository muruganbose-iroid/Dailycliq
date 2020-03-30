<?php
    header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
    header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <!-- Twitter -->
    <meta name="twitter:site" content="@themepixels">
    <meta name="twitter:creator" content="@themepixels">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="DailyCliq">
    <meta name="twitter:description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="twitter:image" content="http://themepixels.me/bracketplus/img/bracketplus-social.png">

    <!-- Facebook -->
    <meta property="og:url" content="http://themepixels.me/bracketplus">
    <meta property="og:title" content="DailyCliq">
    <meta property="og:description" content="Premium Quality and Responsive UI for Dashboard.">

    <meta property="og:image" content="http://themepixels.me/bracketplus/img/bracketplus-social.png">
    <meta property="og:image:secure_url" content="http://themepixels.me/bracketplus/img/bracketplus-social.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="author" content="ThemePixels"> --}}

    <title>Daily CliQ</title>

    <!-- vendor css -->
    <link href="{{asset('app/lib/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
    <link href="{{asset('app/lib/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('app/lib/rickshaw/rickshaw.min.css')}}" rel="stylesheet">
    <link href="{{asset('app/lib/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('app/lib/datatables.net-dt/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('app/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('app/lib/spinkit/css/spinkit.css')}}" rel="stylesheet">
    <!-- Bracket CSS -->
    <link rel="stylesheet" href="{{asset('app/css/brackets2.css')}}">
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script>
     let base_url = '{{route("login")}}';
    </script>
  </head>

  <body>

    <!-- ########## START: LEFT PANEL ########## -->
    <div class="br-logo"><a href="{{route('vendorDashboard')}}"> <img src="{{asset('app/img/logo-small.png')}}" class="img-fluid" alt="daily cliq"></a></div>
    <div class="br-sideleft br-secondary sideleft-scrollbar">
      <label class="sidebar-label pd-x-10 mg-t-20 op-3">Navigation</label>
      <ul class="br-sideleft-menu">
        <li class="br-menu-item" id="dashboard_menu">
          <a href="{{route('showHome')}}" class="br-menu-link" >
            <i class="menu-item-icon icon ion-ios-home-outline tx-24"></i>
            <span class="menu-item-label">Dashboard</span>
          </a><!-- br-menu-link -->
        </li><!-- br-menu-item -->
        <li class="br-menu-item" id="vendor_menu">
          <a href="{{route('showVendors')}}" class="br-menu-link" >
            <i class="menu-item-icon icon ion-ios-people-outline tx-24"></i>
            <span class="menu-item-label">Vendors</span>
          </a><!-- br-menu-link -->
        </li><!-- br-menu-item -->
        <li class="br-menu-item" id="product_menu">
          <a href="{{route('showProducts')}}" class="br-menu-link" >
            <i class="menu-item-icon icon ion-ios-people-outline tx-24"></i>
            <span class="menu-item-label">Products</span>
          </a><!-- br-menu-link -->
        </li><!-- br-menu-item -->

      <!--Murugan Start-->

        <li class="br-menu-item" id="product_menu">
          <a href="{{route('store99products')}}" class="br-menu-link" >
            <i class="menu-item-icon icon ion-ios-people-outline tx-24"></i>
            <span class="menu-item-label">99Store Products</span>
          </a><!-- br-menu-link -->
        </li><!-- br-menu-item -->

      <!--Murugan End-->


        <li class="br-menu-item" id="product_menu">
          <a href="{{route('showAdminProductList')}}" class="br-menu-link" >
            <i class="menu-item-icon icon ion-ios-people-outline tx-24"></i>
            <span class="menu-item-label">Pending Products</span>
          </a><!-- br-menu-link -->
        </li><!-- br-menu-item -->
        <li class="br-menu-item" id="category_menu">
          <a href="{{route('showCategories')}}" class="br-menu-link" >
            <i class="menu-item-icon icon ion-ios-people-outline tx-24"></i>
            <span class="menu-item-label">Product Categories</span>
          </a><!-- br-menu-link -->
        </li><!-- br-menu-item -->
        <li class="br-menu-item" id="manufacturers_menu">
          <a href="{{route('showManufacturers')}}" class="br-menu-link" >
            <i class="menu-item-icon icon ion-ios-people-outline tx-24"></i>
            <span class="menu-item-label">Manufacturers</span>
          </a><!-- br-menu-link -->
        </li><!-- br-menu-item -->
        <li class="br-menu-item" id="brands_menu">
          <a href="{{route('showBrands')}}" class="br-menu-link" >
            <i class="menu-item-icon icon ion-ios-people-outline tx-24"></i>
            <span class="menu-item-label">Brands</span>
          </a><!-- br-menu-link -->
        </li><!-- br-menu-item -->
      </ul><!-- br-sideleft-menu -->

    </div><!-- br-sideleft -->
    <!-- ########## END: LEFT PANEL ########## -->

    <!-- ########## START: HEAD PANEL ########## -->
    <div class="br-header">
      <div class="br-header-left">
        <div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href=""><i class="icon ion-navicon-round"></i></a></div>
        <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href=""><i class="icon ion-navicon-round"></i></a></div>

      </div><!-- br-header-left -->
      <div class="br-header-right">
        <nav class="nav">
          <div class="dropdown">
            <a href="#" class="nav-link pd-x-7 pos-relative" >
              <i class="icon ion-ios-email-outline tx-24"></i>
              <!-- start: if statement -->
              <span class="square-8 bg-danger pos-absolute t-15 r-0 rounded-circle"></span>
              <!-- end: if statement -->
            </a>
            <div class="dropdown-menu dropdown-menu-header">
              <div class="dropdown-menu-label">
                <label>Messages</label>
                <a href="">+ Add New Message</a>
              </div><!-- d-flex -->

              <div class="media-list">
                <!-- loop starts here -->
                <a href="" class="media-list-link">
                  <div class="media">
                    <img src="https://via.placeholder.com/500" alt="">
                    <div class="media-body">
                      <div>
                        <p>Donna Seay</p>
                        <span>2 minutes ago</span>
                      </div><!-- d-flex -->
                      <p>A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring.</p>
                    </div>
                  </div><!-- media -->
                </a>
                <!-- loop ends here -->
                <a href="" class="media-list-link read">
                  <div class="media">
                    <img src="https://via.placeholder.com/500" alt="">
                    <div class="media-body">
                      <div>
                        <p>Samantha Francis</p>
                        <span>3 hours ago</span>
                      </div><!-- d-flex -->
                      <p>My entire soul, like these sweet mornings of spring.</p>
                    </div>
                  </div><!-- media -->
                </a>
                <a href="" class="media-list-link read">
                  <div class="media">
                    <img src="https://via.placeholder.com/500" alt="">
                    <div class="media-body">
                      <div>
                        <p>Robert Walker</p>
                        <span>5 hours ago</span>
                      </div><!-- d-flex -->
                      <p>I should be incapable of drawing a single stroke at the present moment...</p>
                    </div>
                  </div><!-- media -->
                </a>
                <a href="" class="media-list-link read">
                  <div class="media">
                    <img src="https://via.placeholder.com/500" alt="">
                    <div class="media-body">
                      <div>
                        <p>Larry Smith</p>
                        <span>Yesterday</span>
                      </div><!-- d-flex -->
                      <p>When, while the lovely valley teems with vapour around me, and the meridian sun strikes...</p>
                    </div>
                  </div><!-- media -->
                </a>
                <div class="dropdown-footer">
                  <a href=""><i class="fas fa-angle-down"></i> Show All Messages</a>
                </div>
              </div><!-- media-list -->
            </div><!-- dropdown-menu -->
          </div><!-- dropdown -->
          <div class="dropdown">
            <a href="#" class="nav-link pd-x-7 pos-relative">
              <i class="icon ion-ios-bell-outline tx-24"></i>
              <!-- start: if statement -->
              <span class="square-8 bg-danger pos-absolute t-15 r-5 rounded-circle"></span>
              <!-- end: if statement -->
            </a>
            <div class="dropdown-menu dropdown-menu-header">
              <div class="dropdown-menu-label">
                <label>Notifications</label>
                <a href="">Mark All as Read</a>
              </div><!-- d-flex -->

              <div class="media-list">
                <!-- loop starts here -->
                <a href="" class="media-list-link read">
                  <div class="media">
                    <img src="https://via.placeholder.com/500" alt="">
                    <div class="media-body">
                      <p class="noti-text"><strong>Suzzeth Bungaos</strong> tagged you and 18 others in a post.</p>
                      <span>October 03, 2017 8:45am</span>
                    </div>
                  </div><!-- media -->
                </a>
                <!-- loop ends here -->
                <a href="" class="media-list-link read">
                  <div class="media">
                    <img src="https://via.placeholder.com/500" alt="">
                    <div class="media-body">
                      <p class="noti-text"><strong>Mellisa Brown</strong> appreciated your work <strong>The Social Network</strong></p>
                      <span>October 02, 2017 12:44am</span>
                    </div>
                  </div><!-- media -->
                </a>
                <a href="" class="media-list-link read">
                  <div class="media">
                    <img src="https://via.placeholder.com/500" alt="">
                    <div class="media-body">
                      <p class="noti-text">20+ new items added are for sale in your <strong>Sale Group</strong></p>
                      <span>October 01, 2017 10:20pm</span>
                    </div>
                  </div><!-- media -->
                </a>
                <a href="" class="media-list-link read">
                  <div class="media">
                    <img src="https://via.placeholder.com/500" alt="">
                    <div class="media-body">
                      <p class="noti-text"><strong>Julius Erving</strong> wants to connect with you on your conversation with <strong>Ronnie Mara</strong></p>
                      <span>October 01, 2017 6:08pm</span>
                    </div>
                  </div><!-- media -->
                </a>
                <div class="dropdown-footer">
                  <a href=""><i class="fas fa-angle-down"></i> Show All Notifications</a>
                </div>
              </div><!-- media-list -->
            </div><!-- dropdown-menu -->
          </div><!-- dropdown -->
          <div class="dropdown">
            <a href="" class="nav-link nav-link-profile" data-toggle="dropdown">
              <span class="logged-name hidden-md-down">{{Auth::user()->name}}</span>
            <img src="{{asset(Auth::user()->image)}}" class="wd-32 rounded-circle" alt="">
              <span class="square-10 bg-success"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-header wd-250">
              <div class="tx-center">
                <a href=""><img src="{{asset(Auth::user()->image)}}" class="wd-80 rounded-circle" alt=""></a>
                <h6 class="logged-fullname">{{Auth::user()->name}}</h6>
                <p>{{Auth::user()->email}}</p>
              </div>

              <ul class="list-unstyled user-profile-nav">
              <li><a href="{{ route('showProfile') }}"><i class="icon ion-ios-person"></i> Edit Profile</a></li>
                <li><a href=""><i class="icon ion-ios-gear"></i> Settings</a></li>
              <li><a href="{{route('logout')}}"><i class="icon ion-power"></i> Sign Out</a></li>
              </ul>
            </div><!-- dropdown-menu -->
          </div><!-- dropdown -->
        </nav>
      </div><!-- br-header-right -->
    </div><!-- br-header -->
    <!-- ########## END: HEAD PANEL ########## -->

    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">

      @yield('content')

      <!--footer -->
      <footer class="br-footer">
        {{-- <div class="footer-left">
          <div class="mg-b-2">Copyright &copy; 2019. Iroid Technologies. All Rights Reserved.</div>
        </div>
        <div class="footer-right d-flex align-items-center">
          <span class="tx-uppercase mg-r-10">Share:</span>
          <a target="_blank" class="pd-x-5" href="https://www.facebook.com/sharer/sharer.php?u=http%3A//themepixels.me/bracketplus/intro"><i class="fab fa-facebook tx-20"></i></a>
          <a target="_blank" class="pd-x-5" href="https://twitter.com/home?status=Bracket%20Plus,%20your%20best%20choice%20for%20premium%20quality%20admin%20template%20from%20Bootstrap.%20Get%20it%20now%20at%20http%3A//themepixels.me/bracketplus/intro"><i class="fab fa-twitter tx-20"></i></a>
        </div> --}}
      </footer>

    </div><!-- br-mainpanel -->
    <!-- ########## END: MAIN PANEL ########## -->

    <script src="{{asset('app/lib/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('app/lib/jquery-ui/ui/widgets/datepicker.js')}}"></script>
    <script src="{{asset('app/lib/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('app/lib/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('app/lib/moment/min/moment.min.js')}}"></script>
    <script src="{{asset('app/lib/peity/jquery.peity.min.js')}}"></script>
    <script src="{{asset('app/lib/jquery.flot/jquery.flot.js')}}"></script>
    <script src="{{asset('app/lib/jquery.flot/jquery.flot.resize.js')}}"></script>
    <script src="{{asset('app/lib/flot-spline/js/jquery.flot.spline.min.js')}}"></script>
    <script src="{{asset('app/lib/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <script src="{{asset('app/lib/echarts/echarts.min.js')}}"></script>
    <script src="{{asset('app/lib/select2/js/select2.full.min.js')}}"></script>
    <!-- inner pages -->
    <script src="{{asset('app/lib/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('app/lib/datatables.net-dt/js/dataTables.dataTables.min.js')}}"></script>
    <script src="{{asset('app/lib/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('app/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js')}}"></script>
    <script src="{{asset('app/lib/select2/js/select2.min.js')}}"></script>
    <!-- end inner page -->
    <script src="{{asset('app/js/bracket.js')}}"></script>
    <script src="{{asset('app/js/ResizeSensor.js')}}"></script>
    {{-- <script src="{{asset('app/js/dashboard.js')}}"></script> --}}
    <script src="{{asset('js/jquery.validate.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    @yield('scripts')
    <script>

    </script>
  </body>
</html>
