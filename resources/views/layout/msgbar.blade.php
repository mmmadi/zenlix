  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark @if (Session::has('chatStateView')) control-sidebar-open @endif" id="chat-tabs">
    <!-- Create the tabs -->
<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
          <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-users"></i>
          {{trans('layout.users')}} </a></li>


          <li id="chatUIlichat" class="" style="@if (Session::has('chatWith')) @else display:none; @endif"><a href="#control-sidebar-theme-demo-options-tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-comments"></i> {{trans('layout.chat')}}</a></li>


          
        </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading" style="margin-top: 0px;">{{trans('layout.online')}} ({{$onlineUsers->count()}})</h3>
        <ul class="control-sidebar-menu" style="    height: 500px;
    overflow: auto;     overflow-x: hidden;" id="chatOnlineUsers">



@include('user.chat.onlineUsers')
       
        </ul>
        <!-- /.control-sidebar-menu -->




      </div>
      <!-- /.tab-pane -->

 <div class="tab-pane" id="control-sidebar-theme-demo-options-tab">
        <h3 class="control-sidebar-heading" style="margin-top: 0px;">Чат с <span id="chatWithName"></span> 
        <span class="pull-right"> <button id="chatUIClose" style="opacity: 1; color: white;" type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> </span>
        </h3>
        <div class="" style="    margin: -10px;
    padding: 5px;
    background-color: rgb(255, 255, 255);
    border-radius: 5px;">
<div class="direct-chat-messages" style="    padding: 0px; height: 350px;" id="chatContent">
                    <!-- Message. Default to the left -->
                   

{{--                                        
@include('user.chat.chat')
 --}}
                  </div>
                  <div id="chatInputFieldForMsg" style="
    padding-top: 10px;
">
                    
                      <input type="text" name="message" id="chatInputField" data-toUser="" data-totalMsg="" placeholder="{{trans('layout.writemsg')}}" class="form-control input-sm" data-toggle="popover" data-html="true" data-trigger="manual" data-placement="bottom" data-content="<small>{{trans('layout.writeSomething')}}</small>">
                      
                    
                  </div>

                </div>

        </div>

      <!-- /.tab-pane -->
    </div>

  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>