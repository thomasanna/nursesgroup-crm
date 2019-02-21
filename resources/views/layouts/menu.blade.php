<ul class="sidebar-menu" data-widget="tree">
  @if(Auth::guard('admin')->user()->type==1 || Auth::guard('admin')->user()->type==12 || Auth::guard('admin')->user()->type==6)


   <li>
    <a href="{{route('users.home')}}">
      <i class="fa fa-user"></i>
      <span>Users</span>
    </a>
  </li>


 <li class="treeview">
    <a href="#">
      <i class="fa fa-cogs"></i> <span>Settings</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li><a href="{{route('roles.home')}}"><i class="fa fa-circle-o"></i>Roles & Permission</a></li>
    <!--   <li><a href="{{route('permissions.home')}}"><i class="fa fa-circle-o"></i>Permission</a></li> -->
      <li><a href="{{route('taxyears.home')}}"><i class="fa fa-circle-o"></i>Tax Years</a></li>
      </ul>
  </li>

  <li>
    <a href="{{route('branches.home')}}">
      <i class="fa fa-map-marker"></i>
      <span>Branches</span>
    </a>
  </li>
  <li class="treeview">
    <a href="#">
      <i class="fa fa-h-square"></i> <span>Customers</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li><a href="{{route('clients.home')}}"><i class="fa fa-circle-o"></i> Clients</a></li>
      <li><a href="{{route('client_units.home')}}"><i class="fa fa-circle-o"></i> Units</a></li>
    </ul>
  </li>
  @endif
  @if(Auth::guard('admin')->user()->type==1 || Auth::guard('admin')->user()->type==3 || Auth::guard('admin')->user()->type==12
  || Auth::guard('admin')->user()->type==6)
  <li class="treeview">
    <a href="#">
      <i class="fa fa-users"></i> <span>HR</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li><a href="{{route('applicants.dashboard')}}"><i class="fa fa-circle-o"></i> Dashboard</a></li>
      <li><a href="{{route('applicants.home')}}"><i class="fa fa-circle-o"></i> Applicants</a></li>
      <li><a href="{{route('staffs.home.active')}}"><i class="fa fa-circle-o"></i>Active Staff</a></li>
      <li><a href="{{route('staffs.home.inactive')}}"><i class="fa fa-circle-o"></i>Inactive Staff</a></li>
      <li><a href="{{route('applicants.home.terminated')}}"><i class="fa fa-circle-o"></i>Terminated Applicants</a></li>
      <li><a href="{{route('staffs.home.terminated')}}"><i class="fa fa-circle-o"></i>Terminated Staffs</a></li>
      <li><a href="{{route('drivers.home')}}"><i class="fa fa-circle-o"></i>Drivers</a></li>
    </ul>
  </li>

  <li class="treeview">
    <a href="#">
      <i class="fa fa-user"></i> <span>Availabilty</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li><a href="{{route('staffs.availabilty')}}"><i class="fa fa-circle-o"></i> Record</a></li>
      <li><a href="{{route('staffs.availabilty.report')}}"><i class="fa fa-circle-o"></i> Report</a></li>
    </ul>
  </li>

  @endif
  @if(Auth::guard('admin')->user()->type==1 || Auth::guard('admin')->user()->type==12 || Auth::guard('admin')->user()->type==6)
  <li class="treeview">
    <a href="#">
      <i class="fa fa-bar-chart"></i> <span>Rostering</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li><a href="{{route('booking.dashboard')}}"><i class="fa fa-circle-o"></i> Dashboard</a></li>
      <li><a href="{{route('staffs.home.all')}}"><i class="fa fa-circle-o"></i>All Staff</a></li>
      <li><a href="{{route('booking.current')}}"><i class="fa fa-circle-o"></i> Bookings</a></li>
      <li><a href="{{route('booking.allocation.report')}}"><i class="fa fa-circle-o"></i>Allocation Report</a></li>

    </ul>
  </li>

  <li class="treeview">
    <a href="#">
      <i class="fa fa-taxi"></i> <span>Transportation</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li><a href="{{route('transportation.current.trips')}}"><i class="fa fa-circle-o"></i> Current Trips</a></li>
      <li><a href="{{route('transportation.completed.trips')}}"><i class="fa fa-circle-o"></i> Completed Trips</a></li>
      <li><a href="{{route('transportation.archives')}}"><i class="fa fa-circle-o"></i>Archives</a></li>
    </ul>
  </li>
  <li>
    <a href="{{route('timesheet.list')}}">
      <i class="fa fa-id-card"></i>
      <span>Timesheets</span>
    </a>
  </li>

  <li class="treeview">
    <a href="#">
      <i class="fa fa-money"></i> <span>Payees</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li><a href="#"><i class="fa fa-circle-o"></i> Dashboard</a></li>
      <li><a href="{{route('payment.payee.list')}}"><i class="fa fa-circle-o"></i> V & A </a></li>
      <li><a href="{{route('payment.payee.weeks.list')}}"><i class="fa fa-circle-o"></i> Weeks</a></li>
      <li><a href="{{route('payment.payee.archives')}}"><i class="fa fa-circle-o"></i> Archives</a></li>
    </ul>
  </li>

  <li class="treeview">
    <a href="#">
      <i class="fa fa-money"></i> <span>Selfies</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li><a href="#"><i class="fa fa-circle-o"></i> Dashboard</a></li>
      <li><a href="{{route('payment.selfie.list')}}"><i class="fa fa-circle-o"></i> V & A </a></li>
      <li><a href="{{route('payment.selfie.weeks.list')}}"><i class="fa fa-circle-o"></i> Weeks</a></li>
      <li><a href="{{route('payment.selfie.archives')}}"><i class="fa fa-circle-o"></i> Archives</a></li>
    </ul>
  </li>
  @if(Auth::guard('admin')->user()->type==1)
  <li class="treeview">
    <a href="#">
      <i class="fa fa-paper-plane"></i> <span>Unit Bills</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li><a href="{{route('invoices.list')}}"><i class="fa fa-circle-o"></i> Verification</a></li>
      <li><a href="{{route('invoices.monthly.list')}}"><i class="fa fa-circle-o"></i> Monthly</a></li>
      <li><a href="{{route('invoices.weekly.list')}}"><i class="fa fa-circle-o"></i> Weekly</a></li>
    </ul>
  </li>
  @endif
  @endif

  
