@include("layout.header")

  {!! Html::style('plugins/iCheck/square/blue.css'); !!}

@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('at.editingSubjs')}}
    <small>{{trans('at.changeSubjs')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li><a href="{!! URL::to('/admin/ticket/subj'); !!}">{{trans('at.ticketSubjs')}}</a></li>
        <li class="active">{{trans('at.editingSubjs')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-9">
            <div class="box">



                <div class="box-header">
                    <h3 class="box-title"> {{trans('at.editingSubjs2')}}</h3>
                </div>





                <div class="box-body">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->


{!! Form::model($subj, array('action' => array('ConfigTicketController@updateSubj', $subj->id), 'method'=> 'POST', 'class'=>'form-horizontal')) !!}

                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                    {!! Form::label('name', trans('at.nameOfSubj'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('name', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                    </div>
<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('at.save'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
</div>
</div>
{!! Form::close(); !!}

                



            </div>




                    </div><!-- /.box-body -->






























                    </div><!-- /.box -->



<div class="col-md-3">



</div>


                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
<!-- iCheck -->
{!! Html::script('plugins/iCheck/icheck.min.js'); !!}
  <!-- bootstrap-tagsinput -->
  {!! Html::script('plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js'); !!}
<!-- page script -->
<script>
  $(function () {

        $('.tags').tagsinput();


        $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>