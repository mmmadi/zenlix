<!DOCTYPE html>
<html>
@include('install.layouts.head')
  <body>
    <div class="master">
      <div class="box-install">
        <div class="header">
        <img src="{{ asset('dist/img/ZENLIX.png') }}">
            <p><h1 class="header__title">ZENLIX install</h1></p>
        </div>
@include('install.layouts.steps')
        <div class="main">
        
<h3><center>
Your system is prepare to install...</center></h3>


{!! Form::open(array('action' => 'InstallController@storePreInstall', 'method'=> 'PATCH', 'class'=>'form-horizontal')) !!}


    <div class="buttons">
        <button name="Next" value="Next" type="submit" class="btn button">Start install!</button>
    </div>


{!! Form::close(); !!}






        </div>
      </div>
    </div>
  </body>
</html>