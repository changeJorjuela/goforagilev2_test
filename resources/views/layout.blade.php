<!DOCTYPE html>
<html>

<head>
    <html lang="{{ app()->getLocale() }}">
    <title>GO FOR AGILE - OKR Suite By Change Americas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="HandheldFriendly" content="true">
    <meta name="MobileOptimized" content="width">    
    <link type="image/x-icon" rel="icon" href="{{asset("img/IsotipoGFA.png")}}">
    <link rel="stylesheet" href="{{asset("css/font-awesome/css/all.min.css")}}">
    <!-- Common CSS -->
    <link rel="stylesheet" href="{{asset("template/css/bootstrap.min.css")}}" />
    <link rel="stylesheet" href="{{asset("template/fonts/icomoon/icomoon.css")}}" />
    <link rel="stylesheet" href="{{asset("template/css/main.min.css")}}" />
    <link rel="stylesheet" href="{{asset("css/okrs.css")}}" />

    <!-- Other CSS includes plugins - Cleanedup unnecessary CSS -->
    <!-- Chartist css -->
    <link href="{{asset("template/vendor/chartist/css/chartist.min.css")}}" rel="stylesheet" />
    <link href="{{asset("template/vendor/chartist/css/chartist-custom.css")}}" rel="stylesheet" />

    <!-- Datatables -->
    <link rel="stylesheet" href="{{asset("DataTables/datatables-bs4/css/dataTables.bootstrap4.min.css")}}">
    <link rel="stylesheet" href="{{asset("DataTables/datatables-responsive/css/responsive.bootstrap4.min.css")}}">
    <link rel="stylesheet" href="{{asset("DataTables/datatables-buttons/css/buttons.bootstrap4.min.css")}}">
    <link rel="stylesheet" href="{{asset("css/select2.min.css")}}">
    <link rel="stylesheet" href="{{asset("css/toastr.min.css")}}">

    @yield('styles')
</head>

<body>
    <div id="loading-wrapper">
        <div id="loader">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
            <div class="line4"></div>
            <div class="line5"></div>
            <div class="line6"></div>
        </div>
    </div>
    <div class="app-wrap">
        @include("header")
        <div class="app-container">
            @include("aside")
            <div class="app-main">
                    @yield('headerPage')
                <div class="main-content">
                    @yield('contenido')
                </div>
            </div>
        </div>
        @include("footer")
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>
    <!-- <script src="{{asset("js/jquery.min.js")}}"></script> -->
    <!-- <script src="{{asset("js/jquery-migrate.min.js")}}"></script> -->
    <script src="{{asset("template/js/jquery.js")}}"></script>
    <script src="{{asset("template/js/bootstrap.min.js")}}"></script>
    <script src="{{asset("template/vendor/unifyMenu/unifyMenu.js")}}"></script>
    <script src="{{asset("template/vendor/onoffcanvas/onoffcanvas.js")}}"></script>
    <script src="{{asset("template/js/moment.js")}}"></script>

    <!-- Slimscroll JS -->
    <script src="{{asset("template/vendor/slimscroll/slimscroll.min.js")}}"></script>
    <script src="{{asset("template/vendor/slimscroll/custom-scrollbar.js")}}"></script>

    <!-- Chartist JS -->
    <script src="{{asset("template/vendor/chartist/js/chartist.min.js")}}"></script>
    <script src="{{asset("template/vendor/chartist/js/chartist-tooltip.js")}}"></script>
    <script src="{{asset("template/vendor/chartist/js/custom/custom-line-chart.js")}}"></script>
    <script src="{{asset("template/vendor/chartist/js/custom/custom-line-chart1.js")}}"></script>
    <script src="{{asset("template/vendor/chartist/js/custom/custom-area-chart.js")}}"></script>
    <script src="{{asset("template/vendor/chartist/js/custom/donut-chart2.js")}}"></script>
    <script src="{{asset("template/vendor/chartist/js/custom/custom-line-chart4.js")}}"></script>

    <!-- Datatables -->
    <script src="{{asset("DataTables/datatables/jquery.dataTables.min.js")}}"></script>
    <script src="{{asset("DataTables/datatables-bs4/js/dataTables.bootstrap4.min.js")}}"></script>
    <script src="{{asset("DataTables/datatables-responsive/js/dataTables.responsive.min.js")}}"></script>
    <script src="{{asset("DataTables/datatables-responsive/js/responsive.bootstrap4.min.js")}}"></script>
    <script src="{{asset("DataTables/datatables-buttons/js/dataTables.buttons.min.js")}}"></script>
    <script src="{{asset("DataTables/datatables-buttons/js/buttons.bootstrap4.min.js")}}"></script>
    <script src="{{asset("DataTables/jszip/jszip.min.js")}}"></script>
    <script src="{{asset("DataTables/pdfmake/pdfmake.min.js")}}"></script>
    <script src="{{asset("DataTables/pdfmake/vfs_fonts.js")}}"></script>
    <script src="{{asset("DataTables/datatables-buttons/js/buttons.html5.min.js")}}"></script>
    <script src="{{asset("DataTables/datatables-buttons/js/buttons.print.min.js")}}"></script>
    <script src="{{asset("DataTables/datatables-buttons/js/buttons.colVis.min.js")}}"></script>
    <script src="{{asset("js/administracion.js")}}"></script>
    <script src="{{asset("js/select2.min.js")}}"></script>
    <script src="{{asset("js/toastr.min.js")}}"></script>
    <script src="{{asset("template/js/common.js")}}"></script>


    @yield('scripts')
</body>

</html>