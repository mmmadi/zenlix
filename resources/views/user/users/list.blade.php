@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('userslist.title')}}
    <small>Список пользователей Ваших групп</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('userslist.title')}}</li>
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
                    <h3 class="box-title"><i class="fa fa-list-alt"></i> {{trans('userslist.subtitle')}}</h3>
                </div>





                <div class="box-body">






<ul class="products-list product-list-in-box">

@foreach ($users as $user)


                    <li class="item">
                      <div class="product-img">
                        <img src="{{Zen::showUserImgSmall($user->profile->user_img)}}" alt="Product Image">
                      </div>
                      <div class="product-info">
                        <a href="{{URL::to('/user/'.$user->profile->user_urlhash)}}" class="product-title">{{$user->name}} </a><span class="product-description pull-right">

                        



                        </span>
                        <span class="product-description">
                          {{$user->profile->position or Null}}
                        </span>
                      </div>
                    </li><!-- /.item -->

@endforeach

                                
                  </ul>


                {!! $users->render(); !!}



            </div>




                    </div><!-- /.box-body -->
                    </div><!-- /.box -->



<div class="col-md-4">



<div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">{{trans('userslist.title')}}</h3>
                </div><!-- /.box-header -->
                <div class="box-body">



{!! Form::open(array('action' => 'UsersController@showFind', 'method'=> 'POST', 'class'=>'form-horizontal')) !!}

<div class="form-group">
<div class="col-md-12">
{!! Form::text('name', Null, array('class'=>'form-control', 'style'=>'width: 100%', 'placeholder'=>trans('userslist.name'))) !!}
</div>
</div>

<div class="form-group">
<div class="col-md-12">
{!! Form::select('group', $groups, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('userslist.selGroup'))) !!}
</div>
</div>


<div class="col-md-12">
              <button type="submit" class="btn btn-block btn-success btn-flat">{{trans('userslist.find')}}</button>
</div>

{!! Form::close(); !!}






                </div>
                </div>

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