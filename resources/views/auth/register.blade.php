<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ZENLIX | Register user</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  {!! Html::style('bootstrap/css/bootstrap.min.css'); !!}
  <!-- Font Awesome -->
  {!! Html::style('plugins/font-awesome/css/font-awesome.min.css'); !!}
  <!-- Ionicons -->
  {!! Html::style('plugins/ionicons/css/ionicons.min.css'); !!}
  <!-- Theme style -->
  {!! Html::style('dist/css/AdminLTE.min.css'); !!}
  <!-- iCheck -->
  {!! Html::style('plugins/iCheck/square/blue.css'); !!}
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page" style="background: url({!! asset('dist/img/bg3_8.png') !!}) no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;">
<div class="login-box header" style="background-color:white;     border-radius: 10px;">
<div class="row">
<div class="col-sm-7" style="color:#6D6D6D;">




                <h2 class="font-bold">{{trans('authPage.welcomeMsg')}}</h2>
<hr>
                {!!trans('authPage.msg')!!}
<br><br><br><br>
                <p>
                    <small>{{trans('authPage.footerMsg')}}</small>
                </p>

            </div>
<div class="col-sm-5">

 <div class="login-logo " style="    padding: 10px; margin: 0px;">
    <img src="dist/img/ZENLIX.png">
  </div> 
  <!-- /.login-logo -->
  <div class="login-box-body" style="">
    <p class="login-box-msg">{{trans('authPage.fillformReg')}}</p>
{!! Form::open(array('url' => 'register', 'method'=> 'POST')) !!}
    
<div class="form-group has-feedback @if ($errors->has('name')) has-error @endif">
                    {!! Form::text('name', '', array('class'=>'form-control', 'placeholder'=>trans('authPage.name'))); !!}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

                    <div class="form-group has-feedback @if ($errors->has('email')) has-error @endif">
                    {!! Form::text('email', '', array('class'=>'form-control', 'placeholder'=>trans('authPage.email'))); !!}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                    </div>

                    <div class="form-group has-feedback @if ($errors->has('password')) has-error @endif">
                    {!! Form::password('password', array('class'=>'form-control', 'placeholder'=>trans('authPage.password'))); !!}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
                    </div>

                    <div class="form-group has-feedback @if ($errors->has('password_confirmation')) has-error @endif">
                    {!! Form::password('password_confirmation', array('class'=>'form-control', 'placeholder'=>trans('authPage.passConfirm'))); !!}
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
                    </div>

      <div class="row">
        <div class="col-xs-8">

                    <div class="form-group @if ($errors->has('agree')) has-error @endif">
                       
                        <div class="checkbox i-checks"><label> {!! Form::checkbox('agree'); !!}<i></i> {{trans('authPage.agree')}} </label></div>
                        @if ($errors->has('agree')) <p class="help-block">{{ $errors->first('agree') }}</p> @endif
                        </div>




        </div>
        <!-- /.col -->
        <div class="col-xs-4">

        {!! HTML::decode(Form::button(trans('authPage.doRegister'), array('type' => 'submit', 'class'=>'btn btn-primary btn-block btn-flat'))) !!}

        </div>
        <!-- /.col -->
      </div>
    {!! Form::close(); !!}

<!--     <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div> -->
    <!-- /.social-auth-links -->
<center>
@if (Setting::get('RecoveryPasswords') == 'true' )
    <a href="{!! URL::to('/forgot') !!}">{{trans('authPage.lostPass')}}</a><br>
@endif


@if (Setting::get('AuthUsers') == 'true' )
    <a href="{!! URL::to('/register') !!}" class="text-center">{{trans('authPage.reg')}}</a>
@endif
</center>
  </div></div>
</div>

  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.1.4 -->
{!! Html::script('plugins/jQuery/jQuery-2.1.4.min.js'); !!}
<!-- Bootstrap 3.3.5 -->
{!! Html::script('bootstrap/js/bootstrap.min.js'); !!}

<!-- iCheck -->
{!! Html::script('plugins/iCheck/icheck.min.js'); !!}
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
