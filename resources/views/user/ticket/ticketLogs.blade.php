<ul class="timeline">
                <!-- timeline time label -->


@foreach ($ticket->logs as $log)

@if ($log->action == "create")

<li>
<i class="fa fa-tag bg-aqua"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('ticket.logCreate')}}</h3>
                  </div>
</li>

@elseif ($log->action == "comment")

<li>
<i class="fa fa-comments bg-purple"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('ticket.logComment')}}</h3>
                  </div>
</li>

@elseif ($log->action == "refer")

<li>
<i class="fa fa fa-share bg-blue"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('ticket.logRefer')}}</h3>
                  </div>
</li>

@elseif ($log->action == "lock")

<li>
<i class="fa fa-lock bg-yellow"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('ticket.logLock')}}</h3>
                  </div>
</li>
@elseif ($log->action == "lockNext")

<li>
<i class="fa fa-lock bg-yellow"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('ticket.logLock')}}</h3>
                  </div>
</li>
@elseif ($log->action == "unlock")

<li>
<i class="fa fa-unlock bg-maroon"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('ticket.logUnlock')}}</h3>
                  </div>
</li>

@elseif ($log->action == "ok")

<li>
<i class="fa fa-check-circle-o bg-green"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('ticket.logSuccess')}}</h3>
                  </div>
</li>
@elseif ($log->action == "approve")

<li>
<i class="fa fa-check-circle-o bg-green"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('ticket.logApproveOk')}}</h3>
                  </div>
</li>
@elseif ($log->action == "noapprove")

<li>
<i class="fa fa-close bg-red"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('ticket.logApproveNo')}}</h3>
                  </div>
</li>

@elseif ($log->action == "waitok")

<li>
<i class="fa fa-check-circle-o bg-red"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('ticket.logWaitApprove')}}</h3>
                  </div>
</li>
@elseif ($log->action == "unok")

<li>
<i class="fa fa-circle-o bg-red"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('ticket.logNosuccess')}}</h3>
                  </div>
</li>

@elseif ($log->action == "arch")

<li>
<i class="fa fa-archive bg-gray"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header">{{trans('ticket.logArch')}}</h3>
                  </div>
</li>

@elseif ($log->action == "edit")

<li>
<i class="fa fa-pencil-square-o bg-orange"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('ticket.logEdit')}}</h3>
                  </div>
</li>
@elseif ($log->action == "delete")

<li>
<i class="fa fa-trash bg-red"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('ticket.logDelete')}}</h3>
                  </div>
</li>

@elseif ($log->action == "restore")

<li>
<i class="fa fa-recycle bg-green"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($log->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
                    <h3 class="timeline-header"><a href="{{URL::to('/user/'.$log->author->profile->user_urlhash)}}">{{$log->author->name}}</a> {{trans('ticket.logRestore')}}</h3>
                  </div>
</li>

@endif


@endforeach
                <li>
                  <i class="fa fa-clock-o bg-gray"></i>
                </li>

              </ul>