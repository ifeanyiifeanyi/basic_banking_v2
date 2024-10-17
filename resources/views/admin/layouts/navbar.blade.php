<div class="topbar">

    <nav class="navbar-custom">

        <ul class="float-right mb-0 list-inline">
            <!-- language-->


            <li class="list-inline-item dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="dripicons-bell noti-icon"></i>
                    <span class="badge badge-success noti-icon-badge">2</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5><span class="float-right badge badge-danger">87</span>Notification</h5>
                    </div>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-primary"><i class="mdi mdi-cart-outline"></i></div>
                        <p class="notify-details"><b>Your order is placed</b><small class="text-muted">Dummy text of the
                                printing and typesetting industry.</small></p>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-success"><i class="mdi mdi-message"></i></div>
                        <p class="notify-details"><b>New Message received</b><small class="text-muted">You have 87
                                unread messages</small></p>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-warning"><i class="mdi mdi-glass-cocktail"></i></div>
                        <p class="notify-details"><b>Your item is shipped</b><small class="text-muted">It is a long
                                established fact that a reader will</small></p>
                    </a>

                    <!-- All-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item border-top">
                        View All
                    </a>

                </div>
            </li>

            <li class="list-inline-item dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown"
                    href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="{{ auth()->user()->photo }}" alt="user" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5>Welcome</h5>
                    </div>
                    <a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="mdi mdi-account-circle m-r-5 text-muted"></i>
                        Profile</a>
                    <a class="dropdown-item" href="{{ route('profile.2fa') }}"><i class="mdi mdi-wallet m-r-5 text-muted"></i> Set 2Factor Auth</a>

                    <a class="dropdown-item" href="{{ route('admin.update.password') }}">
                        <i class="mdi mdi-settings m-r-5 text-muted"></i> Settings
                    </a>

                    <a class="dropdown-item" href="{{ route('admin.activity-log') }}"><i class="mdi mdi-lock-open-outline m-r-5 text-muted"></i>
                        Activity Log</a>

                    <div class="dropdown-divider"></div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary w-100"><i class="text-white mdi mdi-logout m-r-5 text-muted"></i> Logout</button>
                    </form>
                </div>
            </li>
        </ul>

        <ul class="mb-0 list-inline menu-left">
            <li class="float-left">
                <button class="button-menu-mobile open-left waves-light waves-effect">
                    <i class="mdi mdi-menu"></i>
                </button>
            </li>
        </ul>

        <div class="clearfix"></div>
    </nav>
</div>
