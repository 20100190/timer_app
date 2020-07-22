@extends('layouts.app')

@section('content') 

<div style="margin-left: 50px">

    <form action="test3/save" method="POST" name="s">
        {{ csrf_field() }}  

        <div id="filter" style="height: 25%;min-height: 265px">
            <table class="filter_area">
                <tr>
                    <td style="width: 100px">Client</td>                    
                    <td>
                        <select id="client" name="client" multiple="multiple" class="form-control">                           
                            @foreach ($client as $clients)
                            <option value="{{$clients->id}}">{{$clients->name}}</option>
                            @endforeach
                        </select>
                    </td>                    
                </tr>
                <tr>
                    <td>Project</td>                   
                    <td>
                        <select id="project" name="project" multiple="multiple" style="width: 200px">                          
                            @foreach ($project as $projects)
                            <option value="{{$projects->name}}">{{$projects->name}}</option>
                            @endforeach
                        </select>
                    </td>                   
                </tr>
                <tr>
                    <td>FYE</td>
                    <td>
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
                    </td>
                </tr>
                <tr>
                    <td>VIC</td>
                    <td>
                        <select id="vic" name="vic" multiple="multiple" class="form-control" >                            
                            <option value="1">VIC</option>
                            <option value="2">IC</option>
                            <option value="3">C</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>PIC</td>
                    <td>
                        <select id="pic" name="pic" multiple="multiple" class="form-control" >                            
                            @foreach ($pic as $pic)
                            <option value="{{$pic->id}}">{{$pic->initial}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Role</td>
                    <td>
                        <select id="sel_role" name="sel_role" multiple="multiple" class="form-control" >                            
                            <option value="1">Partner</option>
                            <option value="2">Senior Manager</option> 
                            <option value="3">Manager</option>
                            <option value="4">Experienced Senior</option>
                            <option value="5">Senior</option>
                            <option value="6">Experienced Staff</option>
                            <option value="7">Staff</option>                            
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Staff</td>
                    <td>
                        <select id="sel_staff" name="sel_staff" multiple="multiple" class="form-control" >                            
                            @foreach ($staff as $staff)
                            <option value="{{$staff->id}}">{{$staff->initial}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr style="zoom: 100%">
                    <td style="font-size: 3px">Date From</td>
                    <td>
                        <input type="text" style="width:150px;margin-right: 20px;font-size: 3px" class="form-control datepicker1" id="filter_date" name="filter_date" placeholder="mm/dd/yyyy" value="">                            

                    </td>
                    <td>                      
                        <button id="btn_load" name="btn_load" class="btn btn-primary" type="button" onclick="testData()">
                            <span id="loadingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                            <span id="loadingText">Confirm</span>
                        </button>
                    </td>                    
                </tr>    
            </table> 

        </div>       
       
        <div id="spreadsheet" name="spreadsheet" style="height: 75%;width:100%;zoom: 100%;margin-right: 20px"></div>

        <input type="hidden" value="" id="postArray" name="postArray">
        <input type="hidden" id="budget_info" name="budget_info" value="">

    </form>

</div>   

@endsection