           
@if ($notifyMenu->count() > 0)

            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-bell-o"></i>
              <span class="label label-success">{{$notifyMenu->count()}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have {{$notifyMenu->count()}} {{trans('notifyMenu.notifies')}}</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" style="
    font-size: 12px;
">

@foreach ($notifyMenu as $notify)


@if ($notify->action == "create")
                  <li>
                    <a href="#" class="notifyMenuUIActionLink" data-ticketCode="{{$notify->ticket->code}}">
                      <i class="fa fa-tag text-aqua"></i> {{$notify->author->name}} {{trans('notifyMenu.create')}}
                    </a>
                  </li>
@elseif ($notify->action == "comment")
                  <li>
                    <a href="#" class="notifyMenuUIActionLink" data-ticketCode="{{$notify->ticket->code}}">
                      <i class="fa fa-comments text-purple"></i> {{$notify->author->name}} {{trans('notifyMenu.comment')}}

                    </a>
                  </li>
@elseif ($notify->action == "refer")
                  <li>
                    <a href="#" class="notifyMenuUIActionLink" data-ticketCode="{{$notify->ticket->code}}">
                      <i class="fa fa-share text-blue"></i> {{$notify->author->name}} {{trans('notifyMenu.refer')}}

                    </a>
                  </li>
@elseif ($notify->action == "lock")

                  <li>
                    <a href="#" class="notifyMenuUIActionLink" data-ticketCode="{{$notify->ticket->code}}">
                      <i class="fa fa-lock text-yellow"></i> {{$notify->author->name}} {{trans('notifyMenu.lock')}}

                    </a>
                  </li>
@elseif ($notify->action == "unlock")

                  <li>
                    <a href="#" class="notifyMenuUIActionLink" data-ticketCode="{{$notify->ticket->code}}">
                      <i class="fa fa-unlock text-maroon"></i> {{$notify->author->name}} {{trans('notifyMenu.unlock')}}

                    </a>
                  </li>  
@elseif ($notify->action == "ok")

                  <li>
                    <a href="#" class="notifyMenuUIActionLink" data-ticketCode="{{$notify->ticket->code}}">
                      <i class="fa fa-check-circle-o text-green"></i> {{$notify->author->name}} {{trans('notifyMenu.success')}}

                    </a>
                  </li>  

@elseif ($notify->action == "unok")

                  <li>
                    <a href="#" class="notifyMenuUIActionLink" data-ticketCode="{{$notify->ticket->code}}">
                      <i class="fa fa-check-circle-o text-green"></i> {{$notify->author->name}} {{trans('notifyMenu.noSuccess')}}

                    </a>
                  </li>   
@elseif ($notify->action == "unok")

                  <li>
                    <a href="#" class="notifyMenuUIActionLink" data-ticketCode="{{$notify->ticket->code}}">
                      <i class="fa fa-close text-red"></i> {{$notify->author->name}} {{trans('notifyMenu.noApprove')}}

                    </a>
                  </li> 
@elseif ($notify->action == "waitok")
                  <li>
                    <a href="#" class="notifyMenuUIActionLink" data-ticketCode="{{$notify->ticket->code}}">
                      <i class="fa fa-check-circle-o text-red"></i> {{$notify->author->name}} {{trans('notifyMenu.waitApprove')}}

                    </a>
                  </li> 
@elseif ($notify->action == "noapprove")
                  <li>
                    <a href="#" class="notifyMenuUIActionLink" data-ticketCode="{{$notify->ticket->code}}">
                      <i class="fa fa-circle-o text-red"></i> {{$notify->author->name}} {{trans('notifyMenu.noApproveSuccess')}}

                    </a>
                  </li> 
@elseif ($notify->action == "edit")
                  <li>
                    <a href="#" class="notifyMenuUIActionLink" data-ticketCode="{{$notify->ticket->code}}">
                      <i class="fa fa-pencil-square-o text-orange"></i> {{$notify->author->name}} {{trans('notifyMenu.edit')}}

                    </a>
                  </li> 

@elseif ($notify->action == "restore")

                  <li>
                    <a href="#" class="notifyMenuUIActionLink" data-ticketCode="{{$notify->ticket->code}}">
                    <i class="fa fa-recycle text-aqua"></i> {{$notify->author->name}} {{trans('notifyMenu.restore')}}

                    </a>
                  </li> 
                  @endif
@endforeach
                </ul>
              </li>
              <li class="footer"><a href="#">{{trans('notifyMenu.viewAll')}}</a></li>
            </ul>

            @endif