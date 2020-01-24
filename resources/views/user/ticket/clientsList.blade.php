@foreach ($existClient as $id)

	
<div class="box box-widget widget-user-2">
                <div class="widget-user-header bg-info">
                  <div class="widget-user-image">
                    <img class="img-circle" src="{{Zen::showUserImg(zenlix\User::find($id)->profile->user_img)}}" alt="User Avatar">
                  </div><!-- /.widget-user-image -->
                  <h3 class="widget-user-username">
                  <a href="{{URL::to('/user/'.zenlix\User::find($id)->profile->user_urlhash)}}">
                  	{!! zenlix\User::find($id)->name !!}
                  	</a>
                  	</h3>
                  <h5 class="widget-user-desc">{!! zenlix\User::find($id)->profile->position !!}</h5>
                </div>
                </div>
@endforeach

	

@foreach ($newClient as $id)



    <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-info">
                  <div class="widget-user-image">
                    <img class="img-circle" src="{{Zen::showUserImg(Null)}}" alt="User Avatar">
                  </div><!-- /.widget-user-image -->
                  <h3 class="widget-user-username">{!! $id !!}</h3>
                  <h5 class="widget-user-desc">{{trans('clientsPart.newUser')}}</h5>
                </div>
                <div class="box-footer form-horizontal">

                	<div class="form-group @if ($errors->has('clientNEW['.$id.'][email]')) has-error @endif">
                    
                    <div class="col-sm-12">
                    {!! Form::text('clientNEW['.$id.'][email]', Null, array('class'=>'form-control', 'placeholder'=>'E-mail')) !!}
                    @if ($errors->has('clientNEW['.$id.'][email]')) <p class="help-block">{{ $errors->first('clientNEW['.$id.'][email]') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group">
                    
                    <div class="col-sm-12">
                    {!! Form::text('clientNEW['.$id.'][posada]', Null, array('class'=>'form-control', 'placeholder'=>trans('clientsPart.posada'))) !!}
                    
                    </div>
                    </div>

                    <div class="form-group">
                    
                    <div class="col-sm-12">
                    {!! Form::text('clientNEW['.$id.'][address]', Null, array('class'=>'form-control', 'placeholder'=>trans('clientsPart.address'))) !!}
                    
                    </div>
                    </div>


                </div>
    </div>


@endforeach

