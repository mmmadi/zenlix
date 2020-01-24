@include("layout.header")

@include("layout.topmenu")
@include("layout.navbar")  



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     <i class="fa fa-search"></i> {{trans('search.title')}}
    <small>{{trans('search.subtitle')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">Р{{trans('search.subtitle')}}</li>
        
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-9">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->



            <div class="box">






                <div class="box-body">

@if (count($searchResults) > 0)     

@foreach ($searchResults as $searchResult)

<div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa  fa-file-text"></i>

              <h3 class="box-title">
<a href="{{$searchResult['url'] }}">
              {{$searchResult['title']}}
              </a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<p>{{$searchResult['description'] }}</p>
            </div>



            <!-- /.box-body -->
          </div>

@endforeach
@else

<p>{{trans('search.noResults')}}</p>

@endif


                </div>


                </div>














            
                    </div><!-- /.box -->



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



  });
</script>
</body>
</html>