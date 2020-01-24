</head>

<body class="hold-transition skin-purple sidebar-mini @if (Session::has('sidebarMenuState')) sidebar-collapse @endif">
<div class="wrapper">

  <header class="main-header">

    <!-- Logo -->
    <a href="{!! URL::to('/') !!}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">{!! $SiteNameShort !!}</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img src="{{ $SiteLogo }}"> {!! $SiteName !!}</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">{{trans('layout.toggle')}}</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
<li class="dropdown notifications-menu">
@include("user.calendar.menu")
</li>


<li id="notificationMenuUI" class="dropdown notifications-menu">
@include("user.notification.menu")
          </li>



<li id="chatUIMenuReq" class="dropdown messages-menu">
@include("user.chat.menuReq")
</li>


@if (Session::has('returnToAdmin'))
          <li>
            <a href="#" id="returnToAdmin" class="btn btn-danger btn-xs" ><i class="fa fa-sign-out "></i> 
            Return to Admin Area</a>
          </li>
@endif



          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{!! $userImgSmall !!}" class="user-image" alt="User Image">
              <span class="hidden-xs">{!! $CurUser->name !!}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{!! $userImgSmall !!}" class="img-circle" alt="User Image">

                <p>
                  {!! $CurUser->name !!}
                  <small>{!! $CurUser->profile->position !!}</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-12 text-center">
                    <a href="{!! URL::to('/user/'.$CurUser->profile->user_urlhash) !!}"><small>{{trans('layout.myPage')}}</small></a>
                  </div>
                  <!--div class="col-xs-4 text-center">
                    <a href="#"><small>Подписки</small></a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#"><small>Подписчики</small></a>
                  </div-->
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{!! URL::to('/profile/edit') !!}" class="btn btn-default btn-flat">{{trans('layout.configs')}}</a>
                </div>
                <div class="pull-right">
                  <a href="{!! URL::to('/logout') !!}" class="btn btn-default btn-flat">{{trans('layout.logout')}}</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->



@if ($CurUser->roles->role != "client")
          <li>
            <a href="#" data-toggle="control-sidebar" class="toggleChatView"><i class="fa  fa-commenting "></i> 
            <span id="chatUIOfflineStatus" style="display: none;" class="label label-danger">offline</span>
             <span id="chatUITotalUnreadMsgs" class="label bg-orange" style="@if ($totalUnreadChatMsg == 0) display: none; @endif">{{$totalUnreadChatMsg}}</span>  </a>
          </li>
@else

          <li>
            <a href="#" data-toggle="control-sidebar" class="toggleChatView"><i class="fa  fa-headphones "></i> 
            <span id="chatUIOfflineStatus" style="display: none;" class="label label-danger">offline</span>
             <span id="chatUITotalUnreadMsgs" class="label bg-orange" style="@if ($totalUnreadChatMsg == 0) display: none; @endif">{{$totalUnreadChatMsg}}</span>  </a>
          </li>

@endif

        </ul>
      </div>

    </nav>
  </header>