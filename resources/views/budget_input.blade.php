@extends('layouts.main')

@section('content') 

<form action="test3/save" method="POST" name="s">
    {{ csrf_field() }}  
    <div id="filter_area" style="margin-top: 40px;">
        <div id="filter_left" style="float: left;height: 200px">
            <div class="row" style="zoom: 100%">
                <div class="col col-md-3" >
                    Client
                </div>
                <div class="col col-md-3">
                    <select id="client" name="client" multiple="multiple" class="form-control select2" data-display="static">                           
                        @foreach ($client as $clients)
                        <option value="{{$clients->id}}">{{$clients->name}}</option>
                        @endforeach
                    </select>
                </div>  
            </div>

            <div class="row" style="zoom: 100%">
                <div class="col col-md-3">
                    Project
                </div>
                <div class="col col-md-1">
                    <select id="project" name="project" multiple="multiple" style="width: 200px">                          
                        @foreach ($project as $projects)
                        <option value="{{$projects->project_name}}">{{$projects->project_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row" style="zoom: 100%">
                <div class="col col-md-3">
                    FYE
                </div>
                <div class="col col-md-1">
                    <select id="fye" name="fye" class="form-control" multiple="multiple" >                            
                        <option value="1">1/31</option>
                        <option value="2">2/28</option>
                        <option value="3">3/31</option>
                        <option value="4">4/30</option>
                        <option value="5">5/31</option>
                        <option value="6">6/30</option>
                        <option value="7">7/31</option>
                        <option value="8">8/31</option>
                        <option value="9">9/30</option>
                        <option value="10">10/31</option>
                        <option value="11">11/30</option>
                        <option value="12">12/31</option>
                    </select>
                </div>
            </div>       

            <div class="row" style="zoom: 100%">
                <div class="col col-md-3">
                    VIC
                </div>
                <div class="col col-md-1">
                    <select id="vic" name="vic" multiple="multiple" class="form-control" >                            
                        <option value="1">VIC</option>
                        <option value="2">IC</option>
                        <option value="3">C</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col col-md-3">
                    Date From
                </div>
                <div class="col col-md-1">
                    <input type="text" style="width:150px;hight:10px;margin-right: 20px;font-size: 3px" class="form-control datepicker1" id="filter_date" name="filter_date" placeholder="mm/dd/yyyy" value="">                            
                </div>                
            </div>
        </div>

        <div id="filter_right" style="float: left;margin-left: 80px">
            <div class="row" style="zoom: 100%">
                <div class="col col-md-3">
                    PIC
                </div>
                <div class="col col-md-1">
                    @if(!is_object($loginInitial))
                    <select id="pic" name="pic" multiple="multiple" class="form-control">                            
                        @foreach ($pic as $pics)                    
                        <option value="{{$pics->id}}">{{$pics->initial}}</option>
                        @endforeach
                    </select>
                    @else
                    <select id="pic" name="pic" multiple="multiple" class="form-control" disabled>                            
                        @foreach ($pic as $pics)                    
                        <option value="{{$pics->id}}" @if($loginInitial->initial == $pics->initial) selected @endif>{{$pics->initial}}</option>
                        @endforeach
                    </select>
                    @endif                
                </div>
            </div>

            <div class="row" style="zoom: 100%">
                <div class="col col-md-3">
                    Role
                </div>
                <div class="col col-md-1">
                    <select id="sel_role" name="sel_role" multiple="multiple" class="form-control" >                            
                        @foreach ($role as $roles)                    
                        <option value="{{$roles->id}}">{{$roles->role}}</option>
                        @endforeach                    
                    </select>
                </div>
            </div>

            <div class="row" style="zoom: 100%">
                <div class="col col-md-3">
                    Staff
                </div>
                <div class="col col-md-1">
                    <select id="sel_staff" name="sel_staff" multiple="multiple" class="form-control" >                            
                        @foreach ($staff as $staffs)
                        <option value="{{$staffs->id}}">{{$staffs->initial}}</option>
                        @endforeach
                    </select>
                </div>
            </div>  
            
            <div class="row" style="zoom: 100%;margin-top: 25px">
                <div class="col col-md-3">                    
                </div>
                <div class="col col-md-1">
                    <button id="btn_load" name="btn_load" class="btn btn-primary" type="button" onclick="getInputAllData()" style="width: 150px">
                        <span id="loadingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                        <span id="loadingText">Confirm</span>
                    </button>
                </div>
            </div>            
        </div>
        <div style="float: left">
            <div class="col col-md-3" >
                <input type="button" class="btn btn-primary" value="clear" onclick="clearInputFilter()" style="width: 150px">
            </div>
        </div>
    </div>

    <div id="spreadsheet" name="spreadsheet" style=""></div>
   
    <input type="hidden" value="" id="postArray" name="postArray">
    <input type="hidden" id="budget_info" name="budget_info" value="">

</form>

<script src="{{ asset('js/budgetInput.js') }}"></script>

@endsection