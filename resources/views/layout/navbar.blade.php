<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{!! $userImgSmall !!}" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>{!! $CurUser->name !!}
        </p>
        <a style="color: gray;"> {!! LocalizedCarbon::now()->formatLocalized('%e %f %Y, %H:%M') !!} </a>
      </div>
    </div>
    <!-- search form -->

@if($CurUser->roles->role != "client")

    <form action="{!! URL::to('/search') !!}" method="get" class="sidebar-form">
      <div class="input-group pover" data-html="true" data-toggle="popover" data-trigger="focus" title="{{trans('layout.tags')}}" data-content="{{trans('layout.tagsHelp')}}">
        <input type="text" name="q" class="form-control" placeholder="{{trans('layout.find')}}"        >
        <span class="input-group-btn">
        <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
        </button>
        </span>
      </div>
    </form>

@endif

    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="header">{{trans('layout.nav')}}</li>
      <li {!! Request::is('dashboard') ? ' class="active"' : null !!} ><a href="{!! URL::to('/dashboard') !!}"><i class="fa fa-dashboard"></i> <span>{{trans('layout.dashboard')}}</span></a></li>
      <li class="treeview {!! Request::is('ticket/*') ? ' active' : null !!} {!! Request::is('ticket/list/*') ? ' active' : null !!} {!! Request::is('tickets') ? ' active' : null !!}">
        <a href="#">
        <i class="fa fa-tag"></i> <span>{{trans('layout.tickets')}}</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu ">
          <li {!! Request::is('ticket/create') ? ' class="active"' : null !!} ><a href="{!! URL::to('/ticket/create') !!}"><i class="fa fa-tag"></i> <span>{{trans('layout.ticketCreate')}}</span></a></li>
        @if(($CurUser->roles->role == "admin") || ($CurUser->roles->role == "user"))

          <li {!! Request::is('ticket/list/*') ? ' class="active"' : null !!} ><a href="{!! URL::to('/ticket/list') !!}"><i class="fa fa-list-alt"></i> <span>{{trans('layout.ticketList')}}</span>
          @if ($ticketsInFree != 0) <small class="label pull-right bg-green">{{$ticketsInFree}}</small>@endif </a></li>
          @endif

@if($CurUser->roles->role == "client")
          <li {!! Request::is('tickets/*') ? ' class="active"' : null !!} ><a href="{!! URL::to('/tickets') !!}"><i class="fa fa-list-alt"></i> <span>{{trans('layout.ticketList2')}}</span>
          @if ($ticketsInFree != 0) <small class="label pull-right bg-green">{{$ticketsInFree}}</small>@endif </a></li>
@endif

@if($CurUser->roles->role == "admin")
          <li {!! Request::is('ticket/deleted') ? ' class="active"' : null !!} ><a href="{!! URL::to('/ticket/deleted') !!}"><i class="fa fa-trash"></i> <span>{{trans('layout.ticketDeleted')}}</span>
           </a></li>
@endif

@if($CurUser->roles->role != "client")
          <li {!! Request::is('ticket/planner') ? ' class="active"' : null !!} ><a href="{!! URL::to('/ticket/planner') !!}"><i class="fa fa-clock-o"></i> <span>{{trans('layout.ticketPlanner')}}</span>
           </a></li>
@endif


        </ul>
      </li>



      <li {!! Request::is('users') ? ' class="active"' : null !!} ><a href="{!! URL::to('/users') !!}"><i class="fa fa-users"></i> <span>{{trans('layout.users')}}</span></a></li>
      <li {!! Request::is('groups') ? ' class="active"' : null !!} ><a href="{!! URL::to('/groups') !!}"><i class="fa fa-folder"></i> <span>{{trans('layout.groups')}}</span></a></li>

      <li {!! Request::is('message') ? ' class="active"' : null !!} {!! Request::is('message/*') ? ' class="active"' : null !!}><a href="{!! URL::to('/message') !!}"><i class="fa fa-envelope"></i> <span>{{trans('layout.messages')}} @if ($messagesCount != 0)<small class="label pull-right bg-green messagesCount" >{{$messagesCount}}</small>@endif</span></a></li>

      <li {!! Request::is('help') ? ' class="active"' : null !!} ><a href="{!! URL::to('/help') !!}"><i class="fa fa-graduation-cap"></i> <span>{{trans('layout.helpcenter')}}</span></a></li>


      <li {!! Request::is('calendar') ? ' class="active"' : null !!} ><a href="{!! URL::to('/calendar') !!}"><i class="fa fa-calendar"></i> <span>{{trans('layout.calendar')}}</span></a></li>



@if($CurUser->roles->role != "client")
<li class="treeview  {!! Request::is('report/*') ? ' active' : null !!}">

        <a href="#">
        <i class="fa fa-pie-chart"></i> <span>{{trans('layout.reports')}}</span>
        <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu ">
            <li {!! Request::is('report/user') ? 'class="active"' : null !!}><a href="{!! URL::to('/report/user') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.reportUsers')}}</a></li>

            <li {!! Request::is('report/group') ? 'class="active"' : null !!}><a href="{!! URL::to('/report/group') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.reportGroups')}}</a></li>

        </ul>
</li>

@endif
      




@if($CurUser->roles->role == "admin")
<li class="treeview {!! Request::is('admin') ? ' active' : null !!} {!! Request::is('admin/*') ? ' active' : null !!}">


        <a href="#">
        <i class="fa fa-shield"></i> <span>{{trans('layout.admin')}}</span>
        <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu ">

          <li {!! Request::is('admin/config') ? 'class="active"' : null !!}  {!! Request::is('admin/config/*') ? 'class="active"' : null !!}>
          <a href="#"><i class="fa fa-circle-o"></i> {{trans('layout.perf')}} <i class="fa fa-angle-left pull-right"></i></a>

            <ul class="treeview-menu {!! Request::is('admin/config') ? ' active' : null !!} ">
              <li {!! Request::is('admin/config') ? 'class="active"' : null !!} ><a href="{!! URL::to('/admin/config') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.perfMain')}}</a></li>

              <li {!! Request::is('admin/config/auth') ? 'class="active"' : null !!} ><a href="{!! URL::to('/admin/config/auth') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.auth')}}</a></li>

              <li {!! Request::is('admin/config/notify') ? 'class="active"' : null !!} ><a href="{!! URL::to('/admin/config/notify') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.notify')}}</a></li>

              <li {!! Request::is('admin/config/license') ? 'class="active"' : null !!} ><a href="{!! URL::to('/admin/config/license') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.license')}}</a></li>

              <li {!! Request::is('admin/config/upgrade') ? 'class="active"' : null !!} ><a href="{!! URL::to('/admin/config/upgrade') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.updates')}}</a></li>

              <li {!! Request::is('admin/config/error_logs') ? 'class="active"' : null !!} ><a href="{!! URL::to('/admin/config/error_logs') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.logs')}}</a></li>

            </ul>



            <li {!! Request::is('admin/ticket') ? 'class="active"' : null !!} {!! Request::is('admin/ticket/*') ? 'class="active"' : null !!}><a href="#"><i class="fa fa-circle-o"></i> {{trans('layout.ticketSystem')}} <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">

          <li {!! Request::is('admin/ticket/config') ? 'class="active"' : null !!}><a href="{!! URL::to('/admin/ticket/config') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.ticketConf')}}</a></li>

                <li {!! Request::is('admin/ticket/forms') ? 'class="active"' : null !!}><a href="{!! URL::to('/admin/ticket/forms') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.ticketForm')}}</a></li>

                <li {!! Request::is('admin/ticket/sla') ? 'class="active"' : null !!}><a href="{!! URL::to('/admin/ticket/sla') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.sla')}}</a></li>
                <li {!! Request::is('admin/ticket/adv') ? 'class="active"' : null !!}><a href="{!! URL::to('/admin/ticket/adv') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.ticketAdv')}}</a></li>

<li {!! Request::is('admin/ticket/subj') ? 'class="active"' : null !!}><a href="{!! URL::to('/admin/ticket/subj') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.ticketSubj')}}</a></li>

                <li {!! Request::is('admin/ticket/mail') ? 'class="active"' : null !!}><a href="{!! URL::to('/admin/ticket/mail') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.ticketMail')}}</a></li>


              </ul></li>
              

            <li {!! Request::is('admin/users') ? 'class="active"' : null !!} {!! Request::is('admin/user/*') ? 'class="active"' : null !!} {!! Request::is('admin/users/*') ? 'class="active"' : null !!}>
              <a href="#"><i class="fa fa-circle-o"></i> {{trans('layout.adminUsers')}} <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li {!! Request::is('admin/user/create') ? 'class="active"' : null !!}><a href="{!! URL::to('/admin/user/create') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.usersAdd')}}</a></li>
                <li {!! Request::is('admin/users') ? 'class="active"' : null !!}><a href="{!! URL::to('/admin/users') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.usersList')}}</a></li>
                <li {!! Request::is('admin/users/import') ? 'class="active"' : null !!}><a href="{!! URL::to('/admin/users/import') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.usersImport')}}</a></li>
                <li {!! Request::is('admin/users/adv') ? 'class="active"' : null !!}><a href="{!! URL::to('/admin/users/adv') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.usersAdv')}}</a></li>
              </ul>
            </li>
            <li {!! Request::is('admin/groups') ? 'class="active"' : null !!} {!! Request::is('admin/groups/*') ? 'class="active"' : null !!}>
              <a href="{!! URL::to('/admin/groups') !!}"><i class="fa fa-circle-o"></i> {{trans('layout.userGroups')}}</a>
            </li>
          </li>
        </ul>
        </li>
@endif


      </section>
      <!-- /.sidebar -->
    </aside>