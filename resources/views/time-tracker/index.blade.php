@extends('layouts.main')

<style type="text/css">
    .column1_block {
        z-index: 3;
        position: sticky;
        background-color: white;
        top: 0px;
    }

    .column2_block {
        z-index: 3;
        position: sticky;
        top: 0;
        background-color: white;
        top: 30px
    }

    .column_row_block {
        z-index: 2;
        position: sticky;
        left: 0;
    }

    .font1 * {
        font-family: "Segoe UI";
        font-size: 11px;
    }

    .footer_block {
        position: sticky;
        bottom: 0;
        z-index: 1;
    }

    .col2 {
        width: 200px;
        left: 250px
    }

    .col3 {
        width: 50px;
        left: 450px
    }

    .col4 {
        width: 50px;
        left: 500px
    }

    .col5 {
        width: 50px;
        left: 550px
    }

    .col6 {
        width: 80px;
        left: 600px
    }

    .col7 {
        width: 50px;
        left: 680px
    }

    .col8 {
        width: 60px;
        left: 730px
    }

    .col9 {
        width: 60px;
        left: 790px
    }

    .col10 {
        width: 70px;
        left: 850px
    }

    .col11 {
        width: 60px;
        z-index: 0;
        text-align: center;
    }

    .header-background-color {
        background-color: #e2efda;
    }

    a.p:hover {
        position: relative;
        text-decoration: none;
    }

    a.p span {
        display: none;
        position: absolute;
        top: -140px;
        left: 20px;
    }

    a.p:hover span {
        border: none;
        display: block;
        width: 210px;
        z-index: 10;
    }
</style>
<link rel="stylesheet" href="https://cache.harvestapp.com/static/styles-F5WBYAS5.css" media="all" />

@section('content')

<div style="margin-left: 0px">
    <nav id="sub-nav" class="pds-screen-only">
        <div class="pds-container">
            <ul class="sub-nav-tabs">
                <li><a data-analytics-element-id="sub-nav-time-timesheet" class="current" href="https://budgetwebform.harvestapp.com/time">Timesheet</a></li>
                {{--
                <li>
                    <div id="tt-a74e41ca" class="pds-tooltip-sm pds-text-center" hidden="hidden">Timesheet approvals is included in the Premium plan and for free during your trial.</div><a data-analytics-element-id="sub-nav-time-pending-approval" class="premium" href="/approve">Pending approval<div data-tooltip="true" data-tooltip-placement="bottom" aria-describedby="tt-a74e41ca" class="pds-badge pds-badge-orange-inverse nav-icon-badge pds-ml-xs"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5">
                                <polyline points="17 11 12 6 7 11" />
                                <polyline points="17 18 12 13 7 18" />
                            </svg></div></a>
                </li>
                <li><a data-analytics-element-id="sub-nav-time-unsubmitted" href="/missing_time">Unsubmitted</a></li>
                <li><a data-analytics-element-id="sub-nav-time-archive" href="/approve/archives">Approved</a></li>
                --}}
            </ul>
        </div>
    </nav>
    <div class="pds-container js-timesheet-view">
        <div class="day-view-wrapper js-view-wrapper">
            @include('time-tracker.timesheet-header')
            <div class="new-time-entry-container js-new-time-entry-container"> <button type="button" class="pds-button pds-button-primary button-new-time-entry js-new-time-entry test-new-time-entry" aria-label="Track time" data-analytics-element-id="timesheet-new-entry">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Track time</span>
                </button>
            </div>
            @include('time-tracker.day-view')
        </div>
    </div>


</div>

<script>
    // "global" vars, built using blade
    var imagesUrl = '{{ URL::asset(' / image ') }}';
</script>
<script src="{{ asset('js/budgetWebform.js') }}"></script>
@endsection