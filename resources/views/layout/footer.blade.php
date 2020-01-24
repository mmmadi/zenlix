  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <small class="text-muted"><b>Version</b> {{config('app.zenlix_version')}}</small>
    </div>
    <small class="text-muted"><strong><a href="http://zenlix.com">ZENLIX</a> &copy; 2016.</strong> All rights
    reserved.
    </small>
  </footer>

@if ($CurUser->roles->role == "client")
@include("layout.msgbarClient")
@else
@include("layout.msgbar")
@endif
</div>
<!-- ./wrapper -->
<script type="text/javascript">
  

var CSRF_TOKEN='{!! csrf_token() !!}';
var SYS_URL='{!! URL::to('/'); !!}';

</script>
<!-- jQuery 2.1.4 -->
{!! Html::script('plugins/jQuery/jQuery-2.1.4.min.js?v='.config('app.zenlix_version')); !!}
<!-- jQuery UI 1.11.4 -->
<!-- jQuery 2.1.4 -->

{!! Html::script('dist/js/jquery-ui.min.js?v='.config('app.zenlix_version')); !!}
<!-- Bootstrap 3.3.5 -->
{!! Html::script('bootstrap/js/bootstrap.min.js?v='.config('app.zenlix_version')); !!}


{!! Html::script('plugins/pace/pace.min.js?v='.config('app.zenlix_version')); !!}



<!-- Select2 -->
{!! Html::script('plugins/select2/select2.full.min.js?v='.config('app.zenlix_version')); !!}

<!-- AdminLTE App -->
{!! Html::script('dist/js/app.min.js?v='.config('app.zenlix_version')); !!}

<!-- trumbowyg -->
{!! Html::script('plugins/trumbowyg/trumbowyg.min.js?v='.config('app.zenlix_version')); !!}


<!-- DataTables -->
{!! Html::script('plugins/datatables/media/js/jquery.dataTables.min.js?v='.config('app.zenlix_version')); !!}


{!! Html::script('plugins/datatables/media/js/dataTables.bootstrap.min.js?v='.config('app.zenlix_version')); !!}

{!! Html::script('plugins/autosize/autosize.min.js?v='.config('app.zenlix_version')); !!}

{!! Html::script('plugins/socket.io/socket.io-1.4.3.js?v='.config('app.zenlix_version')); !!}

{!! Html::script('plugins/toastr/toastr.min.js?v='.config('app.zenlix_version')); !!}
{!! Html::script('plugins/ionsound/ion.sound.min.js?v='.config('app.zenlix_version')); !!}

<!-- Page script -->
<script>
  $(document).ready(function() {


//$(document).ajaxStart(function() { Pace.restart(); });

    autosize($('textarea'));
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": true,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "0",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};


ion.sound({
    sounds: [
        {name: "glass"}
    ],

    // main config
    path: "{{URL::to('/plugins/ionsound/')}}/sounds/",
    preload: true,
    //multiplay: true,
    volume: 0.9
});




    //var socket = io.connect( '{!! URL::to('/'); !!}', {
    var socket = io.connect( '{{$WPURL}}', {
        "secure": true,
       // "reconnection": false,
        "reconnectionDelay": 1000,
        "reconnectionDelayMax": 1000,
        "reconnectionAttempts": 4
    });


    socket.emit('join', {
        uniq_id: '{{$CurUser->email}}'
    });

var NODEJS_STATUS='false';
//когда подключился!
socket.on("connect", function(){
    console.log('connected to PUSH-server');
    $("#statusNotifyWebPush").html('connected');
    NODEJS_STATUS='true';
    $("#chatUIOfflineStatus").hide();
    $("#chatUIofflineMSG").hide();
    //chatUIofflineMSG
    socket.emit('join', {
        uniq_id: '{{$CurUser->email}}'
    });
});

//пробую подключиться
socket.on("reconnecting", function(){
    console.log('reconnecting to PUSH-server');
    $("#statusNotifyWebPush").html('reconnecting ...');
});

//не смог подключиться
socket.on("reconnect_failed", function(){
    console.log('failed connect to PUSH-server');
    $("#statusNotifyWebPush").html('offline');
    NODEJS_STATUS='false';
    $("#chatUIOfflineStatus").show();
    $("#chatUIofflineMSG").show();
});




/*console.log('check 1', socket.connected);
socket.on('connect', function() {
  console.log('check 2', socket.connected);
});*/




    socket.on("webPush", function(data) {

      toastr["info"](data.message, data.title,{onclick: function() {window.location=data.url;}});
      ion.sound.play("glass");

    });

    socket.on("chatPush", function(data) {

      //console.log('new chat msg');
@if ($CurUser->roles->role != "client")

      showNewMessage(data.fromid, data.total, data.fromName, data.message);
@else
      showNewMessageClient(data.fromid, data.total, data.fromName, data.message);

@endif
/*message
from
total*/

    });


socket.on("chatReq", function(data) {

  updateChatReqMenu();
  ion.sound.play("glass");

});

socket.on("NotifyMenuMsg", function(data) {

  updateNotifyMenu();
  ion.sound.play("glass");

});



socket.on("chatReqAccept", function(data) {

  //console.log('ok!');
  openChatRequestClient(data.fromid);
  ion.sound.play("glass");

});
//chatClose
@if ($CurUser->roles->role == "client") 
socket.on("chatClose", function(data) {

  $("#chatUIClose").click();
  $("#chatUIsendRequest").removeClass('disabled').text('Отправить запрос');

});
@endif


if (NODEJS_STATUS=='false') {




}


@if ($CurUser->roles->role == "client") 

function openChatRequestClient(userID) {


            $('#chat-tabs li a[href=#control-sidebar-theme-demo-options-tab]').tab('show');
            $('#chatInputField').attr('data-toUser', userID);
            $('#chat-tabs').addClass('control-sidebar-open');
            chatWith(userID);
            $('#chatUIlichat').show();


/*$.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::to('/chat/AcceptRequest') }}",
                data: {
                  _token : CSRF_TOKEN,
                  user: userID
                },
                success: function(res) {}
});*/


}
@endif


function updateNotifyMenu() {


$.ajax({
                              type: "POST",
                              url: "{{URL::to('/updateNotifyMenu') }}",
                              data: {
                                _token : CSRF_TOKEN
                              },
                              success: function(html) {
                                 $("#notificationMenuUI").html(html);
                              }
                        });


}


function updateChatReqMenu() {


$.ajax({
                              type: "POST",
                              url: "{{URL::to('/chat/updateReqMenu') }}",
                              data: {
                                _token : CSRF_TOKEN
                              },
                              success: function(html) {
                                 $("#chatUIMenuReq").html(html);
                              }
                        });

}


function showNewMessageClient(toUserAttr, count, fromName, message) {
var isOpen=$("#chatUIlichat").attr('class');
var toUser=$("#chatInputField").attr('data-touser');
var curCount=$("#chatInputField").attr('data-totalMsg');
    var curCo=$('#chatUITotalUnreadMsgs').text();
    var className='#chatUIuser_'+toUserAttr;
if ($("#chat-tabs").hasClass('control-sidebar-open') == false) {

  toastr["info"](message, fromName, {onclick: function() {
      $("#chat-tabs").addClass('control-sidebar-open');
      $('#chatUIlichat').show();
      openChat(toUserAttr);
  }});

}


if (isOpen == 'active') {
  if (toUser == toUserAttr) {
    $('#chatUIlichat').show();
      chatWith(toUserAttr);
  }
  else {
      $('#chatUITotalUnreadMsgs').text(parseInt(curCo)+1).show();
      $('#chatUIlichat').show();
      $(className).show();
      chatWith(toUserAttr);
  }
}
else {
      $('#chatUITotalUnreadMsgs').text(parseInt(curCo)+1).show();
      $('#chatUIlichat').show();
      $(className).show();
      chatWith(toUserAttr);
}


}


function showNewMessage(toUserAttr, count, fromName, message) {


var isOpen=$("#chatUIlichat").attr('class');
var toUser=$("#chatInputField").attr('data-touser');
var curCount=$("#chatInputField").attr('data-totalMsg');
//console.log(toUserAttr);
//if (isOpen == 'active') {
  //console.log('fired');
    var curCo=$('#chatUITotalUnreadMsgs').text();
    var className='#chatUIuser_'+toUserAttr;

if ($("#chat-tabs").hasClass('control-sidebar-open') == false) {

  toastr["info"](message, fromName, {onclick: function() {
      $("#chat-tabs").addClass('control-sidebar-open');
      openChat(toUserAttr);
  }});

}



if (isOpen == 'active') {
  if (toUser == toUserAttr) {
      chatWith(toUserAttr);
  }
  else {
      $('#chatUITotalUnreadMsgs').text(parseInt(curCo)+1).show();
      $(className).show();
  }
}
else {
      $('#chatUITotalUnreadMsgs').text(parseInt(curCo)+1).show();
      $(className).show();
}


  //если открыто то окно?
/*  if (toUser == toUserAttr) {
      chatWith(toUserAttr);
  }
  else {
    //var curCo=$('#chatUITotalUnreadMsgs').text();
    //var className='#chatUIuser_'+toUserAttr;
      $('#chatUITotalUnreadMsgs').text(parseInt(curCo)+1).show();
      $(className).show();
  }*/
//}

}

/*toastr["info"]("Пользователь Иванов Иван Иванович создал новую заявку.", "создана новая заявка",{onclick: function() {console.log('you clicked on the info toaster n.1')}});*/

        $.ajaxSetup({
        // Disable caching of AJAX responses
        cache: false,
        headers: { 'X-CSRF-TOKEN' : CSRF_TOKEN }
    });


    //Initialize Select2 Elements
    $(".select2").select2({
        allowClear: true
    });



$('[rel=tooltip]').tooltip({container: 'body'});


$(".pover").popover({
    title: 'Заголовок панели',
    content: 'Текст панели',
    trigger: 'hover',
    placement: 'right',
    container: 'body'
  });


    $('.trumbowyg').trumbowyg({
    mobile: true,
    tablet: true,
    removeformatPasted: true,
    btns: ['formatting',
      '|', 'btnGrp-design',
      '|','justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull',
      '|', 'link',
      '|', 'btnGrp-lists',
      '|', 'horizontalRule']
});


//notifyMenuUIActionLink
$('body').on('click', '.notifyMenuUIActionLink', function(event) {
            event.preventDefault();
            var code=$(this).attr('data-ticketCode');

                        $.ajax({
                              type: "POST",
                              url: "{{URL::to('/notifyMenu/read') }}",
                              //dataType: "json",
                              data: {
                                _token : CSRF_TOKEN,
                                //_method: 'PATCH',
                                code: code
                              },
                              success: function(html) {
                                 

                                    window.location = '{{URL::to('/ticket/')}}/'+code;
                              }
                        });


            


          });


$('body').on('click', '.toggleChatView', function(event) {
            event.preventDefault();

                        $.ajax({
                              type: "POST",
                              url: "{{URL::to('/chat/toggle') }}",
                              //dataType: "json",
                              data: {
                                _token : CSRF_TOKEN
                                //_method: 'PATCH',
                                //toUser: userMail
                              },
                              success: function(html) {
                                 

                                    //$("#watching-panel").hide().html(html).fadeIn(500);
                              }
                        });

          });



$('body').on('click', '.sidebar-toggle', function(event) {
            event.preventDefault();

                        $.ajax({
                              type: "POST",
                              url: "{{URL::to('/sidebar/toggle') }}",
                              //dataType: "json",
                              data: {
                                _token : CSRF_TOKEN
                                //_method: 'PATCH',
                                //toUser: userMail
                              },
                              success: function(html) {
                                 

                                    //$("#watching-panel").hide().html(html).fadeIn(500);
                              }
                        });

          });




function openChat(userID) {


            $('#chat-tabs li a[href=#control-sidebar-theme-demo-options-tab]').tab('show');
            $('#chatInputField').attr('data-toUser', userID);

            chatWith(userID);
            $('#chatUIlichat').show();
            var className='#chatUIuser_'+userID;
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::to('/chat/setAsRead') }}",
                data: {
                  _token : CSRF_TOKEN,
                  user: userID
                },
                success: function(res) {
                  $.each(res, function(i, item) {
                      $("#chatUITotalUnreadMsgs").text(item.countTotalUnread);
                      if ( item.countTotalUnread == "0") {
                        $("#chatUITotalUnreadMsgs").hide();
                      }
                  });
                  $(className).hide();

                  
                    //makemytime(true);
                }
            });

}



$('body').on('click', '.chatUserSelect', function(event) {
            event.preventDefault();

            //chatWith($(this).attr('data-toUser'));
openChat($(this).attr('data-toUser'));

          });



//chatUIClose
$('body').on('click', '#chatUIClose', function(event) {
            event.preventDefault();
            var userID = $('#chatInputField').attr('data-toUser');
            $('#chat-tabs li a[href=#control-sidebar-home-tab]').tab('show');
            $('#chatInputField').attr('data-toUser', '');
            $('#chatUIlichat').hide();

            $.ajax({
                type: "POST",
                url: "{{URL::to('/chat/closeCurrent') }}",
                data: {
                  _token : CSRF_TOKEN,
                  user : userID
                },
                success: function() {
                    //makemytime(true);
                }
            });

            ///chat/closeCurrent

          });


function UpdateOnline() {
              $.ajax({
                type: "POST",
                url: "{{URL::to('/online') }}",
                data: {
                  _token : CSRF_TOKEN,
                },
                success: function() {
                    //makemytime(true);
                }
            });
}

    function update_status_time() {
        setInterval(function() {
UpdateOnline();
        }, 300000);
    };

UpdateOnline();
update_status_time();




//chatUserSelectRequest
@if ($CurUser->roles->role != "client") 

function openChatRequest(userID) {


            $('#chat-tabs li a[href=#control-sidebar-theme-demo-options-tab]').tab('show');
            $('#chatInputField').attr('data-toUser', userID);
            $('#chat-tabs').addClass('control-sidebar-open');
            chatWith(userID);
            $('#chatUIlichat').show();


$.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::to('/chat/AcceptRequest') }}",
                data: {
                  _token : CSRF_TOKEN,
                  user: userID
                },
                success: function(res) {}
});


}


$('body').on('click', '.chatUserSelectRequest', function(event) {
            event.preventDefault();
            //chatWith($(this).attr('data-toUser'));
openChatRequest($(this).attr('data-toUser'));

          });


@endif


//chatUIsendResponce
$('body').on('click', 'a#chatUIsendRequest', function(event) {
            event.preventDefault();

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::to('/chat/sendRequest') }}",
                data: {
                  _token : CSRF_TOKEN,
                },
                success: function(res) {
                  $.each(res, function(i, item) {

                      $("a#chatUIsendRequest").addClass('disabled').text(item.msg);

                  });
                    //makemytime(true);
                }
            });

            


          });

function chatWith(userMail) {

                        $.ajax({
                              type: "GET",
                              url: "{{URL::to('/chat/get') }}",
                              dataType: "json",
                              data: {
                                _token : CSRF_TOKEN,
                                //_method: 'PATCH',
                                toUser: userMail
                              },
                              success: function(res) {

                                $.each(res, function(i, item) {
                                 //$('#chatContent').html(html);
                                 $('#chatContent').html(item.html);
                                 $('#chatContent').scrollTop($('#chatContent')[0].scrollHeight);
                                 //$('#chatWithName').text(userName);

                                 $('#chatWithName').text(item.userName);
                                 $('#chatInputField').attr('data-totalMsg', item.totalMsg);
                                    });
                              }
                        });

}


@if (Session::has('chatWith')) 

            $('#chat-tabs li a[href=#control-sidebar-theme-demo-options-tab]').tab('show');
            $('#chatInputField').attr('data-toUser', '{{Session::get('chatWith')}}');
            name='{{Session::get('chatWithName')}}';

            chatWith('{{Session::get('chatWith')}}');
@endif



@if (Session::has('returnToAdmin'))
$('body').on('click', 'a#returnToAdmin', function(event) {
            event.preventDefault();

            //var elID=$(this).attr('data-id');
            $.ajax({
                    type: "POST",
                    url: "{{URL::to('/admin/users/loginAsAdmin') }}",
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      //userid: elID,
                      _method: 'PATCH'
                      
                    },
                    success: function(html) {
                      window.location="{{URL::to('/dashboard')}}";
                    }
                  });
          });
@endif



//chatInputField
$('input#chatInputField').bind('keydown', function(e) {
            if (((e.metaKey || e.ctrlKey) && e.keyCode == 13) || e.keyCode == 13) {
                text=$(this).val();
             if (text.replace(/ /g, '').length > 1) {
                $("input#chatInputField").popover('hide');
                $("#chatInputFieldForMsg").removeClass('has-error');

              toUser=$(this).attr('data-toUser');
              
                        $.ajax({
                              type: "POST",
                              url: "{{URL::to('/chat/send') }}",
                              //dataType: "json",
                              data: {
                                _token : CSRF_TOKEN,
                                //_method: 'PATCH',
                                toUser: toUser,
                                text: text
                              },
                              success: function(html) {
                                 $('input#chatInputField').val('');
                                 chatWith(toUser);

                                    //$("#watching-panel").hide().html(html).fadeIn(500);
                              }
                        });

              }
              else {
                $("input#chatInputField").popover('show');
                $("#chatInputFieldForMsg").addClass('has-error');
                setTimeout(function() {
                    $("input#chatInputField").popover('hide');
                }, 2000);
              }

                //console.log('fired');
            }
        });


});
</script>    

