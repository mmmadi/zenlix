@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('group.groups')}}
    <small>{{trans('group.listYourGroups')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('group.groups')}}</li>
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



                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-alt"></i> {{trans('group.listYourGroups')}}</h3>
                </div>





                <div class="box-body">







@foreach ($groups as $group)
  <div class="box box-widget widget-user" >
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header" style="    height: 70px;     padding: 10px;
                background-color: #F59B4D;
                ">
                  <h3 class="widget-user-username" style="
    color: ;
">
                  <i class="fa {!! $group->icon; !!}"></i> 
                  <a href="{{URL::to('/group/'.$group->group_urlhash)}}" style="color: inherit;">{!! $group->name !!}</a></h3>
                  <h5 class="widget-user-desc" style="
    color: ;
">{!! $group->description !!}</h5>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-4 border-right">
                      <div class="description-block">
                        
                        <span class="description-text">{{$group->GroupUser()->count()}} {{trans('group.members')}}</span>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                    <div class="col-sm-4 border-right">
                      <div class="description-block">
                        
                        <span class="description-text"> {{trans('group.messages')}}</span>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                    <div class="col-sm-4">
                      <div class="description-block">
                      
                        <span class="description-text"> {{trans('group.tickets')}}</span>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div>
              </div>
@endforeach



{!! $groups->render(); !!}



                



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
<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "language": {
                "url": "plugins/datatables/lang/Russian.json",
            },
                  "searching": false,
                  "paging": false,
                        "info": false,
          });
    $('#example2').DataTable({
      "paging": true,
      "language": {
                "url": "plugins/datatables/lang/Russian.json"
            },

      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
</body>
</html>