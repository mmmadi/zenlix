<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ZENLIX | Forgot Password</title>
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
<br><br>
                <p>
                    <small>{{trans('authPage.footerMsg')}}</small>
                </p>

            </div>
<div class="col-sm-5">

 <div class="login-logo " style="    padding: 20px; margin: 0px;">
    <img src="dist/img/ZENLIX.png">
  </div> 
  <!-- /.login-logo -->
  <div class="login-box-body" style="">
    <p class="login-box-msg">{{trans('authPage.forgotPass')}}</p>
{!! Form::open(array('url' => 'forgot', 'method'=> 'POST', 'autocomplete'=>'off')) !!}



@if (session('status'))
                    <div class="flash-message">
<p class="alert alert-success">{{ session('status') }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                    </div> <!-- end .flash-message -->
@else

                    <div class="form-group has-feedback @if ($errors->has('email')) has-error @endif">
                    {!! Form::text('email', '', array('class'=>'form-control input-lg', 'autocorrect'=>'off', 'autocapitalize'=>'off', 'autocomplete'=>'off', 'placeholder'=>trans('authPage.email'))) !!}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                   @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                    </div>


      <div class="row">

        <!-- /.col -->
        <div class="col-xs-12">

            {!! Form::button(trans('authPage.doForgotPass'), array('type' => 'submit', 'class'=>'btn btn-success btn-block btn-flat')); !!}

        </div>
        <!-- /.col -->
      </div>
      @endif
    {!! Form::close() !!}
<br>
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
