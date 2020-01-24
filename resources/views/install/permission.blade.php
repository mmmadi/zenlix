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
        
<p>Checking directory permissions </p>


<ul class="list">
    @foreach($permissions['permissions'] as $permission)
    <li class="list__item list__item--permissions @if($permission['isSet']) success @else error @endif">
        {{ $permission['folder'] }}<span>{{ $permission['permission'] }}</span>
        </li>
    @endforeach
</ul>





@if(!isset($permissions['errors']))
<div class="buttons">
    <a class="button" href="{{ URL::to('/install/requirements') }}">
        Next
    </a>
</div>
@endif
        </div>
      </div>
    </div>
  </body>
</html>