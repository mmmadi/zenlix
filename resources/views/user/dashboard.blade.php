@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
    <i class="fa fa-tachometer"></i> {{trans('dashboard.title')}}
    <small>{{trans('dashboard.subtittle')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('dashboard.title')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">


<div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box bg-maroon">
                <span class="info-box-icon"><i class="fa fa-tag"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">{{trans('dashboard.tickets')}}</span>
                  <span class="info-box-number">{{$ticketsCountAll}}</span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                  </div>
                  <span class="progress-description">
                    {{$ticketsCountOk}} {{trans('dashboard.success')}}
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box bg-orange">
                <span class="info-box-icon"><i class="fa fa-users"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">{{trans('dashboard.users')}}</span>
                  <span class="info-box-number">{{$usersTotal}}</span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                  </div>
                  <span class="progress-description">
                    {{$clientsTotal}} {{trans('dashboard.clients')}}
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box bg-teal">
                <span class="info-box-icon"><i class="fa fa-folder"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">{{trans('dashboard.groups')}}</span>
                  <span class="info-box-number">{{$groupsTotal}}</span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                  </div>
                  <span class="progress-description">
                   {{trans('dashboard.in')}} {{$userGroupsAdmin}} {{trans('dashboard.youSuperadmin')}}
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-comments"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">{{trans('dashboard.msgs')}}</span>
                  <span class="info-box-number">{{$commentsTotal}}</span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                  </div>
                  <span class="progress-description">
                    {{$userCommentsTotal}} {{trans('dashboard.your')}}
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
          </div>

    



    <div class="row">


        <div class="col-md-6">
            <div class="box">
                <div class="box-header"><h3 class="box-title"><i class="fa fa-exclamation-circle"></i> {{trans('dashboard.warnings')}}</h3></div>
<div class="box-footer box-comments" style="background: #FFFFFF;">

@if ($helps->count() == 0)

@else

@foreach ($groupFeed as $feed)


                  <div class="box-comment" >
                    <!-- User image -->
                    <img class="img-circle img-sm" src="{{Zen::showUserImgSmall($feed->author->profile->user_img)}}" alt="user image">
                    <div class="comment-text">
                      <span class="username">
                      <a href="{{URL::to('/group/'.$feed->group->group_urlhash)}}">
                      {{str_limit($feed->group->name, 40)}}
                      </a>
                        <span class="text-muted pull-right">{{LocalizedCarbon::instance($feed->updated_at)->formatLocalized('%H:%M, %d %f %Y')}}</span>
                      </span><!-- /.username -->
                      {{str_limit($feed->text, 120)}}
                      
                    </div><!-- /.comment-text -->
                  </div><!-- /.box-comment -->


@endforeach
@endif



                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box">
                <div class="box-header"><h3 class="box-title"><a href="{{URL::to('/help')}}"><i class="fa fa-graduation-cap"></i> {{trans('dashboard.lastHelper')}}</a></h3></div>
                <div class="box-footer box-comments" style="background: #FFFFFF;">

@if ($helps->count() == 0)

@else

@foreach ($helps as $help)
                  <div class="box-comment">
                    <!-- User image -->
                    <img class="img-circle img-sm" src="{{Zen::showUserImgSmall($help->author->profile->user_img)}}" alt="user image">
                    <div class="comment-text">
                      <span class="username">
                      <a href="{{URL::to('/help/'.$help->slug)}}">
                      {{str_limit($help->name, 50)}}
                      </a>
                        
                        <span class="text-muted pull-right">{{LocalizedCarbon::instance($help->updated_at)->formatLocalized('%H:%M, %d %f %Y')}}</span>
                      </span><!-- /.username -->
                      
                      {{str_limit($help->description, 120)}}
                      
                    </div><!-- /.comment-text -->
                  </div><!-- /.box-comment -->
@endforeach

@endif



                </div>

            </div>
        </div>












    </div>


    <div class="row">


        <div class="col-md-12">
            <div class="box">



                <div class="box-header">
                    <h3 class="box-title"><a href="{{URL::to('/ticket/list/in')}}"><i class="fa fa-list-alt"></i> {{trans('dashboard.lastTickets')}}</a></h3>
                </div>





                <div class="box-body">





<table id="example1" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th><center>#</center></th>
                <th><center><i class="fa fa-info-circle"></i></center></th>
                <th><center>{{trans('dashboard.subj')}}</center></th>
                <th><center>{{trans('dashboard.author')}}</center></th>
                <th><center>{{trans('dashboard.created')}}</center></th>
                
                <th class="no-sort"><center>{{trans('dashboard.user')}}</center></th>
                <th class="no-sort"><center>{{trans('dashboard.target')}}</center></th>
                <th><center>{{trans('dashboard.status')}}</center></th>
            </tr>
        </thead>
        <tbody style="
    font-size: 13px;
"></tbody>

    </table>



                



            </div>




                    </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
<!-- page script -->
<script>
  $(function () {

    $("#example1").DataTable({
        "processing": true,
        "serverSide": true,
        //"searching": false,
        //"paging": false,
        //"bPaginate": false,
        "lengthMenu": [5, 10, 20],
        "pageLength": 5,
        //"searchDelay": 500,
        "order": [[ 4, "desc" ]],
        "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
    } ],
        "ajax": {
          @if ($CurUser->roles->role == "client")
            "url": "{{URL::to('/tickets')}}",
          @else
            "url": "{{URL::to('/ticket/list/in')}}",
          @endif
            "type": "POST"
        },
        "columns": [
            { "data": "1" },
            { "data": "2" },
            { "data": "3" },
            { "data": "4" },
            { "data": "5" },
            { "data": "6" },
            { "data": "7" },
            { "data": "8" }
        ],

      "language": {
                "url": "{!! asset('plugins/datatables/media/lang/Russian.json'); !!}",
            }
    });

  });
</script>
</body>
</html>