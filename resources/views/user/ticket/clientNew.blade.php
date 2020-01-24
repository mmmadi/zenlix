    <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-info">
                  <div class="widget-user-image">
                    <img class="img-circle" src="{{Zen::showUserImg(Null)}}" alt="User Avatar">
                  </div><!-- /.widget-user-image -->
                  <h3 class="widget-user-username">{!! $name !!}</h3>
                  <h5 class="widget-user-desc">{{trans('clientsPart.newUser')}}</h5>
                </div>
                <div class="box-footer form-horizontal">

                	<div class="form-group">
                    
                    <div class="col-sm-12">
                    {!! Form::text('clientNEW['.$name.'][email]', Null, array('class'=>'form-control', 'placeholder'=>'E-mail *')) !!}
                    
                    </div>
                    </div>

                    <div class="form-group">
                    
                    <div class="col-sm-12">
                    {!! Form::text('clientNEW['.$name.'][posada]', Null, array('class'=>'form-control', 'placeholder'=>trans('clientsPart.posada'))) !!}
                    
                    </div>
                    </div>

                    <div class="form-group">
                    
                    <div class="col-sm-12">
                    {!! Form::text('clientNEW['.$name.'][address]', Null, array('class'=>'form-control', 'placeholder'=>trans('clientsPart.address'))) !!}
                    
                    </div>
                    </div>


                </div>
    </div>