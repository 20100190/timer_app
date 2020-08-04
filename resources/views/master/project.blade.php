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

<!-- Message Toast -->
<!--<div class="position-absolute w-100 d-flex flex-column p-4" style="z-index: -1">
    <div class="toast ml-auto" role="alert" data-delay="700" data-autohide="true" style="width: 400px;height: 100px">
        <div class="toast-header">
            <strong class="mr-auto text-primary">保存完了</strong>
            <small class="text-muted"></small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="toast-body">保存完了しました。</div>
    </div>        
</div>-->

<form method="POST" action="/webform/test3" enctype="multipart/form-data" id="taskEnter" name="taskEnter">
    <!--@csrf-->
    <div style="float: left;">        
        <label>Client</label><br>
        <select id="client" name="client" class="form-control">
            <option value="blank"></option>
            @foreach ($client as $clients)
            <option value="{{$clients->id}}">{{$clients->name}}</option>
            @endforeach
        </select>
    </div>

    <div style="float: left;width: 150px">        
        <label>Project Type</label><br>
        <select id="project_type" name="project_type" class="form-control" style="width: 100%" onchange="getProjectName();">      
            <option value="blank"></option>
            @foreach ($projectType as $projectTypes)
            <option value="{{$projectTypes->project_type}}">{{$projectTypes->project_type}}</option>
            @endforeach
            <!--<option value="AUD">AUD</option>
            <option value="COMP">COMP</option>
            <option value="REV">REV</option>
            <option value="CTR">CTR</option>
            <option value="ITR">ITR</option>
            <option value="BM">BM</option>
            <option value="OTH">OTH</option>  -->          
        </select>
    </div>

    <div style="float: left;width: 150px">        
        <label>Project Year</label><br>
        <select id="project_year" name="project_year" class="form-control" style="width: 100%" onchange="getProjectName();">     
            <option value="blank"></option>
            <option value="2019">2019</option>
            <option value="2020">2020</option>
            <option value="2021">2021</option>
            <option value="2022">2022</option>
            <option value="2023">2023</option>
            <option value="2024">2024</option>
            <option value="2025">2025</option>
            <option value="2026">2026</option>            
        </select>
    </div>

    <div style="float: left">        
        <label>Harvest Project Name</label><br>
        <input type="text" value="" class="form-control" id="harvest_project_name" name="harvest_project_name" readonly>
    </div>

    <div style="float: left">   
        <label>&nbsp;</label><br>
        <!--<input type="button" onclick="loadTask()" class="btn btn-primary" style="margin-left: 20px" value="読込">-->
        <button class="btn btn-primary" type="button" onclick="loadTask()">
            <!--<span id="loadingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>-->
            <span id="loadingText">読込</span>
        </button>
    </div>



    <div style="clear: left"></div>

    <div style="float: left">        
        <label>PIC</label><br>
        <select id="pic" name="pic" class="form-control" >                            
            @foreach ($pic as $pic)
            <option value="{{$pic->id}}">{{$pic->initial}}</option>
            @endforeach
        </select>
    </div>

    <div style="float: left">        
        <label>Starts On</label><br>
        <input type="text" style="width:250px;margin-right: 20px" class="form-control datepicker1" id="starts_on" name="starts_on" placeholder="mm/dd/yyyy" value="">                            
    </div>

    <div style="float: left">        
        <label>Ends On</label><br>
        <input type="text" style="width:250px;margin-right: 20px" class="form-control datepicker1" id="ends_on" name="ends_on" placeholder="mm/dd/yyyy" value="">                            
    </div>

    <div style="float: left">        
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
    <div style="float: left">        
        <label>Billable</label><br>
        <select id="billable" name="billable" class="form-control" style="width: 100%">            
            <option value="0">Yes</option>
            <option value="1">No</option>              
        </select>
    </div>

    <div style="clear: left"></div>

    <div style="float: left;width: 700px">        
        <label>Notes</label><br>
        <input type="text" id="note" name="note" class="form-control" style="width: 100%">
    </div>

    <div style="clear: left"></div>

    <!--task-->
    <div style="float: left;margin-top: 20px;margin-right: 100px">
        <!--<span class="label label-default" style="font-size: 12px">Task</span>-->
        <label style="font-size: 20">Task</label>
        <div>新しい行を追加：<input type="button" id="add" name="add" class="btn btn-primary btn-sm" value="追加" onclick="appendRow()"></div>
        <table border="0" id="tbl" style="font-size: 12px;table-layout: fixed;width: 330px" class="table table-sm">
            <thead>
                <tr>
                    <th name="task_no" style="text-align:center; width:30px;">No</th>
                    <th style="width: 200px">Task</th>                        
                    <th style="width:50px;"></th>
                    <th style="width:50px;"></th>                        
                </tr>
            </thead>
            <tbody id="task_body">
            </tbody>
        </table>
    </div>

    <div style="float: left;margin-top: 20px">
        <label style="font-size: 20">Project Budget</label>
        <div>新しい行を追加：<input type="button" id="add" name="add" value="追加" class="btn btn-primary btn-sm" onclick="appendBudgetRow()"></div>
        <table border="0" id="budget_list" class="table table-sm" style="font-size: 12px;table-layout: fixed;width: 700px">                
            <thead>
                <tr>
                    <th style="text-align:center; width:40px;">No</th>
                    <th style="width: 70px">Staff</th>
                    <th style="width: 120px">Role</th>
                    <th style="width: 100px">Budget Hours</th>
                    <th style="width: 50px">Rate</th>
                    <th style="width: 60px">Budget</th>                        
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
                    <td style="width: 50px;text-align: right">Total</td>
                    <td style="width: 60px;text-align: right"><span id="total_budget">0</span></td>                       
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
                <tr>
                    <td style="text-align:right; width:40px;"></td>
                    <td style="width: 70px"></td>
                    <td style="width: 80px"></td>
                    <td colspan="2" style="width: 170px;text-align: right">Engagement Fee / Month</td>                    
                    <td style="width: 60px;text-align: right">
                        <input type="text" class="form-control form-control-sm" id="engagement_fee" name="engagement_fee" value="0" onchange="calc()" style="text-align: right;width: 100%">
                    </td>                        
                    <td style="width:40px;"> </td>
                </tr>
                <tr>
                    <td style="text-align:right; width:40px;"></td>
                    <td style="width: 70px"></td>
                    <td style="width: 80px"></td>
                    <td colspan="2" style="width: 170px;text-align: right"># of montl</td>                    
                    <td style="width: 60px;">
                        <input type="text" class="form-control form-control-sm" id="engagement_monthly" name="engagement_monthly" value="0" onchange="calc()" style="text-align: right;width: 100%">
                    </td>                        
                    <td style="width:40px;"> </td>
                </tr>
                <tr style="border-bottom:1px #000000 solid;">
                    <td style="text-align:right; width:40px;"></td>
                    <td style="width: 70px"></td>
                    <td style="width: 80px"></td>
                    <td colspan="2" style="width: 170px;text-align: right">Adjustmentws</td>                    
                    <td style="width: 60px;text-align: right">
                        <input type="text" class="form-control form-control-sm" id="adjustments" name="adjustments" value="0" onchange="calc()" style="text-align: right;width: 100%">
                    </td>                       
                    <td style="width:40px;"> </td>
                </tr>
                <tr>
                    <td style="text-align:right; width:40px;"></td>
                    <td style="width: 70px"></td>
                    <td style="width: 80px"></td>
                    <td colspan="2" style="width: 170px;text-align: right">Engagement Fee</td>                    
                    <td style="width: 60px;text-align: right"><span id="engagement_total">0</span></td>                       
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
                <tr>
                    <td style="text-align:right; width:40px;"></td>
                    <td style="width: 70px"></td>
                    <td style="width: 80px"></td>
                    <td colspan="2" style="width: 170px;text-align: right">Defference</td>                    
                    <td style="width: 60px;text-align: right"><span id="defference">0</span></td>                       
                    <td style="width:40px;"> </td>
                </tr>
                <tr>
                    <td style="text-align:right; width:40px;"></td>
                    <td style="width: 70px"></td>
                    <td style="width: 80px"></td>
                    <td colspan="2" style="width: 170px;text-align: right">Realization</td>                    
                    <td style="width: 60px;text-align: right"><span id="realization">0%</span></td>                      
                    <td style="width:40px;"> </td>
                </tr>
            </tfoot>
        </table>

    </div>    

    <input type="hidden" id="staff_info" name="staff_info" value="">


</form>

<div style="clear: both">
</div>

<!--<button onclick="save()">xxx</button>-->
<!--<input type="button" onclick="saveForm()" class="btn btn-primary" value="保存">-->
<button id="btn_save" name="btn_save" class="btn btn-primary" type="button" onclick="saveForm()">
    <span id="savingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
    <span id="savingText">保存</span>
</button>

<script src="{{ asset('js/project.js') }}"></script>

@endsection