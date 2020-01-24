<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ZENLIX LICENSE CONTROL</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  {!! Html::style('bootstrap/css/bootstrap.min.css'); !!}
  <!-- Font Awesome -->
  {!! Html::style('plugins/font-awesome/css/font-awesome.min.css'); !!}
  <!-- Theme style -->
  {!! Html::style('dist/css/AdminLTE.min.css'); !!}

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>


<body class="hold-transition lockscreen">

<!-- Automatic element centering -->
<div class="lockscreen-wrapper" style="margin-top: 5%;">
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->
  <div class="lockscreen-logo">
   <div class="login-logo " style="    padding: 10px; margin: 0px;">
    <img src="{{ asset('dist/img/ZENLIX.png') }}">
  </div> 
    <b>LICENSE CONTROL</b>



  </div>
  <!-- User name -->
  <div class="lockscreen-name">Please enter license code</div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">

    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->

{!! Form::open(array('url' => 'license/add', 'method'=> 'POST', 'autocomplete'=>'off', 'class'=>'lockscreen-credentials', 'style'=>'margin-left: 0px;')) !!}

      <div class="input-group @if ($errors->has('licenseCode')) has-error @endif">
        <textarea name="licenseCode" rows="5" class="form-control" placeholder="Insert License Code here" style="resize: none;"></textarea>

        <div class="input-group-btn">
          <button type="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
        </div>
        
      </div>
@if ($errors->has('licenseCode')) <p class="help-block">{{ $errors->first('licenseCode') }}</p> @endif
 {!! Form::close() !!}

    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  <div class="help-block text-center">
    You can receive new license code from <a href="https://support.zenlix.com/">ZENLIX.Accounts</a>.
  </div>
  <div class="text-center">
    Or manually run license updater, via SSH command: <pre>php artisan zenlix:license</pre>
  </div>
  <div class="lockscreen-footer text-center">
    
  </div>
</div>
<!-- /.center -->

<!-- jQuery 2.1.4 -->
{!! Html::script('plugins/jQuery/jQuery-2.1.4.min.js'); !!}
<!-- Bootstrap 3.3.5 -->
{!! Html::script('bootstrap/js/bootstrap.min.js'); !!}
</body>
</html>