@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  

 {!! Html::style('plugins/dropzone/css/dropzone.css'); !!}

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     <i class="fa fa-graduation-cap"></i> {{$help->name}}
    <small>{{trans('help.materialEditor')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li>{{trans('help.title')}}</li>
        <li class="active">{{trans('help.editing')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-10">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->



            <div class="box">



                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-alt"></i> {{trans('help.editing')}}</h3>
                </div>





                <div class="box-body">
     


{!! Form::model($help, array('action' => ['HelpCenterController@update', $help->id], 'method'=> 'POST', 'class'=>'form-horizontal')) !!}

                    <div class="form-group @if ($errors->has('name')) has-error @endif @if ($errors->has('slug')) has-error @endif">
                    {!! Form::label('name', trans('help.nameReq'), array('class'=>'col-sm-2 control-label')) !!}
                    <div class="col-sm-10">
                    {!! Form::text('name', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    @if ($errors->has('slug')) <p class="help-block">{{ $errors->first('slug') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('category_id', trans('help.catReq'), array('class'=>'col-sm-2 control-label')) !!}
                    <div class="col-sm-10">
                    {!! Form::select('category_id', $categories, $help->category_id, array('class'=>'form-control select2')) !!}
                    </div>
                    </div>



                    <div class="form-group @if ($errors->has('description')) has-error @endif">
                    {!! Form::label('description', trans('help.descReq'), array('class'=>'col-sm-2 control-label')) !!}
                    <div class="col-sm-10">
                    {!! Form::textarea('description', Null, array('class'=>'form-control', 'placeholder'=>trans('help.shortDesc'), 'rows'=>'2')) !!}
                    @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
                    </div>
                    </div>

              <div class="form-group @if ($errors->has('text')) has-error @endif">

<label for='unit' class="col-md-2 control-label">
{{trans('help.textReq')}}
</label>
<div class="col-md-10">

{!! Form::textarea('text', Null, array('class'=>'form-control trumbowyg-help', 'id'=>'MsgBox')) !!}
        @if ($errors->has('text')) <p class="help-block">{{ $errors->first('text') }}</p> @endif         
</div>
              </div>




<label for='unit' class="col-md-2 control-label">
{{trans('help.files')}}
</label>

@if ($help->files->count() > 0)

<div class="col-md-10" style="padding: 0px;">
<div class="attachment-block clearfix">
@foreach ($help->files as $file)
<div class="col-md-12 file-element">
                    <i class="attachment-img fa {!! Zen::fileIcon($file->mime); !!}" style="    padding: 3px;"></i>
                    <div class="attachment-pushed" style="margin-left: 22px;">
                      <div class="attachment-text">
                        {{$file->name }} <small> 
                        ({!! round(File::size(storage_path('users/'.$file->user_id.'/'.$file->hash.'.'.$file->extension))/1024/1024, 3) !!} {{trans('help.Mb')}})</small>

                        <button data-hash="{{$file->hash}}" class="pull-right btn btn-default btn-xs delete_file">{{trans('help.delete')}}</button>

                      </div><!-- /.attachment-text -->
                    </div><!-- /.attachment-pushed -->
</div>
@endforeach
</div>
</div>
<div class="col-md-2"></div>

@endif

<div class="col-md-10" style="padding: 0px;">
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
                                <h4 class="text-muted">{{trans('help.dropFiles')}}</h4>
                                <p class="text-muted">
                                    {{trans('help.dropOrClick')}}
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
<span>{{trans('help.delete')}}</span>
</button></td>
</tr>
</tbody></table>
                                                                            </div>
                                                                        </div>

                                                                    </div>




                                
                            </div>

                     
                        </div>
</div>




<br>
<hr>
<em>{{trans('help.access')}}</em>

                    <div class="form-group">
                    {!! Form::label('AcessAll', trans('help.accessTo'), array('class'=>'col-sm-2 control-label')) !!}
                    <div class="col-sm-10">
                    {!! Form::select('AcessAll', ['true'=>'Всем', 'false'=>trans('help.onlyGroups')], $help->access_all, array('class'=>'form-control select2')) !!}
                    </div>
                    </div>


<div class="form-group @if ($errors->has('groups')) has-error @endif">
                      <label for="inputPassword4" class="col-sm-2 control-label">{{trans('help.groups')}}</label>
                      <div class="col-md-10">

                      {!! Form::select('groups[]', $groups, $groupsSel, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'multiple'=>'multiple')) !!}
                      @if ($errors->has('groups')) <p class="help-block">{{ $errors->first('groups') }}</p> @endif     
                      </div>
</div>






<div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
{!! HTML::decode(Form::button(trans('help.save'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
</div>
</div>


{!! Form::close(); !!}

                  



                </div>
                </div>














            
                    </div><!-- /.box -->



<div class="col-md-2">





</div>


                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")

{!! Html::script('plugins/trumbowyg/plugins/upload/trumbowyg.upload.js'); !!}
{!! Html::script('plugins/trumbowyg/plugins/base64/trumbowyg.base64.min.js'); !!}
{!! Html::script('plugins/dropzone/js/dropzone.js'); !!}
 {!! Html::script('plugins/bootbox/bootbox.min.js'); !!}
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


 var previewNode = document.querySelector("#template");
                       previewNode.id = "";
                       var previewTemplate = previewNode.parentNode.innerHTML;
                       previewNode.parentNode.removeChild(previewNode);



                       $('#myid').dropzone({
                           url: SYS_URL+'/help/upload/files',
                           paramName: "helpfile",
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
                                       url: SYS_URL+'/help/files/delete/'+server_file,
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

$('body').on('click', '.delete_file', function(event) {
            event.preventDefault();
            var curEl=$(this);
            var fileHash=$(this).attr('data-hash');

bootbox.confirm('{{trans('help.confirmDeleteFile')}}', function(result) {
          if (result == true) {

            $.ajax({
                                       type: 'POST',
                                       url: SYS_URL+'/help/files/delete/'+fileHash,
                                       data: { _token : CSRF_TOKEN },
                                       dataType: 'html',
                                       success: function(html) {
                                        curEl.closest('div.file-element').hide();
                                       }
                                   });


                



          }
          else {

          }
        });


          });


/*    $('.trumbowyg-help').trumbowyg({
    mobile: true,
    tablet: true,
    removeformatPasted: true,
    btnsAdd: ['upload'],
    btns: ['formatting',
      '|', 'btnGrp-design',
      '|','justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull',
      '|', 'link',
      '|', 'btnGrp-lists',
      '|','insertImage',
      '|', 'horizontalRule']
});*/
  });
</script>
</body>
</html>