@extends('layouts.main')
@section('content') 

<div style="margin-left: 20px">
    <div id="filter_area" style="margin-top: 30px;">
        <div id="filter_left" style="float: left;height: 180px;margin-bottom: 30px">
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-3" >
                    <span class="line-height">Client</span>
                </div>
                <div class="col col-md-3">
                    <select id="client" name="client" multiple="multiple" class="form-control select2" data-display="static">                           
                        @foreach ($client as $clients)
                        <option value="{{$clients->id}}">{{$clients->name}}</option>
                        @endforeach
                    </select>
                </div>  
            </div>   
            
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-3">
                    <span class="line-height">Project</span>
                </div>
                <div class="col col-md-1">
                    <select id="project" name="project" multiple="multiple" style="width: 200px">                          
                        @foreach ($project as $projects)
                        <option value="{{$projects->project_name}}">{{$projects->project_name}}</option>
                        @endforeach
                    </select>
                </div>                
            </div>

            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-3">
                    <span class="line-height">PIC</span>
                </div>
                <div class="col col-md-1">                                        
                    <select id="pic" name="pic" multiple="multiple" class="form-control">                            
                        @foreach ($pic as $pics)                    
                        <option value="{{$pics->id}}">{{$pics->initial}}</option>
                        @endforeach
                    </select>                      
                </div>
            </div>

            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-3">
                    <span class="line-height">Staff</span>
                </div>
                <div class="col col-md-1">
                    @if(!is_object($loginInitial))
                    <select id="sel_staff" name="sel_staff" multiple="multiple" class="form-control" >                            
                        @foreach ($staff as $staffs)
                        <option value="{{$staffs->id}}">{{$staffs->initial}}</option>
                        @endforeach
                    </select>
                    @else
                    <select id="sel_staff" name="sel_staff" multiple="multiple" class="form-control" >                            
                        @foreach ($staff as $staffs)
                        <option value="{{$staffs->id}}" @if($loginInitial->initial == $staffs->initial) selected @endif>{{$staffs->initial}}</option>
                        @endforeach
                    </select>
                    @endif 
                </div>
            </div>      
                        
           <!-- <div class="row entry-filter-bottom">
                <div class="col col-md-3">
                    <span class="line-height">Date From</span>
                </div>
                <div class="col col-md-1">
                    <input type="text" style="width:150px;hight:10px;" class="form-control datepicker1" id="filter_date" name="filter_date" placeholder="mm/dd/yyyy" value="">                            
                </div>                 
            </div>
            
            <div class="row entry-filter-bottom">
                <div class="col col-md-3">
                    <span class="line-height">Date To</span>
                </div>
                <div class="col col-md-1">
                    <input type="text" style="width:150px;hight:10px;" class="form-control datepicker1" id="filter_to" name="filter_to" placeholder="mm/dd/yyyy" value="">                            
                </div>                 
            </div>
            
            <div class="row entry-filter-bottom">
                <div class="col col-md-3">
                    <span class="line-height">Status</span>
                </div>
                <div class="col col-md-1">
                    <select id="status" name="status" class="form-control" style="width:150px;hight:10px;">
                        <option value=""></option>
                        <option value="Imcomplete">Imcomplete</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>                 
            </div>-->

            <div class="row entry-filter-bottom">    
                <div class="col col-md-3">
                    
                </div>
                <div class="col col-md-1" >
                    <input type="button" class="btn btn-default" value="Clear" onclick="clearInputFilter()" style="background-color: white;width: 150px;">
                </div>
                <div class="col" >
                    <button id="btn_load" name="btn_load" class="btn btn-primary" type="button" onclick="loadTaskScheduleData()" style="width: 150px;margin-left: 140px">
                        <span id="loadingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                        <span id="loadingText">Search</span>
                    </button>
                </div>
            </div>
        </div>        
        <div id="filter_left" style="float: left;height: 180px;margin-bottom: 30px;margin-left: 100px">
            <div class="row entry-filter-bottom">
                <div class="col col-md-5">
                    <span class="line-height">Date From</span>
                </div>
                <div class="col col-md-1">
                    <input type="text" style="width:150px;hight:10px;" class="form-control datepicker1" id="filter_date" name="filter_date" placeholder="mm/dd/yyyy" value="{{$lastSunday}}" autocomplete="off">                            
                </div>                 
            </div>
            
            <div class="row entry-filter-bottom" hidden>
                <div class="col col-md-5">
                    <span class="line-height">Date To</span>
                </div>
                <div class="col col-md-1">
                    <input type="text" style="width:150px;hight:10px;" class="form-control datepicker1" id="filter_to" name="filter_to" placeholder="mm/dd/yyyy" value="" autocomplete="off">                            
                </div>                 
            </div>
            
            <div class="row entry-filter-bottom">
                <div class="col col-md-5">
                    <span class="line-height">Due / Prep</span>
                </div>
                <div class="col col-md-1">
                    <select id="status" name="status" class="form-control" style="width:150px;hight:10px;">                        
                        <option value="Due">Due</option>
                        <option value="Prep" selected>Prep</option>
                        <option value="SignOff">Sign Off</option>
                    </select>
                </div>                 
            </div>

            <div class="row entry-filter-bottom">
                <div class="col col-md-5">
                    <span class="line-height">Status</span>
                </div>
                <div class="col col-md-1">
                    <select id="comp_status" name="comp_status" class="form-control" style="width:150px;hight:10px;">
                        <option value=""></option>
                        <option value="Imcomplete">Imcomplete</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>                 
            </div>
        </div>
    </div>

    <div style="clear: both"></div>

    <div style="float: left;margin-bottom: 20px">
        <input type="button" class="btn btn-default" value="< Previous" onclick="loadPreviousWeek()" style="background-color: white;height: 50%;width: 90px;">
        <input type="button" class="btn btn-default" value="Next >" onclick="loadNextWeek()" style="background-color: white;height: 50%;width: 90px;">
    </div>

    <table class="table" id="task_schedule">
        <thead>
            <tr>                
                <th style="width: 60px">Link<br><span id="head_link">&nbsp;</span></th>
                <th style="width: 200px">Client<br><span id="head_client">&nbsp;</span></th>
                <th style="width: 200px">Project<br><span id="head_project">&nbsp;</span></th>     
                <th style="width: 60px">Phase<br><span id="head_phase">&nbsp;</span></th>           
                <th style="width: 60px">Staff<br><span id="head_pic">&nbsp;</span></th>
                <th style="width: 200px"><span id="head_sun_week">Sun</span><br><span id="head_sun">04/05/2021</span></th>
                <th style="width: 200px"><span id="head_mon_week">Mon</span><br><span id="head_mon">04/06/2021</span></th>
                <th style="width: 200px"><span id="head_tue_week">Tue</span><br><span id="head_tue">04/07/2021</span></th>
                <th style="width: 200px"><span id="head_wed_week">Wed</span><br><span id="head_wed">04/08/2021</span></th>
                <th style="width: 200px"><span id="head_thu_week">Thu</span><br><span id="head_thu">04/09/2021</span></th>
                <th style="width: 200px"><span id="head_fri_week">Fri</span><br><span id="head_fri">04/10/2021</span></th>
                <th style="width: 200px"><span id="head_sat_week">Sat</span><br><span id="head_sat">04/11/2021</span></th>
            </tr>
        </thead>
        <tbody id="task_schedule_body"></tbody>
    </table>
  
</div>
<script>
    // "global" vars, built using blade
    var imagesUrl = '{{ URL::asset('/image') }}';
</script>
<script src="{{ asset('js/taskScheduleWeek.js') }}"></script>
@endsection