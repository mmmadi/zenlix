@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('ag.groups')}}
    <small>{{trans('ag.groupLists')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('ag.groups')}}</li>
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
                    <h3 class="box-title"><i class="fa fa-list-alt"></i> {{trans('ag.groupLists')}}</h3>
                </div>





                <div class="box-body">






<table id="example1" class="table table-bordered">
                <thead>
                <tr>
                    <th><center>{{trans('ag.name')}} </center></th>
                    <th><center>{{trans('ag.dateCreate')}} </center></th>
                    <th><center>{{trans('ag.userTotal')}} </center></th>
                    <th><center>{{trans('ag.ticketSystem')}} </center></th>
                    <th class="no-sort"><center>{{trans('ag.action')}} </center></th>
                </tr>
                </thead>
                <tbody>
                

@foreach ($groups as $group)
<tr >
          <td style=" vertical-align: middle; "><small class="">{{$group->name}}</small></td>
          <td style=" vertical-align: middle; "><small class=""><center>{!! LocalizedCarbon::instance($group->created_at)->formatLocalized('%d %f %Y, %H:%M') !!}</center></small></td>
          <td style=" vertical-align: middle; "><small class=""><center>{{$group->GroupUser()->count()}}</center></small></td>
          <td style=" vertical-align: middle; "><small class=""><center>{{trans('ag.ticket')}}</center></small></td>
          <td style=" vertical-align: middle; ">


          {{-- <small class=""><center><a href="{{URL::to('/admin/group/edit/'.$group->id)}}">{{trans('ag.edit')}}</a>/<a href="#" id="del_el" data-id="{{$group->id}}">{{trans('ag.del')}}</a></center></small> --}}


<center>
<div class="btn-group">
                      <a class="btn btn-default btn-xs" href="{{URL::to('/admin/group/edit/'.$group->id)}}"><i class="fa fa-pencil-square-o"></i></a>
                      @if ($group->id != '1')
                      <a class="btn btn-default btn-xs" id="del_el" data-id="{{$group->id}}"><i class="fa fa-trash-o"></i></a>
                      @endif
                      
                    </div>
</center>



          </td>          
  </tr>                      
@endforeach
                    

  
                </tbody>

              </table>


                



            </div>




                    </div><!-- /.box-body -->
                    </div><!-- /.box -->



<div class="col-md-3">

<div class="box box-solid">
                
                <div class="box-body text-center">
<a href="{{URL::to('/admin/group/create')}}" class="btn btn-block bg-maroon btn-sm">{{trans('ag.createNewGroup')}}</a>
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


$('body').on('click', 'a#del_el', function(event) {
            event.preventDefault();

            var elID=$(this).attr('data-id');
            $.ajax({
                    type: "POST",
                    url: "{{URL::to('/admin/group/delete/') }}/"+elID,
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'DELETE'
                      
                    },
                    success: function(html) {
                      window.location="{{URL::to('/admin/groups')}}";
                    }
                  });
          });


    $("#example1").DataTable({

        "order": [[ 1, "desc" ]],
        "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
    } ],


      "language": {
                "url": "plugins/datatables/lang/Russian.json",
            },
                  "searching": false,
                  "paging": false,
                        "info": false,
          });

  });
</script>
</body>
</html>