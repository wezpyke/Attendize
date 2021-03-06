@section('pre_header')
    @if(!$event->is_live)
        <style>
            .sidebar {
                top: 43px;
            }
        </style>
        <div class="alert alert-warning top_of_page_alert">
            This event is not visible to the public. <a href="{{route('MakeEventLive' , ['event_id' => $event->id])}}">Click
                here to make it live</a> .
        </div>
    @endif
@stop
<ul class="nav navbar-nav navbar-left">
    <!-- Show Side Menu -->
    <li class="navbar-main">
        <a href="javascript:void(0);" class="toggleSidebar" title="Show sidebar">
            <span class="toggleMenuIcon">
                <span class="icon"><i class="ico-menu"></i></span>
            </span>
        </a>
    </li>
    <!--/ Show Side Menu -->
    <li class="nav-button">
        <a target="__blank" href="{{$event->event_url}}">
            <span>
                <i class="ico-eye2"></i> &nbsp;<hide class="hidden-xs">View </hide>Event Page
            </span>

        </a>
    </li>
</ul>