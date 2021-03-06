@extends('Shared.Layouts.Master')

@section('title')
@parent
Event Tickets
@stop


@section('top_nav')
@include('ManageEvent.Partials.TopNav')
@stop

@section('page_title')
<i class="ico-ticket mr5"></i>
Event Tickets
@stop

@section('head')

@stop

@section('menu')
@include('ManageEvent.Partials.Sidebar')
@stop


@section('page_header')
<div class="col-md-9">
    <!-- Toolbar -->
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group btn-group-responsive">
            <button  data-modal-id='CreateTicket' href='javascript:void(0);'  data-href="{{route('showCreateTicket', array('event_id'=>$event->id))}}" class='loadModal btn btn-success' type="button" ><i class="ico-ticket"></i> Create Ticket</button>
        </div>
        @if(false)
        <div class="btn-group btn-group-responsive ">
            <button data-modal-id='TicketQuestions' href='javascript:void(0);'  data-href="{{route('showTicketQuestions', array('event_id'=>$event->id))}}"  type="button" class="loadModal btn btn-success" >
                <i class="ico-question"></i> Questions 
            </button>
        </div>
        <div class="btn-group btn-group-responsive ">
            <button type="button" class="btn btn-success" >
                <i class="ico-tags"></i> Coupon Codes 
            </button>
        </div>
        @endif
    </div>
    <!--/ Toolbar -->
</div>
<div class="col-md-3">
   {!! Form::open(array('url' => route('showEventTickets', ['event_id'=>$event->id,'sort_by'=>$sort_by]), 'method' => 'get')) !!}
    <div class="input-group">
        <input name='q' value="{{$q or ''}}" placeholder="Search Tickets.." type="text" class="form-control">
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="ico-search"></i></button>
        </span>
       {!!Form::hidden('sort_by', $sort_by)!!}
    </div>
   {!! Form::close() !!}
</div>
@stop


@section('content')

<!--Start ticket table-->
<div class="row">   

    @if($tickets->count())
    
    <div class='order_options hide'>
        <div class="row">
            <div class="col-md-3 col-xs-6">
                <span class="event_count">
                    {{$tickets->count()}} events
                </span> 
            </div>
            <div class="col-md-2 col-xs-6 col-md-offset-7">

                {!! Form::select('sort_by_select', [
                'quantity_sold' => 'Quantity sold',
                'title' => 'Ticket title',
                'created_at' => 'Createion date',
                'sales_volume' => 'Sales volume'

            ], $sort_by, ['class' => 'form-control pull right']) !!}            </div>

        </div> 
    </div>
    
    @foreach($tickets as $ticket)

    <div id="ticket_{{$ticket->id}}" class="col-md-4 col-sm-6 ">


        <div  class="panel panel-success ticket">

            <div style="cursor: pointer;" data-modal-id='ticket-{{ $ticket->id }}' data-href="{{route('showEditTicket', ['event_id'=>$event->id,'ticket_id'=>$ticket->id])}}" class="panel-heading loadModal">
                <h3 class="panel-title">
                    <i class="ico-ticket ticket_icon mr5 ellipsis"></i>
                    {{{$ticket->title}}}
                    <span class="pull-right">
                        @if($ticket->isFree())
                        FREE
                        @else
                        {{money($ticket->price, $event->currency->code)}}
                        @endif

                    </span>
                </h3>
            </div>

            <div class='panel-body'>
                <ul class="nav nav-section nav-justified mt5 mb5">
                    <li>
                        <div class="section">
                            <h4 class="nm">{{{$ticket->quantity_sold}}}</h4>
                            <p class="nm text-muted">Sold</p>
                        </div>
                    </li>
                    <li>
                        <div class="section">
                            <h4 class="nm">

                                @if($ticket->quantity_available === NULL)
                                &infin;
                                @else
                                {{$ticket->quantity_remaining}}
                                @endif

                            </h4>
                            <p class="nm text-muted">Remaining</p>
                        </div>
                    </li>
                    <li>
                        <div class="section">
                       
                            
                            <h4 class="nm hint--top" title="{{money($ticket->sales_volume, $event->currency->code)}} + {{money($ticket->organiser_fees_volume, $event->currency->code)}} Organiser Booking Fees">
                                {{money($ticket->sales_volume + $ticket->organiser_fees_volume, $event->currency->code)}} <sub title="Doesn't account for refunds.">*</sub>
                            </h4>
                            <p class="nm text-muted">Revenue</p>
                        </div>
                    </li>
                </ul> 
            </div> 
            <div class="panel-footer" style="height: 56px;">
                <ul class="nav nav-section nav-justified">
                    <!--                    <li>
                                            <a data-modal-id='ticket-{{ $ticket->id }}' href='javascript:void(0);'  data-href="{{route('showEditTicket', ['event_id'=>$event->id,'ticket_id'=>$ticket->id])}}" class='loadModal'>
                                                <i class="ico-edit"></i> Edit
                                            </a> 
                                        </li>-->
                    <li>

                        <a href="#">
                            
                            @if($ticket->sale_status === config('attendize.ticket_status_on_sale'))

                            @if($ticket->is_paused)
                            Ticket Sales Paused &nbsp;
                            <span class="pauseTicketSales label label-info" data-id="{{$ticket->id}}" data-route="{{route('postPauseTicket', ['event_id'=>$event->id])}}" >
                                <i class="ico-play4"></i> Resume
                            </span>
                            @else
                                On Sale &nbsp;
                            <span class="pauseTicketSales label label-info" data-id="{{$ticket->id}}" data-route="{{route('postPauseTicket', ['event_id'=>$event->id])}}" >
                                <i class="ico-pause"></i> Pause
                            </span>
                            @endif


                            @else 
                                {{\App\Models\TicketStatus::find($ticket->sale_status)->name}}
                            @endif
                        </a>



                    </li>
                </ul>
            </div>
        </div>

    </div>
    @endforeach

    @else

    @if($q)
    @include('Shared.Partials.NoSearchResults')
    @else
    @include('ManageEvent.Partials.TicketsBlankSlate')
    @endif


    @endif
</div><!--/ end ticket table-->

<div class="row">
    <div class="col-md-12">
        {!! $tickets->render() !!}
    </div>
</div>
@stop

