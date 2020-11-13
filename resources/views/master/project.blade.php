@extends('layouts.main')
<style type="text/css">
    #p2146-2-table {
        width: 100%;
        font-size: 14px;
        border-collapse: collapse;
    }

    #p2146-2-table th,
    #p2146-2-table td {
        border: 1px solid #ccc;
        padding: 12px 8px;
        text-align: center;
    }

    #p2146-2-table th {
        background-color: #ad1457;
        color: #fff;
    }

    #p2146-2-table input,
    #p2146-2-table select {
        width: 100px;
        cursor: pointer;
    }

    #p2146-2-table i {
        font-size: 18px;
        color: #7cb342;
    }

    #p2146-2-table input[type='button'] {
        background-color: #f0f0f0;
        border: 1px solid #aaa;
        border-radius: 2px;
        box-shadow: 0 1px 2px #999;
        font-size: 14px;
    }

    #p2146-2-tbody tr:first-child {
        display: none;
    }

    #p2146-3-table {
        width: 100%;
        font-size: 14px;
        border-collapse: collapse;
    }

    #p2146-3-table th,
    #p2146-3-table td {
        border: 1px solid #ccc;
        padding: 12px 8px;
        text-align: center;
    }

    #p2146-3-table th {
        background-color: #ad1457;
        color: #fff;
    }

    #p2146-3-table input,
    #p2146-3-table select {
        width: 100px;
        cursor: pointer;
    }

    #p2146-3-table i {
        font-size: 18px;
        color: #7cb342;
    }

    #p2146-3-table input[type='button'] {
        background-color: #f0f0f0;
        border: 1px solid #aaa;
        border-radius: 2px;
        box-shadow: 0 1px 2px #999;
        font-size: 14px;
    }

    #p2146-3-tbody tr:first-child {
        display: none;
    }

</style>

@section('content') 

<input type="hidden" id="reqClient" name="reqClient" @if(isset($reqClient)) value="{{$reqClient}}" @else value="" @endif>
<input type="hidden" id="reqProject" name="reqProject" @if(isset($reqProject)) value="{{$reqProject}}" @else value="" @endif>

<!--<form method="POST" action="/webform/test3" enctype="multipart/form-data" id="taskEnter" name="taskEnter" style="margin-left: 20px">-->
<form method="POST" enctype="multipart/form-data" id="taskEnter" name="taskEnter" style="margin-left: 20px" autocomplete="off">
    <!--@csrf-->
    <div class="block-background-color" style="padding-left: 16px;width: 1150px">
        <div class="project-layout" style="float: left;">        
            <label>Client<font style="color: red;vertical-align: middle">&nbsp;*</font></label><br>
            <select id="client" name="client" class="form-control">
                <option value="blank"></option>
                @foreach ($client as $clients)
                <option value="{{$clients->id}}">{{$clients->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="project-layout" style="float: left;width: 150px">        
            <label>Project Type<font style="color: red;vertical-align: middle">&nbsp;*</font></label><br>
            <select id="project_type" name="project_type" class="form-control" style="width: 100%" onchange="getProjectName();">      
                <option value="blank"></option>
                @foreach ($projectType as $projectTypes)
                <option value="{{$projectTypes->project_type}}">{{$projectTypes->project_type}}</option>
                @endforeach                 
            </select>
        </div>

        <div class="project-layout" style="float: left;width: 133px">        
            <label>Project Year<font style="color: red;vertical-align: middle">&nbsp;*</font></label><br>
            <select id="project_year" name="project_year" class="form-control" style="width: 100%" onchange="getProjectName();">     
                <option value="blank"></option>
                @for($i=2015;$i<=2023;$i++)
                <option value="{{$i}}">{{$i}}</option>
                @endfor                
            </select>
        </div>

        <div class="project-layout" style="float: left;width: 180px;margin-right: 19px">        
            <label>Harvest Project Name</label><br>
            <input type="text" value="" class="form-control" id="harvest_project_name" name="harvest_project_name" readonly>
        </div>

        <div class="project-layout" style="float: left">   
            <label>&nbsp;</label><br>            
            <button class="btn btn-primary" type="button" style="width: 65px" onclick="loadTask('search')">                
                <span id="loadingText">Search</span>
            </button>
        </div>
        
        <div class="project-layout" style="float: left">   
            <label>&nbsp;</label><br>            
            <button class="btn btn-primary" type="button" style="width: 100px;background-color: #DCDCDC" onclick="clickDuplicate()">                
                <span id="loadingText">Duplicate</span>
            </button>
        </div>
        <div class="project-layout" style="float: left;width: 1200px; height: 20px">            
        </div>

        <div style="clear: left"></div>
        
        <!--背景色-->
        <div class="project-layout" style="float: left;width: 1200px;background-color: white; height: 40px;margin-left: -16px">            
        </div>
        
        <div style="clear: left"></div>
        
        <div class="project-layout" style="margin-top: 20px;float: left;width: 170px">        
            <label>PIC</label><br>
            <select id="pic" name="pic" class="form-control" >                            
                @foreach ($pic as $pic)
                <option value="{{$pic->id}}">{{$pic->initial}}</option>
                @endforeach
            </select>
        </div>

        <div class="project-layout" style="margin-top: 20px;float: left">        
            <label>Starts On</label><br>
            <input type="text" style="width:150px;" class="form-control datepicker1" id="starts_on" name="starts_on" placeholder="mm/dd/yyyy" value="">                            
        </div>

        <div class="project-layout" style="margin-top: 20px;float: left">        
            <label>Ends On</label><br>
            <input type="text" style="width:150px;" class="form-control datepicker1" id="ends_on" name="ends_on" placeholder="mm/dd/yyyy" value="">                            
        </div>

        <div class="project-layout" style="margin-top: 20px;float: left;width: 133px">        
            <label>FYE</label><br>
            <select id="fye" name="fye" class="form-control">                            
                <option value="1/31">1/31</option>
                <option value="2/28">2/28</option>
                <option value="3/31">3/31</option>
                <option value="4/30">4/30</option>
                <option value="5/31">5/31</option>
                <option value="6/30">6/30</option>
                <option value="7/31">7/31</option>
                <option value="8/31">8/31</option>
                <option value="9/30">9/30</option>
                <option value="10/31">10/31</option>
                <option value="11/30">11/30</option>
                <option value="12/31">12/31</option>
            </select>
        </div>
        <div class="project-layout" style="margin-top: 20px;float: left;width: 181px">        
            <label>Billable</label><br>
            <select id="billable" name="billable" class="form-control" style="width: 100%">            
                <option value="YES">Yes</option>
                <option value="NO">No</option>              
            </select>
        </div>
         <div class="project-layout" style="margin-top: 20px;float: left;width: 181px">        
            <label>Status</label><br>
            <select id="is_archive" name="is_archive" class="form-control" style="width: 100%">            
                <option value="0">Active</option>
                <option value="1">Archived</option>              
            </select>
        </div>

        <div style="clear: left"></div>

        <div style="float: left;width: 864px">        
            <label>Notes</label><br>
            <input type="text" id="note" name="note" class="form-control" style="width: 100%">
        </div>

    </div>

    <div style="clear: left"></div>

    <!--task-->
    <div style="float: left;margin-top: 20px;margin-right: 0px">
        <!--<span class="label label-default" style="font-size: 12px">Task</span>-->       
        <div><label style="font-size: 20;margin-right: 20px">Task</label><input type="button" id="addTaskList" name="addTaskList" class="btn btn-primary btn-sm project-button" value="Add" onclick="appendRow()"></div>
        <table border="0" id="tbl" style="font-size: 12px;table-layout: fixed;width: 330px" class="table table-sm">
            <thead>
                <tr>
                    <th class="project-font-size" name="task_no" style="text-align:center; width:30px;">No</th>
                    <th class="project-font-size" style="width: 200px">Task</th>                        
                    <th style="width:30px;"></th>
                    <th style="width:50px;"></th>  
                    <th style="width:50px;"></th>   
                </tr>
            </thead>
            <tbody id="task_body">
            </tbody>
        </table>
    </div>

    <div style="float: left;margin-top: 20px">        
        <div>
            <label style="font-size: 20;margin-right: 25px">Project Budget</label>
            <input type="button" id="addBudgetList" name="addBudgetList" value="Add" class="btn btn-primary btn-sm project-button" style="width: 147px" onclick="appendBudgetRow()">
        </div>
        <table border="0" id="budget_list" class="table table-sm" style="font-size: 12px;table-layout: fixed;width: 650px">                
            <thead>
                <tr>
                    <th class="project-font-size" style="text-align:center; width:40px;">No</th>
                    <th class="project-font-size" style="width: 70px">Staff</th>
                    <th class="project-font-size" style="width: 120px">Role</th>
                    <th class="project-font-size" style="width: 100px">Budget Hours</th>
                    <th class="project-font-size" style="width: 60px">Rate</th>
                    <th class="project-font-size" style="width: 83px">Budget</th>                        
                    <th style="width:40px;"> </th>
                </tr> 
            </thead>
            <tbody id="project_body"></tbody>
            <tfoot>
                <tr>
                    <td style="text-align:right; width:40px;"></td>
                    <td style="width: 70px"></td>
                    <td style="width: 120px"></td>                    
                    <td style="width: 120px"></td>                    
                    <td class="project-font-size" style="width: 50px;">Total</td>
                    <td class="project-font-size" style="width: 60px;text-align: right"><div style="float: left;font-size: 12px;text-align: left">$</div><span id="total_budget" style="padding-right: 19px">0</span></td>                       
                    <td style="width:40px;"> </td>
                </tr>
                <tr style="height: 30px">
                    <td style="text-align:right; width:40px;"></td>
                    <td style="width: 70px"></td>
                    <td style="width: 80px"></td>
                    <td style="width: 120px"></td>
                    <td style="width: 50px"></td>
                    <td style="width: 60px"></td>                        
                    <td style="width:40px;"> </td>
                </tr>                
            </tfoot>
        </table>
       
       
        <!--<table border="0" id="budget_engagement" class="table table-sm" style="font-size: 12px;table-layout: fixed;width: 650px">
            <thead>
                <tr>
                    <th class="project-font-size" style="text-align:center; width:30px;">No</th>
                    <th class="project-font-size" style="text-align:center; width:130px;">Type</th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_1">Jan-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_2">Feb-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_3">Mar-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_4">Apr-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_5">May-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_6">Jun-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_7">Jul-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_8">Aug-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_9">Sep-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_10">Oct-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_11">Nov-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_12">Dec-20</span></th>
                    <th class="project-font-size" style="width: 100px;text-align: center">Total</th>
                    <th style="width:40px;"> </th>
                    <th style="width:40px;"> </th>
                </tr> 
            </thead>
            <tbody id="budget_engagement_body">                
            </tbody>
            <tfoot>
                <tr>
                    <td class="project-font-size" style="text-align:center; width:130px;">Total</td>
                    <td class="project-font-size" style="width: 90px;text-align: center"></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_jan" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_feb" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_mar" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_apr" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_may" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_jun" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_jul" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_aug" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_sep" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_oct" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_nov" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_dec" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_grand" value="0" style="text-align: right" readonly></td>
                    <td style="width:40px;"> </td>
                </tr> 
            </tfoot>
        </table>-->
        
        <!--<table border="0" id="budget_list_2" class="table table-sm" style="font-size: 12px;table-layout: fixed;width: 650px">
            <thead>
                <tr>
                    <th class="project-font-size" style="text-align:center; width:40px;"></th>
                    <th class="project-font-size" style="width: 70px"></th>
                    <th class="project-font-size" style="width: 120px"></th>
                    <th class="project-font-size" style="width: 100px"></th>
                    <th class="project-font-size" style="width: 60px"></th>
                    <th class="project-font-size" style="width: 70px"></th>                        
                    <th style="width:40px;"> </th>
                </tr> 
            </thead>
            <tr>
                <td style="text-align:right; width:40px;"></td>
                <td style="width: 70px"></td>
                <td style="width: 80px"></td>
                <td class="project-font-size" colspan="2" style="width: 170px;">Difference</td>                    
                <td class="project-font-size" style="width: 60px;text-align: right"><div style="float: left;font-size: 12px;text-align: left">$</div><span id="defference" style="padding-right: 19px">0</span></td>                       
                <td style="width:40px;"> </td>
            </tr>
            <tr>
                <td style="text-align:right; width:40px;"></td>
                <td style="width: 70px"></td>
                <td style="width: 80px"></td>
                <td class="project-font-size" colspan="2" style="width: 170px;">Realization</td>                    
                <td class="project-font-size" style="width: 60px;text-align: right"><span id="realization" style="padding-right: 8px">0%</span></td>                      
                <td style="width:40px;"> </td>
            </tr>
            <tr>
                <td></td>                    
                <td></td>
                <td>
                    @if(isset($isApproval) && $isApproval == 1)
                    @if(isset($isProjectApproved) && $isProjectApproved == 1)
                    <button id="btn_approve" name="btn_approve" class="btn btn-primary project-button" type="button" onclick="" style="margin-top: 30px;background-color: #DCDCDC" disabled>
                        <span id="savingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                        <span id="savingText">Approved</span>
                    </button>
                    @else
                    <button id="btn_approve" name="btn_approve" class="btn btn-primary project-button" type="button" onclick="saveApprove()" style="margin-top: 30px">
                        <span id="savingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                        <span id="savingText">Approve</span>
                    </button>
                    @endif     
                    @else
                    <button id="btn_approve" name="btn_approve" class="btn btn-primary project-button" type="button" onclick="" style="margin-top: 30px;visibility: hidden">
                        <span id="savingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                        <span id="savingText">Approve</span>
                    </button>
                    @endif
                </td>
                <td>                       
                </td>
                <td>
                    <button id="btn_save" name="btn_save" class="btn btn-primary project-button" type="button" onclick="saveForm()" style="margin-top: 30px">
                        <span id="savingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                        <span id="savingText">Save</span>
                    </button>
                </td>
            </tr>
        </table>-->
    </div>    
    
    <div style="clear: both"></div>
    
    <div>
            <label style="font-size: 20;margin-right: 25px;float:left">Engagement Fee</label>
            <input type="button" id="engegementFee" name="engegementFee" value="Add" class="btn btn-primary btn-sm project-button" style="width: 147px;float: left" onclick="appendEngagementRow('',0,0,0,0,0,0,0,0,0,0,0,0)">
            <label style="font-size: 14;margin-left: 25px;float:left;margin-top: 6px;margin-right: 10px">Start</label>
            <select id="start_month" name="start_month" class="form-control" style="width: 100px;float:left;margin-right: 20px" onchange="setEngagementHeader()">
                <option value="1">Jan</option>
                <option value="2">Feb</option>
                <option value="3">Mar</option>
                <option value="4">Apr</option>
                <option value="5">May</option>
                <option value="6">Jun</option>
                <option value="7">Jul</option>
                <option value="8">Aug</option>
                <option value="9">Sep</option>
                <option value="10">Oct</option>
                <option value="11">Nov</option>
                <option value="12">Dec</option>
            </select>
            <select id="engagement_year" name="engagement_year" class="form-control" style="width: 100px;" onchange="setEngagementHeader()">     
                <option value="blank"></option>
                @for($i=2019;$i<=2030;$i++)
                <option value="{{$i}}">{{$i}}</option>
                @endfor                
            </select>
        </div>
    
    <table border="0" id="budget_engagement" class="table table-sm" style="font-size: 12px;table-layout: fixed;width: 650px">
            <thead>
                <tr>
                    <th class="project-font-size" style="text-align:center; width:30px;">No</th>
                    <th class="project-font-size" style="text-align:center; width:200px;">Type</th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_1">Jan-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_2">Feb-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_3">Mar-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_4">Apr-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_5">May-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_6">Jun-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_7">Jul-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_8">Aug-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_9">Sep-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_10">Oct-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_11">Nov-20</span></th>
                    <th class="project-font-size" style="width: 90px;text-align: center"><span id="header_12">Dec-20</span></th>
                    <th class="project-font-size" style="width: 100px;text-align: center">Total</th>
                    <th style="width:40px;"> </th>
                    <th style="width:40px;"> </th>
                </tr> 
            </thead>
            <tbody id="budget_engagement_body">                
            </tbody>
            <tfoot>
                <tr>
                    <td class="project-font-size" style="text-align:center; width:130px;"></td>
                    <td class="project-font-size" style="width: 90px;text-align: center;vertical-align: middle">Total</td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_jan" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_feb" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_mar" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_apr" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_may" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_jun" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_jul" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_aug" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_sep" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_oct" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_nov" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_dec" value="0" style="text-align: right" readonly></td>
                    <td class="project-font-size" style="width: 90px;text-align: center"><input type="text" class="form-control" id="total_grand" value="0" style="text-align: right" readonly></td>
                    <td style="width:40px;"> </td>
                </tr> 
            </tfoot>
        </table>
    
    <table border="0" id="budget_list_2" class="table table-sm" style="margin-left: 360px;font-size: 12px;table-layout: fixed;width: 650px">
        <thead>
            <tr>
                <th class="project-font-size" style="text-align:center; width:40px;"></th>
                <th class="project-font-size" style="width: 70px"></th>
                <th class="project-font-size" style="width: 120px"></th>
                <th class="project-font-size" style="width: 100px"></th>
                <th class="project-font-size" style="width: 60px"></th>
                <th class="project-font-size" style="width: 70px"></th>                        
                <th style="width:40px;"> </th>
            </tr> 
        </thead>
        <tr>
            <td style="text-align:right; width:40px;"></td>
            <td style="width: 70px"></td>
            <td style="width: 80px"></td>
            <td class="project-font-size" colspan="2" style="width: 170px;">Difference</td>                    
            <td class="project-font-size" style="width: 60px;text-align: right"><div style="float: left;font-size: 12px;text-align: left">$</div><span id="defference" style="padding-right: 19px">0</span></td>                       
            <td style="width:40px;"> </td>
        </tr>
        <tr>
            <td style="text-align:right; width:40px;"></td>
            <td style="width: 70px"></td>
            <td style="width: 80px"></td>
            <td class="project-font-size" colspan="2" style="width: 170px;">Realization</td>                    
            <td class="project-font-size" style="width: 60px;text-align: right"><span id="realization" style="padding-right: 8px">0%</span></td>                      
            <td style="width:40px;"> </td>
        </tr>
        <tr>
            <td></td>                    
            <td></td>
            <td>
                @if(isset($isApproval) && $isApproval == 1)
                @if(isset($isProjectApproved) && $isProjectApproved == 1)
                <button id="btn_approve" name="btn_approve" class="btn btn-primary project-button" type="button" onclick="saveApprove()" style="margin-top: 30px;background-color: #DCDCDC">
                    <span id="savingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                    <span id="savingText">Approved</span>
                </button>
                @else
                <button id="btn_approve" name="btn_approve" class="btn btn-primary project-button" type="button" onclick="saveApprove()" style="margin-top: 30px">
                    <span id="savingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                    <span id="savingText">Approve</span>
                </button>
                @endif     
                @else
                <button id="btn_approve" name="btn_approve" class="btn btn-primary project-button" type="button"  onclick="saveApprove()" style="margin-top: 30px;visibility: hidden">
                    <span id="savingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                    <span id="savingText">Approve</span>
                </button>
                @endif
            </td>
            <td>                       
            </td>
            <td>
                <button id="btn_save" name="btn_save" class="btn btn-primary project-button" type="button" onclick="saveForm()" style="margin-top: 30px">
                    <span id="savingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                    <span id="savingText">Save</span>
                </button>
            </td>
        </tr>
    </table>  
    

    
    <input type="hidden" id="staff_info" name="staff_info" value="">
    <input type="hidden" id="task_info" name="task_info" value="">
    <input type="hidden" id="rec_project_id" name="rec_project_id" value="">

</form>

<div style="clear: both">
</div>


<script>
    // "global" vars, built using blade
    var imagesUrl = '{{ URL::asset('/image') }}';
</script>
<script src="{{ asset('js/project.js') }}"></script>

@endsection