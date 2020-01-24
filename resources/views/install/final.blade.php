


<!DOCTYPE html>
<html>
@include('install.layouts.head')
  <body>
    <div class="master">
      <div class="box-install">
        <div class="header">
        <img src="{{ asset('dist/img/ZENLIX.png') }}">
            <p><h1 class="header__title">ZENLIX installation</h1></p>
        </div>
@include('install.layouts.steps')
        <div class="main">


<h4 style="margin-top: 2px;">
<center>
 Your system has been installed successfull!
</center>
</h4>


<p>
You can login with system account using
</p>


<pre>Login: admin@local
Password: p@ssw0rd
</pre>


    <div class="buttons">
        <a href="{{URL::to('/')}}" class="button">Go to Log In page</a>
    </div>
        </div>
      </div>
    </div>
  </body>
</html>