           @if ($calendarMenu->count() > 0)
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-calendar"></i>
              <span class="label label-warning">{{$calendarMenu->count()}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">{{trans('calendar.haveToday')}} {{$calendarMenu->count()}} {{trans('calendar.eventParent')}}</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">


@foreach ($calendarMenu as $CalendarEvent)

                  <li>
                    <a href="{{URL::to('/calendar')}}">
                      <i class="fa fa-circle" style="color: {{$CalendarEvent->backgroundColor}}"></i> {{$CalendarEvent->title}}
                      <small class="pull-right"><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($CalendarEvent->dtStart)->formatLocalized('%H:%M')}}</small>
                    </a>
                  </li>

@endforeach

                </ul>
              </li>
              <li class="footer"><a href="{{URL::to('/calendar')}}">{{trans('calendar.goToCal')}}</a></li>
            </ul>

            @endif