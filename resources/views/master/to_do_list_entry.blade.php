@extends('layouts.main')
@section("content")
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    jQuery('#loader-bg').hide();

    var buttonWidth = "600";
    var buttonWidth2 = "150";

    $('#client').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });

    $('#project').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });

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

    $('#organizer').multiselect({
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
        onChange: function(option, checked, select) {     
            //preparerに選択された人でorganizerの選択肢を作る                   
            var selectedPersonVal = $('#preparer').val();
            var select = document.getElementById("organizer");

            $('select#organizer option').remove();
            
            $('#preparer option:selected').each(function() {
                //selectedPerson.push($(this).text()); 
                // optionタグを作成する
                var option = document.createElement("option");
                // optionタグのテキストを4に設定する
                option.text = $(this).text();
                // optionタグのvalueを4に設定する
                option.value = $(this).val();
                // selectタグの子要素にoptionタグを追加する
                select.appendChild(option);
            });
           
            $('#organizer').multiselect('rebuild');
        },
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
        defaultTime: '11',*/
        startTime: '09:00',
        dynamic: false,
        dropdown: true,
        scrollbar: false,        
        change: setEndTime,   
    });  
    
    

});
</script>

<meta name="csrf-token" content="{{ csrf_token() }}">
<form method="POST" enctype="multipart/form-data" id="taskEnter" name="taskEnter" autocomplete="off">
<input type="hidden" id="to_do_list_id" name="to_do_list_id" value="">
    <div style="margin-left: 20px">
        <div id="filter_area" style="margin-top: 30px;">
            <div id="filter_left" style="float: left;height: 180px;margin-bottom: 30px">
                <div class="row entry-filter-bottom" style="zoom: 100%">
                    <div class="col col-md-3" >
                        <span class="line-height">Client Name</span>
                    </div>          
                    <div class="col col-md-5">
                        <select id="client" name="client" class="form-control select2" data-display="static" onchange="setProjectIDData(false)">
                            <option value="">&nbsp;</option>                           
                            @foreach ($clientList as $clients)
                            <option value="{{$clients->id}}">{{$clients->name}}</option>
                            @endforeach
                        </select>
                    </div>  
                </div>

                <div class="row entry-filter-bottom" style="zoom: 100%">
                    <div class="col col-md-3">
                        <span class="line-height">Project Name</span>
                    </div>
                    <div class="col col-md-1">
                        <!--<select id="project" name="project" class="form-control" onchange="setTaskIDData(false)">     -->
                        <select id="project" name="project" class="form-control">     
                            <option value="">&nbsp;</option>
                            @foreach ($projectList as $projects)
                            <option value="{{$projects->id}}">{{$projects->project_name}}</option>
                            @endforeach
                        </select>
                    </div>  
                </div>
                
                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Task</span>
                    </div>
                    <div class="col col-md-1">
                        <input style="width: 600px" class="form-control" type="text" id="task" name="task" value="">
                        <!--<select id="task" name="task" class="form_control">
                            <option value="">&nbsp;</option>
                            @foreach ($taskList as $tasks)
                            <option value="{{$tasks->id}}">{{$tasks->name}}</option>
                            @endforeach
                        </select>-->
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Requestor</span>
                    </div>
                    <div class="col col-md-1">
                        <select id="requestor" name="requestor" class="form_control">
                            @foreach ($requestorList as $requestors)
                            <option value="{{$requestors->id}}">{{$requestors->initial}}</option>
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
                            <option value="{{$preparers->id}}">{{$preparers->initial}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Organizer</span>
                    </div>
                    <div class="col col-md-1">
                        <select id="organizer" name="organizer" class="form-control">                               
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
                            <option value="{{$optionals->id}}">{{$optionals->initial}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Start Time</sapn>
                    </div>
                    <div class="col col-md-2">
                        <input type="text" style="width: 120px;" class="form-control datepicker1" id="start_date" name="start_date" placeholder="mm/dd/yyyy" value="" autocomplete="off" onchange="setEndTime()">
                    </div>
                    <div class="col col-md-2">
                        <input type="text" style="width: 120px;" class="form-control timepicker" id="start_time" value="09:00 AM" name="start_time" autocomplete="off" onselect="setEndTime()">
                    </div>                    
                    <div class="col col-md-3">
                        <input type="text" style="width: 180px;border: none; background: white" class="form-control" id="pacific_time" name="pacific_time" value="Pacific Time (US & Canada)" autocomplete="off">
                    </div>
                    <!--<div class="col col-sm-2">
                        <input type="text" style="width: 120px;" readonly class="form-control" id="calc_pacific_time" name="calc_pacific_time"  value="" autocomplete="off">
                    </div>   -->            
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Duration</span>
                    </div>
                    <div class="col col-md-1">
                        <input type="number" step="0.25" min= "0" style="width: 120px;" class="form-control" id="duration" name="duration" value="0.25" autocomplete="off" onchange="setEndTime()">
                    </div>
                    <div class="col col-md-1" style="margin-left: 60px">
                        <span class="line-height"><font size="4">hr</font></sapn>
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">End Time</sapn>
                    </div>
                    <div class="col col-md-3">
                        <input type="text" style="width: 600px;" class="form-control" id="end_time" name="end_time" value="" autocomplete="off">
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Progress</span>
                    </div>
                    <div class="col col-md-1">
                        <!--<input type="text" style="width: 120px;" class="form-control" id="progress" name="progress" value="" autocomplete="off">-->
                        <select id="progress" name="progress" class="form-control" style="width: 120px">
                            <option value="0"></option>    
                            <option value="25">25%</option>
                            <option value="50">50%</option>
                            <option value="75">75%</option>
                            <option value="90">90%</option>
                            <option value="95">95%</option>
                            <option value="100">Completed</option>                            
                        </select>
                    </div>
                    <div class="col col-md-1" style="margin-left: 60px">
                        <span class="line-height"><font size="4">%</font></sapn>
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Location</span>
                    </div>
                    <div class="col col-md-1">
                        <input type="text" style="width: 600px;" class="form-control" id="location" value="" autocomplete="off">
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Memo</span>
                    </div>
                    <div class="col col-md-1">
                        <textarea style="width: 600px; height: 105px;" class="form-control" name="memo" id="memo" placeholder="Memo" value="" autocomplete="off"></textarea>
                    </div>
                </div>

                <div class="row entry-filter-bottom">
                    <div class="col col-md-3">
                        <span class="line-height">Add to Budget Hours?</span>
                    </div>
                    <div class="col col-md-1">
                        <!--<input type="text" style="width: 120px;" class="form-control" id="progress" name="progress" value="" autocomplete="off">-->
                        <select id="add_budget_hours" name="add_budget_hours" class="form-control" style="width: 120px">                            
                            <option value="0">Yes</option>
                            <option value="1" selected>No</option>                                                   
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
<script src="{{ asset('js/to_do_list_entry.js') . '?p=' . rand() }}"></script>

@endsection
