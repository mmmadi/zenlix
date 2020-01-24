<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">



  <!-- Bootstrap 3.3.5 -->
  {!! Html::style('bootstrap/css/bootstrap.min.css?v='.config('app.zenlix_version')); !!}
  <!-- Font Awesome -->
  {!! Html::style('plugins/font-awesome/css/font-awesome.min.css?v='.config('app.zenlix_version')); !!}
  <!-- Ionicons -->
  {!! Html::style('plugins/ionicons/css/ionicons.min.css?v='.config('app.zenlix_version')); !!}

  <!-- Select2 -->
  {!! Html::style('plugins/select2/select2.min.css?v='.config('app.zenlix_version')); !!}

 {!! Html::style('plugins/toastr/toastr.min.css?v='.config('app.zenlix_version')); !!}

  <!-- dataTables -->

  
  {!! Html::style('plugins/datatables/media/css/dataTables.bootstrap.min.css?v='.config('app.zenlix_version')); !!}


  
 
  <!-- Trumbowyg -->
  {!! Html::style('plugins/trumbowyg/ui/trumbowyg.min.css?v='.config('app.zenlix_version')); !!}

  <!-- Theme style -->
  {!! Html::style('dist/css/AdminLTE.min.css?v='.config('app.zenlix_version')); !!}
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  {!! Html::style('dist/css/skins/_all-skins.min.css?v='.config('app.zenlix_version')); !!}
 {!! Html::style('plugins/select2/select2-bootstrap.css?v='.config('app.zenlix_version')); !!}

 {!! Html::style('plugins/pace/pace.min.css?v='.config('app.zenlix_version')); !!}

 
 <style type="text/css">
  
.navbar-nav>.notifications-menu>.dropdown-menu>li .menu>li>a {
padding: 7px;
}
.navbar-nav>.notifications-menu>.dropdown-menu>li .menu>li>a>.glyphicon, .navbar-nav>.notifications-menu>.dropdown-menu>li .menu>li>a>.fa, .navbar-nav>.notifications-menu>.dropdown-menu>li .menu>li>a>.ion {

    width: 15px;

  }

</style>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<title>{{ $PageTittle }}</title>