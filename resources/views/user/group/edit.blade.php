@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  


<style type="text/css">
  

.users-list>li {
    width: 20%;
  }

</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{$group->name}}
    <small>{{trans('group.groupEditing')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li>{{trans('group.groups')}}</li>
        <li>{{$group->name}}</li>
        <li class="active">{{trans('group.editing')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-8">



                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                    </div> <!-- end .flash-message -->


            <div class="box">



                <div class="box-header">
                    <h3 class="box-title"> {{trans('group.groupEditing')}}</h3>
                </div>





                <div class="box-body">


{!! Form::model($group, array('action' => array('GroupsController@update', $group->group_urlhash), 'method'=> 'POST', 'class'=>'form-horizontal')) !!}


                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                    {!! Form::label('name', trans('group.name'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('name', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('description')) has-error @endif">
                    {!! Form::label('description', trans('group.desc'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('description', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
                    </div>
                    </div>

                    

                    <div class="form-group @if ($errors->has('description')) has-error @endif">
                    {!! Form::label('description_full', trans('group.descFull'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::textarea('description_full', null, array('class'=>'form-control', 'rows'=>'3')) !!}
                    @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('slogan')) has-error @endif">
                    {!! Form::label('slogan', trans('group.slogan'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('slogan', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('slogan')) <p class="help-block">{{ $errors->first('slogan') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('address')) has-error @endif">
                    {!! Form::label('address', trans('group.address'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('address', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('address')) <p class="help-block">{{ $errors->first('address') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('facebook')) has-error @endif">
                    {!! Form::label('facebook', trans('group.fb'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('facebook', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('facebook')) <p class="help-block">{{ $errors->first('facebook') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('twitter')) has-error @endif">
                    {!! Form::label('twitter', trans('group.twitter'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('twitter', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('twitter')) <p class="help-block">{{ $errors->first('twitter') }}</p> @endif
                    </div>
                    </div>




<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('group.save'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
</div>
</div>


{!! Form::close(); !!}



</div>
</div>




                </div>


<div class="col-md-4">








</div>


            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
<!-- page script -->
<script>
  $(function () {

  });
</script>
</body>
</html>