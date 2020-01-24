@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('upgrade.title')}}
    <small>{{trans('upgrade.checking')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('upgrade.title')}}</li>
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
                    <h3 class="box-title"><i class="fa fa-repeat"></i> {{trans('upgrade.title')}}</h3>
                </div>





                <div class="box-body">




<div class="col-md-2">
    <a class="btn btn-app" id="checkNewVersionBtn">
                <i class="fa fa-repeat"></i> Check
</a>
</div>
<div class="col-md-10">
    
    <div class="callout callout-default">

                <p>{{trans('upgrade.current')}} <strong>{{config('app.zenlix_version')}}</strong></p>
              </div>

</div>
<div class="col-md-12">



<div id="msgOk" class="alert alert-success alert-dismissible" style="display:none;">
                
                <h4><i class="icon fa fa-check"></i> {{trans('upgrade.msgAlreadyV')}}</h4>
                {{trans('upgrade.msgNoNewV')}}
</div>

<div id="msgFail" class="alert alert-warning alert-dismissible" style="display:none;">
                
                <h4><i class="icon fa fa-warning"></i> {{trans('upgrade.msgNewV')}}</h4>
                <p>{{trans('upgrade.msgNewV2')}} </p>


                <center><button id="makeLastVersion" type="button" class="btn btn-success btn-sm"> <i id="btnUpdateClass" class="fa fa-refresh"></i> <span id="btnUpText">{{trans('upgrade.btnToUp')}}</span></button></center>
</div>



<pre id="updateResult" style="display:none;"></pre>
</div>





            </div>




                    </div><!-- /.box-body -->
                    </div><!-- /.box -->



<div class="col-md-3">
<div class="box">
<div class="box-header with-border">
              <i class="fa fa-info"></i>

              <h3 class="box-title">{{trans('upgrade.info')}}</h3>
            </div>
<div class="box-body">
{{trans('upgrade.infoDesc')}}
<pre>php artisan zenlix:update
</pre>
</div>
</div>
</div>


                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
<!-- page script -->
<script>
  $(function () {


//checkNewVersionBtn

$('body').on('click', '#checkNewVersionBtn', function(event) {
            event.preventDefault();
            

                        $.ajax({
                              type: "POST",
                              url: "{{URL::to('/admin/config/update/check_version') }}",
                              dataType: "json",
                              data: {
                                _token : CSRF_TOKEN,
                                CURRENT_VERSION: '{{config('app.zenlix_version')}}'
                              },
                              success: function(json) {
                                 $.each(json, function(i, item) {
                                    if (item.res == true) {
                                        $("#msgFail").hide().fadeIn(500);
                                    }
                                    else {
                                        $("#msgOk").hide().fadeIn(500);
                                    }
                                                                     });
                              }
                        });


            


          });


//makeLastVersion

$('body').on('click', '#makeLastVersion', function(event) {
            event.preventDefault();
            
//$("#updateResult").hide().fadeIn(500);

$("#btnUpdateClass").addClass('fa-spin');
$("#makeLastVersion").addClass('disabled');
$("#btnUpText").text('Updating... Please wait.');

                        $.ajax({
                              type: "POST",
                              url: "{{URL::to('/admin/config/update/make') }}",
                              //dataType: "json",
                              data: {
                                _token : CSRF_TOKEN
                              },
                              success: function(res) {
                                 $("#updateResult").hide().html(res).fadeIn(500);
                                 $("#btnUpdateClass").removeClass('fa-spin');
                                 $("#makeLastVersion").removeClass('disabled');
                                 $("#btnUpText").text('Update now!');
                                 $("#msgFail").hide();
                              }
                        });


            


          });




  });
</script>
</body>
</html>