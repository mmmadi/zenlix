@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('license.title')}}
    <small>{{trans('license.checking')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('license.title')}}</li>
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
                    <h3 class="box-title"><i class="fa fa-key"></i> {{trans('license.title')}}</h3>
                </div>





                <div class="box-body">


<table class="table table-striped">
            <thead>
            <tr>
              <th>{{trans('license.productCode')}}</th>
              <th>{{trans('license.owner')}}</th>
              <th>{{trans('license.msg')}}</th>
              <th>{{trans('license.lastCheck')}}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>{{$UID}}</td>
              <td>{{$owner}}</td>
              <td>{{$msg}}</td>
              <td>{{$time}}</td>
            </tr>

            </tbody>
          </table>



            </div>




                    </div><!-- /.box-body -->

<div class="box">

<div class="box-body">

<div class="col-md-2">
    <a class="btn btn-app" id="checkNewLicenseBtn">
                <i id="btnUpdateClass" class="fa fa-repeat"></i> Check
</a>
</div>

<div class="col-md-10">
    
    <div class="callout callout-default">

                <p>{{trans('license.manualCheck')}}</p>
              </div>

</div>

<div class="col-md-12">
<pre id="licenseResult" style="display:none;"></pre>

</div>


</div>
</div>



                    </div><!-- /.box -->



<div class="col-md-3">

<div class="box">
<div class="box-header with-border">
              <i class="fa fa-info"></i>

              <h3 class="box-title">{{trans('license.info')}}</h3>
            </div>
<div class="box-body">
{{trans('license.infoDesc')}} 
<pre>php artisan zenlix:license
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

//checkNewLicenseBtn

$('body').on('click', '#checkNewLicenseBtn', function(event) {
            event.preventDefault();
            
//$("#updateResult").hide().fadeIn(500);

$("#btnUpdateClass").addClass('fa-spin');
$("#checkNewLicenseBtn").addClass('disabled');
$("#checkNewLicenseBtn").text('Please wait...');

                        $.ajax({
                              type: "POST",
                              url: "{{URL::to('/admin/config/license/make') }}",
                              //dataType: "json",
                              data: {
                                _token : CSRF_TOKEN
                              },
                              success: function(res) {
                                 $("#licenseResult").hide().html(res).fadeIn(500);
                                 $("#btnUpdateClass").removeClass('fa-spin');
                                 //$("#checkNewLicenseBtn").removeClass('disabled');
                                 $("#checkNewLicenseBtn").text('Success!');
                                 
                              }
                        });


            


          });




  });
</script>
</body>
</html>