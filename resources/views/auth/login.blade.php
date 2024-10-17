<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>{{ config('app.name') }} - Login</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Mannatthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">

</head>


<body class="fixed-left">

    <div class="accountbg"></div>
    <div class="wrapper-page">

        <div class="card">
            <div class="card-body">

                <div class="text-center m-b-15">
                    <a href="index.html" class="logo logo-admin"><img src="assets/images/logo.png" height="24"
                            alt="logo"></a>
                </div>

                <div class="p-3">
                    @if (session('status'))
                        <div class="m-3 alert alert-danger">
                            <ul>
                                @if (is_array(session('status')))
                                    @foreach (session('status') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                @else
                                    <li>{{ session('status') }}</li>
                                @endif
                            </ul>
                        </div>
                    @endif
                    <form class="form-horizontal m-t-20" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-12">
                                <input class="form-control" name="email" type="text" value="{{ old('email') }}"
                                    placeholder="Username / Email Address" autocomplete="username" autofocus>
                                @error('email')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <input class="form-control" name="password" type="password"
                                    autocomplete="current-password" placeholder="Password">
                                @error('password')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="remember" class="custom-control-input"
                                        id="customCheck1">
                                    <label class="custom-control-label" for="customCheck1">Remember me</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-center form-group row m-t-20">
                            <div class="col-12">
                                <button class="btn btn-danger btn-block waves-effect waves-light" type="submit">Log
                                    In</button>
                            </div>
                        </div>

                        <div class="mb-0 form-group m-t-10 row">
                            <div class="col-sm-7 m-t-20">
                                <a href="{{ route('password.request') }}" class="text-muted"><i
                                        class="mdi mdi-lock"></i>
                                    <small>Forgot your password ?</small></a>
                            </div>
                            <div class="col-sm-5 m-t-20">
                                <a href="{{ route('register') }}" class="text-muted"><i
                                        class="mdi mdi-account-circle"></i>
                                    <small>Create an account ?</small></a>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/modernizr.min.js"></script>
    <script src="assets/js/detect.js"></script>
    <script src="assets/js/fastclick.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/jquery.blockUI.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/js/jquery.nicescroll.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

</body>

</html>
