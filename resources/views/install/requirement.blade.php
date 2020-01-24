


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


<p>Checking requirements ... </p>


        <ul class="list">
    @foreach($requirements['requirements'] as $extention => $enabled)
    <li class="list__item @if($enabled) success @else error @endif">{{ $extention }}</li>
    @endforeach
</ul>

@if(!isset($requirements['errors']))
    <div class="buttons">
        <a class="button" href="{{ URL::to('/install/config') }}">
        Next
        </a>
    </div>
@endif

        </div>
      </div>
    </div>
  </body>
</html>