<!DOCTYPE html>
<head>
    <!--Meta-->
    <meta charset="UTF-8">
    <title>MHoC GEXIV Admin</title>
    <meta property="og:site_name" content="MHoC GEXIV Admin"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <!--MHOC specific CSS-->
    <style>
        .bg-mhoc {
            background-color: #006B3E;
        }
    </style>
    <!--Some chart javascript-->
    <script src="{{asset('js/graphs.js')}}"></script>
</head>
<body>
<!--Main Navigation-->
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-mhoc">
        <div class="container">
            <a class="navbar-brand" href="{{route('index')}}"><strong>MHoC GEXIV Admin</strong></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                        <a class="nav-link" href="{{route('admin.index')}}">Home</a>
                    </li>
                </ul>
                <ul class="navbar-nav nav-flex-icons">
                    <li class="nav-item">
                        <a class="nav-link"><i class="fa fa-shield-alt"></i>
                            &nbsp;{{Auth::user()->username}}
                        </a>
                    </li>
                    <li>
                        <a href="{{route('index')}}" class="nav-link">Exit</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
@if (\Session::has('success'))
    <div class="container pt-4">
        <p class="note note-success">{!! \Session::get('success') !!}</p>
    </div>
@elseif (\Session::has('error'))
    <div class="container pt-4">
        <p class="note note-danger">{!! \Session::get('error') !!}</p>
    </div>
@elseif (\Session::has('info'))
    <div class="container pt-4">
        <p class="note note-info">{!! \Session::get('info') !!}</p>
    </div>
@endif
<main class="">
    @yield('content')
</main>
<!-- Footer -->
<footer class="page-footer font-small bg-mhoc pt-4">
    <!-- Footer Text -->
    <div class="container text-center">
            <p>Developed by /u/Lieselta</p>
    </div>
    <!-- Footer Text -->
    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">© 2019 Copyright
        <a href="https://reddit.com/r/MHOC/"> Reddit Model House of Commons</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->

</body>
