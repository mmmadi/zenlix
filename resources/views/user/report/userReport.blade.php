@include("layout.header")

  {!! Html::style('plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css'); !!}
    {!! Html::style('plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css'); !!}

@include("layout.topmenu")
@include("layout.navbar")  



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     <i class="fa fa-pie-chart"></i> {{trans('report.reports')}}
    <small>{{trans('report.userReports')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li>{{trans('report.reports')}}</li>
        <li class="active">{{trans('report.userReports')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-12">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->



            <div class="box">


                <div class="box-header">
                    <h3 class="box-title">{{trans('report.userReports')}} {{$user->name}}</h3>
                </div>



                <div class="box-body">
      


<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_6" data-toggle="tab" aria-expanded="true">{{trans('report.fullInfo')}}</a></li>
              <li class=""><a href="#tab_1" data-toggle="tab" aria-expanded="false">{{trans('report.Created')}}</a></li>
              <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">{{trans('report.Received')}}</a></li>
              <li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false">{{trans('report.Success')}} </a></li>
              <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">{{trans('report.Referers')}}</a></li>
              <li class=""><a href="#tab_7" data-toggle="tab" aria-expanded="false">{{trans('report.SlaTickets')}}</a></li>

              <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">{{trans('report.LogActions')}}</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane" id="tab_1">

<table id="example1" class="table table-bordered example1">
                <thead>
                <tr>
                    <th><center>{{trans('report.subj')}} </center></th>
                    <th><center>{{trans('report.code')}} </center></th>
                    <th><center>{{trans('report.prio')}}</center></th>
                    <th><center>{{trans('report.createdAt')}} </center></th>
                    <th><center>{{trans('report.successAt')}} </center></th>
                    <th><center>{{trans('report.target')}} </center></th>
                    <th><center>{{trans('report.client')}} </center></th>
                </tr>
                </thead>
                <tbody>
@foreach ($createdTickets as $createdTicket)
<tr >
<td>{{str_limit($createdTicket->subject, 20)}}</td>
<td><a href="{{URL::to('ticket/'.$createdTicket->code)}}">{{$createdTicket->code}}</a></td>
<td>{!! $createdTicket->tp !!}</td>
<td>{{LocalizedCarbon::instance($createdTicket->created_at)->formatLocalized('%e %f %Y, %H:%M')}}</td>
<td>{{$createdTicket->dateOk}}</td>
<td>{!! $createdTicket->targetString !!}</td>
<td>{!! $createdTicket->clientGen !!}</td>
</tr>
@endforeach
                </tbody>
</table>

              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
<table id="example1" class="table table-bordered example1">
                <thead>
                <tr>
                    <th><center>{{trans('report.subj')}} </center></th>
                    <th><center>{{trans('report.code')}} </center></th>
                    <th><center>{{trans('report.prio')}}</center></th>
                    <th><center>{{trans('report.createdAt')}} </center></th>
                    <th><center>{{trans('report.successAt')}} </center></th>
                    <th><center>{{trans('report.author')}} </center></th>
                    <th><center>{{trans('report.target')}} </center></th>
                    <th><center>{{trans('report.client')}} </center></th>
                </tr>
                </thead>
                <tbody>
@foreach ($receivedTickets as $receivedTicket)
<tr >
<td>{{str_limit($receivedTicket->subject, 20)}}</td>
<td><a href="{{URL::to('ticket/'.$receivedTicket->code)}}">{{$receivedTicket->code}}</a></td>
<td>{!! $receivedTicket->tp !!}</td>
<td>{{LocalizedCarbon::instance($receivedTicket->created_at)->formatLocalized('%e %f %Y, %H:%M')}}</td>
<td>{{$receivedTicket->dateOk}}</td>
<td>{!! $receivedTicket->authorUser->name !!}</td>
<td>{!! $receivedTicket->targetString !!}</td>
<td>{!! $receivedTicket->clientGen !!}</td>
</tr>
@endforeach
                </tbody>
</table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">

<ul class="timeline">
                <!-- timeline time label -->


@foreach ($logs as $log)

@if ($log->action == "create")

<li>
<i class="fa fa-tag bg-aqua"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('report.logActionCreate')}} <a href="{{URL::to('/ticket/'.$log->ticket->code)}}">#{{$log->ticket->code}}</a> ({{str_limit($log->ticket->subject, 20)}})</h3>
                  </div>
</li>

@elseif ($log->action == "comment")

<li>
<i class="fa fa-comments bg-purple"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('report.logActionComment')}} <a href="{{URL::to('/ticket/'.$log->ticket->code)}}">#{{$log->ticket->code}}</a> ({{str_limit($log->ticket->subject, 20)}})</h3>
                  </div>
</li>

@elseif ($log->action == "refer")

<li>
<i class="fa fa fa-share bg-blue"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('report.logActionRefer')}} <a href="{{URL::to('/ticket/'.$log->ticket->code)}}">#{{$log->ticket->code}}</a> ({{str_limit($log->ticket->subject, 20)}})</h3>
                  </div>
</li>

@elseif ($log->action == "lock")

<li>
<i class="fa fa-lock bg-yellow"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('report.logActionLock')}} <a href="{{URL::to('/ticket/'.$log->ticket->code)}}">#{{$log->ticket->code}}</a> ({{str_limit($log->ticket->subject, 20)}})</h3>
                  </div>
</li>
@elseif ($log->action == "lockNext")

<li>
<i class="fa fa-lock bg-yellow"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('report.logActionLock2')}} <a href="{{URL::to('/ticket/'.$log->ticket->code)}}">#{{$log->ticket->code}}</a> ({{str_limit($log->ticket->subject, 20)}})</h3>
                  </div>
</li>
@elseif ($log->action == "unlock")

<li>
<i class="fa fa-unlock bg-maroon"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('report.logActionUnlock')}} <a href="{{URL::to('/ticket/'.$log->ticket->code)}}">#{{$log->ticket->code}}</a> ({{str_limit($log->ticket->subject, 20)}})</h3>
                  </div>
</li>

@elseif ($log->action == "ok")

<li>
<i class="fa fa-check-circle-o bg-green"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('report.logActionSuccess')}} <a href="{{URL::to('/ticket/'.$log->ticket->code)}}">#{{$log->ticket->code}}</a> ({{str_limit($log->ticket->subject, 20)}})</h3>
                  </div>
</li>
@elseif ($log->action == "approve")

<li>
<i class="fa fa-check-circle-o bg-green"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('report.logActionApprove')}} <a href="{{URL::to('/ticket/'.$log->ticket->code)}}">#{{$log->ticket->code}}</a> ({{str_limit($log->ticket->subject, 20)}}) и отметил как выполненная</h3>
                  </div>
</li>
@elseif ($log->action == "noapprove")

<li>
<i class="fa fa-close bg-red"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('report.logActionApprove2')}} <a href="{{URL::to('/ticket/'.$log->ticket->code)}}">#{{$log->ticket->code}}</a> ({{str_limit($log->ticket->subject, 20)}}) и не принял</h3>
                  </div>
</li>

@elseif ($log->action == "waitok")

<li>
<i class="fa fa-check-circle-o bg-red"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('report.logActionWaitApprove')}} <a href="{{URL::to('/ticket/'.$log->ticket->code)}}">#{{$log->ticket->code}}</a> ({{str_limit($log->ticket->subject, 20)}})</h3>
                  </div>
</li>
@elseif ($log->action == "unok")

<li>
<i class="fa fa-circle-o bg-red"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('report.logActionNotOk')}} <a href="{{URL::to('/ticket/'.$log->ticket->code)}}">#{{$log->ticket->code}}</a> ({{str_limit($log->ticket->subject, 20)}})</h3>
                  </div>
</li>



@elseif ($log->action == "edit")

<li>
<i class="fa fa-pencil-square-o bg-orange"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('report.logActionEdit')}} <a href="{{URL::to('/ticket/'.$log->ticket->code)}}">#{{$log->ticket->code}}</a> ({{str_limit($log->ticket->subject, 20)}})</h3>
                  </div>
</li>
@elseif ($log->action == "delete")

<li>
<i class="fa fa-trash bg-red"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('report.logActionDelete')}} <a href="{{URL::to('/ticket/'.$log->ticket->code)}}">#{{$log->ticket->code}}</a> ({{str_limit($log->ticket->subject, 20)}})</h3>
                  </div>
</li>

@elseif ($log->action == "restore")

<li>
<i class="fa fa-recycle bg-green"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('report.logActionRestore')}} <a href="{{URL::to('/ticket/'.$log->ticket->code)}}">#{{$log->ticket->code}}</a> ({{str_limit($log->ticket->subject, 20)}})</h3>
                  </div>
</li>

@endif


@endforeach
                <li>
                  <i class="fa fa-clock-o bg-gray"></i>
                </li>

              </ul>


              </div>


<div class="tab-pane" id="tab_4">

<table id="example1" class="table table-bordered example1">
                <thead>
                <tr>
                    <th><center>{{trans('report.subj')}} </center></th>
                    <th><center>{{trans('report.code')}} </center></th>
                    <th><center>{{trans('report.prio')}}</center></th>
                    <th><center>{{trans('report.createdAt')}} </center></th>
                    <th><center>{{trans('report.successAt')}} </center></th>
                    <th><center>{{trans('report.author')}} </center></th>
                    <th><center>{{trans('report.target')}} </center></th>
                    <th><center>{{trans('report.client')}} </center></th>
                </tr>
                </thead>
                <tbody>
@foreach ($referTickets as $referTicket)
<tr >
<td>{{str_limit($referTicket->subject, 20)}}</td>
<td><a href="{{URL::to('ticket/'.$referTicket->code)}}">{{$referTicket->code}}</a></td>
<td>{!! $referTicket->tp !!}</td>
<td>{{LocalizedCarbon::instance($referTicket->created_at)->formatLocalized('%e %f %Y, %H:%M')}}</td>
<td>{{$referTicket->dateOk}}</td>
<td>{!! $referTicket->authorUser->name !!}</td>
<td>{!! $referTicket->targetString !!}</td>
<td>{!! $referTicket->clientGen !!}</td>
</tr>
@endforeach
                </tbody>
</table>


</div>

<div class="tab-pane" id="tab_5">
<table id="example1" class="table table-bordered example1">
                <thead>
                <tr>
                    <th><center>{{trans('report.subj')}} </center></th>
                    <th><center>{{trans('report.code')}} </center></th>
                    <th><center>{{trans('report.prio')}}</center></th>
                    <th><center>{{trans('report.createdAt')}} </center></th>
                    <th><center>{{trans('report.successAt')}} </center></th>
                    <th><center>{{trans('report.author')}} </center></th>
                    <th><center>{{trans('report.target')}} </center></th>
                    <th><center>{{trans('report.client')}} </center></th>
                </tr>
                </thead>
                <tbody>
@foreach ($successTickets as $successTicket)
<tr >
<td>{{str_limit($successTicket->subject, 20)}}</td>
<td><a href="{{URL::to('ticket/'.$successTicket->code)}}">{{$successTicket->code}}</a></td>
<td>{!! $successTicket->tp !!}</td>
<td>{{LocalizedCarbon::instance($successTicket->created_at)->formatLocalized('%e %f %Y, %H:%M')}}</td>
<td>{{$successTicket->dateOk}}</td>
<td>{!! $successTicket->authorUser->name !!}</td>
<td>{!! $successTicket->targetString !!}</td>
<td>{!! $successTicket->clientGen !!}</td>
</tr>
@endforeach
                </tbody>
</table>
</div>

<div class="tab-pane" id="tab_7">
<table id="example1" class="table table-bordered example1">
                <thead>
                <tr>
                    <th><center>{{trans('report.subj')}} </center></th>
                    <th><center>{{trans('report.code')}} </center></th>
                    <th><center>{{trans('report.prio')}}</center></th>
                    <th><center>{{trans('report.createdAt')}} </center></th>
                    <th><center>{{trans('report.successAt')}} </center></th>
                    <th><center>{{trans('report.author')}} </center></th>
                    <th><center>{{trans('report.target')}} </center></th>
                    <th><center>{{trans('report.client')}} </center></th>
                    <th><center>{{trans('report.slaReact')}} </center></th>
                    <th><center>{{trans('report.slaWork')}} </center></th>
                    <th><center>{{trans('report.slaDeadline')}} </center></th>
                </tr>
                </thead>
                <tbody>
@foreach ($slaTickets as $slaTicket)
<tr >
<td>{{str_limit($slaTicket->subject, 20)}}</td>
<td><a href="{{URL::to('ticket/'.$slaTicket->code)}}">{{$slaTicket->code}}</a></td>
<td>{!! $slaTicket->tp !!}</td>
<td>{{LocalizedCarbon::instance($slaTicket->created_at)->formatLocalized('%e %f %Y, %H:%M')}}</td>
<td>{{$slaTicket->dateOk}}</td>
<td>{!! $slaTicket->authorUser->name !!}</td>
<td>{!! $slaTicket->targetString !!}</td>
<td>{!! $slaTicket->clientGen !!}</td>
<td>@if ($slaTicket->slaReactionStatus == true) 
<small><span class="text-green">
  {{trans('report.slaGood')}} {{$slaTicket->slaReactionFact}} / {{$slaTicket->slaReactionRegl}}
  </span></small> 
  @else 
  <small><span class="text-red">{{trans('report.slaBad')}} {{$slaTicket->slaReactionFact}} / {{$slaTicket->slaReactionRegl}} </span></small> 

  @endif</td>
<td>@if ($slaTicket->slaWorkStatus == true) <small><span class="text-green">
  {{trans('report.slaGood')}} {{$slaTicket->slaWorkFact}} / {{$slaTicket->slaWorkRegl}}
  </span></small> 
  @else 
  <small><span class="text-red">{{trans('report.slaBad')}} {{$slaTicket->slaWorkFact}} / {{$slaTicket->slaWorkRegl}} </span></small> 

  @endif</td>
<td>@if ($slaTicket->slaDeadlineStatus == true) <small><span class="text-green">
  {{trans('report.slaGood')}} {{$slaTicket->slaDeadlineFact}} / {{$slaTicket->slaDeadlineRegl}}
  </span></small> 
  @else 
  <small><span class="text-red">{{trans('report.slaBad')}} {{$slaTicket->slaDeadlineFact}} / {{$slaTicket->slaDeadlineRegl}} </span></small> 

  @endif</td>
</tr>
@endforeach
                </tbody>
</table>
</div>

<div class="tab-pane active" id="tab_6">


<canvas id="myChart" ></canvas>

<center><h4>{{trans('report.slaSuccess')}}</h4></center>
<div class="row">
<div class="col-md-4"><canvas id="myChartReact" ></canvas><center>{{trans('report.react')}}</center></div>
<div class="col-md-4"><canvas id="myChartWork" ></canvas><center>{{trans('report.work')}}</center></div>
<div class="col-md-4"><canvas id="myChartDeadline" ></canvas><center>{{trans('report.deadline')}}</center></div>
</div>
</div>



              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>









                </div>


                </div>














            
                    </div><!-- /.box -->






                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")

{!! Html::script('plugins/datatables/media/js/dataTables.tableTools.js'); !!}
{!! Html::script('plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js'); !!}
{!! Html::script('plugins/datatables/extensions/Buttons/js/buttons.print.min.js'); !!}
{!! Html::script('plugins/datatables/extensions/Buttons/js/buttons.html5.min.js'); !!}
{!! Html::script('plugins/datatables/extensions/Buttons/js/jszip.min.js'); !!}

{!! Html::script('plugins/pdfmake/build/pdfmake.min.js'); !!}
{!! Html::script('plugins/pdfmake/build/vfs_fonts.js'); !!}

{!! Html::script('plugins/chartjs/Chart.min.js'); !!}


<!-- page script -->
<script>
  $(function () {

var data = {
    labels: ["{{trans('report.chartCreated')}}", "{{trans('report.chartIn')}}", "{{trans('report.chartRefer')}}", "{{trans('report.chartSuccess')}}", "{{trans('report.chartSla')}}"],
    datasets: [
        {
            label: "My First dataset",
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "rgba(220,220,220,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data: [{{$createdTickets->count()}}, {{$receivedTickets->count()}}, {{$referTickets->count()}}, {{$successTickets->count()}}, {{$slaTickets->count()}}]
        }
    ]
};

var dataReact = [
    {
        value: {{$countReactionNo}},
        color:"#F7464A",
        highlight: "#FF5A5E",
        label: "{{trans('report.chartLblOver')}}"
    },
    {
        value: {{$countReactionOk}},
        color: "#46BFBD",
        highlight: "#5AD3D1",
        label: "{{trans('report.chartLblInTime')}}"
    }
];
var dataWork = [
    {
        value: {{$countWorkNo}},
        color:"#F7464A",
        highlight: "#FF5A5E",
        label: "{{trans('report.chartLblOver')}}"
    },
    {
        value: {{$countWorkOk}},
        color: "#46BFBD",
        highlight: "#5AD3D1",
        label: "{{trans('report.chartLblInTime')}}"
    }
];
var dataDeadline = [
    {
        value: {{$countDeadlineNo}},
        color:"#F7464A",
        highlight: "#FF5A5E",
        label: "{{trans('report.chartLblOver')}}"
    },
    {
        value: {{$countDeadlineOk}},
        color: "#46BFBD",
        highlight: "#5AD3D1",
        label: "{{trans('report.chartLblInTime')}}"
    }
];



var ctx = document.getElementById("myChart").getContext("2d");
var myBarChart = new Chart(ctx).Bar(data, {
  responsive: true,
  tooltipFontFamily: " 'Helvetica', 'Arial', sans-serif",

});

var ctxReact = document.getElementById("myChartReact").getContext("2d");
var ctxWork = document.getElementById("myChartWork").getContext("2d");
var ctxDeadline = document.getElementById("myChartDeadline").getContext("2d");

var myPieChartReact = new Chart(ctxReact).Pie(dataReact, {
  responsive: true,
  tooltipFontFamily: " 'Helvetica', 'Arial', sans-serif"
});

var myPieChartWork = new Chart(ctxWork).Pie(dataWork, {
  responsive: true,
  tooltipFontFamily: " 'Helvetica', 'Arial', sans-serif"
});

var myPieChartDeadline = new Chart(ctxDeadline).Pie(dataDeadline, {
  responsive: true,
  tooltipFontFamily: " 'Helvetica', 'Arial', sans-serif"
});


    $(".example1").DataTable({
      dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
      //stateSave: true,
      "language": {
                "url": "plugins/datatables/lang/Russian.json",
            },
                  "searching": false,
                  "paging": false,
                  "info": true,
                  "order": [[ 3, "desc" ]],
          "columnDefs": [ {
          "targets": 'no-sort',
         // "orderable": false,
    } ],
          });

  });
</script>
</body>
</html>