@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  
{!! Html::style('plugins/iCheck/minimal/purple.css'); !!}
 {!! Html::style('plugins/dropzone/css/dropzone.css'); !!}
<style type="text/css">
  
  .select2-result-repository { padding-top: 4px; padding-bottom: 3px; }
.select2-result-repository__avatar { float: left; width: 60px; margin-right: 10px; }
.select2-result-repository__avatar img { width: 100%; height: auto; border-radius: 2px; }
.select2-result-repository__meta { margin-left: 70px; }
.select2-result-repository__title { color: black; font-weight: bold; word-wrap: break-word; line-height: 1.1; margin-bottom: 4px; }
.select2-result-repository__forks, .select2-result-repository__stargazers { margin-right: 1em; }
.select2-result-repository__forks, .select2-result-repository__stargazers, .select2-result-repository__watchers { display: inline-block; color: #aaa; font-size: 11px; }
.select2-result-repository__description { font-size: 13px; color: #777; margin-top: 4px; }
.select2-results__option--highlighted .select2-result-repository__title { color: white; }
.select2-results__option--highlighted .select2-result-repository__forks, .select2-results__option--highlighted .select2-result-repository__stargazers, .select2-results__option--highlighted .select2-result-repository__description, .select2-results__option--highlighted .select2-result-repository__watchers { color: #c6dcef; }



</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     <i class="fa fa-envelope"></i> {{trans('message.title')}}
    <small>{{trans('message.answering')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('message.title')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">

<div class="col-md-3">
  

<a href="{{URL::to('/message')}}" class="btn btn-primary btn-block margin-bottom">{{trans('message.backToMsg')}}</a>

@include('user.message.nav')

{{-- <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Labels</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div> --}}

</div>
<div class="col-md-9">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->

        

<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{trans('message.answering')}}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              
              

{!! Form::open(array('action' => 'MessageController@store', 'method'=> 'PATCH', 'class'=>'form-horizontal')) !!}

<div class="form-group @if ($errors->has('to')) has-error @endif">
<div class="col-md-12">
{!! Form::select('to', [$message->from_user_id=>$message->fromUser->name], $message->from_user_id, array('class'=>'form-control input-sm js-data-client', 'style'=>'width: 100%', 'data-placeholder'=>trans('message.to'))) !!}
@if ($errors->has('to')) <p class="help-block">{{ $errors->first('to') }}</p> @endif
</div>
</div>

<div class="form-group @if ($errors->has('subject')) has-error @endif">
<div class="col-md-12">
{!! Form::text('subject', 'Re: '.$message->subject, array('class'=>'form-control', 'style'=>'width: 100%', 'placeholder'=>trans('message.subj'))) !!}
@if ($errors->has('subject')) <p class="help-block">{{ $errors->first('subject') }}</p> @endif
</div>
</div>

<div class="form-group @if ($errors->has('text')) has-error @endif">
<div class="col-md-12">
{!! Form::textarea('text', $message->text, array('class'=>'form-control trumbowyg-help', 'id'=>'MsgBox')) !!}
@if ($errors->has('text')) <p class="help-block">{{ $errors->first('text') }}</p> @endif
</div>
</div>

<div class="col-md-12">

<div class="" style="background: #eee;
    display: block;
    margin: 5px 5px 10px 5px;
    min-height: 70px;
    overflow: hidden;
    position: relative;
    width: auto;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;">
                            
                            <div  style="cursor:pointer;">


<div class="dz-clickable" id="myid">

                                                                            <div class="dz-message" data-dz-message="">
                                                                               <div class="text-center m-t-md">
                                <h4 class="text-muted">{{trans('message.dropFiles')}}</h4>
                                <p class="text-muted">
                                    {{trans('message.dropOrClick')}}
                                </p>
                                </div>
                                                                            </div>
                                                                        
<div class="table table-striped" style="margin-bottom: 0px;" class="files" id="previews">

                                                                            <div id="template" class="file-row">



                                                                                <!-- This is used as the file preview template -->
<table class="table" style="margin-bottom: 0px; background-color: #E6E6E6;">
<tbody><tr>
<td style="width:50%"><p class="name" data-dz-name></p> </td>
<td><small class="text-muted"><p class="size" data-dz-size></p></small></td>
<td style="width:30%">

<div class="progress progress-xs" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                    <div style="width: 0%;" class="progress-bar" data-dz-uploadprogress></div>
                                </div>



</td>
<td class="pull-right"><button data-dz-remove class="btn btn-xs btn-danger delete">
<i class="glyphicon glyphicon-trash"></i>
<span>{{trans('message.delete')}}</span>
</button></td>
</tr>
</tbody></table>
                                                                            </div>
                                                                        </div>

                                                                    </div>




                                
                            </div>

                     
                        </div>

</div>
            <div class="">
              <div class="pull-right">
                


               
                {!! HTML::decode(Form::button('<i class=\'fa fa-envelope-o\'></i> '.trans('message.answer'), array('type' => 'submit', 'class'=>'btn btn-primary', 'value'=>'send', 'name'=>'action'))) !!}
              </div>
             <a href="{{URL::to('/message/new')}}" class="btn btn-default"><i class='fa fa-times'></i> {{trans('message.reset')}}</a>
             
            </div>

{!! Form::close(); !!}



              
            </div>
            <!-- /.box-body -->

            <!-- /.box-footer -->
          </div>





</div>

                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
{!! Html::script('plugins/trumbowyg/plugins/upload/trumbowyg.upload.js'); !!}
{!! Html::script('plugins/trumbowyg/plugins/base64/trumbowyg.base64.min.js'); !!}
{!! Html::script('plugins/dropzone/js/dropzone.js'); !!}
<!-- page script -->
<script>
  $(function () {
    
$('.trumbowyg-help').trumbowyg({
    btnsDef: {
        // Create a new dropdown
        image: {
            dropdown: ['insertImage', 'upload', 'base64'],
            ico: 'insertImage'
        }
    },
    // Redefine the button pane
    btns: ['viewHTML',
            '|', 'formatting',
            '|', 'btnGrp-semantic',
            '|', 'link',
            '|', 'image',
            '|', 'btnGrp-justify',
            '|', 'btnGrp-lists',
            '|', 'horizontalRule']
});



function formatRepo (repo) {
      if (repo.loading) return repo.text;

      var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'><img src='" + repo.img + "' /></div>" +
        "<div class='select2-result-repository__meta'>" +
          "<div class='select2-result-repository__title'>" + repo.name + "</div>";

      if (repo.id) {
        markup += "<div class='select2-result-repository__description'>" + repo.position + "</div>";
      }

      markup +=
      "</div>" +
      "</div></div>";

      return markup;
    }

    function formatRepoSelection (repo) {
      return repo.name || repo.text;
    }


$(".js-data-client").select2({
  allowClear: true,
  ajax: {
    url: "{{ URL::to('/message/users') }}",
    dataType: 'json',
    allowClear: true,
    placeholder: "Select an attribute",
    delay: 250,
    data: function (params) {
      return {
        q: params.term
      };
    },
    processResults: function (data, params) {
      // parse the results into the format expected by Select2
      // since we are using custom formatting functions we do not need to
      // alter the remote JSON data, except to indicate that infinite
      // scrolling can be used
      params.page = params.page || 1;
 
      return {
        results: data.items
      };
    },
    cache: true
  },
  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
  minimumInputLength: 1,
  templateResult: formatRepo, // omitted for brevity, see the source of this page
  templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
})

;

 var previewNode = document.querySelector("#template");
                       previewNode.id = "";
                       var previewTemplate = previewNode.parentNode.innerHTML;
                       previewNode.parentNode.removeChild(previewNode);



                       $('#myid').dropzone({
                           url: SYS_URL+'/message/upload/files',
                           paramName: "messagefile",
                           params: {
                               //mode: 'upload_post_file',
                               _token : CSRF_TOKEN
                           },
                           removedfile: function(file) {
                              
                               var _ref;
                               return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                           },
                           maxThumbnailFilesize: 5,
                           previewTemplate: previewTemplate,
                           previewsContainer: "#previews",
                           autoQueue: true,
                           maxFiles: 50,
                           init: function() {
                               this.on('success', function(file, response) {

                                console.log(response.status);

                                       if (response.status == "success") {
                                           $(file.previewTemplate).append('<input type="hidden" name="server_file[]" class="server_file" value="' + response.hash + '">');

                                       } else if (response.status == "error") {
                                           //$(file.previewTemplate).append('<div class="alert alert-danger">'+item.msg+'</div>');
                                           $(file.previewTemplate).html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + response.message + '</div>').fadeOut(3000);
                                       }
                               });
                               this.on("removedfile", function(file) {
                                   var server_file = $(file.previewTemplate).children('.server_file').val();
                                   //console.log(server_file);
                                   $.ajax({
                                       type: 'POST',
                                       url: SYS_URL+'/message/files/delete/'+server_file,
                                       data: { _token : CSRF_TOKEN },
                                       dataType: 'html',
                                   });
                               });
                               this.on("addedfile", function(file) {
                                   //console.log(file);
                               });
                               this.on('drop', function(file) {
                                   //alert('file');
                               });
                           }
                       });



  });
</script>
</body>
</html>