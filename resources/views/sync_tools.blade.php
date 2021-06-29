@extends('layouts.main')
@section('content') 

<style>
    .button-dist {
        margin-right: 10px;
    }
</style>

<div style="margin-left: 40px;margin-top: 40px">

    <label style="font-size: 20px;">Harvest</label><br><br>
 
    <button id="btn_load" name="btn_load" class="btn btn-primary button-dist" type="button" onclick="syncProjectData()" style="width: 150px;margin-left: 0px">        
        <span id="loadingText">Sync Project</span>
    </button>

    <button id="btn_load" name="btn_load" class="btn btn-primary button-dist" type="button" onclick="syncUserData()" style="width: 150px;margin-left: 0px">        
        <span id="loadingText">Sync User</span>
    </button>

    <button id="btn_load" name="btn_load" class="btn btn-primary button-dist" type="button" onclick="syncClientData()" style="width: 150px;margin-left: 0px">        
        <span id="loadingText">Sync Client</span>
    </button>    

    <button id="btn_load" name="btn_load" class="btn btn-primary button-dist" type="button" onclick="syncInvoiceData()" style="width: 150px;margin-left: 0px">        
        <span id="loadingText">Sync Invoice</span>
    </button>

    <button id="btn_load" name="btn_load" class="btn btn-primary button-dist" type="button" onclick="syncExpenseData()" style="width: 150px;margin-left: 0px">        
        <span id="loadingText">Sync Expense</span>
    </button>

    <button id="btn_load" name="btn_load" class="btn btn-primary button-dist" type="button" onclick="syncTaskData()" style="width: 150px;margin-left: 0px">        
        <span id="loadingText">Sync Tasks</span>
    </button>

    <br><br><br>


    <div style="float: left;padding-top: 5px"><label>From</label></div>
    <input type="text" class="form-control datepicker1" id="time-entry-from" name="time-entry-from" style="float: left;width: 150px;margin-right: 10px">
    <div style="float: left;padding-top: 5px"><label>To</label></div>
    <input type="text" class="form-control datepicker1" id="time-entry-to" name="time-entry-to" style="float: left;width: 150px;margin-right: 10px">

    <button id="btn_load" name="btn_load" class="btn btn-primary" type="button" onclick="syncTimeEntry()" style="width: 150px;margin-left: 0px">        
        <span id="loadingText">Sync Time Entry</span>
    </button>

    <br><br><br><br><br>

    <label style="font-size: 20px;">Budget-Webform</label><br><br>

    <button id="btn_load" name="btn_load" class="btn btn-primary" type="button" onclick="syncEngagementFee()" style="width: 150px;margin-left: 0px">        
        <span id="loadingText">Engagement Fee</span>
    </button>

    <br><br>

    <button id="btn_load" name="btn_load" class="btn btn-primary" type="button" onclick="createProjectData()" style="width: 150px;margin-left: 0px">        
        <span id="loadingText">Create Project</span>
    </button>

   
  
</div>
<script>
    // "global" vars, built using blade
    var imagesUrl = '{{ URL::asset('/image') }}';
    $('.datepicker1').datepicker({
        format: "mm/dd/yyyy",
        language: "en",
        autoclose: true,
        orientation: 'bottom left'
    });
</script>
<script src="{{ asset('js/syncTool.js') }}"></script>
@endsection