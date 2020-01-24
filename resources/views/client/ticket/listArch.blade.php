@include("layout.header")


@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-list-alt"></i> {{trans('clientTL.tickets')}}
        <small>{{trans('clientTL.ticketLists')}}</small>
      </h1>
<ol class="breadcrumb">
                        @include("layout.breadcrumb")
                        <li class="active">{{trans('clientTL.ticketLists')}}</li>
                    </ol>
    </section>

    <!-- Main content -->
    <section class="content">
<div class="row">
<div class="col-md-12">

  <div class="box box-solid">
    <div class="box-body">
@include("client.ticket.listNav")  

<br>

<div class="col-md-12">

<table id="example1" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th><center>#</center></th>
                <th><center><i class="fa fa-info-circle"></i></center></th>
                <th><center>{{trans('clientTL.subj')}}</center></th>
                <th><center>{{trans('clientTL.author')}}</center></th>
                <th><center>{{trans('clientTL.created')}}</center></th>
                <th><center>{{trans('clientTL.success')}}</center></th>
                <th class="no-sort"><center>{{trans('clientTL.user')}}</center></th>
                <th class="no-sort"><center>{{trans('clientTL.target')}}</center></th>
                <th class="no-sort"><center>{{trans('clientTL.status')}}</center></th>
            </tr>
        </thead>
        <tbody style="
    font-size: 13px;
"></tbody>
        <tfoot>
            <tr>
                <th><center>#</center></th>
                <th><center><i class="fa fa-info-circle"></i></center></th>
                <th><center>{{trans('clientTL.subj')}}</center></th>
                <th><center>{{trans('clientTL.author')}}</center></th>
                <th><center>{{trans('clientTL.created')}}</center></th>
                <th><center>{{trans('clientTL.success')}}</center></th>
                <th><center>{{trans('clientTL.user')}}</center></th>
                <th><center>{{trans('clientTL.target')}}</center></th>
                <th><center>{{trans('clientTL.status')}}</center></th>
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
        "order": [[ 4, "desc" ]],
        "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
    } ],
        "ajax": {
            "url": "{{URL::to('/tickets/arch')}}",
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
            { "data": "8" },
            { "data": "9" }
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