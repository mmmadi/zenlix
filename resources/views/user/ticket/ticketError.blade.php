@include("layout.header")

  <!-- iCheck -->
  {!! Html::style('plugins/iCheck/square/blue.css'); !!}


<div class="wrapper">

@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
                    <h1>
                        <i class="fa fa-ticket"></i> {{trans('ticket.accessTitle')}}
                        <small>
                            {{trans('ticket.accessSubtitle')}}
                        </small>
                    </h1>
                    <ol class="breadcrumb">
                        @include("layout.breadcrumb")
                        <li class="active">{{trans('ticket.ticket')}}</li>
                    </ol>
                </section>

    <!-- Main content -->
<section class="content">
          <div class="error-page">
            
            <div class="error-content">
              <h3><i class="fa fa-warning text-yellow"></i> {{trans('ticket.accessSubtitle')}}</h3>
                   <div class="callout callout-warning">
                                        <p> {{trans('ticket.accessInfo')}} </p>

                                        <ul>
                                        <li>{{trans('ticket.accessInfoAuthor')}}
                                        </li>
                                        <li>{{trans('ticket.accessInfoTarget')}}
                                        </li>
                                        <li>{{trans('ticket.accessInfoWatching')}}
                                        </li>
                                        <li>{{trans('ticket.accessInfoAdmin')}}
                                        </li>
                                        </ul>
                                    </div>

            </div><!-- /.error-content -->
          </div><!-- /.error-page -->



                                        
                    

                </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
{!! Html::script('plugins/iCheck/icheck.min.js'); !!}
<!-- page script -->
<script>
  $(function () {
        $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>