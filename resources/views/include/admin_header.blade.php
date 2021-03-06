<!-- Logo -->
<a href="dashboard.html" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>M</b>CC</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>MCC</b> E Payment</span>
</a>
<!-- Header Navbar -->
<nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <!-- The user image in the navbar-->
                    <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                    <span class="hidden-xs">{{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}</span>
                </a>
                <ul class="dropdown-menu">
                    <!-- Menu Body -->
                    <!-- Menu Footer-->
                    <li class="user-footer"> 
                        <div class="pull-left">
                            <a href="{{ url('admin/changepassword') }}" class="btn btn-default btn-flat">Change Password</a>
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                              <!--  {{ csrf_field() }} -->
                            </form>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
