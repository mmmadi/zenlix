      <div class="btn-group btn-group-justified">

            <a class="btn btn-default btn-sm btn-flat {!! Request::is('tickets') ? ' active' : null !!}" role="button" id="link_out" href="{!! URL::to('/tickets') !!}"><i class="fa fa-upload"></i> {{trans('clientTL.myTickets')}} <span id="label_list_out">
           	@if ($ticketOutCount != 0)
            <span id="label_list_in">({{$ticketOutCount}}) </span>
            @endif
            	

            </span> </a>
            <a class="btn btn-default btn-sm btn-flat {!! Request::is('tickets/arch') ? ' active' : null !!}" role="button" href="{!! URL::to('/tickets/arch') !!}"><i class="fa fa-archive"></i> {{trans('clientTL.Arch')}}
<span id="label_list_arch">
           	@if ($ticketArchCount != 0)
            <span id="label_list_in">({{$ticketArchCount}}) 
            @endif
</span>            	


            </a>
        </div>