      <div class="btn-group btn-group-justified">
            <a class="btn btn-default btn-sm btn-flat {!! Request::is('ticket/list/in') ? ' active' : null !!} " role="button" href="{!! URL::to('/ticket/list/in') !!}"><i class="fa fa-download"></i> {{trans('ticketLists.In')}} 
            @if ($ticketsInFree != 0)
            <span id="label_list_in">({{$ticketsInFree}}) </span>
            @endif

            </a>
            <a class="btn btn-default btn-sm btn-flat {!! Request::is('ticket/list/out') ? ' active' : null !!}" role="button" id="link_out" href="{!! URL::to('/ticket/list/out') !!}"><i class="fa fa-upload"></i> {{trans('ticketLists.Out')}} <span id="label_list_out">
           	@if ($ticketOutCount != 0)
            <span id="label_list_in">({{$ticketOutCount}}) </span>
            @endif
            	

            </span> </a>
            <a class="btn btn-default btn-sm btn-flat {!! Request::is('ticket/list/arch') ? ' active' : null !!}" role="button" href="{!! URL::to('/ticket/list/arch') !!}"><i class="fa fa-archive"></i> {{trans('ticketLists.Arch')}}
<span id="label_list_arch">
           	@if ($ticketArchCount != 0)
            <span id="label_list_in">({{$ticketArchCount}}) 
            @endif
</span>            	


            </a>
        </div>