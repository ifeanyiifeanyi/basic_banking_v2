<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }} | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully responsive admin theme which can be used to build CRM, CMS,ERP etc." name="description" />
    <meta content="{{ config('app.name') }}" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset("") }}users/assets/images/favicon.ico">

    <!-- Theme Config Js -->
    <script src="{{ asset("") }}users/assets/js/config.js"></script>

    <!-- App css -->
    <link href="{{ asset("") }}users/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{ asset("") }}users/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    @yield('css')
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">


        <!-- ========== Topbar Start ========== -->
        @include('members.layouts.partials.navbar')
        <!-- ========== Topbar End ========== -->


        <!-- ========== Left Sidebar Start ========== -->
        @include('members.layouts.partials.sidebar')
        <!-- ========== Left Sidebar End ========== -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item active">@yield('title')</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">@yield('title')</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    @yield('member')

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
           @include('members.layouts.partials.footer')
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Vendor js -->
    <script src="{{ asset("") }}users/assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="{{ asset("") }}users/assets/js/app.min.js"></script>
@yield('javascript')
</body>

</html>
