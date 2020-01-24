@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  
<style type="text/css">
.dataTables_filter {
display: none; 
}
</style>
{!! Html::style('plugins/iCheck/minimal/purple.css'); !!}

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     <i class="fa fa-envelope"></i> {{trans('message.title')}}
    @if ($messagesCount != 0)<small>{{$messagesCount}} {{trans('message.countMsgs')}}</small>@endif
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('message.title')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">

<div class="col-md-3">
  

<a href="{{URL::to('/message/new')}}" class="btn btn-primary btn-block margin-bottom">{{trans('message.writeMsg')}}</a>

@include('user.message.nav')



</div>
<div class="col-md-9">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->

        

          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Список сообщений</h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input type="text" class="form-control input-sm search-box" placeholder="{{trans('message.findMsg')}}">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">















              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle" ><i class="fa fa-square-o"></i>
                </button>
                <div class="btn-group">
                  <button id="del_elements" type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>

                  
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm" id="checkNew"><i class="fa fa-refresh"></i></button>

                <!-- /.pull-right -->
              </div>









<div class="table-responsive mailbox-messages">
<table id="example1" class="table table-hover table-striped">
                <thead>
                <tr>
                    <th class="no-sort"></th>
                    <th class="no-sort"></th>
                    <th><center>{{trans('message.From')}} </center></th>
                    <th><center>{{trans('message.subj')}} </center></th>
                    <th class="no-sort"></th>
                    <th><center>{{trans('message.Date')}} </center></th>
                </tr>
                </thead>

                <tbody id="datatable-body">
                

@foreach ($messages as $message)

                  <tr @if ($message->read_flag == 'true') style="font-weight: 600;" @endif>
                    <td><input type="checkbox" name="el" value="{{$message->message_urlhash}}"></td>
                    <td class="mailbox-star"></td>
                    <td class="mailbox-name"><a href="{{URL::to('/message/'.$message->message_urlhash)}}">{{$message->fromUser->name}}</a></td>
                    <td class="mailbox-subject">{{str_limit($message->subject, 20)}} - {{str_limit(strip_tags($message->text), 40)}}
                    </td>
                    <td class="mailbox-attachment">@if ($message->files->count() != 0) <i class="fa fa-paperclip"></i> @endif</td>
                    <td class="mailbox-date">                        <span data-toggle="tooltip"  data-placement="top" title="
                    {{ LocalizedCarbon::parse($message->created_at)->format('d M Y H:i:s') }}
                    ">{{ LocalizedCarbon::instance($message->created_at)->diffForHumans() }}</span></td>
                  </tr>
     
@endforeach
                    

  
                </tbody>

              </table>
</div>


              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->

          </div>





</div>

                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
{!! Html::script('plugins/iCheck/icheck.min.js'); !!}
{!! Html::script('plugins/bootbox/bootbox.min.js'); !!}
<!-- page script -->
<script>
  $(function () {
    $('.mailbox-messages input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_minimal-purple'
      
    });
 function drawDt() {

return $("#example1").DataTable({
      stateSave: true,
      destroy: true,
      "bLengthChange": false,
      //sDom: '<"search-box"r><"H"lf>t<"F"ip>',
      "language": {
                "url": "plugins/datatables/lang/Russian.json",
            },
                  "searching": true,
                  "paging": true,
                  "info": true,
                  "order": [[ 5, "desc" ]],

                                    //"order": [[ 2, "desc" ]],
          "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
    } ],
          });

 }
 oTable = drawDt();






/*    oTable = $("#example1").DataTable({
      stateSave: true,
      "bLengthChange": false,
      //sDom: '<"search-box"r><"H"lf>t<"F"ip>',
      "language": {
                "url": "plugins/datatables/lang/Russian.json",
            },
                  "searching": true,
                  "paging": true,
                  "info": true,
                  "order": [[ 5, "desc" ]],

                                    //"order": [[ 2, "desc" ]],
          "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
    } ],
          });*/
messagesCount= {{$messagesCount}};


$('body').on('click', '#checkNew', function(event) {
            event.preventDefault();
//oTable = drawDt();
console.log(messagesCount);

$.ajax({
                    type: "POST",
                    url: "{{URL::to('/message/checkNew') }}",
                    dataType: "json",
                    data: {
                      counter: messagesCount,
                      _token : CSRF_TOKEN,
                      _method: 'POST'
                    },
                    success: function(html) {
                      $.each(html, function(i, item) {
                        console.log(item.status);
                        if (item.status == 'true') {
                         // oTable.destroy();
                         messagesCount = item.counter;
                         $(".messagesCount").text(item.counter);

                          window.location = "{{URL::to('/message')}}";
                          
                        }
                      });
                      //window.location = "{{URL::to('/message')}}";

                    }
                  });

          });


$('body').on('click', '#del_elements', function(event) {
            event.preventDefault();
var matches = [];
$(".mailbox-messages input[type='checkbox']:checked").each(function() {
    matches.push(this.value);
});
if (matches.length != 0) { 

bootbox.confirm('{{trans('message.confirmDelete')}}', function(result) {
                if (result == true) {

//console.log(matches);
$.ajax({
                    type: "POST",
                    url: "{{URL::to('/message/deleteMany') }}",
                    //dataType: "json",
                    data: {
                      elements: matches,
                      _token : CSRF_TOKEN,
                      _method: 'DELETE'
                    },
                    success: function(html) {

                      window.location = "{{URL::to('/message')}}";

                    }
                  });


                }
                else if (result == false) {}
              });

}
          });




$('.search-box').keyup(function(){
      oTable.search($(this).val()).draw() ;
});


    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
      var clicks = $(this).data('clicks');
      if (clicks) {
        //Uncheck all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
      } else {
        //Check all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("check");
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
      }
      $(this).data("clicks", !clicks);
    });







    //Handle starring for glyphicon and font awesome
    $(".mailbox-star").click(function (e) {
      e.preventDefault();
      //detect type
      var $this = $(this).find("a > i");
      var glyph = $this.hasClass("glyphicon");
      var fa = $this.hasClass("fa");

      //Switch states
      if (glyph) {
        $this.toggleClass("glyphicon-star");
        $this.toggleClass("glyphicon-star-empty");
      }

      if (fa) {
        $this.toggleClass("fa-star");
        $this.toggleClass("fa-star-o");
      }
    });
  });
</script>
</body>
</html>