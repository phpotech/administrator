<!-- header logo: style can be found in header.less -->
<header class="main-header">
    <a href="/{{ $mainConfig->get('prefix') }}" class="logo">
        <!-- Add the class icon to your logo image or logo icon to add the margining -->
        <span class="logo-mini">{!! config('administrator.title_mini', 'AP') !!}</span>
        <span class="logo-lg">{!! config('administrator.title', 'Administration panel') !!}</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                {{--@include('administrator::partials.badges')--}}

                <li>
                    <a class="dropdown-toggle active" href="{{ route('admin_model_index', ['page' => 'languages']) }}">
                        <i class="fa fa-envelope"></i>
                        Languages
                    </a>
                </li>

                @include('administrator::partials.settings')

                @include('administrator::partials.user')
                {{--todo: implement header pages ..--}}
            </ul>
        </div>
    </nav>
</header>