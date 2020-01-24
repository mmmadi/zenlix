@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     <i class="fa fa-graduation-cap"></i> {{trans('help.catsEditing')}}
    <small>{{trans('help.cats')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li>{{trans('help.title')}}</li>
        <li class="active">{{trans('help.catsEditing')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-10">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->



            <div class="box">



                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-alt"></i> {{trans('help.catsEditing')}}</h3>
                </div>





                <div class="box-body">
     


{!! Form::model($category, array('action' => array('HelpCenterController@updateCategory', $category->id), 'method'=> 'POST', 'class'=>'form-horizontal')) !!}

                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                    {!! Form::label('name', trans('help.nameofCat'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('name', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                    </div>
<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('help.save'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
</div>
</div>


{!! Form::close(); !!}

                  



                </div>
                </div>














            
                    </div><!-- /.box -->



<div class="col-md-2">





</div>


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