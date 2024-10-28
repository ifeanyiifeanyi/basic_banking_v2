<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>{{ config('app.name') }} - @yield('title')</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Mannatthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="/assets/images/favicon.ico">

    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="/assets/css/style.css" rel="stylesheet" type="text/css">

    <!-- DataTables -->
    <link href="/assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="/assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    @yield('css')
</head>


<body class="fixed-left">

    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- ========== Left Sidebar Start ========== -->
        @include('admin.layouts.sidebar')

        <!-- Start right Content here -->

        <div class="content-page">
            <!-- Start content -->
            <div class="content">

                <!-- Top Bar Start -->
                @include('admin.layouts.navbar')
                <!-- Top Bar End -->

                <div class="page-content-wrapper ">

                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="page-title-box">
                                    <div class="float-right btn-group">
                                        <ol class="p-0 m-0 breadcrumb hide-phone">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('admin.dashboard') }}">Home</a></li>
                                            <li class="breadcrumb-item active">@yield('title')</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">@yield('title')</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title end breadcrumb -->
                        @yield('admin')
                    </div><!-- container -->

                </div> <!-- Page content Wrapper -->

            </div> <!-- content -->

            @include('admin.layouts.footer')

        </div>
        <!-- End Right content here -->

    </div>
    <!-- END wrapper -->


    <!-- jQuery  -->
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/modernizr.min.js"></script>
    <script src="/assets/js/detect.js"></script>
    <script src="/assets/js/fastclick.js"></script>
    <script src="/assets/js/jquery.slimscroll.js"></script>
    <script src="/assets/js/jquery.blockUI.js"></script>
    <script src="/assets/js/waves.js"></script>
    <script src="/assets/js/jquery.scrollTo.min.js"></script>
    <script src="{{ asset('js/bank-js') }}"></script>


     <!-- Required datatable js -->
     <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
     <script src="/assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
     <!-- Buttons examples -->
     <script src="/assets/plugins/datatables/dataTables.buttons.min.js"></script>
     <script src="/assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
     <script src="/assets/plugins/datatables/jszip.min.js"></script>
     <script src="/assets/plugins/datatables/pdfmake.min.js"></script>
     <script src="/assets/plugins/datatables/vfs_fonts.js"></script>
     <script src="/assets/plugins/datatables/buttons.html5.min.js"></script>
     <script src="/assets/plugins/datatables/buttons.print.min.js"></script>
     <script src="/assets/plugins/datatables/buttons.colVis.min.js"></script>
     <!-- Responsive examples -->
     <script src="/assets/plugins/datatables/dataTables.responsive.min.js"></script>
     <script src="/assets/plugins/datatables/responsive.bootstrap4.min.js"></script>

     <!-- Datatable init js -->
     <script src="/assets/pages/datatables.init.js"></script>

    <!-- App js -->
    <script src="/assets/js/app.js"></script>
    @yield('javascript')
</body>

</html>
