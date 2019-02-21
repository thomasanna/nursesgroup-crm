<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <?php echo
         header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
         header("Cache-Control: post-check=0, pre-check=0", false);
         header("Pragma: no-cache");
         header('Content-Type: text/html');?>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Nurses Group UK - @yield('title')</title>
      <!-- Tell the browser to be responsive to screen width -->
      <link href="{{asset('public/images/favicon.ico')}}" rel="shortcut icon">
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <!-- Bootstrap 3.3.7 -->
      <link rel="stylesheet" href="{{asset('public/css/bootstrap.min.css')}}">
      @stack('css')
      <!-- Font Awesome -->
      <link rel="stylesheet" href="{{asset('public/css/font-awesome/css/font-awesome.min.css')}}">
      <!-- Ionicons -->
      <link rel="stylesheet" href="{{asset('public/css/Ionicons/css/ionicons.min.css')}}">
      <!-- Theme style -->
      <link rel="stylesheet" href="{{asset('public/css/AdminLTE.min.css')}}?{{time()}}">
      <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
      <link rel="stylesheet" href="{{asset('public/css/_all-skins.min.css')}}?{{time()}}">
      <link rel="stylesheet" href="{{asset('public/css/app.css')}}?{{time()}}">
      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <!-- Google Font -->
      <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Courgette|Overlock|Rancho" rel="stylesheet">
   </head>
   <body class="hold-transition skin-purple sidebar-mini sidebar-collapse">
      <div class="wrapper">
         <header class="main-header">
            <!-- Logo -->
            <a href="{{route('home.dashboard')}}" class="logo">
               <!-- mini logo for sidebar mini 50x50 pixels -->
               <span class="logo-mini"><b>N</b>G</span>
               <!-- logo for regular state and mobile devices -->
               <span class="logo-lg"><b>Nurses</b>Group UK</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
               <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
               <span class="sr-only">Toggle navigation</span>
               </a>
               <div class="navbar-custom-menu">
                  <ul class="nav navbar-nav">
                     <!-- User Account: style can be found in dropdown.less -->
                     <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs">{{Auth::guard('admin')->user()->name}}</span>
                        </a>
                        <ul class="dropdown-menu" style="width:450px "  >
                           <!-- User image -->
                           <li class="user-header">
                              <p>
                                 {{Auth::guard('admin')->user()->name}}
                                 <small>{{Auth::guard('admin')->user()->email}}</small>
                              </p>
                           </li>
                           <!-- Menu Footer-->
                           <li class="user-footer">
                              <div class="pull-left">
                                 <button class="btn btn-default btn-flat" data-target="#passwordResetModal" data-toggle="modal">Change Password</button>
                              </div>
                              <div class="pull-right">
                                 <a href="{{route('admin.logout')}}" class="btn btn-default btn-flat btn btn-outline-danger">Sign out</a>
                              </div>
                           </li>
                        </ul>
                     </li>
                  </ul>
               </div>
               <a href="{{route('drivers.home')}}" type="button" class="btn btn-danger navbar-btn pull-right">Drivers</a>
               <a href="{{route('client_units.staffs.home')}}" type="button" class="btn btn-success navbar-btn pull-right m-r-10">Units</a>
               <a href="{{route('staffs.home.all')}}" type="button" class="btn btn-success navbar-btn pull-right m-r-10">Staffs</a>
               <a href="{{route('booking.current')}}" type="button" class="btn btn-success navbar-btn pull-right m-r-10">Bookings</a>
               <a href="{{route('timesheet.list')}}" type="button" class="btn btn-success navbar-btn pull-right m-r-10">Timesheets</a>
               <a href="{{route('staffs.availabilty')}}" type="button" class="btn btn-success navbar-btn pull-right m-r-10">Availability</a>
            </nav>
         </header>
         <!-- Left side column. contains the logo and sidebar -->
         <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
               @include('layouts.menu')
            </section>
            <!-- /.sidebar -->
         </aside>
         <!-- Content Wrapper. Contains page content -->
         <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <!-- Main content -->
            <section class="content">
               @yield('content')
            </section>
            <!-- /.content -->
         </div>
         <!-- /.content-wrapper -->
         <footer class="main-footer">
            <div class="pull-right hidden-xs">
               <b>Version</b> {{config('app.version')}}
            </div>
            <strong>Copyright &copy; {{date('Y')}}</strong>
         </footer>
         <div class="modal fade" tabindex="-1" id="passwordResetModal" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-sm">
               <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">
                     &times;
                     </button>
                     <h4 class="modal-title">Change Password</h4>
                  </div>
                  <form  method="POST" action="{{route('change.password')}}">
                     {!! csrf_field() !!}
                     <div class="modal-body">
                        <div class="form-group">
                           <label for="current-password">Current Password</label>
                           <input class="form-control" placeholder="Current Password"
                              required   type="text" name="current-password">
                        </div>
                        <div class="form-group">
                           <label for="new-password">New Password</label>
                           <input class="form-control" placeholder="New Password" type="password" required name="new-password">
                        </div>
                        <div class="form-group">
                           <label for="Confirm-Password">Confirm Password</label>
                           <input class="form-control" placeholder="Confirm-Password" type="password"  required name="confirm-password">
                        </div>
                     </div>
                     <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-primary"
                           data-dismiss="modal">Close</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- ./wrapper -->
      <!-- jQuery 3 -->
      <script src="{{asset('public/js/jquery.min.js')}}">></script>
      <!-- jQuery UI 1.11.4 -->
      <script src="{{asset('public/js/jquery-ui.min.js')}}">></script>
      <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
      <script>
         $.widget.bridge('uibutton', $.ui.button);
      </script>
      <!-- Bootstrap 3.3.7 -->
      <script src="{{asset('public/js/bootstrap.min.js')}}">></script>
      <!-- Slimscroll -->
      <script src="{{asset('public/js/jquery.slimscroll.min.js')}}">></script>
      <!-- FastClick -->
      <script src="{{asset('public/js/fastclick.js')}}">></script>
      <!-- AdminLTE App -->
      <script src="{{asset('public/js/adminlte.min.js')}}">></script>
      <script src="{{asset('public/js/global.js')}}">></script>
      @stack('scripts')
</body>
</html>
