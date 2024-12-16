@extends('layouts.main')
@section("content")
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    jQuery('#loader-bg').hide();

    var buttonWidth = "600";
    var buttonWidth2 = "150";

    /*$('#task').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });*/

    $('#requestor').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });

    $('#preparer').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });

    $('#optional').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });

    $('.datepicker1').datepicker({
        format: "mm/dd/yyyy",
        language: "en",
        autoclose: true,
        orientation: 'bottom left'
    });

    $('#start_time').timepicker({
        timeFormat: 'hh:mm p',
        interval: 60,
        /*minTime: '10',
        maxTime: '6:00pm',
        defaultTime: '11',
        startTime: '10:00',*/
        dynamic: false,
        dropdown: true,
        scrollbar: false,     
        change: setEndTime,   
    });  
    

});
</script>


<meta name="csrf-token" content="{{ csrf_token() }}">
<input type="hidden" id="to_do_list_id" name="to_do_list_id" value="{{$todo->id}}">
<input type="hidden" id="client" name="client" value="{{$client->id}}">
<input type="hidden" id="project" name="project" value="{{$project->id}}">
<form method="POST" class="form-horizontal" action="/master/{{ $todo->id }}" id="taskEnter" name="todoListForm" autocomplete="off">
    
    
    
    <div style="margin-left: 40px">        
        <div id="filter_area" style="margin-top: 30px;">
            <div id="filter_left" style="float: left;height: 180px;margin-bottom: 30px">
                <div class="row entry-filter-bottom" style="zoom: 100%">
                    <div class="col col-md-3" >
                        <span class="line-height">Client Name</span>
                    </div>          
                    <div class="col col-md-6">
                        <span style="vertucal-style: middle;">{{$client->name}}</span>
                    </div>  
                </div>

                <div class="row entry-filter-bottom" style="zoom: 100%">
                    <div class="col col-md-3">
                        <span class="line-height">Project Name</span>
                    </div>
                    <div class="col col-md-6">
                        <span style="vertucal-style: middle;">{{$project->project_name}}</span>
                    </div>  
                </div>
                
                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Task</span>
                    </div>
                    <div class="col col-md-1">
                        <input style="width: 600px" class="form-control" type="text" id="task" name="task" value="{{$selected_task_id}}">
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Requestor</span>
                    </div>
                    <div class="col col-md-1">
                        <select id="requestor" name="requestor" class="form_control">
                            @foreach ($requestorList as $requestors)
                            @if ($requestors->id == $requestor->id)
                            <option value="{{$requestors->id}}" selected>{{$requestors->initial}}</option>
                            @else
                            <option value="{{$requestors->id}}">{{$requestors->initial}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Asignee</span>
                    </div>
                    <div class="col col-md-1">
                        <select id="preparer" name="preparer" multiple="multiple" class="form-control">
                            @foreach ($preparerList as $preparers)
                            @if (in_array($preparers->id, $preparer_id_list))
                            <option value="{{$preparers->id}}" selected>{{$preparers->initial}}</option>
                            @else
                            <option value="{{$preparers->id}}">{{$preparers->initial}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Optional Personnel</span>
                    </div>
                    <div class="col col-md-1">
                        <select id="optional" name="optional" multiple="multiple" class="form-control">
                            @foreach ($optionalList as $optionals)
                            @if (in_array($optionals->id, $optional_id_list))
                            <option value="{{$optionals->id}}" selected>{{$optionals->initial}}</option>
                            @else
                            <option value="{{$optionals->id}}">{{$optionals->initial}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Start Time</span>
                    </div>
                    <div class="col col-md-2">
                        <input type="text" style="width: 120px;" class="form-control datepicker1" id="start_date" name="start_date" placeholder="mm/dd/yyyy" value="{{$start_date}}" autocomplete="off" onchange="setEndTime()">
                    </div>
                    <div class="col col-md-2">
                        <input type="text" style="width: 120px;" class="form-control timepicker" id="start_time" value="{{$start_time}}" name="start_time" autocomplete="off" onselect="setEndTime()">
                    </div>
                    <div class="col col-md-3">
                        <input type="text" style="width: 180px;border: none; background: white" class="form-control" id="pacific_time" name="pacific_time" value="Pacific Time (US & Canada)" autocomplete="off">
                    </div>
                    <!--<div class="col col-sm-2">
                        <input type="text" style="width: 120px;" readonly class="form-control" id="calc_pacific_time" name="calc_pacific_time"  value="" autocomplete="off">
                    </div>-->
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Duration</span>
                    </div>
                    <div class="col col-md-1">
                    <input type="number" step="0.25" min= "0" style="width: 120px;" class="form-control" id="duration" name="duration" value="{{$duration}}" autocomplete="off" onchange="setEndTime()">
                    </div>
                    <div class="col col-md-1" style="margin-left: 60px">
                        <span class="line-height"><font size="4">hr</font></span>
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">End Time</span>
                    </div>
                    <div class="col col-md-3">
                        <input type="text" style="width: 600px;" class="form-control" id="end_time" name="end_time" value="{{$end_time}}" autocomplete="off">
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Progress</span>
                    </div>
                    <div class="col col-md-1">
                        <!--<input type="text" style="width: 120px;" class="form-control" id="progress" name="progress" value="{{$progress}}" autocomplete="off">-->
                        <select id="progress" name="progress" class="form-control" style="width: 120px">
                            <option value="0"></option>    
                            <option value="25" @if($progress == "25") selected @endif>25%</option>
                            <option value="50" @if($progress == "50") selected @endif>50%</option>
                            <option value="75" @if($progress == "75") selected @endif>75%</option>
                            <option value="90" @if($progress == "90") selected @endif>90%</option>
                            <option value="95" @if($progress == "95") selected @endif>95%</option>
                            <option value="100" @if($progress == "100") selected @endif>Completed</option>                            
                        </select>
                    </div>
                    <div class="col col-md-1" style="margin-left: 60px">
                        <span class="line-height"><font size="4">%</font></span>
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Location</span>
                    </div>
                    <div class="col col-md-1">
                        <input type="text" style="width: 600px;" class="form-control" id="location" value="{{$location}}" autocomplete="off">
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Memo</span>
                    </div>
                    <div class="col col-md-1">
                        <textarea style="width: 600px; height: 105px;" class="form-control" name="memo" id="memo" placeholder="Memo" value="{{$memo}}" autocomplete="off">{{$memo}}</textarea>
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Add to Budget Hours?</span>
                    </div>
                    <div class="col col-md-1">
                        <!--<input type="text" style="width: 120px;" class="form-control" id="progress" name="progress" value="" autocomplete="off">-->
                        <select id="add_budget_hours" name="add_budget_hours" class="form-control" style="width: 120px">                                                        
                            <option value="1" @if($addBudgetHours == "1") selected @endif>No</option>                                               
                            <option value="0" @if($addBudgetHours == "0") selected @endif>Yes</option>                                                                              
                        </select>
                    </div>                    
                </div>

                <div class="row entry-filter-bottom" style="margin-left: 648px">
                    <button id="btn_save" name="btn_save" class="btn btn-primary project-button" type="button" onclick="saveForm()">
                        <span id="savingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                        <span id="savingText">Save</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    // "global" vars, built using blade
    var imagesUrl = '{{ URL::asset('/image') }}';
</script>
<script src="{{ asset('js/to_do_list_entry.js') }}"></script>

@endsection
