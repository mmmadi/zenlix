


<!DOCTYPE html>
<html>
@include('vendor.installer.layouts.head')
  <body>
    <div class="master">
      <div class="box">
        <div class="header">
        <img src="dist/img/ZENLIX.png">
            <h1 class="header__title">{{ trans('messages.welcome.title') }}</h1>
        </div>
@include('vendor.installer.layouts.steps')
        <div class="main">
        <p>
              <center>ZENLIX license</center>

<textarea class="textarea" name="envConfig">license</textarea>

              </p>


    <div class="buttons">
        <a href="{{ route('LaravelInstaller::environment') }}" class="button">I Agree with License</a>
    </div>
        </div>
      </div>
    </div>
  </body>
</html>