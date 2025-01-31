@extends('layouts.main')

@section('content') 
<input type="hidden" id="receiveClientId" @if(isset($reqClientId)) value="{{$reqClientId}}" @else value="" @endif> 
<input type="hidden" id="receiveProjectId" @if(isset($reqProjectId)) value="{{$reqProjectId}}" @else value="" @endif>
<input type="hidden" id="receiveGroup" @if(isset($reqGroup)) value="{{$reqGroup}}" @else value="" @endif>

       <form action="" method="POST" id="s" name="s" style="margin-left: 20px;overflow-x: scroll;"  autocomplete="off">
    {{ csrf_field() }}  
    <div id="filter_area" style="margin-top: 30px;">
        <div id="filter_left" style="float: left;height: 150px;margin-bottom: 50px">
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-2" >
                    <span class="line-height">Client<font style="color: red;vertical-align: middle">&nbsp;&nbsp;&nbsp;*</font></span>
                </div>
                <div class="col col-md-3">
                    <select id="client" name="client" class="form-control select2" data-display="static" onchange="setProjectData()">    
                        <option value="">&nbsp;</option>
                        @foreach ($client as $clients)
                        <option value="{{$clients->id}}">{{$clients->name}}</option>
                        @endforeach
                    </select>
                </div>  
            </div>

            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-2">
                    <span class="line-height">Project<font style="color: red;vertical-align: middle">&nbsp;*</font></span>
                </div>
                <div class="col col-md-1">
                    <select id="project" name="project" style="width: 200px">     
                        <option value="">&nbsp;</option>
                        @foreach ($project as $projects)
                        <option value="{{$projects->project_name}}">{{$projects->project_name}}</option>
                        @endforeach
                    </select>
                </div>                
            </div>
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-2">
                    <span class="line-height">Month</span>
                </div>
                <div class="col col-md-1">
                    <select id="group" name="group" style="width: 200px">     
                        <option value="">&nbsp</option>
                        <option value="January">January</option>
                        <option value="February">February</option>
                        <option value="March">March</option>
                        <option value="April">April</option>
                        <option value="May">May</option>
                        <option value="June">June</option>
                        <option value="July">July</option>
                        <option value="August">August</option>
                        <option value="September">September</option>
                        <option value="October">October</option>
                        <option value="November">November</option>
                        <option value="December">December</option>                        
                    </select>
                </div>       
            </div>

            <div class="row entry-filter-bottom">                           
                <div class="col col-md-3" >
                    <input type="button" class="btn btn-default" value="Clear" onclick="clearFilter()" style="background-color: white;width: 150px;margin-left: 85px">
                </div>
                <div class="col" >
                    <button id="btn_load" name="btn_load" class="btn btn-primary" type="button" onclick="loadPhaseData()" style="width: 150px;margin-left: 140px">
                        <span id="loadingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                        <span id="loadingText">Search</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div style="clear: both"></div>

    <div style="">
        @for($i=1;$i<=10;$i++)
        <div id="div_phase_table{{$i}}" style="margin-bottom: 50px">
            <div style="width: 2000px">
                <label style="font-size: 20px;width: 455px"><input type="text" id="label_phase{{$i}}" name="label_phase{{$i}}" style="width: 100px;vertical-align: middle;border:solid 0px;" readonly><span id="label_phase_desc{{$i}}" style="vertical-align: middle"></span></label>
                
                <input type="button" id="contact_list{{$i}}" name="contact_list{{$i}}" class="btn btn-primary btn-sm" style="width: 150px" value="Add" onclick="appendPhase1Row(this)">
                
                <input type="button" id="cd_expand{{$i}}" name="cd_expand{{$i}}" class="btn btn-primary btn-sm" style="margin-left: 80px;width: 110px" value="Copy All" onclick="expandColumn({{$i}},1)">
                <input type="button" id="pr_expand{{$i}}" name="pr_expand{{$i}}" class="btn btn-primary btn-sm" style="margin-left: 16px;width: 80px" value="Copy All" onclick="expandColumn({{$i}},2)">
                <input type="button" id="pp_expand{{$i}}" name="pp_expand{{$i}}" class="btn btn-primary btn-sm" style="margin-left: 16px;width: 90px" value="Copy All" onclick="expandColumn({{$i}},3)">
                <!--<input type="button" id="ps_expand{{$i}}" name="ps_expand{{$i}}" class="btn btn-primary btn-sm" style="margin-left: 16px;width: 90px" value="Expand" onclick="expandColumn({{$i}},4)">-->
                <input type="button" id="rr_expand{{$i}}" name="rr_expand{{$i}}" class="btn btn-primary btn-sm" style="margin-left: 126px;width: 80px" value="Copy All" onclick="expandColumn({{$i}},5)">
                <input type="button" id="pre_expand{{$i}}" name="pre_expand{{$i}}" class="btn btn-primary btn-sm" style="margin-left: 16px;width: 92px" value="Copy All" onclick="expandColumn({{$i}},6)">
                <!--<input type="button" id="rsi_expand{{$i}}" name="rsi_expand{{$i}}" class="btn btn-primary btn-sm" style="margin-left: 16px;width: 90px" value="Expand" onclick="expandColumn({{$i}},7)">-->
                <input type="button" id="rr2_expand{{$i}}" name="rr2_expand{{$i}}" class="btn btn-primary btn-sm" style="margin-left: 126px;width: 85px" value="Copy All" onclick="expandColumn({{$i}},8)">
                <input type="button" id="pre2_expand{{$i}}" name="pre2_expand{{$i}}" class="btn btn-primary btn-sm" style="margin-left: 16px;width: 90px" value="Copy All" onclick="expandColumn({{$i}},9)">
                <!--<input type="button" id="rsi2_expand{{$i}}" name="rsi2_expand{{$i}}" class="btn btn-primary btn-sm" style="margin-left: 16px;width: 90px" value="Expand" onclick="expandColumn({{$i}},10)">-->
                <!--<input type="button" id="me_expand{{$i}}" name="me_expand{{$i}}" class="btn btn-primary btn-sm" style="margin-left: 126px;width: 80px" value="Copy All" onclick="expandColumn({{$i}},11)">-->
                @if($i == 1)
                <input class="btn btn-primary btn-sm" id="btn_monthly_data" name="btn_monthly_data" type="button" style="height: 30px;margin-left: 126px;background-color: #483d8b" onclick="saveAnnualize()" value="Annualize">
                @endif
            </div>
            <table border="0" id="phase_{{$i}}" class="table table-sm" style="font-size: 14px;table-layout: fixed;width: 650px">  
                <thead>
                    <tr>
                        <th style="width: 30px">No</th>
                        <th style="width: 0px;visibility: collapse">ID</th>
                        <th style="width: 250px">Task</th>
                        <th style="width: 400px">Description</th>
                        <th style="width: 130px">Completion Due</th>
                        <th style="width: 100px">Preparer</th>
                        <th style="width: 110px">Planned Prep</th>
                        <th style="width: 110px">Prep Sign-Off</th>
                        <th style="width: 100px">Reviewer</th>
                        <th style="width: 110px">Planned Review</th>
                        <th style="width: 110px">Reviewer<br>Sign-Off</th>
                        <th style="width: 100px">Reviewer2</th>
                        <th style="width: 110px">Planned Review2</th>
                        <th style="width: 110px">2nd Reviewer Sign-Off</th>
                        <th style="width: 400px">Memo</th>
                        <th style="width: 40px">&nbsp;</th>
                        <th style="width: 0px;visibility: collapse">Memo</th>
                        <th style="width: 0px;visibility: collapse">Phase Group ID</th>
                    </tr>
                </thead>
                <tbody id="phase{{$i}}_body"></tbody>
            </table>
        </div>
        @endfor
    </div>

    <div class="form-group">            
        <div class="col-md-4">
            <input class="btn btn-primary" id="btn_update" name="btn_update" type="button" onclick="saveForm('update')" value="Update">
        </div>
    </div>  
    
    <!--<div class="form-group">            
        <div class="col-md-4">
            <input class="btn btn-primary" id="btn_monthly_data" name="btn_monthly_data" type="button" onclick="saveAnnualize()" value="Annualize">
        </div>
    </div>  -->

    <input type="hidden" value="" id="postArray" name="postArray">
    <input type="hidden" id="budget_info" name="budget_info" value="">
    <input type="hidden" id="staff_info" name="staff_info" value="">
    <input type="hidden" id="clicked_button" name="clicked_button" value="">

</form>

<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#loader-bg').hide();
        var buttonWidth = "400px";

        var contents = document.getElementById("s");
        var windowHt = $(window).innerHeight() - 50;
        contents.style.height = windowHt + "px";

        $('#client').multiselect({
            buttonWidth: buttonWidth,
            maxHeight: 700,
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            onDropdownShown: function(even) {
                this.$filter.find('.multiselect-search').focus();
            },
            includeSelectAllOption: true,
        });
        $('#project').multiselect({
            buttonWidth: buttonWidth,
            maxHeight: 700,
            enableFiltering: true,
            includeSelectAllOption: true,
            enableCaseInsensitiveFiltering: true,
            onDropdownShown: function(even) {
                this.$filter.find('.multiselect-search').focus();
            },
            onChange: function (element, checked) {
                if (checked == true) {
                    $('#group').multiselect('enable');
                    if (!element.val().match("BM ")) {
                        $('#group').multiselect('disable');
                        $('#group').multiselect('deselect', $("#group").val());
                        $("#group").multiselect('select', "");
                    }
                }
                //$("#group").multiselect('select', element.val());
            }
        });
        $('#group').multiselect({
            buttonWidth: buttonWidth,
            maxHeight: 700,
            enableFiltering: false,
        });

        var receiveClientId = document.getElementById("receiveClientId").value;
        var receiveProjectId = document.getElementById("receiveProjectId").value;
        var receiveGroup = document.getElementById("receiveGroup").value;
        if (receiveClientId != "" || receiveProjectId != "") {
            $('#client').multiselect('select', receiveClientId);
            $('#project').multiselect('select', receiveProjectId);
            if(receiveProjectId.match("BM ")){
                var d = new Date();
                var month = d.getMonth() + 1;
                
                if(receiveGroup != ""){
                    month = receiveGroup;
                }

                if(month == 1){
                    $('#group').multiselect('select', "January");
                }else if(month == 2){
                    $('#group').multiselect('select', "February");
                }else if(month == 3){
                    $('#group').multiselect('select', "March");
                }else if(month == 4){
                    $('#group').multiselect('select', "April");
                }else if(month == 5){
                    $('#group').multiselect('select', "May");
                }else if(month == 6){
                    $('#group').multiselect('select', "June");
                }else if(month == 7){
                    $('#group').multiselect('select', "July");
                }else if(month == 8){
                    $('#group').multiselect('select', "August");
                }else if(month == 9){
                    $('#group').multiselect('select', "September");
                }else if(month == 10){
                    $('#group').multiselect('select', "October");
                }else if(month == 11){
                    $('#group').multiselect('select', "November");
                }else if(month == 12){
                    $('#group').multiselect('select', "December");
                }
                
            }
            
            loadPhaseData();
        }

    });

    $(window).resize(function () {
        setHeight();
    });

    function setHeight() {
        var contents = document.getElementById("s");
        var windowHt = $(window).innerHeight() - 50;
        contents.style.height = windowHt + "px";
    }

    function appendPhase1Row(obj) {
        var buttonName = obj.name;
        var buttonIndex = buttonName.replace("contact_list", "");
        var objTBL = document.getElementById("phase_" + buttonIndex);
        if (!objTBL)
            return;

        //group_id
        var groupId = 1;
        if (objTBL.rows[1].cells[17].children[0].value != null) {
            groupId = objTBL.rows[1].cells[17].children[0].value;
        }

        insertPhase1Row("", "", "", buttonIndex, "0", "", "", "", "", "", "", "", "", "", "", true, 0, "", "", groupId);
    }

    function insertPhase1Row(id, name, description, buttonIndex, phaseItemId, comp, prep, planndPrep, prepSignOff, reviewer, plannedReview, reviewSignOff, reviewer2, plannedReview2, reviewSignOff2, isClickBtnAdd, isStandard, memo, colMemo, phaseGroupId, projectPhaseItemId) {
        // 最終行に新しい行を追加
        var phase1_tbody = document.getElementById("phase" + buttonIndex + "_body");
        var bodyLength = phase1_tbody.rows.length;
        var count = bodyLength + 1;
        var row = phase1_tbody.insertRow(bodyLength);
        
        //YMD
        var now_jpn = moment();
        var nowDate = now_jpn.format("MM/DD/YYYY");       

        if (id != "") {
            count = id;
        }

        var imagesUrl = '{{URL::asset('/image')}}';

        /*$('.datepicker1').datepicker({
         defaultViewDate: Date(),
         format: "mm/dd/yyyy",
         language: "en",
         autoclose: true,
         orientation: 'bottom left'
         });*/

        // 列の追加
        var c1 = row.insertCell(0);
        var c2 = row.insertCell(1);
        var c3 = row.insertCell(2);
        var c4 = row.insertCell(3);
        var c5 = row.insertCell(4);
        var c6 = row.insertCell(5);
        var c7 = row.insertCell(6);
        var c8 = row.insertCell(7);
        var c9 = row.insertCell(8);
        var c10 = row.insertCell(9);
        var c11 = row.insertCell(10);
        var c12 = row.insertCell(11);
        var c13 = row.insertCell(12);
        var c14 = row.insertCell(13);
        var c15 = row.insertCell(14);
        var c16 = row.insertCell(15);
        var c17 = row.insertCell(16);
        var c18 = row.insertCell(17);  
        var c19 = row.insertCell(18);  
        var c20 = row.insertCell(19);  

        c1.style.cssText = "vertical-align: middle";
        
        var prepBackgroundColor = "background-color: transparent";        
        if(prepSignOff == "" && comp != "" && compFromToDate(nowDate,comp) && compFromToDate(nowDate,planndPrep)){
            prepBackgroundColor = "background-color: #cc0000";
        }else if(prepSignOff == "" && compFromToDate(nowDate,planndPrep)){
            prepBackgroundColor = "background-color: #f4cccc";
        }
        
        var rev1BackgroundColor = "background-color: transparent";
        if(reviewSignOff == "" && comp != "" && compFromToDate(nowDate,comp) && compFromToDate(nowDate,plannedReview)){
            rev1BackgroundColor = "background-color: #cc0000";
        }else if(reviewSignOff == "" && compFromToDate(nowDate,plannedReview)){
            rev1BackgroundColor = "background-color: #f4cccc";
        }
        
        var rev2BackgroundColor = "background-color: transparent";
        if(reviewSignOff2 == "" && comp != "" && compFromToDate(nowDate,comp) && compFromToDate(nowDate,plannedReview2)){
            rev2BackgroundColor = "background-color: #cc0000";
        }else if(reviewSignOff2 == "" && compFromToDate(nowDate,plannedReview2)){
            rev2BackgroundColor = "background-color: #f4cccc";
        }

        var prepStaffInitialOption = "<option value=''></option>";
        var staffInfo = JSON.parse(document.getElementById("staff_info").value);
        for (sCnt = 0; sCnt < staffInfo.length; sCnt++) {
            if (staffInfo[sCnt].id == prep) {
                prepStaffInitialOption += '<option value="' + staffInfo[sCnt].id + '" selected>' + staffInfo[sCnt].initial + '</option>';
            } else {
                prepStaffInitialOption += '<option value="' + staffInfo[sCnt].id + '">' + staffInfo[sCnt].initial + '</option>';
            }
        }

        var reviewerStaffInitialOption = "<option value=''></option>";
        for (sCnt = 0; sCnt < staffInfo.length; sCnt++) {
            if (staffInfo[sCnt].id == reviewer) {
                reviewerStaffInitialOption += '<option value="' + staffInfo[sCnt].id + '" selected>' + staffInfo[sCnt].initial + '</option>';
            } else {
                reviewerStaffInitialOption += '<option value="' + staffInfo[sCnt].id + '">' + staffInfo[sCnt].initial + '</option>';
            }
        }

        var reviewer2StaffInitialOption = "<option value=''></option>";
        for (sCnt = 0; sCnt < staffInfo.length; sCnt++) {
            if (staffInfo[sCnt].id == reviewer2) {
                reviewer2StaffInitialOption += '<option value="' + staffInfo[sCnt].id + '" selected>' + staffInfo[sCnt].initial + '</option>';
            } else {
                reviewer2StaffInitialOption += '<option value="' + staffInfo[sCnt].id + '">' + staffInfo[sCnt].initial + '</option>';
            }
        }

        var readonlyStr = "";
        if (!isClickBtnAdd && isStandard == 1) {
            readonlyStr = "readonly";
        }

        // 各列に表示内容を設定
        c1.innerHTML = '<span class="seqno-phase' + buttonIndex + '">' + count + '</span>';
        c2.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'id" type="text" id="phase' + buttonIndex + '_id' + count + '" name="phase' + buttonIndex + '_id' + count + '" value="' + phaseItemId + '" style="width: 100%" readonly>';
        //c3.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'task" type="text" id="phase' + buttonIndex + '_task' + count + '" name="phase' + buttonIndex + '_task' + count + '" value="' + name + '" style="width: 100%;font-weight: bold" ' + readonlyStr + '>';
        c3.innerHTML = '<textarea class="form-control inpphase' + buttonIndex + 'task" type="text" id="phase' + buttonIndex + '_task' + count + '" name="phase' + buttonIndex + '_task' + count + '"  style="resize: none;width: 100%;font-weight: bold" ' + readonlyStr + '>' + name + '</textarea>';
        c4.innerHTML = '<textarea class="form-control inpphase' + buttonIndex + 'description" type="text" id="phase' + buttonIndex + '_description' + count + '" name="phase' + buttonIndex + '_description' + count + '" style="resize: none;width: 100%" ' + readonlyStr + '>' + description + '</textarea>';
        c5.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'comp datepicker1" type="text" id="phase' + buttonIndex + '_comp' + count + '" name="phase' + buttonIndex + '_comp' + count + '" value="' + comp + '" style="width: 100%">';
        c6.innerHTML = '<select class="form-control inpphase' + buttonIndex + 'prep" type="text" id="phase' + buttonIndex + '_prep' + count + '" name="phase' + buttonIndex + '_prep' + count + '" value="' + prep + '" style="width: 100%">' + prepStaffInitialOption + '</select>';
        c7.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'plannedprep datepicker1" type="text" id="phase' + buttonIndex + '_planned_prep' + count + '" name="phase' + buttonIndex + '_planned_prep' + count + '" value="' + planndPrep + '" style="width: 100%">';
        c8.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'prepsignoff datepicker1" type="text" id="phase' + buttonIndex + '_prep_signoff' + count + '" name="phase' + buttonIndex + '_prep_signoff' + count + '" value="' + prepSignOff + '" style="width: 100%;' + prepBackgroundColor + '" onchange="setPhaseBackground(this)">';
        c9.innerHTML = '<select class="form-control inpphase' + buttonIndex + 'reviewer1" type="text" id="phase' + buttonIndex + '_reviewer1' + count + '" name="phase' + buttonIndex + '_reviewer1' + count + '" value="' + reviewer + '" style="width: 100%">' + reviewerStaffInitialOption + '</select>';
        c10.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'plannedreview1 datepicker1" type="text" id="phase' + buttonIndex + '_planned_review1' + count + '" name="phase' + buttonIndex + '_planned_review1' + count + '" value="' + plannedReview + '" style="width: 100%">';
        c11.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'reviewsignoff1 datepicker1" type="text" id="phase' + buttonIndex + '_review_signoff1' + count + '" name="phase' + buttonIndex + '_review_signoff1' + count + '" value="' + reviewSignOff + '" style="width: 100%;' + rev1BackgroundColor + '" onchange="setPhaseBackground(this)">';
        c12.innerHTML = '<select class="form-control inpphase' + buttonIndex + 'reviewer2" type="text" id="phase' + buttonIndex + '_reviewer2' + count + '" name="phase' + buttonIndex + '_reviewer2' + count + '" value="' + reviewer2 + '" style="width: 100%">' + reviewer2StaffInitialOption + '</select>';
        c13.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'plannedreview2 datepicker1" type="text" id="phase' + buttonIndex + '_planned_review2' + count + '" name="phase' + buttonIndex + '_planned_review2' + count + '" value="' + plannedReview2 + '" style="width: 100%">';
        c14.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'reviewsignoff2 datepicker1" type="text" id="phase' + buttonIndex + '_review_signoff2' + count + '" name="phase' + buttonIndex + '_review_signoff2' + count + '" value="' + reviewSignOff2 + '" style="width: 100%;' + rev2BackgroundColor + '" onchange="setPhaseBackground(this)">';
        c15.innerHTML = '<textarea class="form-control inpphase' + buttonIndex + 'colmemo" type="text" id="phase' + buttonIndex + '_col_memo' + count + '" name="phase' + buttonIndex + '_col_memo' + count + '" style="resize: none;width: 100%">' + colMemo + '</textarea>';
        if (isClickBtnAdd || isStandard == 0) {
            c16.innerHTML = '<button class="delphase' + buttonIndex + 'btn btn btn-sm" type="button" id="delPhase' + buttonIndex + 'Btn' + count + '" value="Delete" onclick="return deletePhase1Row(this,' + buttonIndex + ',' + projectPhaseItemId + ')" style="background-color: transparent"><img src="' + imagesUrl + "/delete.png" + '"></button>';
        } else {
            c16.innerHTML = '<button class="delphase' + buttonIndex + 'btn btn btn-sm" type="button" id="delPhase' + buttonIndex + 'Btn' + count + '" value="Delete" onclick="return doNotUseRow(this,' + buttonIndex + ',' + count + ')" style="background-color: transparent"><img src="' + imagesUrl + "/not_use.png" + '"></button>';
        }
        c17.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'memo datepicker1" type="text" id="phase' + buttonIndex + '_memo' + count + '" name="phase' + buttonIndex + '_memo' + count + '" value="' + memo + '" style="width: 100%;visibility:hidden">';
        c18.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'group datepicker1" type="text" id="phase' + buttonIndex + '_group' + count + '" name="phase' + buttonIndex + '_group' + count + '" value="' + phaseGroupId + '" style="width: 100%;visibility:hidden">';
        c19.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'standard datepicker1" type="text" id="phase' + buttonIndex + '_standard' + count + '" name="phase' + buttonIndex + '_standard' + count + '" value="' + isStandard + '" style="width: 100%;visibility:hidden">';
        c20.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'projectphaseid datepicker1" type="text" id="phase' + buttonIndex + '_project_phase_id' + count + '" name="phase' + buttonIndex + '_project_phase_id' + count + '" value="' + projectPhaseItemId + '" style="width: 100%;visibility:hidden">';

        $('.datepicker1').datepicker({
            defaultViewDate: Date(),
            format: "mm/dd/yyyy",
            language: "en",
            autoclose: true,
            orientation: 'bottom left'
        });
        
        $('#phase' + buttonIndex + '_task' + count).autosize(); 
        $('#phase' + buttonIndex + '_description' + count).autosize(); 
        $('#phase' + buttonIndex + '_col_memo' + count).autosize(); 
    }

    function deletePhase1Row(obj, buttonIndex, projectPhaseItemId) {
        delRowCommon(obj, "seqno-phase" + buttonIndex);

        // id/name ふり直し
        var tagElements = document.getElementsByTagName("input");
        if (!tagElements)
            return false;

        var selectTagElements = document.getElementsByTagName("select");
        
        var textareaTagElements = document.getElementsByTagName("textarea");

        var seq = 1;
        reOrderElementTag(tagElements, "inpphase" + buttonIndex + "id", "phase" + buttonIndex + "_id");
        reOrderElementTag(textareaTagElements, "inpphase" + buttonIndex + "task", "phase" + buttonIndex + "_task");
        reOrderElementTag(textareaTagElements, "inpphase" + buttonIndex + "description", "phase" + buttonIndex + "_description");
        reOrderElementTag(tagElements, "inpphase" + buttonIndex + "comp", "phase" + buttonIndex + "_comp");
        reOrderElementTag(selectTagElements, "inpphase" + buttonIndex + "prep", "phase" + buttonIndex + "_prep");
        reOrderElementTag(tagElements, "inpphase" + buttonIndex + "plannedprep", "phase" + buttonIndex + "_planned_prep");
        reOrderElementTag(tagElements, "inpphase" + buttonIndex + "prepsignoff", "phase" + buttonIndex + "_prep_signoff");
        reOrderElementTag(selectTagElements, "inpphase" + buttonIndex + "reviewer1", "phase" + buttonIndex + "_reviewer1");
        reOrderElementTag(tagElements, "inpphase" + buttonIndex + "plannedreview1", "phase" + buttonIndex + "_planned_review1");
        reOrderElementTag(tagElements, "inpphase" + buttonIndex + "reviewsignoff1", "phase" + buttonIndex + "_review_signoff1");
        reOrderElementTag(selectTagElements, "inpphase" + buttonIndex + "reviewer2", "phase" + buttonIndex + "_reviewer2");
        reOrderElementTag(tagElements, "inpphase" + buttonIndex + "plannedreview2", "phase" + buttonIndex + "_planned_review2");
        reOrderElementTag(tagElements, "inpphase" + buttonIndex + "reviewsignoff2", "phase" + buttonIndex + "_review_signoff2");
        reOrderElementTag(textareaTagElements, "inpphase" + buttonIndex + "colmemo", "phase" + buttonIndex + "_col_memo");
        reOrderElementTag(tagElements, "inpphase" + buttonIndex + "memo", "phase" + buttonIndex + "_memo");
        reOrderElementTag(tagElements, "inpphase" + buttonIndex + "group", "phase" + buttonIndex + "_group");
        reOrderElementTag(tagElements, "inpphase" + buttonIndex + "standard", "phase" + buttonIndex + "_standard");
        reOrderElementTag(tagElements, "inpphase" + buttonIndex + "projectphaseid", "phase" + buttonIndex + "_project_phase_id");

        reOrderElementTag(tagElements, "delphase" + buttonIndex + "btn", "delPhase" + buttonIndex + "Btn");
        
        //削除処理
        delRowDirect(projectPhaseItemId);

    }

    function delRowCommon(obj, seqNoId) {
        // 確認
        if (!confirm("この行を削除しますか？"))
            return;

        if (!obj)
            return;

        var objTR = obj.parentNode.parentNode;
        var objTBL = objTR.parentNode;

        if (objTBL)
            objTBL.deleteRow(objTR.sectionRowIndex);

        // <span> 行番号ふり直し
        var tagElements = document.getElementsByTagName("span");
        if (!tagElements)
            return false;

        var seq = 1;
        for (var i = 0; i < tagElements.length; i++)
        {
            //if (tagElements[i].className.match(seqno))
            if (tagElements[i].className === seqNoId)
                tagElements[i].innerHTML = seq++;
        }
    }

    function reOrderElementTag(tagElements, className, idName) {
        var seq = 1;
        for (var i = 0; i < tagElements.length; i++)
        {
            if (tagElements[i].className.match(className)) {
                tagElements[i].setAttribute("id", idName + seq);
                tagElements[i].setAttribute("name", idName + seq);
                ++seq;
            }
        }
    }
    
    function loadPhaseData() {
        var project = $("#project").val();
        var group = $("#group").val();
        if(project.match("BM ") && group == ""){
            Swal.fire({
                position: 'top',
                icon: 'error',
                title: 'Error',
                html: "Select Month"
            });
            return;
        }
        
        loadPhaseDataDetail("");
    }
    
    function visibleTable(hiddenNo,status){
        for(var cnt=hiddenNo; cnt <= 10; cnt++){
            document.getElementById("div_phase_table" + cnt).style.visibility = status;
            document.getElementById("div_phase_table" + cnt).style.marginBottom = "50px";
            document.getElementById("div_phase_table" + cnt).style.height = "";
            if(status == "hidden"){
                document.getElementById("div_phase_table" + cnt).style.marginBottom = "0px";
                document.getElementById("div_phase_table" + cnt).style.height = "0px";
            }            
        }
    }

    function delRowDirect(projectPhaseItemId){

        $.ajax({
            url: "/test3/delRowWorkList/" + projectPhaseItemId + "/",
            beforeSend: function () {
                //処理中           
                jQuery('#loader-bg').show();
            },
        }).success(function (data) {

        }).done(function(){           
            jQuery('#loader-bg').hide();
        });

    }

    function loadPhaseDataDetail(submitType) {

        var client = $("#client").val();
        var project = $("#project").val();
        var group = $("#group").val();

        if (group == "") {
            group = "blank";
        }

        $.ajax({
            url: "/test3/getWorkList/" + client + "/" + project + "/" + group + "/",
            beforeSend: function () {
                //処理中           
                jQuery('#loader-bg').show();
            },
        }).success(function (data) {

            clearAllList();
            
            //不要な枠を非表示
            visibleTable(1,"visible");
            var projectType = $("#project").val().split(" - ")[0];
            if(projectType == "AUD" || projectType == "REV" || projectType == "COMP"){
                visibleTable(7,"hidden");              
            }else if(projectType == "BM"){
                visibleTable(5,"hidden");              
            }

            //staff情報セット
            $('#staff_info').val(JSON.stringify(data.staff));

            for (var i = 0; i < data.phase.length; i++) {
                document.getElementById("label_phase" + (parseInt(i) + 1)).value = data.phase[i].name;
                document.getElementById("label_phase_desc" + (parseInt(i) + 1)).innerHTML = data.phase[i].description;
            }

            //detail            
            for (var cnt = 0; cnt < data.phase1Detail.length; cnt++) {
                for (var cnt2 = 0; cnt2 < data.phase1Detail[cnt].length; cnt2++) {
                    var buttonIndex = cnt + 1;
                    var rowId = cnt2 + 1;
                    var comp = "";
                    var prep = "";
                    var planndPrep = "";
                    var prepSignOff = "";
                    var reviewer = "";
                    var plannedReview = "";
                    var reviewSignOff = "";
                    var reviewer2 = "";
                    var plannedReview2 = "";
                    var reviewSignOff2 = "";
                    var isStandard = "";
                    var memo = "";
                    var colMemo = "";
                    var phaseGroupId = "";
                    var phaseItemId = "";
                    var projectPhaseItemId = "";

                    if (data.phase1Detail[cnt][cnt2].due_date != null) {
                        comp = convDateFormat(data.phase1Detail[cnt][cnt2].due_date);
                    }

                    if (data.phase1Detail[cnt][cnt2].preparer != null) {
                        prep = data.phase1Detail[cnt][cnt2].preparer;
                    }

                    if (data.phase1Detail[cnt][cnt2].planed_prep != null) {
                        planndPrep = convDateFormat(data.phase1Detail[cnt][cnt2].planed_prep);
                    }

                    if (data.phase1Detail[cnt][cnt2].prep_sign_off != null) {
                        prepSignOff = convDateFormat(data.phase1Detail[cnt][cnt2].prep_sign_off);
                    }

                    if (data.phase1Detail[cnt][cnt2].reviewer != null) {
                        reviewer = data.phase1Detail[cnt][cnt2].reviewer;
                    }

                    if (data.phase1Detail[cnt][cnt2].planned_review != null) {
                        plannedReview = convDateFormat(data.phase1Detail[cnt][cnt2].planned_review);
                    }

                    if (data.phase1Detail[cnt][cnt2].review_sign_off != null) {
                        reviewSignOff = convDateFormat(data.phase1Detail[cnt][cnt2].review_sign_off);
                    }

                    if (data.phase1Detail[cnt][cnt2].reviewer2 != null) {
                        reviewer2 = data.phase1Detail[cnt][cnt2].reviewer2;
                    }

                    if (data.phase1Detail[cnt][cnt2].planned_review2 != null) {
                        plannedReview2 = convDateFormat(data.phase1Detail[cnt][cnt2].planned_review2);
                    }

                    if (data.phase1Detail[cnt][cnt2].review_sign_off2 != null) {
                        reviewSignOff2 = convDateFormat(data.phase1Detail[cnt][cnt2].review_sign_off2);
                    }

                    if (data.phase1Detail[cnt][cnt2].is_standard != null) {
                        isStandard = data.phase1Detail[cnt][cnt2].is_standard;
                    }

                    if (data.phase1Detail[cnt][cnt2].memo != null) {
                        memo = data.phase1Detail[cnt][cnt2].memo;
                    }

                    if (data.phase1Detail[cnt][cnt2].col_memo != null) {
                        colMemo = data.phase1Detail[cnt][cnt2].col_memo;
                    }

                    if(data.phase1Detail[cnt][cnt2].phase_group_id != null) {
                        phaseGroupId = data.phase1Detail[cnt][cnt2].phase_group_id
                    }

                    if(data.phase1Detail[cnt][cnt2].phase_item_id != null) {
                        phaseItemId = data.phase1Detail[cnt][cnt2].phase_item_id
                    }

                    if(data.phase1Detail[cnt][cnt2].id != null) {
                        projectPhaseItemId = data.phase1Detail[cnt][cnt2].id
                    }

                    insertPhase1Row(parseInt(rowId), data.phase1Detail[cnt][cnt2].name, data.phase1Detail[cnt][cnt2].description,
                            buttonIndex, phaseItemId, comp, prep, planndPrep, prepSignOff, reviewer, plannedReview, reviewSignOff, reviewer2, plannedReview2, reviewSignOff2, false, isStandard, memo, colMemo, phaseGroupId, projectPhaseItemId);
                }
            }

            for (var blockCnt = 1; blockCnt <= 10; blockCnt++) {
                var tblTbody = document.getElementById('phase' + blockCnt + '_body');
                for (var i = 0, rowLen = tblTbody.rows.length; i < rowLen; i++) {
                    var cells = tblTbody.rows[i].cells[16].children[0].value;
                    if (cells !== undefined && cells == "Disabled") {
                        tblTbody.rows[i].cells[4].children[0].readOnly = true;
                        tblTbody.rows[i].cells[6].children[0].readOnly = true;
                        tblTbody.rows[i].cells[7].children[0].readOnly = true;
                        tblTbody.rows[i].cells[7].children[0].style.backgroundColor = "#DCDCDC";
                        tblTbody.rows[i].cells[9].children[0].readOnly = true;
                        tblTbody.rows[i].cells[10].children[0].readOnly = true;
                        tblTbody.rows[i].cells[10].children[0].style.backgroundColor = "#DCDCDC";
                        tblTbody.rows[i].cells[12].children[0].readOnly = true;
                        tblTbody.rows[i].cells[13].children[0].readOnly = true;
                        tblTbody.rows[i].cells[13].children[0].style.backgroundColor = "#DCDCDC";
                        tblTbody.rows[i].cells[14].children[0].readOnly = true;
                        var prepObj = tblTbody.rows[i].cells[5].children[0];
                        prepObj.options[0].selected = true;
                        prepObj.style.cssText = "background-color: " + "#DCDCDC";
                        for (var cnt = 1; cnt < prepObj.length; cnt++) {
                            prepObj.options[cnt].disabled = "disabled";
                        }

                        prepObj = tblTbody.rows[i].cells[8].children[0];
                        prepObj.style.cssText = "background-color: " + "#DCDCDC";
                        for (var cnt = 1; cnt < prepObj.length; cnt++) {
                            prepObj.options[cnt].disabled = "disabled";
                        }

                        prepObj = tblTbody.rows[i].cells[11].children[0];
                        prepObj.style.cssText = "background-color: " + "#DCDCDC";
                        for (var cnt = 1; cnt < prepObj.length; cnt++) {
                            prepObj.options[cnt].disabled = "disabled";
                        }
                    }
                }
            }            
            
            //annualize
            document.getElementById("btn_monthly_data").style.backgroundColor = "#483d8b";
            if(data.annualize > 1){
                document.getElementById("btn_monthly_data").style.backgroundColor = "#DCDCDC";                
            }
          
        }).error(function (XMLHttpRequest, textStatus, errorThrown) {
            clearAllList();

            Swal.fire({
                position: 'top',
                icon: 'error',
                title: 'Error',
                html: "Project does not exist"
            });

            //alert('error!!!');
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);

            jQuery('#loader-bg').hide();
        }).done(function(){
            if(submitType != "") {
                saveForm('monthlyData');
                document.getElementById("btn_monthly_data").style.backgroundColor = "#DCDCDC";
            }else {
                jQuery('#loader-bg').hide();
            }
            
        });
    }

    function clearAllList() {
        for (var i = 1; i <= 10; i++) {
            var table = document.getElementById("phase_" + parseInt(i));
            var label = document.getElementById("label_phase" + parseInt(i));
            var desc = document.getElementById("label_phase_desc" + parseInt(i));
            //Label初期化
            label.value = "";
            desc.innerHTML = "";
            //List初期化
            while (table.rows[ 1 ])
                table.deleteRow(1);
        }
    }

    function convDateFormat(value) {
        var valueArray = value.split("-");
        return valueArray[1] + "/" + valueArray[2] + "/" + valueArray[0];
    }

    function clearFilter() {
        var clientSelectedValue = document.getElementById("client").value;
        var projectSelectedValue = document.getElementById("project").value;
        var groupSelectedValue = document.getElementById("group").value;
        $('#client').multiselect('deselect', clientSelectedValue);
        $('#client').multiselect('select', "");

        $('#project').multiselect('deselect', projectSelectedValue);
        $('#project').multiselect('select', "");

        $('#group').multiselect('deselect', groupSelectedValue);
        $('#group').multiselect('select', "");
    }

    function doNotUseRow(obj, buttonIndex, count) {
        var backGroundColorDisabled = "#DCDCDC";
        var backGroundColorEnable = "white";
        var prepObj = document.getElementById('phase' + buttonIndex + '_prep' + count);
        var reviewer1Obj = document.getElementById('phase' + buttonIndex + '_reviewer1' + count);
        var reviewer2Obj = document.getElementById('phase' + buttonIndex + '_reviewer2' + count);

        //document.getElementById('phase' + buttonIndex + '_memo' + count).value = "";
        if (document.getElementById('phase' + buttonIndex + '_memo' + count).value == "") {
            document.getElementById('phase' + buttonIndex + '_memo' + count).value = "Disabled";
            document.getElementById('phase' + buttonIndex + '_comp' + count).readOnly = true;
            document.getElementById('phase' + buttonIndex + '_comp' + count).value = "";
            //document.getElementById('phase' + buttonIndex + '_prep' + count).disabled = true;            
            prepObj.style.cssText = "background-color: " + backGroundColorDisabled;
            prepObj.options[0].selected = true;
            for (var i = 1; i < prepObj.length; i++) {
                prepObj.options[i].disabled = "disabled";
            }
            document.getElementById('phase' + buttonIndex + '_planned_prep' + count).readOnly = true;
            document.getElementById('phase' + buttonIndex + '_planned_prep' + count).value = "";
            document.getElementById('phase' + buttonIndex + '_prep_signoff' + count).readOnly = true;
            document.getElementById('phase' + buttonIndex + '_prep_signoff' + count).style.backgroundColor = "#DCDCDC";
            document.getElementById('phase' + buttonIndex + '_prep_signoff' + count).value = "";
            //document.getElementById('phase' + buttonIndex + '_reviewer1' + count).disabled = true;            
            reviewer1Obj.style.cssText = "background-color: " + backGroundColorDisabled;
            reviewer1Obj.options[0].selected = true;
            for (var i = 1; i < reviewer1Obj.length; i++) {
                reviewer1Obj.options[i].disabled = "disabled";
            }
            document.getElementById('phase' + buttonIndex + '_planned_review1' + count).readOnly = true;
            document.getElementById('phase' + buttonIndex + '_planned_review1' + count).value = "";
            document.getElementById('phase' + buttonIndex + '_review_signoff1' + count).readOnly = true;
            document.getElementById('phase' + buttonIndex + '_review_signoff1' + count).style.backgroundColor = "#DCDCDC";
            document.getElementById('phase' + buttonIndex + '_review_signoff1' + count).value = "";
            //document.getElementById('phase' + buttonIndex + '_reviewer2' + count).disabled = true;
            reviewer2Obj.style.cssText = "background-color: " + backGroundColorDisabled;
            reviewer2Obj.options[0].selected = true;
            for (var i = 1; i < reviewer2Obj.length; i++) {
                reviewer2Obj.options[i].disabled = "disabled";
            }
            document.getElementById('phase' + buttonIndex + '_planned_review2' + count).readOnly = true;
            document.getElementById('phase' + buttonIndex + '_planned_review2' + count).value = "";
            document.getElementById('phase' + buttonIndex + '_review_signoff2' + count).readOnly = true;
            document.getElementById('phase' + buttonIndex + '_review_signoff2' + count).style.backgroundColor = "#DCDCDC";
            document.getElementById('phase' + buttonIndex + '_review_signoff2' + count).value = "";
            
            document.getElementById('phase' + buttonIndex + '_col_memo' + count).value = "";
            document.getElementById('phase' + buttonIndex + '_col_memo' + count).readOnly = true;
            document.getElementById('phase' + buttonIndex + '_col_memo' + count).style.backgroundColor = "#DCDCDC";
        } else {
            document.getElementById('phase' + buttonIndex + '_memo' + count).value = "";
            document.getElementById('phase' + buttonIndex + '_comp' + count).readOnly = false;
            //document.getElementById('phase' + buttonIndex + '_prep' + count).disabled = false;
            var prepObj = document.getElementById('phase' + buttonIndex + '_prep' + count);
            prepObj.style.cssText = "background-color: " + backGroundColorEnable;
            for (var i = 1; i < prepObj.length; i++) {
                prepObj.options[i].disabled = "";
            }
            document.getElementById('phase' + buttonIndex + '_planned_prep' + count).readOnly = false;
            document.getElementById('phase' + buttonIndex + '_prep_signoff' + count).readOnly = false;
            document.getElementById('phase' + buttonIndex + '_prep_signoff' + count).style.backgroundColor = "transparent";            
            //document.getElementById('phase' + buttonIndex + '_reviewer1' + count).disabled = false;
            reviewer1Obj.style.cssText = "background-color: " + backGroundColorEnable;
            for (var i = 1; i < reviewer1Obj.length; i++) {
                reviewer1Obj.options[i].disabled = "";
            }
            document.getElementById('phase' + buttonIndex + '_planned_review1' + count).readOnly = false;
            document.getElementById('phase' + buttonIndex + '_review_signoff1' + count).readOnly = false;
            document.getElementById('phase' + buttonIndex + '_review_signoff1' + count).style.backgroundColor = "transparent";
            //document.getElementById('phase' + buttonIndex + '_reviewer2' + count).disabled = false;
            reviewer2Obj.style.cssText = "background-color: " + backGroundColorEnable;
            for (var i = 1; i < reviewer2Obj.length; i++) {
                reviewer2Obj.options[i].disabled = "";
            }
            document.getElementById('phase' + buttonIndex + '_planned_review2' + count).readOnly = false;
            document.getElementById('phase' + buttonIndex + '_review_signoff2' + count).readOnly = false;
            document.getElementById('phase' + buttonIndex + '_review_signoff2' + count).style.backgroundColor = "transparent";
           
            document.getElementById('phase' + buttonIndex + '_col_memo' + count).readOnly = false;
            document.getElementById('phase' + buttonIndex + '_col_memo' + count).style.backgroundColor = "transparent";
        }

    }
        
    function getErrorWorkList() {
        var isError = "true";
        //phase1 to phase 10
        for(var tableCnt=1; tableCnt<= 10; tableCnt++){
            var tableObj = document.getElementById("phase_" + tableCnt);
            for(var rowCnt=1; rowCnt<tableObj.rows.length; rowCnt++){
                var compDue = document.getElementById("phase" + tableCnt + "_comp" + rowCnt).value;
                var prep = document.getElementById("phase" + tableCnt + "_planned_prep" + rowCnt);
                var rev1 = document.getElementById("phase" + tableCnt + "_planned_review1" + rowCnt);
                var rev2 = document.getElementById("phase" + tableCnt + "_planned_review2" + rowCnt);
                prep.style.color = "black";
                rev1.style.color = "black";
                rev2.style.color = "black";
                //complete dueよりplanned dateが大きい場合Warningー----------------
                if(compFromToDate(prep.value,compDue)){
                    prep.style.color = "red";                    
                }                
                if(compFromToDate(rev1.value,compDue)){
                    rev1.style.color = "red";
                }                
                if(compFromToDate(rev2.value,compDue)){
                    rev2.style.color = "red";
                }                
                if(compFromToDate(prep.value,compDue) || compFromToDate(rev1.value,compDue) || compFromToDate(rev2.value,compDue)){                    
                    isError = "confirm";
                }                    
                //--------------------------------------------------------------------
                //planned prep < planned review < planned review2　以外はエラー
                //if(compFromToDate(rev1.value,prep.value) || compFromToDate(rev2.value,rev1.value) || compFromToDate(rev2.value,prep.value)){
                //    isError = "false";
                //}
                if(rev1.value != "" && prep.value != "" && !compFromToDate(rev1.value,prep.value)){
                    isError = "false";
                }
                if(rev2.value != "" && rev1.value != "" && !compFromToDate(rev2.value,rev1.value)){
                    isError = "false";
                }
                if(rev2.value != "" && prep.value != "" && !compFromToDate(rev2.value,prep.value)){
                    isError = "false";
                }
            }
        }
        
        return isError;
    }
    
    function saveForm(btnValue) {
        document.getElementById("clicked_button").value = btnValue;
        //入力　エラーチェック
        var {isError, isBlankDateError} = getErrorEntry();
        var errorText = "";
        if(isError){
            errorText = errorText + "need to enter assign information";
        }
        if(isBlankDateError){
            if(errorText != ""){
                errorText = errorText + "<br>";
            }
            errorText = errorText + "need to enter date";
        }
        
        if(errorText != ""){
            Swal.fire({
                position: 'top',
                icon: 'error',
                title: 'Error',
                html: errorText,
            });
            return;
        }
        
        //妥当性　エラーチェック
        var isError = getErrorWorkList();
        if (isError == "confirm") {
            Swal.fire({
                title: 'Warning',
                text: "Planned Prep, Review(2) date are not set before Completion Due.\n Is it ok to save this?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    saveDetail();
                }
            });
            return;
        }else if(isError == "false"){
            Swal.fire({
                position: 'top',
                icon: 'error',
                title: 'Error',
                width: '400px',
                html: "Planned review(2) date(s) must be set after planned prep date<br>Planned review 2 date must be set after planned review date"
            });
        } else {
            saveDetail();  
        } 
      
    }

    function saveDetail() {        
        var params = $("form").serialize();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/master/work-list",
            type: "POST",
            data: params,
            timeout: 10000,
            beforeSend: function (xhr, settings) {
                //処理中
                jQuery('#loader-bg').show();
            },
            complete: function (xhr, textStatus) {
                //処理済                          
                showToast();          
                jQuery('#loader-bg').hide();      
            },
            success: function (result, textStatus, xhr) {
                loadPhaseDataDetail("");  
            },
            error: function (data) {
                console.debug(data);
                jQuery('#loader-bg').hide();    
            }
        });        
    }

    function showToast() {
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: 'Saved',
            showConfirmButton: false,
            timer: 1500
        });
    }
    
    function expandColumn(phaseCnt, colNo) {
        var tableObj = document.getElementById("phase_" + phaseCnt);
        var cellObj = null;
        var copyObj = null;
                
        for(var rowCnt=1; rowCnt<tableObj.rows.length; rowCnt++){
            
            if(document.getElementById('phase' + phaseCnt + '_memo' + rowCnt).value == "Disabled"){
                continue;
            }
            
            if(colNo == 1){
                copyObj = document.getElementById('phase' + phaseCnt + '_comp1');
                cellObj = document.getElementById('phase' + phaseCnt + '_comp' + rowCnt);
                cellObj.value = copyObj.value;
            }else if(colNo == 2){
                copyObj = document.getElementById('phase' + phaseCnt + '_prep1');
                cellObj = document.getElementById('phase' + phaseCnt + '_prep' + rowCnt);
                cellObj.selectedIndex = copyObj.selectedIndex;
            }else if(colNo == 3){
                copyObj = document.getElementById('phase' + phaseCnt + '_planned_prep1');
                cellObj = document.getElementById('phase' + phaseCnt + '_planned_prep' + rowCnt);
                cellObj.value = copyObj.value;
            }else if(colNo == 4){
                copyObj = document.getElementById('phase' + phaseCnt + '_prep_signoff1');
                cellObj = document.getElementById('phase' + phaseCnt + '_prep_signoff' + rowCnt);
                cellObj.value = copyObj.value;
            }else if(colNo == 5){
                copyObj = document.getElementById('phase' + phaseCnt + '_reviewer11');
                cellObj = document.getElementById('phase' + phaseCnt + '_reviewer1' + rowCnt);
                cellObj.selectedIndex = copyObj.selectedIndex;
            }else if(colNo == 6){
                copyObj = document.getElementById('phase' + phaseCnt + '_planned_review11');
                cellObj = document.getElementById('phase' + phaseCnt + '_planned_review1' + rowCnt);
                cellObj.value = copyObj.value;
            }else if(colNo == 7){
                copyObj = document.getElementById('phase' + phaseCnt + '_review_signoff11');
                cellObj = document.getElementById('phase' + phaseCnt + '_review_signoff1' + rowCnt);
                cellObj.value = copyObj.value;
            }else if(colNo == 8){
                copyObj = document.getElementById('phase' + phaseCnt + '_reviewer21');
                cellObj = document.getElementById('phase' + phaseCnt + '_reviewer2' + rowCnt);
                cellObj.selectedIndex = copyObj.selectedIndex;
            }else if(colNo == 9){
                copyObj = document.getElementById('phase' + phaseCnt + '_planned_review21');
                cellObj = document.getElementById('phase' + phaseCnt + '_planned_review2' + rowCnt);
                cellObj.value = copyObj.value;
            }else if(colNo == 10){
                copyObj = document.getElementById('phase' + phaseCnt + '_review_signoff21');
                cellObj = document.getElementById('phase' + phaseCnt + '_review_signoff2' + rowCnt);
                cellObj.value = copyObj.value;
            }else if(colNo == 11){
                copyObj = document.getElementById('phase' + phaseCnt + '_col_memo1');
                cellObj = document.getElementById('phase' + phaseCnt + '_col_memo' + rowCnt);
                cellObj.value = copyObj.value;
            }
            
        }
    }
    
    function setPhaseBackground(obj) {
        obj.style.backgroundColor = "transparent";
    }
    
    function getErrorEntry(){
        var isError = false;
        var isBlankDateError = false;
        var errorColor = "yellow";
        var defaultColor = "transparent";
        for(var tableCnt=1; tableCnt<= 10; tableCnt++){
            var tableObj = document.getElementById("phase_" + tableCnt);
            if(typeof tableObj === "undefined"){
                break;
            }
            
            for(var rowCnt=1; rowCnt<tableObj.rows.length; rowCnt++){
                var prep = document.getElementById("phase" + tableCnt + "_prep" + rowCnt);
                var rev1 = document.getElementById("phase" + tableCnt + "_reviewer1" + rowCnt);
                var rev2 = document.getElementById("phase" + tableCnt + "_reviewer2" + rowCnt);
                
                var prepDate = document.getElementById("phase" + tableCnt + "_planned_prep" + rowCnt);
                var rev1Date = document.getElementById("phase" + tableCnt + "_planned_review1" + rowCnt);
                var rev2Date = document.getElementById("phase" + tableCnt + "_planned_review2" + rowCnt);
            
                var prepSignOff = document.getElementById("phase" + tableCnt + "_prep_signoff" + rowCnt);
                var rev1SignOff = document.getElementById("phase" + tableCnt + "_review_signoff1" + rowCnt);
                var rev2SignOff = document.getElementById("phase" + tableCnt + "_review_signoff2" + rowCnt);
                
                prep.style.backgroundColor = defaultColor;
                rev1.style.backgroundColor = defaultColor;
                rev2.style.backgroundColor = defaultColor;
                prepDate.style.backgroundColor = defaultColor;
                rev1Date.style.backgroundColor = defaultColor;
                rev2Date.style.backgroundColor = defaultColor;
                            
                //preparerチェック
                if(prep.value == "" && prepDate.value != ""){
                    prep.style.backgroundColor = errorColor;
                    isError = true;
                }
                if(prep.value == "" && prepSignOff.value != ""){
                    prep.style.backgroundColor = errorColor;
                    isError = true;
                }
                //reviewer1チェック
                if(rev1.value == "" && rev1Date.value != ""){
                    rev1.style.backgroundColor = errorColor;
                    isError = true;
                }
                if(rev1.value == "" && rev1SignOff.value != ""){
                    rev1.style.backgroundColor = errorColor;
                    isError = true;
                }
                //reviewer2チェック
                if(rev2.value == "" && rev2Date.value != ""){
                    rev2.style.backgroundColor = errorColor;
                    isError = true;
                }
                if(rev2.value == "" && rev2SignOff.value != ""){
                    rev2.style.backgroundColor = errorColor;
                    isError = true;
                }
                
                //assignされているのに、PlanDateがブランク
                //preparerチェック
                if(prep.value != "" && prepDate.value == ""){
                    prepDate.style.backgroundColor = errorColor;
                    isBlankDateError = true;
                }
               
                //reviewer1チェック
                if(rev1.value != "" && rev1Date.value == ""){
                    rev1Date.style.backgroundColor = errorColor;
                    isBlankDateError = true;
                }
                
                //reviewer2チェック
                if(rev2.value != "" && rev2Date.value == ""){
                    rev2Date.style.backgroundColor = errorColor;
                    isBlankDateError = true;
                }               
            }
        }
        //return isError;
        return {
            isError : isError,
            isBlankDateError : isBlankDateError
        };
    }
    
    function saveAnnualize() {
       Swal.fire({
           title: 'Warning',
           html: "Are you sure to annualize the tasks? <br> All existing data in other months will be overwritten and can not be restored. <br> It is strongly recommended to use this function only at the time of creating a yearly task list.",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Yes',
           width: '500px'
       }).then((result) => {
           if (result.value) {
               var groupSelectedValue = document.getElementById("group").value;
               $('#group').multiselect('deselect', groupSelectedValue);
               $('#group').multiselect('select', "January");
               loadPhaseDataDetail("monthly");              
           }
       });
    }


</script>

@endsection