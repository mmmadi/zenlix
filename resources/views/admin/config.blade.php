@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{trans('ac.systemConfig')}}
            <small>
                {{trans('ac.mainConfig')}}
            </small>
        </h1>
        <ol class="breadcrumb">
            @include("layout.breadcrumb")
            <li class="active">
                {{trans('ac.systemConfig')}}
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-9">
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                    <p class="alert alert-{{ $msg }}">
                        {{ Session::get('alert-' . $msg) }}
                        <button aria-hidden="true" class="close" data-dismiss="alert" type="button">
                            ×
                        </button>
                    </p>
                    @endif
                        @endforeach
                </div>
                <!-- end .flash-message -->
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">
                            <i class="fa fa-list-alt">
                            </i>
                            {{trans('ac.systemConfig')}}
                        </h3>
                    </div>
                    <div class="box-body">
                        {!! Form::open(array('action' => 'ConfigSystemController@update', 'method'=> 'POST','files'=> true, 'class'=>'form-horizontal')) !!}
                        <div class="form-group @if ($errors->has('sitename')) has-error @endif">
                            {!! Form::label('sitename', trans('ac.name'), array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-4">
                                {!! Form::text('sitename', Setting::get('sitename'), array('class'=>'form-control')) !!}
                    @if ($errors->has('sitename'))
                                <p class="help-block">
                                    {{ $errors->first('sitename') }}
                                </p>
                                @endif
                            </div>
                            <div class="col-sm-1 control-label">
                                {{trans('ac.short')}}
                            </div>
                            <div class="col-sm-4">
                                {!! Form::text('sitenameShort', Setting::get('sitenameShort'), array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group @if ($errors->has('slogan')) has-error @endif">
                            {!! Form::label('slogan', trans('ac.slogan'), array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::text('slogan', Setting::get('slogan'), array('class'=>'form-control')) !!}
                    @if ($errors->has('slogan'))
                                <p class="help-block">
                                    {{ $errors->first('slogan') }}
                                </p>
                                @endif
                            </div>
                        </div>
                        <div class="form-group @if ($errors->has('sitelogo')) has-error @endif">
                            {!! Form::label('sitelogo', trans('ac.logotype'), array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-2">
                                <img alt="4" class="img-responsive" src="{{$SiteLogo}}" style=" max-height: 50px; ">
                                </img>
                            </div>
                            <div class="col-sm-5">
                                {!! Form::file('sitelogo', null, array('class'=>'form-control')) !!}
                                        @if ($errors->has('sitelogo'))
                                <p class="help-block">
                                    {{ $errors->first('sitelogo') }}
                                </p>
                                @endif
                            </div>
                            <div class="col-sm-2">
                                <a href="#" id="destroyLogo">
                                    delete
                                </a>
                            </div>
                        </div>
                        <div class="form-group @if ($errors->has('locale')) has-error @endif">
                            {!! Form::label('locale', trans('profile.lang'), array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('locale', array('en'=>'English','ru'=>'Русский', 'uk'=>'Українська'), $locale, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%'))
!!}
@if ($errors->has('locale'))
                                <p class="help-block">
                                    {{ $errors->first('locale') }}
                                </p>
                                @endif
                            </div>
                        </div>
                        <div class="form-group @if ($errors->has('siteURL')) has-error @endif">
                            {!! Form::label('siteURL', trans('ac.url'), array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::text('siteURL', config('app.url'), array('class'=>'form-control')) !!}
                    @if ($errors->has('siteURL'))
                                <p class="help-block">
                                    {{ $errors->first('siteURL') }}
                                </p>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('SSLMode', trans('ac.ssl'), array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('SSLMode', ['true'=>trans('ac.yes'), 'false'=>trans('ac.no')], $ssl, array('class'=>'form-control select2')) !!}
                            </div>
                        </div>
                        <div class="form-group @if ($errors->has('timezone')) has-error @endif">
                            {!! Form::label('timezone', trans('ac.timezone'), array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::text('timezone', config('app.timezone'), array('class'=>'form-control')) !!}
                    @if ($errors->has('timezone'))
                                <p class="help-block">
                                    {{ $errors->first('timezone') }}
                                </p>
                                @endif
                            </div>
                        </div>
                        {{--
                        <div class="form-group">
                            {!! Form::label('langDef', 'Язык по умолчанию', array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('langDef', ['en'=>'English', 'ru'=>'Русский'], [config('app.locale')], array('class'=>'form-control select2')) !!}
                            </div>
                        </div>
                        --}}
                        <div class="form-group">
                            {!! Form::label('DebugMode', trans('ac.debug'), array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('DebugMode', ['true'=>trans('ac.yes'), 'false'=>trans('ac.no')], $cd, array('class'=>'form-control select2')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('APIStatus', trans('ac.apiSystem'), array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('APIStatus', ['true'=>trans('ac.yes'), 'false'=>trans('ac.no')], Setting::get('apiStatus', 'false'), array('class'=>'form-control select2')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-8">
                                {!! HTML::decode(Form::button(trans('ac.save'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
                            </div>
                        </div>
                        {!! Form::close(); !!}
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            <div class="col-md-3">
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
@include("layout.footer")
<!-- page script -->
<script>
    $(function () {

//destroyLogo
$('body').on('click', 'a#destroyLogo', function(event) {
            event.preventDefault();

            
            $.ajax({
                    type: "POST",
                    url: "{{URL::to('/admin/config') }}",
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'DELETE'
                      
                    },
                    success: function(html) {
                      window.location="{{URL::to('/admin/config')}}";
                    }
                  });
          });



    $(".select2").select2({
        allowClear: false
    });


  });
</script>
