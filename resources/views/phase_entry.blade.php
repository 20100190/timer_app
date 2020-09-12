@extends('layouts.main')
@section('content') 

<div style="margin-left: 20px">
    <div id="filter_area" style="margin-top: 30px;">
        <div id="filter_left" style="float: left;height: 200px;margin-bottom: 30px">
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-2" >
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
                <div class="col col-md-2">
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
                <div class="col col-md-2">
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
                <div class="col col-md-2">
                    <span class="line-height">Staff</span>
                </div>
                <div class="col col-md-1">
                    <select id="sel_staff" name="sel_staff" multiple="multiple" class="form-control" >                            
                        @foreach ($staff as $staffs)
                        <option value="{{$staffs->id}}">{{$staffs->initial}}</option>
                        @endforeach
                    </select>
                </div>
            </div>              

            <div class="row entry-filter-bottom">                           
                <div class="col col-md-3" >
                    <input type="button" class="btn btn-default" value="Clear" onclick="clearInputFilter()" style="background-color: white;width: 150px;margin-left: 85px">
                </div>
                <div class="col" >
                    <button id="btn_load" name="btn_load" class="btn btn-primary" type="button" onclick="getProjectAllData()" style="width: 150px;margin-left: 140px">
                        <span id="loadingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                        <span id="loadingText">Search</span>
                    </button>
                </div>
            </div>
        </div>

        <div id="filter_right" style="float: left;margin-left: 80px">
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-4">
                    <span class="line-height">VIC</span>
                </div>
                <div class="col col-md-1">
                    <select id="vic" name="vic" multiple="multiple" class="form-control" >                            
                        <option value="1">VIC</option>
                        <option value="2">IC</option>
                        <option value="3">C</option>
                    </select>
                </div>
            </div>


            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-4">
                    <span class="line-height">Role</span>
                </div>
                <div class="col col-md-1">
                    <select id="sel_role" name="sel_role" multiple="multiple" class="form-control" >                            
                        @foreach ($role as $roles)                    
                        <option value="{{$roles->id}}">{{$roles->role}}</option>
                        @endforeach                    
                    </select>
                </div>
            </div>

            <div class="row entry-filter-bottom">
                <div class="col col-md-4">
                    <span class="line-height">Date From</span>
                </div>
                <div class="col col-md-1">
                    <input type="text" style="width:150px;hight:10px;margin-right: 20px;" class="form-control datepicker1" id="filter_date" name="filter_date" placeholder="mm/dd/yyyy" value="">                            
                </div>                 
            </div>

            <div class="row entry-filter-bottom" style="zoom: 100%;">    
                <div class="col col-md-4">                   
                </div>
                <div class="col col-md-1">

                </div>
            </div>            
        </div>        
    </div>



    <div id='spreadsheet2'></div>

    <input type="hidden" id="phaseCTR">
    <input type="hidden" id="phaseBM">
    <input type="hidden" id="phaseAUD">
    <input type="hidden" id="phaseREV">
    <input type="hidden" id="phaseCOMP">
    <input type="hidden" id="phaseITR">
    <input type="hidden" id="phaseTOPC">
    <input type="hidden" id="phaseOTH">
    <input type="hidden" id="phaseCTRColor">
    <input type="hidden" id="phaseAUDColor">
    <input type="hidden" id="phaseREVColor">
    <input type="hidden" id="phaseCOMPColor">
    <input type="hidden" id="phaseITRColor">
    <input type="hidden" id="phaseBMColor">
    <input type="hidden" id="phaseTOPCColor">
    <input type="hidden" id="phaseOTHColor">
</div>

<script src="{{ asset('js/phaseEntry.js') }}"></script>
@endsection