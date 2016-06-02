<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Log in</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- bootstrap 3.0.2 -->
    <link href="<?= asset($assets . '/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="<?= asset($assets . '/css/AdminLTE.min.css') ?>" rel="stylesheet" type="text/css"/>
    <!-- iCheck -->
    <link href="<?= asset($assets . '/plugins/iCheck/square/blue.css') ?>" rel="stylesheet" type="text/css"/>
</head>
<body class="hold-transition login-page">
@include('administrator::partials.messages')
<div class="login-box">
    <div class="login-logo">
        <a href="/"><i class="fa fa-home"></i></a>&#160;<span>{!! config('administrator.title') !!}</span>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        {!! Form::open() !!}

        <div class="input-group has-feedback">
            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
            {!! Form::text($identity, null, ['class' => 'form-control', 'placeholder' => ucfirst($identity)]) !!}
        </div>

        <div class="input-group has-feedback">
            <span class="input-group-addon"><i class="fa fa-key"></i></span>
            {!! Form::password($credential, ['class' => 'form-control', 'placeholder' => ucfirst($credential)]) !!}
        </div>

        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                        <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false"
                             style="position: relative;"><input type="checkbox"
                                                                style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);">
                            <ins class="iCheck-helper"
                                 style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                        </div>
                        Remember Me
                    </label>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div>
            <!-- /.col -->
        </div>
        {!! Form::close() !!}

        <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in
                using
                Facebook</a>
            <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in
                using
                Google+</a>
        </div>
        <!-- /.social-auth-links -->

        <a href="#">I forgot my password</a><br>
        <a href="register.html" class="text-center">Register a new membership</a>

    </div>
    <!-- /.login-box-body -->
</div>

<script src="<?= asset($assets . '/plugins/jQuery/jQuery-2.2.0.min.js') ?>"></script>

<script src="<?= asset($assets . '/js/bootstrap.min.js') ?>"></script>

<script src="<?= asset($assets . '/plugins/iCheck/icheck.min.js') ?>"></script>
</body>
</html>