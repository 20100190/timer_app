@extends('layouts.main')

<style type="text/css">
    .column1_block{
        z-index: 3;
        position: sticky;                
        background-color: white;     
        top: 0px;
    }
    .column2_block{
        z-index: 3;
        position: sticky;
        top:0;
        background-color: white;
        top: 30px
    }
    .column_row_block{
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
        bottom:0;
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

</style>      

@section('content')   

<div style="margin-left: 0px">

    <div style="overflow: hidden;height: 5%;margin-left: 20px;margin-right: 20px;text-align: right">       
        <!--<button style="" onclick="closeOverrall()">閉じる</button>-->
        <input type="image" id="btn_open_close" src="{{ URL::asset('/image') }}/close.png" onclick="closeOverrall()">
    </div>
    <div id="div3" style="width: 800px;height: 300px;position: absolute;margin-top: 50px;margin-left: 20px;z-index: 10">
        <div id="filter_left" style="float: left">
            <div class="row" style="zoom: 100%">
                <div class="col col-md-3" >
                    Client
                </div>
                <div class="col col-md-3">
                    <select id="client" name="client" multiple="multiple" class="form-control">            
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
                    <select id="project" name="project" multiple="multiple" style="width: 200px;">                        
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
                    <input type="text" style="width:100px;margin-right: 20px" class="form-control datepicker1" id="filter_date_from" name="filter_date_from" placeholder="mm/dd/yyyy" value="">                            
                </div>                
            </div>
            
            <div class="row">
                <div class="col col-md-3">
                    Date To
                </div>
                <div class="col col-md-1">
                    <input type="text" style="width:100px;margin-right: 20px" class="form-control datepicker1" id="filter_date_to" name="filter_date_to" placeholder="mm/dd/yyyy" value="">                            
                </div>                
            </div>
            
        </div>       

        <div id="filter_right" style="float: left;margin-left: 100px">
            <div class="row" style="zoom: 100%">
                <div class="col col-md-3">
                    PIC
                </div>
                <div class="col col-md-1">
                    <select id="pic" name="pic" multiple="multiple" class="form-control" >                            
                        @foreach ($pic as $pic)
                        <option value="{{$pic->id}}">{{$pic->initial}}</option>
                        @endforeach
                    </select>           
                </div>
            </div>
            
            <div class="row" style="zoom: 100%">
                <div class="col col-md-3">
                    Role
                </div>
                <div class="col col-md-1">
                    <select id="sel_role" name="sel_role" multiple="multiple" class="form-control" >                            
                        <option value="1">Partner</option>
                        <option value="2">Senior Manager</option> 
                        <option value="3">Manager</option>
                        <option value="4">Experienced Senior</option>
                        <option value="5">Senior</option>
                        <option value="6">Experienced Staff</option>
                        <option value="7">Staff</option>                            
                    </select>
                </div>
            </div>
            
            <div class="row" style="zoom: 100%">
                <div class="col col-md-3">
                    Staff
                </div>
                <div class="col col-md-1">
                    <select id="sel_staff" name="sel_staff" multiple="multiple" class="form-control" >                            
                        @foreach ($staff as $staff)
                        <option value="{{$staff->id}}">{{$staff->initial}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="row" style="zoom: 100%;margin-top: 60px">
                <div class="col col-md-3">                    
                </div>
                <div class="col col-md-1">
                    <button id="btn_load" name="btn_load" class="btn btn-primary" type="button" style="width: 150px" onclick="getData()">
                        <span id="loadingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                        <span id="loadingText">Confirm</span>
                    </button>
                </div>
            </div>
            
        </div>       
    </div>
    <div id="div1" style="overflow: hidden;height: 40%;margin-left: 20px;min-height: 350px;margin-right: 20px;position: relative";>          
        <div style="width: 100%;float: left">
            <table class="font1" border="0" id="summary_list" style="table-layout: fixed;width:98%;">
                <thead>
                    <!--Header 1-->
                    <tr style="height: 30px">                        
                        <td class="column1_block" style="width: 250px;left: 0px;background-color: white"></td>
                        <td class="column1_block col2" style="background-color: white"></td>
                        <td class="column1_block col3" style="background-color: white"></td>
                        <td class="column1_block col4" style="background-color: white"></td>
                        <td class="column1_block col5" style="background-color: white"></td>
                        <td class="column1_block col6" style="background-color: white"></td>
                        <td class="column1_block col7" style="background-color: white"></td>
                        <td class="column1_block col8 font-bold border-top-style-list" style="background-color: white">Overrall</td>
                        <td class="column1_block col9 font-bold border-top-style-list" style="background-color: white">Total</td>
                        <td class="column1_block col10 border-top-style-list" style="background-color: white"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column1_block font-bold border-top-style-list" id="td_h2_month{{$i}}" style="width: 50px;z-index: 0;text-align: center;background-color: white"><span id="h2_month{{$i}}"></td>
                        @endfor

                    </tr>
                    <!--Header 2-->
                    <tr style="height: 30px">
                        <td class="column2_block" style="left: 0px;background-color: white"></td>
                        <td class="column2_block col2" style="background-color: white"></td>
                        <td class="column2_block col3" style="background-color: white"></td>
                        <td class="column2_block col4" style="background-color: white"></td>
                        <td class="column2_block col5" style="background-color: white"></td>
                        <td class="column2_block col6" style="background-color: white"></td>
                        <td class="column2_block col7" style="background-color: white"></td>
                        <td class="column2_block col8 font-bold border-bottom-style-list" style="background-color: white;text-align: center">Name</td>
                        <td class="column2_block col9 font-bold border-bottom-style-list" style="background-color: white;text-align: center">Total</td>
                        <td class="column2_block col10 font-bold border-bottom-style-list" style="background-color: white;text-align: center">Unassigned hours</td>

                        @for($i=1;$i<=52;$i++)
                        <td class="column2_block font-bold border-bottom-style-list" id="td_month{{$i}}" style="width: 50px;z-index: 0;text-align: center;background-color: white"><span id="month{{$i}}"></td>
                        @endfor                        
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="column_row_block" style="background-color: white;"></td>
                        <td class="column_row_block col2" colspan="2" style="background-color: white;"></td>                        
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial1"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal1"></span></td>
                        <td class="column_row_block col10" style="background-color: white;text-align: right"></td>

                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot01{{$i}}" style="background-color: white;text-align: right"><span id="ot01{{$i}}"></td>
                        @endfor                         
                    </tr>                                
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" colspan="2" style="background-color: white;"></td>                        
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial2"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal2"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot02{{$i}}" style="background-color: white;text-align: right"><span id="ot02{{$i}}"></td>
                        @endfor    
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial3"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal3"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot03{{$i}}" style="background-color: white;text-align: right"><span id="ot03{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial4"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal4"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot04{{$i}}" style="background-color: white;text-align: right"><span id="ot04{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial5"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal5"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot05{{$i}}" style="background-color: white;text-align: right"><span id="ot05{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial6"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal6"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot06{{$i}}" style="background-color: white;text-align: right"><span id="ot06{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial7"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal7"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot07{{$i}}" style="background-color: white;text-align: right"><span id="ot07{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial8"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal8"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot08{{$i}}" style="background-color: white;text-align: right"><span id="ot08{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white;"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial9"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal9"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot09{{$i}}" style="background-color: white;text-align: right"><span id="ot09{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white;"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial10"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal10"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot10{{$i}}" style="background-color: white;text-align: right"><span id="ot10{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial11"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal11"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot11{{$i}}" style="background-color: white;text-align: right"><span id="ot11{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial12"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal12"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot12{{$i}}" style="background-color: white;text-align: right"><span id="ot12{{$i}}"></td>
                        @endfor             
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial13"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal13"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot13{{$i}}" style="background-color: white;text-align: right"><span id="ot13{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial14"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal14"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot14{{$i}}" style="background-color: white;text-align: right"><span id="ot14{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial15"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal15"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot15{{$i}}" style="background-color: white;text-align: right"><span id="ot15{{$i}}"></td>
                        @endfor  
                    </tr>                    
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial16"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal16"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot16{{$i}}" style="background-color: white;text-align: right"><span id="ot16{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial17"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal17"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot17{{$i}}" style="background-color: white;text-align: right"><span id="ot17{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial18"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal18"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot18{{$i}}" style="background-color: white;text-align: right"><span id="ot18{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial19"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal19"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot19{{$i}}" style="background-color: white;text-align: right"><span id="ot19{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial20"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal20"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot20{{$i}}" style="background-color: white;text-align: right"><span id="ot20{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial21"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal21"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot21{{$i}}" style="background-color: white;text-align: right"><span id="ot21{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial22"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal22"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot22{{$i}}" style="background-color: white;text-align: right"><span id="ot22{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial23"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal23"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot23{{$i}}" style="background-color: white;text-align: right"><span id="ot23{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial24"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal24"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot24{{$i}}" style="background-color: white;text-align: right"><span id="ot24{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial25"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal25"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot25{{$i}}" style="background-color: white;text-align: right"><span id="ot25{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial26"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal26"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot26{{$i}}" style="background-color: white;text-align: right"><span id="ot26{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial27"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal27"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot27{{$i}}" style="background-color: white;text-align: right"><span id="ot27{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial28"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal28"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot28{{$i}}" style="background-color: white;text-align: right"><span id="ot28{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial29"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal29"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot29{{$i}}" style="background-color: white;text-align: right"><span id="ot29{{$i}}"></td>
                        @endfor  
                    </tr>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8" style="background-color: white;"><span id="ot_initial30"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal30"></span></td>
                        <td class="column_row_block col10" style="background-color: white;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot30{{$i}}" style="background-color: white;text-align: right"><span id="ot30{{$i}}"></td>
                        @endfor  
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="column_row_block" style="background-color: white"></td>
                        <td class="column_row_block col2" style="background-color: white;"></td>
                        <td class="column_row_block col3" style="background-color: white;"></td>
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8 border-top-style-list border-bottom-style-list" style="background-color: white;"></td>
                        <td class="column_row_block col9 border-top-style-list border-bottom-style-list" style="background-color: white;text-align: right"><span id="otAll">0</span></td>
                        <td class="column_row_block col10 border-top-style-list border-bottom-style-list" style="background-color: white;text-align: right;"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11 border-top-style-list border-bottom-style-list" id="td_otTotal{{$i}}" style="background-color: white;text-align: right"><span id="otTotal{{$i}}"></td>
                        @endfor  
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div id="div2" style="overflow: scroll;height: 57%;margin-left: 20px";>  
        <div style="width: 100%">
            <table class="font1" border="0" id="budget_list" style="table-layout: fixed;width:100%">
                <thead>
                    <tr style="height: 30px">
                        <td class="column1_block font-bold border-top-style-list" style="width: 250px;left: 0px">Client</td>
                        <td class="column1_block col2 font-bold border-top-style-list">Project</td>
                        <td class="column1_block col3 font-bold border-top-style-list">FYE</td>
                        <td class="column1_block col4 font-bold border-top-style-list">VIC</td>
                        <td class="column1_block col5 font-bold border-top-style-list">PIC</td>
                        <td class="column1_block col6 font-bold border-top-style-list">Role</td>
                        <td class="column1_block col7 font-bold border-top-style-list">Staff</td>
                        <td class="column1_block col8 font-bold border-top-style-list">Budget</td>
                        <td class="column1_block col9 font-bold border-top-style-list">Assigned</td>
                        <td class="column1_block col10 font-bold border-top-style-list">Diff</td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column1_block font-bold border-top-style-list" id="td_h_month{{$i}}" style="width: 50px;z-index: 0;text-align: center;font-weight: bold"><span id="h_month{{$i}}"></td>
                        @endfor                          
                    </tr>
                    <tr style="height: 30px">
                        <td class="column2_block border-bottom-style-list" style="left: 0px;"></td>
                        <td class="column2_block col2 border-bottom-style-list"></td>
                        <td class="column2_block col3 border-bottom-style-list"></td>
                        <td class="column2_block col4 border-bottom-style-list"></td>
                        <td class="column2_block col5 border-bottom-style-list"></td>
                        <td class="column2_block col6 border-bottom-style-list"></td>
                        <td class="column2_block col7 border-bottom-style-list"></td>
                        <td class="column2_block col8 border-bottom-style-list"></td>
                        <td class="column2_block col9 border-bottom-style-list"></td>
                        <td class="column2_block col10 border-bottom-style-list"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column2_block col11 font-bold border-bottom-style-list" id="td_d_month{{$i}}" style="font-weight: bold"><span id="d_month{{$i}}"></td>
                        @endfor                           
                    </tr>
                </thead>
                <tbody></tbody>                
            </table>
        </div>
    </div>
</div>

<script>
    // "global" vars, built using blade
    var imagesUrl = '{{ URL::asset('/image') }}';
</script>
<script src="{{ asset('js/budgetWebform.js') }}"></script>
@endsection