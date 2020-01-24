<style type="text/css">
  .info-box-content {
    margin-left: 60px;
  }
  .info-box-icon {
        width: 60px;
  }
</style>


<div class="row">
    
    <div class="col-md-12">


<div class="box box-default">
                    <div class="box-header with-border">
                      <h3 class="box-title">{{trans('slabox.slaInfo')}}</h3>
                      <div class="box-tools pull-right">
                        
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        
                      </div>
                    </div><!-- /.box-header -->
                    <div class="box-body no-padding">
                    <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-bolt"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">{{trans('slabox.react')}}</span>
                  <span class="info-box-number" style="white-space: nowrap;  overflow: hidden; text-overflow: ellipsis;" id="reaction_timer" data-status="{{ $SLALog->reaction_time_status }}">
@if ($SLALog->reglamentReactionPercent >=100)
{{trans('slabox.over')}}
@else
<time id="f" datetime="{{ $SLALog->reaction_time }}"><span></span></time>
@endif
                  </span>
                  <div class="progress">
                    <div class="progress-bar" style="width: {{$SLALog->reglamentReactionPercent}}%"></div>
                  </div>
                  <span class="progress-description">
                    {{trans('slabox.reglament')}} <span>{{$SLALog->reglamentReaction}}</span>
                  </span>
                </div><!-- /.info-box-content -->
              </div>


<div class="info-box bg-yellow">
                <span class="info-box-icon"><i class="fa fa-lock"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">{{trans('slabox.work')}}</span>

                  <span class="info-box-number" style="white-space: nowrap;  overflow: hidden; text-overflow: ellipsis;" id="work_timer" data-status="{{ $SLALog->work_time_status }}">
@if ($SLALog->reglamentWorkPercent >=100)
{{trans('slabox.over')}}
@else
                  <time id="f" datetime="{{ $SLALog->work_time }}"><span></span></time>
@endif
                  </span><div class="progress">
                    <div class="progress-bar" style="width: {{$SLALog->reglamentWorkPercent}}%"></div>
                  </div>
                  <span class="progress-description">
                    {{trans('slabox.reglament')}} {{$SLALog->reglamentWork}}
                  </span>
                </div><!-- /.info-box-content -->
              </div>




<div class="info-box bg-orange " style="background-color: #D81B60 !important;">
                <span class="info-box-icon"><i class="fa fa-check-square"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">{{trans('slabox.deadline')}}</span>
                  <span class="info-box-number" style="white-space: nowrap;  overflow: hidden; text-overflow: ellipsis;" id="deadline_timer" data-status="{{ $SLALog->deadline_time_status }}">
@if ($SLALog->reglamentDeadlinePercent >=100)
{{trans('slabox.over')}}
@else
                  <time id="f" datetime="{{ $SLALog->deadline_time }}"><span></span></time>
@endif
                  </span>

                  <div class="progress">
                    <div class="progress-bar" style="width: {{$SLALog->reglamentDeadlinePercent}}%"></div>
                  </div>
                  <span class="progress-description">
                    {{trans('slabox.reglament')}} {{$SLALog->reglamentDeadline}}
                  </span>
                </div><!-- /.info-box-content -->
              </div>
</div>

</div>

</div>
</div>