<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ config('administrator.title')  }}</title>

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    {{--todo: build gulp:sass stylesheets instead code below ..--}}
    <!-- bootstrap 3.3.6 -->
    <link href="<?= asset($assets . '/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 -->
    <link href="<?= asset($assets . '/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- font Awesome -->
    {{--<link href="<?= asset($assets . '/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css" />--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    {{--<link href="<?= asset($assets . '/css/ionicons.min.css') ?>" rel="stylesheet" type="text/css" />--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link href="<?= asset($assets . '/dist/css/AdminLTE.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Theme Skins -->
    {{--<link href="<?= asset($assets . '/lte2/dist/css/skins/_all-skins.min.css') ?>" rel="stylesheet" type="text/css">--}}
    <link href="<?= asset($assets . '/dist/css/skins/skin-purple.min.css') ?>" rel="stylesheet" type="text/css">

    <link href="<?= asset($assets . '/plugins/datepicker/datepicker3.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset($assets . '/plugins/daterangepicker/daterangepicker-bs3.css') ?>" rel="stylesheet" type="text/css" />

    @yield('css')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <!--<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>-->
    {{--<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>--}}
    <![endif]-->

    @yield('headjs')
</head>
<body class="fixed skin-purple" data-spy="scroll" data-target="#scrollspy">
<div class="wrapper">
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
                @include('administrator::partials.badges')

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
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
            @include('administrator::partials/navigation')
        </div>
        <!-- /.sidebar -->
    </aside>

    <div class="content-wrapper" style="min-height: 209px">
        <div class="content-header">
            <h1>
                <span style="color: #605ca8" class="{{ isset($navigation->getCurrentPage()['icon']) ? $navigation->getCurrentPage()['icon'] : 'fa fa-hashtag' }}"></span>&nbsp;{{ $title }}
                @if(isset($description))
                    <small>{!! $description !!}</small>
                @endif
            </h1>
                {!! $breadcrumbs !!}
        </div>

        @include('administrator::partials/messages')

        <div class="content body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-solid" style="border-radius: 0;">
                        {{--todo: add some widget here for current eloquent --}}
                        <div class="box-header">
                            <h3 class="box-title">###implement some widgets here ..</h3>
                        </div><!-- /.box-header -->

                        @yield('filter')

                        <div class="box-body table-responsive">
                            @yield('content')
                        </div>
                    </div><!-- /.box -->
                </div>
            </div>
        </div>
    </div>

    @include('administrator::partials/footer')
</div><!-- ./wrapper -->

<!-- jQuery 2.0.2 -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<!-- slimScroll -->
<script src="<?= asset($assets . '/plugins/slimScroll/jquery.slimscroll.min.js') ?>" type="text/javascript"></script>
<!-- Bootstrap -->
<script src="<?= asset($assets . '/bootstrap/js/bootstrap.min.js') ?>" type="text/javascript"></script>
<!-- Bootstrap WysiHtml5 -->
<script src="<?= asset($assets . '/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') ?>" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="<?= asset($assets . '/dist/js/app.min.js') ?>" type="text/javascript"></script>
<!-- Admin Main Js -->
<script src="<?= asset($assets . '/js/main.js') ?>" type="text/javascript"></script>

<script src="<?= asset($assets . '/plugins/datepicker/bootstrap-datepicker.js') ?>" type="text/javascript"></script>
<script src="<?= asset($assets . '/plugins/daterangepicker/daterangepicker.js') ?>" type="text/javascript"></script>

@yield('js')

</body>
</html>