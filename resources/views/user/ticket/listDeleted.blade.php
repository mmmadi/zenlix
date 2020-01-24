@include("layout.header")


@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-trash"></i> {{trans('ticketLists.tickets')}}
        <small>{{trans('ticketLists.listOfDeleted')}}</small>
      </h1>
<ol class="breadcrumb">
                        @include("layout.breadcrumb")
                        <li class="active">{{trans('ticketLists.listOfDeleted')}}</li>
                    </ol>
    </section>

    <!-- Main content -->
    <section class="content">
<div class="row">
<div class="col-md-12">

  <div class="box box-solid">
    <div class="box-body">

<div class="callout callout-info">
                <p>{{trans('ticketLists.deletedInfo')}}</p>
              </div>
<br>

<div class="col-md-12">

<table id="example1" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th><center>#</center></th>
                <th><center><i class="fa fa-info-circle"></i></center></th>
                <th><center>{{trans('ticketLists.subject')}}</center></th>
                <th><center>{{trans('ticketLists.author')}}</center></th>
                <th><center>{{trans('ticketLists.created')}}</center></th>
                <th><center>{{trans('ticketLists.deletedAt')}}</center></th>
                <th class="no-sort"><center>{{trans('ticketLists.user')}}</center></th>
                <th class="no-sort"><center>{{trans('ticketLists.target')}}</center></th>
                
            </tr>
        </thead>
        <tbody style="
    font-size: 13px;
"></tbody>
        <tfoot>
            <tr>
                <th><center>#</center></th>
                <th><center><i class="fa fa-info-circle"></i></center></th>
                <th><center>{{trans('ticketLists.subject')}}</center></th>
                <th><center>{{trans('ticketLists.author')}}</center></th>
                <th><center>{{trans('ticketLists.created')}}</center></th>
                <th><center>{{trans('ticketLists.deletedAt')}}</center></th>
                <th><center>{{trans('ticketLists.user')}}</center></th>
                <th><center>{{trans('ticketLists.target')}}</center></th>
                
            </tr>
        </tfoot>
    </table>
</div>



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

    $("#example1").DataTable({
        "processing": true,
        "serverSide": true,
        //"searchDelay": 500,
        "order": [[5, "desc" ]],
        "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
    } ],
        "ajax": {
            "url": "{{URL::to('/ticket/list/deleted')}}",
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



    /*$('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });*/
  });
</script>
</body>
</html>