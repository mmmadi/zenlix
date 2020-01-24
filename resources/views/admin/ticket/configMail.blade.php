@include("layout.header")
{!! Html::style('plugins/iCheck/square/blue.css'); !!}


@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('at.ticketPerfs')}}
    <small>{{trans('at.mailFetching')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="">{{trans('at.ticketPerfs')}}</li>
        <li class="active">{{trans('at.mailFetching')}}</li>
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
                    <h3 class="box-title"><i class="fa fa-list-alt"></i> {{trans('at.conf')}}</h3>
                </div>





                <div class="box-body">





{!! Form::open(array('action' => 'ConfigTicketController@updateTicketMail', 'method'=> 'POST', 'class'=>'form-horizontal')) !!}




                    <div class="form-group">
                    {!! Form::label('status', 'Статус', array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('status', ['true'=>trans('at.active'), 'false'=>trans('at.no')], [Setting::get('ticket.ReceiveMail.status')], array('class'=>'form-control select2')) !!}
                    </div>
                    </div>


                    <div class="form-group">
                    {!! Form::label('receiveAnon', trans('at.fetchAnon'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('receiveAnon', ['true'=>trans('at.active'), 'false'=>trans('at.no')], [Setting::get('ticket.ReceiveMail.receiveAnon')], array('class'=>'form-control select2')) !!}
                    </div>
                    </div>









                    <div class="form-group @if ($errors->has('AuthMail')) has-error @endif">
                    {!! Form::label('AuthMail', trans('at.email'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('AuthMail', Setting::get('ticket.ReceiveMail.AuthMail'), array('class'=>'form-control')) !!}
                    @if ($errors->has('AuthMail')) <p class="help-block">{{ $errors->first('AuthMail') }}</p> @endif
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('AuthAddr')) has-error @endif">
                    {!! Form::label('AuthAddr', trans('at.adr'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-4">
                    {!! Form::text('AuthAddr', Setting::get('ticket.ReceiveMail.AuthAddr'), array('class'=>'form-control')) !!}
                    @if ($errors->has('AuthAddr')) <p class="help-block">{{ $errors->first('AuthAddr') }}</p> @endif
                    </div>
                    <div class="col-sm-1 control-label">
                    {{trans('at.port')}}
                    </div>
                    <div class="col-sm-4">
                      {!! Form::text('AuthPort', Setting::get('ticket.ReceiveMail.AuthPort'), array('class'=>'form-control')) !!}
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('AuthLogin')) has-error @endif">
                    {!! Form::label('AuthLogin', trans('at.login'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('AuthLogin', Setting::get('ticket.ReceiveMail.AuthLogin'), array('class'=>'form-control')) !!}
                    @if ($errors->has('AuthLogin')) <p class="help-block">{{ $errors->first('AuthLogin') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('AuthPass')) has-error @endif">
                    {!! Form::label('AuthPass', trans('at.pass'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::password('AuthPass', array('class'=>'form-control')) !!}
                    @if ($errors->has('AuthPass')) <p class="help-block">{{ $errors->first('AuthPass') }}</p> @endif
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('ServerDirectory')) has-error @endif">
                    {!! Form::label('ServerDirectory', trans('at.catalog'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('ServerDirectory', Setting::get('ticket.ReceiveMail.ServerDirectory'), array('class'=>'form-control')) !!}
                    @if ($errors->has('ServerDirectory')) <p class="help-block">{{ $errors->first('ServerDirectory') }}</p> @endif
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('AuthSecurity')) has-error @endif">
                    {!! Form::label('AuthSecurity', trans('at.security'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('AuthSecurity', Setting::get('ticket.ReceiveMail.AuthSecurity'), array('class'=>'form-control')) !!}
                    @if ($errors->has('AuthSecurity')) <p class="help-block">{{ $errors->first('AuthSecurity') }}</p> @endif

<pre><small>/pop3                     - for POP3 protocol connection
/imap/ssl                 - for IMAP secure connection
/pop3/ssl/novalidate-cert - POP3 with self-signed cert
/ssl                      - use the Secure Socket Layer to encrypt the session
/validate-cert            - validate certificates from TLS/SSL server (this is the default behavior)
/novalidate-cert          - do not validate certificates from TLS/SSL server, needed if server uses self-signed certificates
/tls                      - force use of start-TLS to encrypt the session, and reject connection to servers that do not support it
/nntp                     - NNTP protocol connection
</small></pre>


                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('filter')) has-error @endif">
                    {!! Form::label('filter', trans('at.filter'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('filter', Setting::get('ticket.ReceiveMail.filter'), array('class'=>'form-control')) !!}
                    @if ($errors->has('filter')) <p class="help-block">{{ $errors->first('filter') }}</p> @endif


<pre><small>ALL - return all mails matching the rest of the criteria
ANSWERED - match mails with the \\ANSWERED flag set
BCC "string" - match mails with "string" in the Bcc: field
BEFORE "date" - match mails with Date: before "date"
BODY "string" - match mails with "string" in the body of the mail
CC "string" - match mails with "string" in the Cc: field
DELETED - match deleted mails
FLAGGED - match mails with the \\FLAGGED (sometimes referred to as Important or Urgent) flag set
FROM "string" - match mails with "string" in the From: field
KEYWORD "string" - match mails with "string" as a keyword
NEW - match new mails
OLD - match old mails
ON "date" - match mails with Date: matching "date"
RECENT - match mails with the \\RECENT flag set
SEEN - match mails that have been read (the \\SEEN flag is set)
SINCE "date" - match mails with Date: after "date"
SUBJECT "string" - match mails with "string" in the Subject:
TEXT "string" - match mails with text "string"
TO "string" - match mails with "string" in the To:
UNANSWERED - match mails that have not been answered
UNDELETED - match mails that are not deleted
UNFLAGGED - match mails that are not flagged
UNKEYWORD "string" - match mails that do not have the keyword "string"
UNSEEN - match mails which have not been read yet
</small></pre>


                    </div>
                    </div>


                    <div class="form-group">
                    {!! Form::label('test', trans('at.test'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    <button type="button" class="btn btn-default btn-sm btn-flat" id="testBtnFM">{{trans('at.testConnect')}}</button>
                    <p class="help-block"><small>{{trans('at.saveBeforeTest')}}</small></p>
                    <div id="testResFM"></div>
                    </div>
                    </div>



                    <hr>

   <div class="form-group @if ($errors->has('targetGroup')) has-error @endif" >

<label for='unit' class="col-md-3 control-label" >
{{trans('at.to')}}
</label>
<div class="col-md-5">
{!! Form::select('targetGroup', $tG, [Setting::get('ticket.ReceiveMail.targetGroup')], array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('at.selGroup'))) !!}
                @if ($errors->has('targetGroup')) <p class="help-block">{{ $errors->first('targetGroup') }}</p> @endif
</div>
<div class="col-md-4">

{!! Form::select('targetUsers[]', $tU, explode(',', Setting::get('ticket.ReceiveMail.targetUsers')), array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('at.orUsers'), 'multiple'=>'multiple')) !!}
                
                
</div>
              </div>





   <div class="form-group @if ($errors->has('clients')) has-error @endif" >

<label for='unit' class="col-md-3 control-label" >
{{trans('at.clients')}}
</label>
<div class="col-md-9">

{!! Form::select('clients[]', $tU, explode(',', Setting::get('ticket.ReceiveMail.clients')), array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('at.orUsers'), 'multiple'=>'multiple')) !!}
                
                
</div>
              </div>

   <div class="form-group @if ($errors->has('watching')) has-error @endif" >

<label for='unit' class="col-md-3 control-label" >
{{trans('at.watching')}}
</label>
<div class="col-md-9">

{!! Form::select('watching[]', $tU, explode(',', Setting::get('ticket.ReceiveMail.watching')), array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('at.orUsers'), 'multiple'=>'multiple')) !!}
                
                
</div>
              </div>

<div class="form-group">

<label for='unit' class="col-md-3 control-label">
Тэги
</label>

<div class="col-md-9">
{!! Form::select('tags[]', explode(',', Setting::get('ticket.ReceiveMail.tags')), explode(',', Setting::get('ticket.ReceiveMail.tags')), array('class'=>'form-control input-sm select2-tags', 'style'=>'width: 100%', 'data-placeholder'=>'Тэги', 'multiple'=>'multiple')) !!}

                
</div>
              </div>



<div class="form-group">
                      <label for="upload_files" class="col-sm-3 control-label">{{trans('at.uploadFiles')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('upload_files', ['true'=>trans('at.active'), 'false'=>trans('at.noActive')], [Setting::get('ticket.ReceiveMail.upload_files')], array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

                    <div class="form-group @if ($errors->has('upload_files_types')) has-error @endif">
                    {!! Form::label('upload_files_types', trans('at.fileTypes'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">


{!! Form::select('upload_files_types[]', ['jpeg'=>'jpeg','bmp'=>'bmp','png'=>'png','pdf'=>'pdf','doc'=>'doc','docx'=>'docx'], explode(',', Setting::get('ticket.ReceiveMail.upload_files_types')), array('class'=>'form-control input-sm select2-tags', 'style'=>'width: 100%', 'data-placeholder'=>trans('at.fileTypes'), 'multiple'=>'multiple')) !!}

                    @if ($errors->has('upload_files_types')) <p class="help-block">{{ $errors->first('upload_files_types') }}</p> @endif
                    </div>
                    </div>


                  <div class="form-group @if ($errors->has('upload_files_count')) has-error @endif">
                    {!! Form::label('upload_files_count', trans('at.fileCounts'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('upload_files_count', Setting::get('ticket.ReceiveMail.upload_files_count'), array('class'=>'form-control')) !!}
                    @if ($errors->has('upload_files_count')) <p class="help-block">{{ $errors->first('upload_files_count') }}</p> @endif
                    </div>
                    </div>

                  <div class="form-group @if ($errors->has('upload_files_size')) has-error @endif">
                    {!! Form::label('upload_files_size', trans('at.fileLimits'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('upload_files_size', Setting::get('ticket.ReceiveMail.upload_files_size'), array('class'=>'form-control')) !!}
                    @if ($errors->has('upload_files_size')) <p class="help-block">{{ $errors->first('upload_files_size') }}</p> @endif
                    </div>
                    </div>





                
<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('at.save'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
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
{!! Html::script('plugins/iCheck/icheck.min.js'); !!}
<!-- page script -->
<script>
  $(function () {

$(".select2-tags").select2({
tags: true,
tokenSeparators: [',', ' ']

});

$('body').on('click', 'button#testBtnFM', function(event) {
            event.preventDefault();
            
            $("#testResFM").html('Connecting...');
$.ajax({
                    type: "POST",
                    url: "{{URL::to('/admin/ticket/mail/test') }}",
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'POST'
                    },
                    success: function(html) {
                        $("#testResFM").html(html);
                      //window.location = "{{URL::to('/ticket/list')}}";

                    }
                  });
              });  



        $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>