




<!DOCTYPE html>
<html>
@include('vendor.installer.layouts.head')
  <body>
    <div class="master">
      <div class="box">
        <div class="header">
        <img src="../dist/img/ZENLIX.png">
            <h1 class="header__title">{{ trans('messages.environment.title') }}</h1>
        </div>
@include('vendor.installer.layouts.steps')
        <div class="main">


@if (session('message'))
    <p class="alert">{{ session('message') }}</p>
    @endif
    <form method="post" action="{{ route('LaravelInstaller::environmentSave') }}">
        <textarea class="textarea" name="envConfig">{{ $envConfig }}</textarea>
        {!! csrf_field() !!}
        <div class="buttons buttons--right">
             <button class="button button--light" type="submit">{{ trans('messages.environment.save') }}</button>
        </div>
    </form>
    @if(!isset($environment['errors']))
    <div class="buttons">
        <a class="button" href="{{ route('LaravelInstaller::requirements') }}">
            {{ trans('messages.next') }}
        </a>
    </div>
    @endif

        </div>
      </div>
    </div>
  </body>
</html>