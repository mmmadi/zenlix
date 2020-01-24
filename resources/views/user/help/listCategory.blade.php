@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     <i class="fa fa-graduation-cap"></i> {{trans('help.title')}}
    <small>{{trans('help.list')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('help.title')}}</li>
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



<div class="box box-solid">
            <div class="box-body">

<h3>{{$cat->name}}</h3>

            </div>
</div>



@if ($cat->help->count() == 0)

<div class="alert alert-info alert-dismissible">
                
                <h4><i class="icon fa fa-info"></i> {{trans('help.Empty')}}</h4>
                {{trans('help.EmptyCat')}}
              </div>

@else

@foreach ($helps as $help)

<div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa  fa-file-text"></i>

              <h3 class="box-title">
<a href="{{URL::to('/help/'.$help->slug)}}">
              {{$help->name}}
              </a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<p>{{$help->description}}</p>
            </div>


<div class="box-footer">
              {{trans('help.author')}} <a href="{{URL::to('/user/'.$help->author->profile->user_urlhash)}}">{{$help->author->name}}</a>
<span class="pull-right">{{LocalizedCarbon::instance($help->updated_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
            </div>


            <!-- /.box-body -->
          </div>

@endforeach

@endif





            {!! $helps->render(); !!}
                    </div><!-- /.box -->



<div class="col-md-4">

<a href="{{URL::to('/help/add')}}" class="btn btn-block bg-orange btn-sm">{{trans('help.addMaterial')}}</a>
<br>


            <div class="box">



                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-alt"></i> {{trans('help.cats')}}</h3>
                    <div class="box-tools">
                <a href="{{URL::to('/help/edit/category')}}" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="{{trans('help.catsEditor')}}">
                  <i class="fa fa-cog"></i></a></div>
                </div>





                <div class="box-body no-padding">




                                                  <ul class="cat-list sortable">
                                    @foreach ($categories as $cat)
                                      @if ($cat->parent_id == 0)
                                    <li id="list-{{$cat->id}}"><div>

                                    <span class="text">

                                    <a href="{{URL::to('/help/cat/'.$cat->id)}}">{{$cat->name}} </a>
                                    @if ($cat->help->count() > 0)
                                    <small>({{$cat->help->count()}})</small>
                                    @endif
                                    </span>


                                    </div>

                                    @include('user.help.categoryTreeList', array('cat', $cat))

                                    </li>
                                    @endif

                                    @endforeach
                                </ul>



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