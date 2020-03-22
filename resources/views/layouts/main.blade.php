<!DOCTYPE html>
<html lang="en">
<head>
    <!--Meta-->
    <meta charset="UTF-8">
    <title>@yield('title', '') - MHoC GEXIII Results</title>
    <meta property="og:site_name" content="MHoC GEXIII Results"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="@yield('title', '') - MHoC GEXIII Results" />
    <meta property="og:description" content="@yield('description')" />
    <meta property="og:image" content="@yield('image',asset('img/mhoc.png'))"/>
    <meta property="og:url" content="{{\Illuminate\Support\Facades\URL::current()}}"/>
    <meta name="theme-color" content="@yield('theme-color', '#006B3E')"/>
    <meta name="description" content="@yield('description')">
    <link rel="shortcut icon" href="{{ asset('img/mhoc.png') }}">
    <!--MDBoostrap-->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.7/css/mdb.min.css" rel="stylesheet">
    <!-- JQuery -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.7/js/mdb.min.js"></script>
    <!--MHOC specific CSS-->
    <link rel="stylesheet" href="{{asset('css/mhoc.css')}}">
    <!--Some chart javascript-->
    <script src="{{asset('js/graphs.js')}}"></script>
    <!--Parliament diagram-->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/item-series.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="{{asset('js/diagram.js')}}"></script>
</head>
<body>
<!--Main Navigation-->
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-mhoc">
        <div class="container">
            <a class="navbar-brand" href="{{route('index')}}">
                <img src="@yield('nav-bar-img',asset('img/HoCIconWhite.png'))" width="30" class="d-inline-block align-top" alt="">
                &nbsp;<span class="d-inline-block align-centre font-weight-bold" style="margin-top: 4.5px;">MHoC GEXIII Results</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                        <a class="nav-link" href="{{route('index')}}">Overview</a>
                    </li>
                    <li class="nav-item {{ Request::is('stateoftheparties') || Request::is('stateoftheparties/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{route('stateoftheparties')}}">State of the Parties</a>
                    </li>
                    <li class="nav-item {{ Request::is('constituencies') || Request::is('constituencies/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{route('constituencies')}}">Constituencies</a>
                    </li>
                    <li class="nav-item {{ Request::is('coalitionmaker') || Request::is('coalitionmaker/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{route('coalitionmaker')}}">Coalition Maker</a>
                    </li>
                    <li class="nav-item {{ Request::is('candidates') || Request::is('candidates/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{route('candidates')}}">Candidates</a>
                    </li>
                </ul>
                <ul class="navbar-nav nav-flex-icons">
                    {{--<li data-toggle="modal" data-target="#infoModal" class="nav-item">
                        <a class="nav-link"><i class="fa fa-info"></i></a>
                    </li>--}}
                    @if (Auth::check())
                    <li class="nav-item">
                        <a href="{{route('admin.index')}}" class="nav-link"><i class="fa fa-shield-alt"></i>
                            &nbsp;{{Auth::user()->username}}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <div style="margin:0;border-radius:0;" class="alert alert-light"><div class="container">
        <span class="red-text"><i class="fas fa-circle"></i> <b>LIVE</b></span>&nbsp;Watch the Sky News stream <a href="#">here</a>&nbsp;|&nbsp;Check out #press-announcements on Discord for live constituency results
    </div>
</header>
<!-- Full Height Modal Right -->
<div class="modal fade top" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-top" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">What is this?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn text-white bg-mhoc" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Full Height Modal Right -->
<!--Main Navigation-->
<main class="">
    @yield('content')
</main>
<!-- Footer -->
<footer class="page-footer font-small bg-mhoc pt-4">
    <!-- Footer Text -->
    <div class="container text-center pb-4">
            <p>Developed by /u/ellielia</p><br/>
            <a href="{{route('admin.auth.login')}}">Login
            </a>
    </div>
    <!-- Footer Text -->
    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">© 2019-2020 Copyright
        <a href="https://reddit.com/r/MHOC/"> Reddit Model House of Commons</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->

</body>
</html>
