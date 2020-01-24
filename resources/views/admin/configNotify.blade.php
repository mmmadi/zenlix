@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('ac.systemConfig')}}
    <small>{{trans('ac.systemNotify')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li >{{trans('ac.systemConfig')}}</li>
        <li class="active">{{trans('ac.systemNotify')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-9">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->


                        


            <div class="box">



                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-alt"></i> {{trans('ac.perfNotify')}}</h3>
                </div>





                <div class="box-body">





{!! Form::open(array('action' => 'ConfigSystemController@updateNotify', 'method'=> 'POST', 'class'=>'form-horizontal')) !!}


<em>{{trans('ac.notifyMail')}}</em>

                    <div class="form-group">
                    {!! Form::label('mailStatus', trans('ac.status'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('mailStatus', ['true'=>trans('ac.active'), 'false'=>trans('ac.no')], Setting::get('mailStatus'), array('class'=>'form-control select2')) !!}
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('mailFromMail')) has-error @endif">
                    {!! Form::label('mailFromMail', trans('ac.emailSender'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('mailFromMail', $mailFromMail, array('class'=>'form-control')) !!}
                    @if ($errors->has('mailFromMail')) <p class="help-block">{{ $errors->first('mailFromMail') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('mailFromName')) has-error @endif">
                    {!! Form::label('mailFromName', trans('ac.nameSender'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('mailFromName', $mailFromName, array('class'=>'form-control')) !!}
                    @if ($errors->has('mailFromName')) <p class="help-block">{{ $errors->first('mailFromName') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('mailType', trans('ac.type'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('mailType', ['smtp'=>'smtp','sendmail'=>'sendmail','mailgun'=>'mailgun','mandrill'=>'mandrill'], config('mail.driver'), array('class'=>'form-control select2')) !!}
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('mailAddress')) has-error @endif">
                    {!! Form::label('mailAddress', trans('ac.adr'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-4">
                    {!! Form::text('mailAddress', config('mail.host'), array('class'=>'form-control')) !!}
                    @if ($errors->has('mailAddress')) <p class="help-block">{{ $errors->first('mailAddress') }}</p> @endif
                    </div>
                    <div class="col-sm-1 control-label">
                    {{trans('ac.port')}}
                    </div>
                    <div class="col-sm-4">
                      {!! Form::text('mailPort', config('mail.port'), array('class'=>'form-control')) !!}
                    </div>
                    </div>


                    <div class="form-group">
                    {!! Form::label('mailSecurity', trans('ac.security'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('mailSecurity', ['false'=>trans('ac.no'),'ssl'=>'ssl','tls'=>'tls'], config('mail.encryption'), array('class'=>'form-control select2')) !!}
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('mailLogin')) has-error @endif">
                    {!! Form::label('mailLogin', trans('ac.login'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('mailLogin', config('mail.username'), array('class'=>'form-control')) !!}
                    @if ($errors->has('mailLogin')) <p class="help-block">{{ $errors->first('mailLogin') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('mailPass')) has-error @endif">
                    {!! Form::label('mailPass', trans('ac.pass'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::password('mailPass', array('class'=>'form-control')) !!}
                    @if ($errors->has('mailPass')) <p class="help-block">{{ $errors->first('mailPass') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('test', trans('ac.test'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
<div class="input-group input-group-sm">
                <input type="text" class="form-control" placeholder="Ваш email" id="testValMail">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-default btn-flat" id="testBtnMail">{{trans('ac.sendMessage')}}</button>
                    </span>
              </div>
                    <p class="help-block"><small>{{trans('ac.saveBeforeTest')}}</small></p>
                    <div id="testResMail"></div>
                    </div>
                    </div>


<hr>
<em>{{trans('ac.notifySMS')}}</em>

                    <div class="form-group">
                    {!! Form::label('smsStatus', trans('ac.status'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('smsStatus', ['true'=>trans('ac.active'), 'false'=>trans('ac.no')], Setting::get('smsStatus'), array('class'=>'form-control select2')) !!}
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('smsLogin')) has-error @endif">
                    {!! Form::label('smsLogin', trans('ac.login'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('smsLogin', Setting::get('smsLogin'), array('class'=>'form-control')) !!}
                    @if ($errors->has('smsLogin')) <p class="help-block">{{ $errors->first('smsLogin') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('smsPassword')) has-error @endif">
                    {!! Form::label('smsPassword', trans('ac.pass'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::password('smsPassword', array('class'=>'form-control')) !!}
                    @if ($errors->has('smsPassword')) <p class="help-block">{{ $errors->first('smsPassword') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('smsAccess', trans('ac.allowNotify'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('smsAccess[]', $smsNotify, $smsNotifySel, array('class'=>'form-control select2', 'multiple')) !!}
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('test', trans('ac.test'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
<div class="input-group input-group-sm">
                <input type="text" class="form-control" placeholder="Ваш тел в формате +3805...." id="testValSMS">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-default btn-flat" id="testBtnSMS">{{trans('ac.sendMessage')}}</button>
                    </span>
              </div>
                    <p class="help-block"><small>{{trans('ac.saveBeforeTest')}}</small></p>
                    <div id="testResSMS"></div>
                    </div>
                    </div>

<hr>
<em>{{trans('ac.notifyPB')}}</em>

                    <div class="form-group">
                    {!! Form::label('pbStatus', trans('ac.status'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('pbStatus', ['true'=>trans('ac.active'), 'false'=>trans('ac.no')], Setting::get('pbStatus'), array('class'=>'form-control select2')) !!}
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('pbKey')) has-error @endif">
                    {!! Form::label('pbKey', trans('ac.apiKey'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('pbKey', $pbKeyVal, array('class'=>'form-control')) !!}
                    @if ($errors->has('pbKey')) <p class="help-block">{{ $errors->first('pbKey') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('test', trans('ac.test'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
<div class="input-group input-group-sm">
                <input type="text" class="form-control" placeholder="Ваш адрес pushbullet" id="testValPB">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-default btn-flat" id="testBtnPB">{{trans('ac.sendMessage')}}</button>
                    </span>
              </div>
              <p class="help-block"><small>{{trans('ac.saveBeforeTest')}}</small></p>
              <div id="testResPB"></div>
                    
                    </div>
                    </div>

<hr>
<em>{{trans('ac.notifyWP')}}</em>
                    <div class="form-group @if ($errors->has('WPURL')) has-error @endif">
                    {!! Form::label('WPURL', trans('ac.URL'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('WPURL', Setting::get('WPURL'), array('class'=>'form-control')) !!}
                    @if ($errors->has('WPURL')) <p class="help-block">{{ $errors->first('WPURL') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('WPPORT')) has-error @endif">
                    {!! Form::label('WPPORT', trans('ac.portOnServer'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('WPPORT', $WPPORT, array('class'=>'form-control')) !!}
                    @if ($errors->has('WPPORT')) <p class="help-block">{{ $errors->first('WPPORT') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('WPPORT', trans('ac.test'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    <button type="button" class="btn btn-default btn-sm btn-flat" id="testBtnWP">{{trans('ac.sendMessage')}}</button>
                    <p class="help-block"><small>{{trans('ac.saveBeforeTest')}}</small></p>
                    <div id="testResWP"></div>
                    </div>
                    </div>


<div class="form-group">
{!! Form::label('status', trans('ac.status'), array('class'=>'col-sm-3 control-label')) !!}
<div class="col-sm-9 control-label">
<div id="statusNotifyWebPush" class="pull-left"></div>
</div>
</div>

                
<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('ac.save'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
</div>
</div>


{!! Form::close(); !!}


            </div>




                    </div><!-- /.box-body -->
                    </div><!-- /.box -->



<div class="col-md-3">


</div>


                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
<!-- page script -->
<script>
  $(function () {



$('body').on('click', 'button#testBtnMail', function(event) {
            event.preventDefault();
            var val=$("#testValMail").val();
            $("#testResMail").html('Sending test mail...');
$.ajax({
                    type: "POST",
                    url: "{{URL::to('/admin/config/notify/testMail') }}",
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'POST',
                      val: val
                    },
                    success: function(html) {
                        $("#testResMail").html(html);
                      //window.location = "{{URL::to('/ticket/list')}}";

                    }
                  });
              });
          
$('body').on('click', 'button#testBtnSMS', function(event) {
            event.preventDefault();
            var val=$("#testValSMS").val();
            $("#testResSMS").html('Sending test sms...');
$.ajax({
                    type: "POST",
                    url: "{{URL::to('/admin/config/notify/testSMS') }}",
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'POST',
                      val: val
                    },
                    success: function(html) {
                        $("#testResSMS").html(html);
                      //window.location = "{{URL::to('/ticket/list')}}";

                    }
                  });
              });          

$('body').on('click', 'button#testBtnPB', function(event) {
            event.preventDefault();
            var val=$("#testValPB").val();
            $("#testResPB").html('Sending test Pushbullett message...');
$.ajax({
                    type: "POST",
                    url: "{{URL::to('/admin/config/notify/testPB') }}",
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'POST',
                      val: val
                    },
                    success: function(html) {
                        $("#testResPB").html(html);
                      //window.location = "{{URL::to('/ticket/list')}}";

                    }
                  });
              });  

$('body').on('click', 'button#testBtnWP', function(event) {
            event.preventDefault();
            
            $("#testResWP").html('Sending test web push...');
$.ajax({
                    type: "POST",
                    url: "{{URL::to('/admin/config/notify/testWP') }}",
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'POST'
                    },
                    success: function(html) {
                        $("#testResWP").html(html);
                      //window.location = "{{URL::to('/ticket/list')}}";

                    }
                  });
              });  

/*

testValMail
testBtnMail
testResMail

testValSMS
testBtnSMS
testResSMS

testValPB
testBtnPB
testResPB

testBtnWP
testResWP

*/


/*socket.on("connect", function(){
    console.log('connected to PUSH-server');
});

//пробую подключиться
socket.on("reconnecting", function(){
    console.log('reconnecting to PUSH-server');
});

//не смог подключиться
socket.on("reconnect_failed", function(){
    console.log('failed connect to PUSH-server');
});*/


    $(".select2").select2({
        allowClear: false
    });


  });
</script>
</body>
</html>