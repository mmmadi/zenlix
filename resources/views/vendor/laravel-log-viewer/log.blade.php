@include("layout.header")


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>

      .stack {
        font-size: 0.85em;
      }
      .date {
        min-width: 75px;
      }
      .text {
        word-break: break-all;
      }
      a.llv-active {
        z-index: 2;
        background-color: #f5f5f5;
        border-color: #777;
      }
    </style>


@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('logPage.title')}}
    <small>{{trans('logPage.events')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('logPage.title')}}</li>
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
                    <h3 class="box-title"><i class="fa fa-warning"></i> {{trans('logPage.title')}}</h3>
                </div>





                <div class="box-body">


<div class="col-sm-12 col-md-12 table-container">
          @if ($logs === null)
            <div>
              {{trans('logPage.overSize')}}
            </div>
          @else
          <table id="table-log" class="table table-striped">
            <thead>
              <tr>
                <th>{{trans('logPage.level')}}</th>
                <th>{{trans('logPage.date')}}</th>
                <th>{{trans('logPage.content')}}</th>
              </tr>
            </thead>
            <tbody>

@foreach($logs as $key => $log)
<tr>
  <td class="text-{{{$log['level_class']}}}"><span class="glyphicon glyphicon-{{{$log['level_img']}}}-sign" aria-hidden="true"></span> &nbsp;{{$log['level']}}</td>
  <td class="date">{{{$log['date']}}}</td>
  <td class="text">
    @if ($log['stack']) <a class="pull-right expand btn btn-default btn-xs" data-display="stack{{{$key}}}"><span class="glyphicon glyphicon-search"></span></a>@endif
    {{{$log['text']}}}
    @if (isset($log['in_file'])) <br />{{{$log['in_file']}}}@endif
    @if ($log['stack']) <div class="stack" id="stack{{{$key}}}" style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}</div>@endif
  </td>
</tr>
@endforeach

            </tbody>
          </table>
          @endif
          <div>
            <a href="?dl={{ base64_encode($current_file) }}"><span class="glyphicon glyphicon-download-alt"></span> {{trans('logPage.download')}}</a>
            -
            <a id="delete-log" href="?del={{ base64_encode($current_file) }}"><span class="glyphicon glyphicon-trash"></span> {{trans('logPage.delete')}}</a>
          </div>
        </div>



            </div>




                    </div><!-- /.box-body -->
                    </div><!-- /.box -->



<div class="col-md-3">

          <div class="list-group">
            @foreach($files as $file)
              <a href="?l={{ base64_encode($file) }}" class="list-group-item @if ($current_file == $file) llv-active @endif">
                {{$file}}
              </a>
            @endforeach
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

$('#table-log').DataTable({
          "order": [ 1, 'desc' ],
          "stateSave": true,
          "stateSaveCallback": function (settings, data) {
            window.localStorage.setItem("datatable", JSON.stringify(data));
          },
          "stateLoadCallback": function (settings) {
            var data = JSON.parse(window.localStorage.getItem("datatable"));
            if (data) data.start = 0;
            return data;
          }
        });
        $('.table-container').on('click', '.expand', function(){
          $('#' + $(this).data('display')).toggle();
        });
        $('#delete-log').click(function(){
          return confirm('Are you sure?');
        });


  });
</script>
</body>
</html>









