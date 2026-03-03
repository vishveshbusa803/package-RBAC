<!-- ========== Left Sidebar Start ========== -->
<style>
    body[data-sidebar-size=sm] .vertical-menu {
        position: fixed !important;
    }
</style>

<div class="vertical-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{url('index')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/logo-dark.png') }}" alt="" height="20">
            </span>
        </a>

        <a href="{{url('index')}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/logo-light.png') }}" alt="" height="20">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">@lang('translation.Menu')</li>

                @can('dashboard-view')
                <li>
                    <a href="{{url('index')}}">
                        <i class="uil-home-alt"></i><span class="badge rounded-pill bg-primary float-end">01</span>
                        <span>@lang('translation.Dashboard')</span>
                    </a>
                </li>
                @endcan


                <li>
                    <a href="{{ route('auth.settings') }}" class="waves-effect">
                        <i class="uil uil-setting"></i> <!-- Settings icon for Auth Setting -->
                        <span>Auth Setting</span>
                    </a>
                </li>

                @can('role-view')
                <li class="menu-title">Access Control</li>
                @endcan

                @can('role-view')
                <li>
                    <a href="{{ route('roles.index') }}" class="waves-effect">
                        <i class="uil uil-sitemap"></i>
                        <span>Roles</span>
                    </a>
                </li>
                @endcan

                @can('permission-view')
                <li>
                    <a href="{{ route('permissions.index') }}" class="waves-effect">
                        <i class="uil uil-key-skeleton"></i>
                        <span>Permissions</span>
                    </a>
                </li>
                @endcan




            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
