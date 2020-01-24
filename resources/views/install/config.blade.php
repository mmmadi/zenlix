<!DOCTYPE html>
<html>
@include('install.layouts.head')
  <body>
    <div class="master">
      <div class="box-install">
        <div class="header">
        <img src="{{ asset('dist/img/ZENLIX.png') }}">
            <p><h1 class="header__title">ZENLIX database configuration</h1></p>
        </div>
@include('install.layouts.steps')
        <div class="main">
        

{!! Form::open(array('action' => 'InstallController@storeConfig', 'method'=> 'PATCH', 'class'=>'form-horizontal')) !!}

                    <div class="form-group @if ($errors->has('dbtype')) has-error @endif">
                    
                    <div class="col-md-6 col-md-offset-3 ">
                    {!! Form::select('dbtype', ['mysql'=>'MySQL', 'pgsql'=>'PostgeSQL', 'sqlsrv'=>'sqlsrv'],Null, array('class'=>'form-control')) !!}
                    @if ($errors->has('dbtype')) <p class="help-block">{{ $errors->first('dbtype') }}</p> @endif
                    </div>
                    </div>



                    <div class="form-group @if ($errors->has('dbhost')) has-error @endif">
                    
                    <div class="col-md-6 col-md-offset-3 ">
                    {!! Form::text('dbhost', null, array('class'=>'form-control', 'placeholder'=>'DB Host')) !!}
                    @if ($errors->has('dbhost')) <p class="help-block">{{ $errors->first('dbhost') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('dbname')) has-error @endif">
                    
                    <div class="col-md-6 col-md-offset-3 ">
                    {!! Form::text('dbname', null, array('class'=>'form-control', 'placeholder'=>'DB name')) !!}
                    @if ($errors->has('dbname')) <p class="help-block">{{ $errors->first('dbname') }}</p> @endif
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('dblogin')) has-error @endif">
                    
                    <div class="col-md-6 col-md-offset-3 ">
                    {!! Form::text('dblogin', null, array('class'=>'form-control', 'placeholder'=>'Login')) !!}
                    @if ($errors->has('dblogin')) <p class="help-block">{{ $errors->first('dblogin') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('dbpass')) has-error @endif">
                    
                    <div class="col-md-6 col-md-offset-3 ">
                    {!! Form::password('dbpass', array('class'=>'form-control', 'placeholder'=>'Password')) !!}
                    @if ($errors->has('dbpass')) <p class="help-block">{{ $errors->first('dbpass') }}</p> @endif
                    </div>
                    </div>

    <div class="buttons">
        <button name="Next" value="Next" type="submit" class="btn button">Save Config</button>
    </div>


{!! Form::close(); !!}






        </div>
      </div>
    </div>
  </body>
</html>