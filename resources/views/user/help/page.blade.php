@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     <i class="fa fa-graduation-cap"></i> {{$help->name}}
    <small>{{trans('help.title')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li>{{trans('help.title')}}</li>
        <li class="active">{{$help->name}}</li>
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






                <div class="box-body">
     
                {!! $help->text !!}

                </div>
<div class="box-footer">
              {{trans('help.author')}} <a href="{{URL::to('/user/'.$help->author->profile->user_urlhash)}}">{{$help->author->name}}</a>
<span class="pull-right">{{LocalizedCarbon::instance($help->updated_at)->formatLocalized('%d %f %Y, %H:%M')}}</span>
            </div>

                </div>














            
                    </div><!-- /.box -->



<div class="col-md-3">

@if ($CurUser->roles->role != 'client')
<a href="{{URL::to('/help/edit/'.$help->slug)}}" class="btn btn-block bg-orange btn-sm">{{trans('help.edit')}}</a>
<a href="#" id="del_el" class="btn btn-block bg-maroon btn-sm">{{trans('help.delete')}}</a>
@endif

<br>



<div class="box box-solid">
            <div class="box-header with-border">
              

              <h3 class="box-title">
              {{trans('help.desc')}}</h3>




            </div>
            <!-- /.box-header -->
            <div class="box-body">
<p><small> {{$help->description}} </small></p>
            </div>

            <!-- /.box-body -->
          </div>

@if ($help->files->count() > 0)
<div class="box box-solid">
            <div class="box-header with-border">
              

              <h3 class="box-title">

              {{trans('help.files')}}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">


                  <div class="attachment-block clearfix">

@foreach ($help->files as $file)
<div class="col-md-12 no-padding"><br></div>
<div class="col-md-12 no-padding">
                    <i class="attachment-img fa {!! Zen::fileIcon($file->mime); !!}" style="    padding: 3px;"></i>
                    <div class="attachment-pushed" style="margin-left: 22px;">
                      <div class="attachment-text">
                        {{$file->name }} <small> 
                        ({!! round(File::size(storage_path('users/'.$file->user_id.'/'.$file->hash.'.'.$file->extension))/1024/1024, 3) !!} {{trans('help.Mb')}})</small>
                        <a href="{{ URL::to('/help/files/download/'.$file->hash) }}" class="pull-right btn btn-default btn-xs">{{trans('help.download')}}</a>
                      </div><!-- /.attachment-text -->
                    </div><!-- /.attachment-pushed -->
                    </div>
@endforeach

                  </div><!-- /.attachment-block -->
            </div>

            <!-- /.box-body -->
          </div>
@endif


</div>


                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
{!! Html::script('plugins/bootbox/bootbox.min.js'); !!}

<!-- page script -->
<script>
  $(function () {


$('body').on('click', '#del_el', function(event) {
            event.preventDefault();
            var elID='{{$help->id}}';
bootbox.confirm('{{trans('help.confirmDelete')}}', function(result) {
                if (result == true) {
            
            $.ajax({
                    type: "POST",
                    url: "{{URL::to('/help/delete/') }}/"+elID,
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'DELETE'
                      
                    },
                    success: function(html) {
                      window.location="{{URL::to('/help')}}";
                    }
                  });
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