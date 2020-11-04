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

@section('content')   

<div style="margin-left: 0px">

    <div style="overflow: hidden;height: 5%;margin-left: 20px;margin-right: 20px;text-align: right">       
        <!--<button style="" onclick="closeOverrall()">閉じる</button>-->
        <input type="image" id="btn_open_close" src="{{ URL::asset('/image') }}/close.png" onclick="closeOverrall()">
    </div>
    <div id="div3" style="width: 800px;height: 300px;position: absolute;margin-top: 0px;margin-left: 20px;z-index: 10">
        <div id="filter_left" style="float: left">
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-3">
                    <span class="line-height">Client</span>
                </div>
                <div class="col col-md-3">
                    <select id="client" name="client" multiple="multiple" class="form-control" onchange="setProjectData(true)">            
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
                    <select id="project" name="project" multiple="multiple" style="width: 200px;">                        
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
                <div class="col col-md-5">
                    <select id="pic" name="pic" multiple="multiple" class="form-control" >                            
                        @foreach ($pic as $pic)
                        <option value="{{$pic->id}}">{{$pic->initial}}</option>
                        @endforeach
                    </select>           
                </div>
                <div class="col col-md-3">
                    <select id="pic_or" name="pic_or" class="form-control" >                            
                        <option value="">And</option>
                        <option value="or">Or</option>
                    </select>           
                </div>
            </div>
            
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-3">
                    <span class="line-height">Staff</span>
                </div>
                <div class="col col-md-1">
                    <select id="sel_staff" name="sel_staff" multiple="multiple" class="form-control" >                            
                        @foreach ($staff as $staff)
                        <option value="{{$staff->id}}">{{$staff->initial}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="row entry-filter-bottom">
                <div class="col col-md-3">
                    <span class="line-height">Date From</span>
                </div>
                <div class="col col-md-1">
                    <input type="text" style="width:150px;margin-right: 20px" class="form-control datepicker1" id="filter_date_from" name="filter_date_from" placeholder="mm/dd/yyyy" value="">                            
                </div>                      
            </div>
            
            <div class="row entry-filter-bottom">
                <div class="col col-md-3">
                    <span class="line-height">Date To</span>
                </div>
                <div class="col col-md-4">
                    <input type="text" style="width:150px;margin-right: 20px" class="form-control datepicker1" id="filter_date_to" name="filter_date_to" placeholder="mm/dd/yyyy" value="">                            
                </div>  
                <div class="col col-md-1">
                   
                </div>
            </div> 
            <div class="row entry-filter-bottom">
                <div class="col">
                    <button id="btn_load" name="btn_load" class="btn btn-default" type="button" style="background-color: white;width: 150px;margin-left: 121px" onclick="clearShowFilter()">
                        <span id="loadingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                        <span id="loadingText">Clear</span>
                    </button>
                    <button id="btn_load" name="btn_load" class="btn btn-primary" type="button" style="width: 150px;margin-left: 0px" onclick="getData()">
                        <span id="loadingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                        <span id="loadingText">Search</span>
                    </button>
                </div>
               
            </div>
            
        </div>       

        <div id="filter_right" style="float: left;margin-left: 30px">
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-3">
                    <span class="line-height">FYE</span>
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
            
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-3">
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
                <div class="col col-md-3">
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
            
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-3">
                    <span class="line-height">Status</span>
                </div>
                <div class="col col-md-1">
                    <select id="sel_status" name="sel_status" class="form-control" >                                                    
                        <option value="All">All</option>                        
                        <option value="Active">Active</option>       
                        <option value="Inactive">Inactive</option>    
                    </select>
                </div>
            </div>
            
            <div class="row entry-filter-bottom" style="zoom: 100%;margin-top: 93px">
                <div class="col col-md-3">                    
                </div>
                <div class="col col-md-1">
                    
                </div>
            </div>
            
        </div>       
    </div>
    <div id="div1" style="overflow: hidden;height: 400px;margin-left: 20px;min-height: 350px;margin-right: 20px;position: relative";>           
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
                        <td class="column1_block col8 font-bold border-top-style-list header-background-color border-left-style-list">Overrall</td>
                        <td class="column1_block col9 font-bold border-top-style-list header-background-color">Total</td>
                        <td class="column1_block col10 border-top-style-list header-background-color"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column1_block font-bold border-top-style-list header-background-color" id="td_h2_month{{$i}}" style="width: 50px;z-index: 0;text-align: center"><span id="h2_month{{$i}}"></td>
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
                        <td class="column2_block col8 font-bold border-bottom-style-list header-background-color border-left-style-list" style="text-align: center">Name</td>
                        <td class="column2_block col9 font-bold border-bottom-style-list header-background-color" style="text-align: center">Total</td>
                        <td class="column2_block col10 font-bold border-bottom-style-list header-background-color" style="text-align: center">Unassigned hours</td>

                        @for($i=1;$i<=52;$i++)
                        <td class="column2_block font-bold border-bottom-style-list header-background-color" id="td_month{{$i}}" style="width: 50px;z-index: 0;text-align: center;"><span id="month{{$i}}"></td>
                        @endfor                        
                    </tr>
                </thead>
                <tbody>
                    @for($x = 1; $x <=30; $x++)
                    <tr>
                        <td class="column_row_block" style="background-color: white;"></td>
                        <td class="column_row_block col2" colspan="2" style="background-color: white;"></td>                        
                        <td class="column_row_block col4" style="background-color: white;"></td>
                        <td class="column_row_block col5" style="background-color: white;"></td>
                        <td class="column_row_block col6" style="background-color: white;"></td>
                        <td class="column_row_block col7" style="background-color: white;"></td>
                        <td class="column_row_block col8 border-left-style-list" style="background-color: white;"><span id="ot_initial{{$x}}"></span></td>
                        <td class="column_row_block col9" style="background-color: white;text-align: right"><span id="ot_ptotal{{$x}}"></span></td>
                        <td class="column_row_block col10" style="background-color: white;text-align: right"><span id="ot_uh{{$x}}"></span></td>

                        @for($i=1;$i<=52;$i++)
                        <td class="column_row_block col11" id="td_ot{{sprintf('%02d',$x)}}{{$i}}" style="background-color: white;text-align: right"><span id="ot{{sprintf('%02d',$x)}}{{$i}}"></td>
                        @endfor                         
                    </tr>       
                    @endfor                    
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
                        <td class="column_row_block col8 border-top-style-list border-bottom-style-list border-left-style-list" style="background-color: white;"></td>
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

     <a href="#" class="p" style="margin-left: 885px">Legend<span><img style="width: 1000px" src="{{ URL::asset('/image') }}/legend.JPG"></span></a>
     
    <div id="div2" style="overflow: scroll;height: 47%;margin-left: 20px;">  
        <div style="width: 100%">
            <table class="font1" border="0" id="budget_list" style="table-layout: fixed;width:100%">
                <thead>
                    <tr style="height: 30px">
                        <td class="column1_block font-bold border-top-style-list header-background-color border-left-style-list" style="width: 250px;left: 0px;text-align:center">Client</td>
                        <td class="column1_block col2 font-bold border-top-style-list header-background-color" style="text-align: center">Project</td>
                        <td class="column1_block col3 font-bold border-top-style-list header-background-color" style="text-align: center">FYE</td>
                        <td class="column1_block col4 font-bold border-top-style-list header-background-color" style="text-align: center">VIC</td>
                        <td class="column1_block col5 font-bold border-top-style-list header-background-color" style="text-align: center">PIC</td>
                        <td class="column1_block col6 font-bold border-top-style-list header-background-color" style="text-align: center">Role</td>
                        <td class="column1_block col7 font-bold border-top-style-list header-background-color" style="text-align: center">Staff</td>
                        <td class="column1_block col8 font-bold border-top-style-list header-background-color" style="text-align: center">Budget</td>
                        <td class="column1_block col9 font-bold border-top-style-list header-background-color" style="text-align: center">Assigned</td>
                        <td class="column1_block col10 font-bold border-top-style-list header-background-color" style="text-align: center">Diff</td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column1_block font-bold border-top-style-list header-background-color" id="td_h_month{{$i}}" style="width: 50px;z-index: 0;text-align: center;font-weight: bold"><span id="h_month{{$i}}"></td>
                        @endfor                          
                    </tr>
                    <tr style="height: 30px">
                        <td class="column2_block border-bottom-style-list header-background-color border-left-style-list" style="left: 0px;"></td>
                        <td class="column2_block col2 border-bottom-style-list header-background-color"></td>
                        <td class="column2_block col3 border-bottom-style-list header-background-color"></td>
                        <td class="column2_block col4 border-bottom-style-list header-background-color"></td>
                        <td class="column2_block col5 border-bottom-style-list header-background-color"></td>
                        <td class="column2_block col6 border-bottom-style-list header-background-color"></td>
                        <td class="column2_block col7 border-bottom-style-list header-background-color"></td>
                        <td class="column2_block col8 border-bottom-style-list header-background-color"></td>
                        <td class="column2_block col9 border-bottom-style-list header-background-color"></td>
                        <td class="column2_block col10 border-bottom-style-list header-background-color"></td>
                        @for($i=1;$i<=52;$i++)
                        <td class="column2_block col11 font-bold border-bottom-style-list header-background-color" id="td_d_month{{$i}}" style="font-weight: bold"><span id="d_month{{$i}}"></td>
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