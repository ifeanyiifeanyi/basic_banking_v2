<div class="leftside-menu">

    <!-- Brand Logo Light -->
    <a href="index.html" class="logo logo-light">
        <span class="logo-lg">
            <img src="/users/assets/images/logo.png" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="/users/assets/images/logo-sm.png" alt="small logo">
        </span>
    </a>

    <!-- Brand Logo Dark -->
    <a href="index.html" class="logo logo-dark">
        <span class="logo-lg">
            <img src="/users/assets/images/logo-dark.png" alt="dark logo">
        </span>
        <span class="logo-sm">
            <img src="/users/assets/images/logo-sm.png" alt="small logo">
        </span>
    </a>

    <!-- Sidebar -left -->
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <!--- Sidemenu -->
        <ul class="side-nav">


            <li class="side-nav-item">
                <a href="{{ route('member.dashboard')}}" class="side-nav-link">
                    <i class="ri-dashboard-3-line"></i>
                    <span> Dashboard </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('member.profile') }}" class="side-nav-link">
                    <i class="ri-account-pin-box-fill"></i>
                    <span> Profile </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('member.money_transfer.create') }}" class="side-nav-link">
                    <i class="ri-wallet-2-fill"></i>
                    <span> Money Transfer </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPages" aria-expanded="false"
                    aria-controls="sidebarPages" class="side-nav-link">
                    <i class="ri-pages-line"></i>
                    <span> Account Management </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarPages">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('member.account.index') }}">Accounts</a>
                        </li>
                        <li>
                            <a href="{{ route('member.account.create') }}">Create Account</a>
                        </li>
                        <li>
                            <a href="{{ route('member.account.report') }}">Account Report</a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="side-nav-title">Components</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarBaseUI" aria-expanded="false"
                    aria-controls="sidebarBaseUI" class="side-nav-link">
                    <i class="ri-briefcase-line"></i>
                    <span> Base UI </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarBaseUI">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="ui-accordions.html">Accordions</a>
                        </li>
                        <li>
                            <a href="ui-alerts.html">Alerts</a>
                        </li>
                        <li>
                            <a href="ui-avatars.html">Avatars</a>
                        </li>
                        <li>
                            <a href="ui-buttons.html">Buttons</a>
                        </li>
                        <li>
                            <a href="ui-badges.html">Badges</a>
                        </li>
                        <li>
                            <a href="ui-breadcrumb.html">Breadcrumb</a>
                        </li>
                        <li>
                            <a href="ui-cards.html">Cards</a>
                        </li>
                        <li>
                            <a href="ui-carousel.html">Carousel</a>
                        </li>
                        <li>
                            <a href="ui-collapse.html">Collapse</a>
                        </li>
                        <li>
                            <a href="ui-dropdowns.html">Dropdowns</a>
                        </li>
                        <li>
                            <a href="ui-embed-video.html">Embed Video</a>
                        </li>
                        <li>
                            <a href="ui-grid.html">Grid</a>
                        </li>
                        <li>
                            <a href="ui-links.html">Links</a>
                        </li>
                        <li>
                            <a href="ui-list-group.html">List Group</a>
                        </li>
                        <li>
                            <a href="ui-modals.html">Modals</a>
                        </li>
                        <li>
                            <a href="ui-notifications.html">Notifications</a>
                        </li>
                        <li>
                            <a href="ui-offcanvas.html">Offcanvas</a>
                        </li>
                        <li>
                            <a href="ui-placeholders.html">Placeholders</a>
                        </li>
                        <li>
                            <a href="ui-pagination.html">Pagination</a>
                        </li>
                        <li>
                            <a href="ui-popovers.html">Popovers</a>
                        </li>
                        <li>
                            <a href="ui-progress.html">Progress</a>
                        </li>
                        <li>
                            <a href="ui-spinners.html">Spinners</a>
                        </li>
                        <li>
                            <a href="ui-tabs.html">Tabs</a>
                        </li>
                        <li>
                            <a href="ui-tooltips.html">Tooltips</a>
                        </li>
                        <li>
                            <a href="ui-typography.html">Typography</a>
                        </li>
                        <li>
                            <a href="ui-utilities.html">Utilities</a>
                        </li>
                    </ul>
                </div>
            </li>



        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>
