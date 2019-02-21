<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Nurses Group UK - @yield('title')</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link rel="stylesheet" href="{{asset('public/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/unitArea/assets/css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/unitArea/assets/css/light-bootstrap-dashboard.css')}}">
    <link rel="stylesheet" href="{{asset('public/unitArea/assets/css/demo.css')}}">
    @stack('css')
    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{asset('public/unitArea/assets/css/pe-icon-7-stroke.css')}}">

</head>
<body>

<div class="wrapper">
    @include('unitArea.layouts.menu')

    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">@yield('title')</a>
                </div>
                <div class="collapse navbar-collapse">

                    <ul class="nav navbar-nav navbar-right">
                        <li>
                           <a href="{{route('unit.area.account.account')}}">
                               <p>Account</p>
                            </a>
                        </li>
                      
                        <li>
                            <a href="{{route('unit.area.logout')}}">
                                <p>Log out</p>
                            </a>
                        </li>
						<li class="separator hidden-lg"></li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="content">
            @yield('content')
        </div>


        <footer class="footer">
            <div class="container-fluid">
         <p class="copyright pull-right">Copyright © 2019 - NursesGroup - v1.0.0
                    
                </p>
            </div>
        </footer>

    </div>
</div>

@yield('modal')
</body>
    <!--   Core JS Files   -->
    <script src="{{asset('public/unitArea/assets/js/jquery.3.2.1.min.js')}}">></script>
    <script src="{{asset('public/unitArea/assets/js/bootstrap.min.js')}}">></script>
     @stack('scripts')
</html>
