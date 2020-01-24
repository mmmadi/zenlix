


<!DOCTYPE html>
<html>
@include('install.layouts.head')
  <body>
    <div class="master">
      <div class="box-install">
        <div class="header">
        <img class="" src="{{ asset('dist/img/ZENLIX.png') }}">
            <p><h1 class="header__title">Welcome to ZENLIX installation!</h1></p>
        </div>
@include('install.layouts.steps')
        <div class="main">
        
              <center>Please, read ZENLIX License</center>

<textarea class="textarea" name="envConfig">{{$license}}</textarea>

              


    <div class="buttons">
        <a href="{{URL::to('install/permissions')}}" class="btn button">I Agree with License</a>
    </div>
        </div>
      </div>
    </div>
  </body>
</html>