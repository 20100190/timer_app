/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {
    $('.datepicker1').datepicker({
        defaultViewDate: Date(),
        format: "mm/dd/yyyy",
        language: "en",
        autoclose: true,
        orientation: 'bottom left'
    });

    $('#task_body').sortable();

    //$("#fye option:not(:selected)").prop('disabled', true);
    
     jQuery('#loader-bg').hide();
     
     var reqClient = document.getElementById("reqClient").value;
     var reqProject = document.getElementById("reqProject").value;
     if (reqClient != ""){
         $("#client").val(reqClient);
     }
     if (reqProject != "") {
         //var projectStr = reqProject.split(" - ");
         $("#project_type").val(reqProject.slice(0,-7));
         $("#project_year").val(reqProject.slice(-4));
         
         $("#harvest_project_name").val(reqProject);
     }
     if (reqClient != "" && reqProject != "") {        
        loadTask();
    }

});

$('#task_body').bind('sortstop', function () {
    // 番号を設定している要素に対しループ処理
    $(this).find('[name="task_no"]').each(function (idx) {
        // タグ内に通し番号を設定（idxは0始まりなので+1する）       
        $(this).html('<span class="seqno-task">' + parseInt(idx + 1) + '</span>');
    });

    //order_noにnoを代入
    var specific_tbody = document.getElementById("task_body");
    var bodyLength = specific_tbody.rows.length;
    for (cnt = 0; cnt < bodyLength; cnt++) {
        //alert(specific_tbody.rows[cnt].cells[0].innerText + " " + specific_tbody.rows[cnt].cells[3].children[0].name);
        specific_tbody.rows[cnt].cells[4].children[0].value = specific_tbody.rows[cnt].cells[0].innerText;
    }
   
});

function reOrderTaskNo() {
    // 番号を設定している要素に対しループ処理
    $(this).find('[name="task_no"]').each(function (idx) {
        // タグ内に通し番号を設定（idxは0始まりなので+1する）
        $(this).html('<span class="seqno-task">' + parseInt(idx + 1) + '</span>');
    });

    //order_noにnoを代入
    var specific_tbody = document.getElementById("task_body");
    var bodyLength = specific_tbody.rows.length;
    for (cnt = 0; cnt < bodyLength; cnt++) {
        //alert(specific_tbody.rows[cnt].cells[0].innerText + " " + specific_tbody.rows[cnt].cells[3].children[0].name);
        specific_tbody.rows[cnt].cells[4].children[0].value = specific_tbody.rows[cnt].cells[0].innerText;
    }
}

function appendRow()
{
    var objTBL = document.getElementById("tbl");
    if (!objTBL)
        return;

    insertTaskRow("", "", "",true);

    // 追加した行の入力フィールドへフォーカスを設定
    //var objInp = document.getElementById("task_name" + count);
    //if (objInp)
    //    objInp.focus();
}

function insertTaskRow(name, status, taskId, isNew) {
    // 最終行に新しい行を追加
    var specific_tbody = document.getElementById("task_body");
    var bodyLength = specific_tbody.rows.length;
    var count = bodyLength + 1;
    var row = specific_tbody.insertRow(bodyLength);

    // 列の追加
    var c1 = row.insertCell(0);
    c1.setAttribute("name", "task_no");
    var c2 = row.insertCell(1);
    //var c3 = row.insertCell(2);
    //var c4 = row.insertCell(3);
    var c3 = row.insertCell(2);
    var c4 = row.insertCell(3);
    var c5 = row.insertCell(4);
    var c6 = row.insertCell(5);
    
    //var status = "readonly";
    /*var taskNameObj = '<input class="inpname form-control form-control-sm" type="text" id="task_name' + count + '" name="task_name' + count + '" value="' + name + '" style="width: 100%" readonly>';
    var taskInfo = JSON.parse(document.getElementById("task_info").value);
    if (isNew) {
        taskNameObj = '<input type="text" name="task_name' + count + '" id="task_name' + count + '" class="inpname form-control form-control-sm" list="samplelist">';
        taskNameObj += '<datalist id="samplelist">';
        for (tCnt = 0; tCnt < taskInfo.length; tCnt++) {
            taskNameObj += '<option value="' + taskInfo[tCnt].name + '"></option>';
        }
        taskNameObj += '</datalist>';
    }*/
    taskNameObj = '<select class="inpname form-control form-control-sm" id="task_name' + count + '" name="task_name' + count + '" style="width: 100%">';
    var taskInfo = JSON.parse(document.getElementById("task_info").value);     
    for (tCnt = 0; tCnt < taskInfo.length; tCnt++) {
        if(isNew){
            var isExistTask = false;
            for(hCnt = 1; hCnt < count; hCnt++){
                if(document.getElementById("task_name" + hCnt).value == taskInfo[tCnt].name){
                    isExistTask = true;
                }                    
            }
            //add new
            if(tCnt == 0){
                if(isExistTask){
                    taskNameObj += '<option style="background-color: lightgray" value="' + taskInfo[tCnt].name + '" selected disabled>' + taskInfo[tCnt].name + '</option>';
                }else{
                    taskNameObj += '<option value="' + taskInfo[tCnt].name + '" selected>' + taskInfo[tCnt].name + '</option>';
                } 
            }else{
                if(isExistTask){
                    taskNameObj += '<option style="background-color: lightgray" value="' + taskInfo[tCnt].name + '" disabled>' + taskInfo[tCnt].name + '</option>';
                }else{
                    taskNameObj += '<option value="' + taskInfo[tCnt].name + '">' + taskInfo[tCnt].name + '</option>';
                }
                
            }            
        }else{
            //load
            if(taskInfo[tCnt].name == name){
                taskNameObj += '<option value="' + taskInfo[tCnt].name + '" selected >' + taskInfo[tCnt].name + '</option>';
            }else{
                taskNameObj += '<option value="' + taskInfo[tCnt].name + '" disabled>' + taskInfo[tCnt].name + '</option>';
            }
        }
        /*if(tCnt == count - 1){
            if(isNew){
                taskNameObj += '<option value="' + taskInfo[tCnt].name + '" selected>' + taskInfo[tCnt].name + '</option>';
            }else{
                taskNameObj += '<option value="' + taskInfo[tCnt].name + '" selected disabled>' + taskInfo[tCnt].name + '</option>';
            }                
        }else{
            if(isNew){
                taskNameObj += '<option value="' + taskInfo[tCnt].name + '">' + taskInfo[tCnt].name + '</option>';
            }else{
                taskNameObj += '<option value="' + taskInfo[tCnt].name + '" disabled>' + taskInfo[tCnt].name + '</option>';
            }
        } */           
    }    
    taskNameObj += '</select>';

    // 各列にスタイルを設定
    c1.style.cssText = "text-align:center;vertical-align: middle ";
    c5.style.cssText = "visibility: collapse";
    c6.style.cssText = "visibility: collapse";
    
    // 各列に表示内容を設定
    c1.innerHTML = '<span class="seqno-task">' + count + '</span>';
    //c2.innerHTML = '<input class="inpname form-control form-control-sm" type="text"   id="task_name' + count + '" name="task_name' + count + '" value="' + name + '" style="width: 100%">';
    c2.innerHTML = taskNameObj;
    c3.innerHTML = '<img src="'+ imagesUrl + "/arrow.png" +'" style="width: 20px;height: 20px;margin-top: 8px">';
    c4.innerHTML = '<input class="delbtn btn btn-sm" type="image" src="'+ imagesUrl + "/delete.png" +'" id="delBtnTask' + count + '" value="Delete" onclick="deleteRow(this);return false;">';
    c5.innerHTML = '<input class="inporder" type="text" id="order' + count + '" name="order' + count + '" value="' + count + '" style="width: 20px">';
    c6.innerHTML = '<input class="inptaskid" type="text" id="task_id' + count + '" name="task_id' + count + '" value="' + taskId + '" style="width: 20px">';
}

function appendBudgetRow()
{
    var objTBL = document.getElementById("budget_list");
    if (!objTBL)
        return;

    // 最終行に新しい行を追加
    var specific_tbody = document.getElementById("project_body");
    var bodyLength = specific_tbody.rows.length;
    var count = bodyLength + 1;
    var row = specific_tbody.insertRow(bodyLength);

    // 列の追加
    var c1 = row.insertCell(0);
    var c2 = row.insertCell(1);
    var c3 = row.insertCell(2);
    var c4 = row.insertCell(3);
    var c5 = row.insertCell(4);
    var c6 = row.insertCell(5);   
    var c7 = row.insertCell(6);

    // 各列にスタイルを設定
    c2.style.cssText = "width: 150px";
    c1.style.cssText = "text-align:center;vertical-align: middle";

    var staffInitialOption = "<option value=''></option>";
    var staffInfo = JSON.parse(document.getElementById("staff_info").value);
    for (sCnt = 0; sCnt < staffInfo.length; sCnt++) {
        staffInitialOption += '<option value="' + staffInfo[sCnt].id + '">' + staffInfo[sCnt].initial + '</option>';
    }

    // 各列に表示内容を設定
    c1.innerHTML = '<span class="seqno">' + count + '</span>';
    c2.innerHTML = '<select class="inpassign form-control form-control-sm" id="assign' + count + '" name="assign' + count + '" onchange="setStaffRate(this,' + count + ')">' + staffInitialOption + '</select>';
    c3.innerHTML = '<input class="inprole form-control form-control-sm" id="role' + count + '" name="role' + count + '" style="width: 100%" value="' + '" readonly>';
    c4.innerHTML = '<input class="inphours form-control form-control-sm" type="text" oninput="inputHarfCharPeriod(this)" onchange="calc()" id="hours' + count + '" name="hours' + count + '" value="0" style="text-align: right;width: 100%">';
    c5.innerHTML = '<div style="float: left;margin-right: 3px;margin-top: 7px">$</div><input class="inprate form-control form-control-sm" type="text" onchange="calc()" id="rate' + count + '" name="rate' + count + '" value="0" style="text-align: right;width: 50px" readonly>';
    c6.innerHTML = '<div style="float: left;margin-right: 3px;margin-top: 7px">$</div><input class="inpbudget form-control form-control-sm" type="text" id="budget' + count + '" name="budget' + count + '" value="0" style="text-align: right;width: 73px"  readonly>';
    //c7.innerHTML = '<input class="edtBudgetBtn btn btn-success btn-sm" type="button" id="edtBtn' + count + '" value="確定" onclick="editRowBudgetList(this)">';
    c7.innerHTML = '<input class="delBudgetBtn btn btn-sm" type="image" src="'+ imagesUrl + "/delete.png" +'" id="delBtnBudget' + count + '" value="Delete" onclick="delRowBudgetList(this);return false;">';

}

function insertBudgetRow(staffId, role, hours) {
    // 最終行に新しい行を追加
    var specific_tbody = document.getElementById("project_body");
    var bodyLength = specific_tbody.rows.length;
    var count = bodyLength + 1;
    var row = specific_tbody.insertRow(bodyLength);

    // 列の追加
    var c1 = row.insertCell(0);
    var c2 = row.insertCell(1);
    var c3 = row.insertCell(2);
    var c4 = row.insertCell(3);
    var c5 = row.insertCell(4);
    var c6 = row.insertCell(5);
    //var c7 = row.insertCell(6);
    var c8 = row.insertCell(6);

    // 各列にスタイルを設定
    c2.style.cssText = "width: 150px";
    c1.style.cssText = "text-align:center;vertical-align: middle";  

    var staffInitialOption = "<option value=''></option>";
    var staffInfo = JSON.parse(document.getElementById("staff_info").value);
    for (sCnt = 0; sCnt < staffInfo.length; sCnt++) {
        if (staffId == staffInfo[sCnt].id) {
            staffInitialOption += '<option selected value="' + staffInfo[sCnt].id + '">' + staffInfo[sCnt].initial + '</option>';
        } else {
            staffInitialOption += '<option value="' + staffInfo[sCnt].id + '">' + staffInfo[sCnt].initial + '</option>';
        }
    }

    var staffRate = 0;
    var staffInfo = JSON.parse(document.getElementById("staff_info").value);
    for (sCnt = 0; sCnt < staffInfo.length; sCnt++) {
        if (staffId == staffInfo[sCnt].id) {
            staffRate = staffInfo[sCnt].rate;
        }
    }

    // 各列に表示内容を設定
    c1.innerHTML = '<span class="seqno">' + count + '</span>';
    c2.innerHTML = '<select class="inpassign form-control form-control-sm" id="assign' + count + '" name="assign' + count + '" onchange="setStaffRate(this,' + count + ')">' + staffInitialOption + '</select>';
    c3.innerHTML = '<input class="inprole form-control form-control-sm" id="role' + count + '" name="role' + count + '" style="width: 100%" value="' + role + '" readonly>';
    c4.innerHTML = '<input class="inphours form-control form-control-sm" type="text" oninput="inputHarfCharPeriod(this)" onchange="calc()" id="hours' + count + '" name="hours' + count + '" value="' + hours + '" style="text-align: right;width: 100%">';
    c5.innerHTML = '<div style="float: left;margin-right: 3px;margin-top: 7px">$</div><input class="inprate form-control form-control-sm" type="text" onchange="calc()" id="rate' + count + '" name="rate' + count + '" value="' + staffRate + '" style="text-align: right;width: 50px;float: left" readonly>';
    c6.innerHTML = '<div style="float: left;margin-right: 3px;margin-top: 7px">$</div><input class="inpbudget form-control form-control-sm" type="text" id="budget' + count + '" name="budget' + count + '" value="0" style="text-align: right;width: 73px"  readonly>';
    //c7.innerHTML = '<input class="edtBudgetBtn btn btn-success btn-sm" type="button" id="edtBtn' + count + '" value="確定" onclick="editRowBudgetList(this)">';
    c8.innerHTML = '<input class="delBudgetBtn btn btn-sm" type="image" src="'+ imagesUrl + "/delete.png" +'" id="delBtnBudget' + count + '" value="Delete" onclick="delRowBudgetList(this);return false;">';

}

/*
 * deleteRow: 削除ボタン該当行を削除
 */
function deleteRow(obj)
{
    delRowCommon(obj, "seqno-task");

    // id/name ふり直し
    var tagElements = document.getElementsByTagName("input");
    if (!tagElements)
        return false;
    
    var stagElements = document.getElementsByClassName("inpname form-control form-control-sm");
  
    var seq = 1;
    reOrderElementTag(stagElements, "inpname", "task_name");
    reOrderElementTag(tagElements, "inpstatus", "task_status");
    reOrderElementTag(tagElements, "inporder", "order");
    reOrderElementTag(tagElements, "inptaskid", "task_id");

    //reOrderElementTag(tagElements, "edtbtn", "edtBtn");
    reOrderElementTag(tagElements, "delbtn", "delBtn");
    
    reOrderTaskNo();
}

function editRow(obj)
{
    var objTR = obj.parentNode.parentNode;
    var rowId = objTR.sectionRowIndex + 1;
    var objInp = document.getElementById("task_name" + rowId);
    //var objSt = document.getElementById("task_status" + rowId);
    var objBtn = document.getElementById("edtBtn" + rowId);

    if (!objInp || !objBtn)
        return;

    // モードの切り替えはボタンの値で判定   
    if (objBtn.value == "編集")
    {
        objInp.style.cssText = "border:1px solid #888;"
        objInp.readOnly = false;
        objInp.focus();
        //objSt.disabled = "";
        objBtn.value = "確定";
    } else
    {
        objInp.style.cssText = "border:none;"
        objInp.readOnly = true;
        //objSt.disabled = "disabled";
        objBtn.value = "編集";
    }
}

function editRowBudgetList(obj)
{
    var objTR = obj.parentNode.parentNode;
    var rowId = objTR.sectionRowIndex + 1;
    var objHours = document.getElementById("hours" + rowId);
    var objRate = document.getElementById("rate" + rowId);
    var objBtn = document.getElementById("edtBtn" + rowId);

    // モードの切り替えはボタンの値で判定   
    if (objBtn.value == "編集")
    {
        $("#assign" + rowId + " option:not(:selected)").prop('disabled', false);
        $("#role" + rowId + " option:not(:selected)").prop('disabled', false);
        objHours.readOnly = false;
        objRate.readOnly = false;
        //objInp.focus();
        objBtn.value = "確定";
    } else
    {
        $("#assign" + rowId + " option:not(:selected)").prop('disabled', true);
        $("#role" + rowId + " option:not(:selected)").prop('disabled', true);
        objHours.readOnly = true;
        objRate.readOnly = true;
        objBtn.value = "編集";
    }
}

function delRowBudgetList(obj) {
    delRowCommon(obj, "seqno");

    // id/name ふり直し
    var tagElements = document.getElementsByTagName("input");
    if (!tagElements)
        return false;

    var selectTagElements = document.getElementsByTagName("select");

    reOrderElementTag(tagElements, "inphours", "hours");
    reOrderElementTag(selectTagElements, "inpassign", "assign");
    reOrderElementTag(tagElements, "inprole", "role");
    reOrderElementTag(tagElements, "inprate", "rate");
    reOrderElementTag(tagElements, "inpbudget", "budget");

    reOrderElementTag(tagElements, "edtBudgetBtn", "edtBtn");
    reOrderElementTag(tagElements, "delBudgetBtn", "delBtn");

    //再計算
    calc();
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

function getProjectName() {
    $("#harvest_project_name").val($("#project_type").val() + " - " + $("#project_year").val());
}

function save() {
    document.taskEnter.submit();
}

function calc() {
    //var objTBL = document.getElementById("budget_list");
    var objTBL = document.getElementById("project_body");
    if (!objTBL)
        return;

    var count = objTBL.rows.length;

    var total = 0;
    var totalHour = 0;
    for (var cnt = 1; cnt <= count; cnt++) {        
        var budgetAmount = parseFloat(removeComma(document.getElementById("hours" + cnt).value)) * parseFloat(removeComma(document.getElementById("rate" + cnt).value));        
        document.getElementById("budget" + cnt).value = budgetAmount.toLocaleString();
        total += parseFloat(removeComma(document.getElementById("budget" + cnt).value));        
        totalHour += parseFloat(removeComma(document.getElementById("hours" + cnt).value));
    }

    document.getElementById("total_budget").innerHTML = total.toLocaleString();
    document.getElementById("total_hours").innerHTML = totalHour.toLocaleString();
    
    var engTotal = document.getElementById("total_grand").value;//(parseInt(removeComma(document.getElementById("engagement_fee").value)) * parseInt(removeComma(document.getElementById("engagement_monthly").value))) + parseInt(removeComma(document.getElementById("adjustments").value));
    /*document.getElementById("engagement_total").innerHTML = 0;
    if (!isNaN(engTotal)) {
        document.getElementById("engagement_total").innerHTML = engTotal.toLocaleString();//(parseInt(document.getElementById("engagement_fee").value) * parseInt(document.getElementById("engagement_monthly").value)) + parseInt(document.getElementById("adjustments").value);
    }*/
    
    var defTotal = parseInt(removeComma(engTotal)) - total;
    document.getElementById("defference").innerHTML = 0;
    if (!isNaN(defTotal)) {
        document.getElementById("defference").innerHTML = defTotal.toLocaleString();//parseInt(document.getElementById("engagement_total").innerHTML) - total;
    }
    
    var realization = (new Decimal(parseInt(removeComma(engTotal))).div(total).times(100).toFixed(1));
    document.getElementById("realization").innerHTML = "0%";
    if (!isNaN(realization)) {
        document.getElementById("realization").innerHTML = realization + "%";
    }
}

function clickDuplicate(){
    Swal.fire({
        title: 'Duplicate',
        text: "Do you want to copy the project?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.value) {            
            loadTask("duplicate");
        }
    });
}

function loadTask(buttonType) {

    var client = $("#client").val();
    var type = $("#project_type").val();
    var year = $("#project_year").val();

    $.ajax({
        url: "/test3/getProjectInfo/" + client + "/" + type + "/" + year + "/",
    }).success(function (data) {

        //staff情報セット
        $('#staff_info').val(JSON.stringify(data.staff));
        
        //task情報セット
        $('#task_info').val(JSON.stringify(data.allTask));
        
        //project id 保持
        if(data.project != null){
            $('#rec_project_id').val(data.project.id);            
        }        
        
        var param1 = document.getElementById("project_type").value;
        var param2 = document.getElementById("project_year").value;
        var param3 = document.getElementById("reqClient").value;
        if(param3 == ""){
            param3 = document.getElementById("client").value;
        }
        
        //パラメータ書き換え
        history.replaceState('','',"/master/project/" + param3 + "/" + param1 + " - " + param2);
        
        //approved
        /*document.getElementById("btn_approve").disabled = false;
        document.getElementById("savingText").innerHTML = "Approve";
        $("#taskEnter").find('input,textarea,select,button').prop('disabled', false);        
        if(data.project != null && data.project.is_approval == 1){
            document.getElementById("btn_approve").disabled = true;
            document.getElementById("savingText").innerHTML = "Unapprove";
            $("#taskEnter").find('input,textarea,select,button').prop('disabled', true);     
            $("#btn_save").prop('disabled', false);
            $("#is_archive").prop('disabled', false);    
            
            $("#project_body").find('input,select,button').prop('disabled', true);    
              
        }
        document.getElementById("btn_approve").disabled = false;*/

        //project
        //初期化
        document.getElementById("starts_on").value = "";
        document.getElementById("ends_on").value = "";
        //document.getElementById("engagement_fee").value = 0;
        //document.getElementById("engagement_monthly").value = 0;
        //document.getElementById("adjustments").value = 0;
        document.getElementById("billable").selectedIndex = 0;
        document.getElementById("note").value = "";
        document.getElementById("pic").selectedIndex = 0;
        document.getElementById("fye").selectedIndex = 0;
        document.getElementById("is_archive").selectedIndex = 0;
        document.getElementById("archive_date").value = "";

        if (data.project !== null) {
            if (data.project.start != "") {
                var startArray = data.project.start.split("-");
                document.getElementById("starts_on").value = startArray[1] + "/" + startArray[2] + "/" + startArray[0];
            }
            if (data.project.end != "") {
                var endArray = data.project.end.split("-");
                document.getElementById("ends_on").value = endArray[1] + "/" + endArray[2] + "/" + endArray[0];
            }
            //document.getElementById("engagement_fee").value = data.project.engagement_fee_unit.toLocaleString();
            //document.getElementById("engagement_monthly").value = data.project.invoice_per_year;
            //document.getElementById("adjustments").value = data.project.adjustments;
            //document.getElementById("billable").selectedIndex = data.project.billable;
            $("#billable").val(data.project.billable);  
            document.getElementById("note").value = data.project.note;
            //document.getElementById("pic").selectedIndex = data.project.pic - 1;
            $("#pic").val(data.project.pic);  
            if(data.project.is_archive == 1){
                $("#is_archive").val(data.project.is_archive);  
                //$("#archive_date").val(data.project.archive_date);  
                if (data.project.archive_date != "") {
                    var archiveArray = data.project.archive_date.split("-");
                    document.getElementById("archive_date").value = archiveArray[1] + "/" + archiveArray[2] + "/" + archiveArray[0];
                }
            }            

            document.getElementById("harvest_project_id").value = data.project.project_harvest_id;
        }
        
        if (document.getElementById("starts_on").value == "") {
            var defaultDate = new Date();
            document.getElementById("starts_on").value = ('00' + (defaultDate.getMonth() + 1)).slice(-2) + "/" + defaultDate.getDate() + "/" + defaultDate.getFullYear();
        }
        
        if (document.getElementById("ends_on").value == "") {
            var defaultDate = new Date();
            document.getElementById("ends_on").value = ('00' + (defaultDate.getMonth() + 1)).slice(-2) + "/" + defaultDate.getDate() + "/" + defaultDate.getFullYear();
        }
        

        var fyeMonth = data.client.fye.split("/")["0"];
        document.getElementById("fye").selectedIndex = fyeMonth - 1;

        //task 初期化
        $("#task_body").empty();
        for (var cnt = 0; cnt < data.task.length; cnt++) {
            insertTaskRow(data.task[cnt]["name"], data.task[cnt]["is_checked"], data.task[cnt]["task_id"],false);
        }

        //budget
        $("#project_body").empty();
        for (var cnt = 0; cnt < data.budget.length; cnt++) {
            insertBudgetRow(data.budget[cnt]["staff_id"], data.budget[cnt]["role"], data.budget[cnt]["budget_hour"]);
        }
        
        //engagement
        //初期化
        $("#budget_engagement_body").empty();
        var startMonth = "1";
        var startYear = "2020";
        //totalEngagementColumn();
        for (var cnt = 0; cnt < data.engagement.length; cnt++) {                  
            
            appendEngagementRow(data.engagement[cnt]["type"],Number(data.engagement[cnt]["col1"]).toLocaleString(),data.engagement[cnt]["col2"],data.engagement[cnt]["col3"],data.engagement[cnt]["col4"],data.engagement[cnt]["col5"],data.engagement[cnt]["col6"],data.engagement[cnt]["col7"],data.engagement[cnt]["col8"],data.engagement[cnt]["col9"],data.engagement[cnt]["col10"],data.engagement[cnt]["col11"],data.engagement[cnt]["col12"],data.engagement[cnt]["doc_type"],data.engagement[cnt]["location"]);
            calcEngagementFeeDetail(parseInt(cnt)+1);
            
            startMonth = data.engagement[cnt].start_month;
            startYear = data.engagement[cnt].start_year;
        }
        totalEngagementColumn();
        
        document.getElementById("start_month").selectedIndex = parseInt(startMonth) -1;
        document.getElementById("engagement_year").value = startYear;
        
        setEngagementHeader();

        //計算実行
        calc();
        
        //disabled設定
        document.getElementById("btn_approve").disabled = false;
        document.getElementById("savingText").innerHTML = "Approve";
        
        $("#taskEnter").find('input,textarea,select,button').prop('disabled', false);
        if (data.project != null && data.project.is_approval == 1) {
            document.getElementById("btn_approve").disabled = true;
            document.getElementById("savingText").innerHTML = "Unapprove";
            $("#taskEnter").find('input,textarea,select,button').prop('disabled', true);
            $("#btn_save").prop('disabled', false);
            $("#is_archive").prop('disabled', false);
            $("#btnDuplicate").prop('disabled', false);
        }
        document.getElementById("btn_approve").disabled = false;

        $("#archive_date").prop('disabled', true);
        $("#sync_archive_status").prop('disabled', true);
        if(document.getElementById("is_archive").value == "1"){
            $("#archive_date").prop('disabled', false);
            $("#sync_archive_status").prop('disabled', false);
        }
        
        //duplicate時初期化
        if(buttonType == "duplicate"){
            document.getElementById("project_year").selectedIndex = 0;
            document.getElementById("starts_on").value = "";
            document.getElementById("ends_on").value = "";
        }


    }).done((data, textStatus, jqXHR) => {
        
        if(buttonType == "duplicate"){
            $("#taskEnter").find('input,textarea,select,button').prop('disabled', false);
            showDuplicateToast();
        }
        
    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        if (XMLHttpRequest.status === 401) {
            location.href = location.protocol + "//" + location.hostname + "/login";
        }
    });
}

function setStaffRate(assignObj, rowNo) {
    var id = assignObj.value;

    var staffInfo = JSON.parse(document.getElementById("staff_info").value);
    for (sCnt = 0; sCnt < staffInfo.length; sCnt++) {
        if (id == staffInfo[sCnt].id) {
            document.getElementById("rate" + rowNo).value = staffInfo[sCnt].rate;
            document.getElementById("role" + rowNo).value = staffInfo[sCnt].billing_title;
        }
    }
}

function getIsDuplicate(arr1) {
    var s = new Set(arr1);
    return s.size != arr1.length;
}

function getErrorText(){
    var objTBL = document.getElementById("project_body");
    if (!objTBL)
        return;

    var count = objTBL.rows.length;
    
    var objTaskTBL = document.getElementById("tbl");
    var taskCount = objTaskTBL.rows.length;
    
    var objEngagementFeeTBL = document.getElementById("budget_engagement_body");
    var engagementCount = objEngagementFeeTBL.rows.length;
    
    var staffArray = [];
    var errorText = "";
    var isStaffError = false;
    var isHoursError = false;
    var isTaskError = false;
    var isEngagementFeeError = false;
    var isEngegementDocTypeError = false;
    for (var cnt = 1; cnt <= count; cnt++) {
        var assign = document.getElementById("assign" + cnt).selectedIndex;
        var hours = document.getElementById("hours" + cnt).value;
        if(assign == 0){
            isStaffError = true;
        }
        
        if(hours == "" || hours == 0){
            isHoursError = true;
        }
        
        staffArray.push(assign);
        
    }
    
    for(var cnt = 1; cnt < taskCount; cnt++){
        var taskName = document.getElementById("task_name" + cnt).value;
        if(taskName == ""){
            isTaskError = true;
        }
    }
    
    //budget engagement
    for(var cnt = 1; cnt <= engagementCount; cnt++){
        var engageTypeName = document.getElementById("type" + cnt).value;
        if(engageTypeName == ""){
            isEngagementFeeError = true;
        }

        var engageDocType = document.getElementById("dec_type" + cnt).value;
        if(engageDocType == ""){
            isEngegementDocTypeError = true;
        }
    }

    //taskの重複チェック
    var tblCnt = document.getElementById("tbl").rows.length;
    var isTaskExistCntError = false;
    for(cnt = 1; cnt < tblCnt; cnt++){
        var targetTaskName = document.getElementById("task_name" + cnt).value;
        var existCnt = 0;
        for(bCnt = 1; bCnt < tblCnt; bCnt++){
            var compTaskName = document.getElementById("task_name" + bCnt).value;
            if(targetTaskName == compTaskName){
                existCnt += 1;
                if(existCnt == 2){
                    isTaskExistCntError = true;
                    break;
                }                
            }
        }
        if(existCnt == 2){
            isTaskExistCntError = true;
            break;
        }
    }

    //task重複チェック
    if(isTaskExistCntError){
        errorText += "Duplicate Task exists.<br>";
    }
    
    //未選択チェック
    if(isStaffError){
        errorText += "Staff field is required.<br>";
    }
    
    //Staff重複チェック
    if(getIsDuplicate(staffArray)){
        errorText += "Duplicate Staff exists.<br>";
    }
    
    //Budget hours 未入力チェック
    if(isHoursError){
        errorText += "Budget Hour is required.<br>";
    }
    
    //starts on 未入力チェック
    var startsOnVal = document.getElementById("starts_on").value;
    if(startsOnVal == ""){
        errorText += "Starts on is required.<br>";
    }
    
    //ends on 未入力チェック
    var endsOnVal = document.getElementById("ends_on").value;
    if(endsOnVal == ""){
        errorText += "Ends on is required.<br>";
    }

    //starts on ends on 妥当性チェック
    if(startsOnVal != "" && endsOnVal != ""){
        var startsOnArray = startsOnVal.split("/");
        var endsOnArray = endsOnVal.split("/");
        var startsOnString = startsOnArray[2].concat(("00" + startsOnArray[0]).slice(-2),("00" + startsOnArray[1]).slice(-2));
        var endsOnString = endsOnArray[2].concat(("00" + endsOnArray[0]).slice(-2),("00" + endsOnArray[1]).slice(-2));
        if(Number(startsOnString) >= Number(endsOnString)){
            errorText += "Ends on must be after Starts on.<br>";
        }
    }
    
    //project year 未入力
    var projectYearVal = document.getElementById("project_year").value;
    if(projectYearVal == "blank"){
        errorText += "Project Year is required.<br>";
    }    
    
    if(isTaskError){
        errorText += "Task field is required.<br>";
    }
    
    if(isEngagementFeeError){
        errorText += "Engagament Fee Type is required.<br>";
    }

    if(isEngegementDocTypeError){
        errorText += "Engagament Fee Doc Type is required.<br>";
    }

    //archive date 必須
    var isArchiveVal = document.getElementById("is_archive").value;
    var archiveDateVal = document.getElementById("archive_date").value;
    if(isArchiveVal == 1 && archiveDateVal == ""){
        errorText += "Archive Date is required.<br>";
    }
    
    return errorText;
}

function saveForm() {
    
    //エラーチェック
    var errorText = getErrorText();
    if (errorText != "") {
        showErrorToast(errorText);
        return;
    }        
    
    $("#taskEnter").find('input,textarea,select,button').prop('disabled', false);   
    
    saveDetail();
    
    /*
    var params = $("form").serialize();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/master/project/test3",
        type: "POST",
        data: params,
        timeout: 10000,
        beforeSend: function (xhr, settings) {
            //Buttonを無効にする
            //$('#btnProfileUpdate').attr('disabled', true);
            //処理中のを通知するアイコンを表示する
            //$('#boxEmailSettings').append('<div class="overlay" id ="spin" name = "spin"><i class="fa fa-refresh fa-spin"></i></div>');

            //処理中
            $("#savingSpinner").css("visibility", "visible");
            $("#savingText").html("保存中");
            $("#taskEnter").find(':input').attr('disabled', true);
            $("#btn_save").attr('disabled', true);

        },
        complete: function (xhr, textStatus) {
            //処理中アイコン削除
            //$('#spin').remove();
            //$('#btnProfileUpdate').attr('disabled', false);
            //処理済
            $("#savingSpinner").css("visibility", "hidden");
            $("#savingText").html("保存");
            $("#taskEnter").find(':input').attr('disabled', false);
            $("#taskEnter").find(':input').removeAttr('disabled');
            $("#btn_save").attr('disabled', false);
            $("#btn_save").removeAttr('disabled');

            showToast();
        },
        success: function (result, textStatus, xhr) {
            //ret = jQuery.parseJSON(result);
            //Alertで送信結果を表示する
            //if (ret.success) {
            //    $('#alert_profile_content').html(ret.message);
            //    $('#alerts_profile').attr('class', 'alert alert-success alert-dismissible');
            //} else {
            //    var messageBags = ret.errors;
            //    $('#alertContent').html('');
            //    var html = '';
            //    jQuery.each(messageBags, function (key, value) {
            //        var fieldName = key;
            //        var errorMessages = value;
            //        jQuery.each(errorMessages, function (msgID, msgContent) {
            //            html += '<li>' + msgContent + '</li>';
            //        });
            //    });
            //    $('#alert_profile_content').html(html);
            //    $('#alerts_profile').attr('class', 'alert alert-danger alert-dismissible');
            //}
            //$('#alerts_profile').show();
        },
        error: function (data) {
            //$('#btnProfileUpdate').attr('disabled', false);
            console.debug(data);
        }
    });*/
}

function saveDetail(){
    var params = $("form").serialize();
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/master/project/test3",
        type: "POST",
        data: params,
        timeout: 10000,
        beforeSend: function (xhr, settings) {
            //Buttonを無効にする
            //$('#btnProfileUpdate').attr('disabled', true);
            //処理中のを通知するアイコンを表示する
            //$('#boxEmailSettings').append('<div class="overlay" id ="spin" name = "spin"><i class="fa fa-refresh fa-spin"></i></div>');
            jQuery('#loader-bg').show();
            //処理中
            $("#savingSpinner").css("visibility", "visible");
            //$("#savingText").html("保存中");
            $("#taskEnter").find(':input').attr('disabled', true);
            $("#btn_save").attr('disabled', true);

        },
        complete: function (xhr, textStatus) {
            //処理中アイコン削除
            //$('#spin').remove();
            //$('#btnProfileUpdate').attr('disabled', false);
            //処理済
            $("#savingSpinner").css("visibility", "hidden");
            //$("#savingText").html("保存");
            $("#taskEnter").find(':input').attr('disabled', false);
            $("#taskEnter").find(':input').removeAttr('disabled');
            $("#btn_save").attr('disabled', false);
            $("#btn_save").removeAttr('disabled');
            
            //disabled設定
            if (document.getElementById("savingText").innerHTML == "Unapprove") {
                $("#taskEnter").find('input,textarea,select,button').prop('disabled', true);
                $("#btn_save").prop('disabled', false);
                $("#is_archive").prop('disabled', false);
                $("#btnDuplicate").prop('disabled', false);
                document.getElementById("btn_approve").disabled = false;
            }

            $("#archive_date").prop('disabled', true);
            $("#sync_archive_status").prop('disabled', true);
            if(document.getElementById("is_archive").value == "1"){
                $("#archive_date").prop('disabled', false);
                $("#sync_archive_status").prop('disabled', false);
            }

            showToast();

            jQuery('#loader-bg').hide();

            loadTask('search');
        },
        success: function (result, textStatus, xhr) {
            //ret = jQuery.parseJSON(result);
            //Alertで送信結果を表示する
            //if (ret.success) {
            //    $('#alert_profile_content').html(ret.message);
            //    $('#alerts_profile').attr('class', 'alert alert-success alert-dismissible');
            //} else {
            //    var messageBags = ret.errors;
            //    $('#alertContent').html('');
            //    var html = '';
            //    jQuery.each(messageBags, function (key, value) {
            //        var fieldName = key;
            //        var errorMessages = value;
            //        jQuery.each(errorMessages, function (msgID, msgContent) {
            //            html += '<li>' + msgContent + '</li>';
            //        });
            //    });
            //    $('#alert_profile_content').html(html);
            //    $('#alerts_profile').attr('class', 'alert alert-danger alert-dismissible');
            //}
            //$('#alerts_profile').show();
        },
        error: function (data) {
            //$('#btnProfileUpdate').attr('disabled', false);
            console.debug(data);
        }
    });
}

function saveApprove(){
    var projectId = document.getElementById("rec_project_id").value;
    var obj = document.getElementById("btn_approve");    
    var appText = document.getElementById("savingText").innerHTML;
    var harvestProjectId = document.getElementById("harvest_project_id").value;
    if(harvestProjectId == ""){
        harvestProjectId = "blank"
    }
    
    if(appText == "Approve"){
        obj.style.backgroundColor = "#DCDCDC";
        document.getElementById("savingText").innerHTML = "Unapprove";
        //obj.disabled = true;
    }else {
        obj.style.backgroundColor = "#3c8dbc";
        document.getElementById("savingText").innerHTML = "Approve";
    }
    
    var message = "Approved";
    if(appText == "Unapprove"){
        message = "Unapproved";
    }
    
    jQuery('#loader-bg').show();

    $.ajax({
        url: "/master/project-list/save/" + projectId + "/" + appText + "/" + harvestProjectId,
        dataType: "json",
        success: data => {
            //disabled制御
            $("#taskEnter").find('input,textarea,select,button').prop('disabled', false);
            if(message == "Approved"){
                $("#taskEnter").find('input,textarea,select,button').prop('disabled', true);
                $("#btn_save").prop('disabled', false);
                $("#is_archive").prop('disabled', false);
                $("#btnDuplicate").prop('disabled', false);
                document.getElementById("btn_approve").disabled = false;
            }

            //harvest id セット
            if(data.harvest_project.id != null){
                document.getElementById("harvest_project_id").value = data.harvest_project.id;
            }
            
            Swal.fire({
                position: 'top',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 1500
            });

            jQuery('#loader-bg').hide();
        },        
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

function showErrorToast(errorText) {
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: 'Error',
        html: errorText
    });
}

function showDuplicateToast() {
    Swal.fire({
        position: 'top',
        icon: 'success',
        title: 'Duplicate',
        showConfirmButton: false,
        timer: 1500
    });
}

//半角数字のみ
function inputHarfChar($this)
{
    var str=$this.value;
    while(str.match(/[^0-9]/))
    {
        str=str.replace(/[^0-9]/,"");
    }
    $this.value=str;
}

//半角数字、ピリオドのみ
function inputHarfCharPeriod($this)
{
    var str=$this.value;
    while(str.match(/[^0-9.-]/))
    {
        str=str.replace(/[^0-9.-]/,"");
    }
    $this.value=str;
}

//半角英字のみ
function inputHarfAlpha($this)
{
    var str=$this.value;
    while(str.match(/[^a-zA-Z]/))
    {
        str=str.replace(/[^a-zA-Z]/,"");
    }
    $this.value=str;
}

function appendEngagementRow(type,jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,dec,docType,location){
    var objTBL = document.getElementById("budget_engagement");
    if (!objTBL)
        return;

    // 最終行に新しい行を追加
    var specific_tbody = document.getElementById("budget_engagement_body");
    var bodyLength = specific_tbody.rows.length;
    var count = bodyLength + 1;
    var row = specific_tbody.insertRow(bodyLength);

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

    var decTypeSelect = '<select class="inpengagementdec form-control" id="dec_type' + count + '" name="dec_type' + count + '">';    

    decTypeSelect += '<option value=""></option>';
    if(docType == 1){
        decTypeSelect += '<option value="1" selected>Engagement Letter</option>';
    }else{
        decTypeSelect += '<option value="1">Engagement Letter</option>';
    }
    
    if(docType == 2){
        decTypeSelect += '<option value="2" selected>Email</option>';    
    }else {
        decTypeSelect += '<option value="2">Email</option>';    
    }
    if(docType == 3){
        decTypeSelect += '<option value="3" selected>Other Agreement</option>';
    }else {
        decTypeSelect += '<option value="3">Other Agreement</option>';
    }
    if(docType == 4){
        decTypeSelect += '<option value="4" selected>Est’d - PY</option>';
    }else {
        decTypeSelect += '<option value="4">Est’d - PY</option>';
    }    
    if(docType == 5){
        decTypeSelect += '<option value="5" selected>Esti’d - Rough</option>';
    }else {
        decTypeSelect += '<option value="5">Esti’d - Rough</option>';
    }   
    decTypeSelect += '</select>';
    
    // 各列にスタイルを設定
    //c2.style.cssText = "width: 150px";
    c1.style.cssText = "vertical-align: middle;text-align: center";
    c18.style.cssText = "vertical-align: middle";
    c19.style.cssText = "vertical-align: middle";
   
    // 各列に表示内容を設定
    c1.innerHTML = '<span class="seqnoengagement">' + count + '</span>';    
    c2.innerHTML = '<input class="inpengagementtype form-control form-control-sm" id="type' + count + '" name="type' + count + '" style="width: 100%" value="' + type + '">';
    c3.innerHTML = decTypeSelect;
    c4.innerHTML = '<input class="inpengagementlocation form-control form-control-sm" id="location' + count + '" name="location' + count + '" style="width: 100%;" value="' + location + '">';
    c5.innerHTML = '<input class="inpengagementjan form-control form-control-sm" id="jan' + count + '" name="jan' + count + '" style="width: 100%;text-align: right" oninput="inputHarfCharPeriod(this)" onchange="calcEngagementFee(this,' + count + ')" value="' + jan + '">';
    c6.innerHTML = '<input class="inpengagementfeb form-control form-control-sm" id="feb' + count + '" name="feb' + count + '" style="width: 100%;text-align: right" oninput="inputHarfCharPeriod(this)" onchange="calcEngagementFee(this,' + count + ')" value="' + feb + '">';
    c7.innerHTML = '<input class="inpengagementmar form-control form-control-sm" id="mar' + count + '" name="mar' + count + '" style="width: 100%;text-align: right" oninput="inputHarfCharPeriod(this)" onchange="calcEngagementFee(this,' + count + ')" value="' + mar + '">';
    c8.innerHTML = '<input class="inpengagementapr form-control form-control-sm" id="apr' + count + '" name="apr' + count + '" style="width: 100%;text-align: right" oninput="inputHarfCharPeriod(this)" onchange="calcEngagementFee(this,' + count + ')" value="' + apr + '">';
    c9.innerHTML = '<input class="inpengagementmay form-control form-control-sm" id="may' + count + '" name="may' + count + '" style="width: 100%;text-align: right" oninput="inputHarfCharPeriod(this)" onchange="calcEngagementFee(this,' + count + ')" value="' + may + '">';
    c10.innerHTML = '<input class="inpengagementjun form-control form-control-sm" id="jun' + count + '" name="jun' + count + '" style="width: 100%;text-align: right" oninput="inputHarfCharPeriod(this)" onchange="calcEngagementFee(this,' + count + ')" value="' + jun + '">';
    c11.innerHTML = '<input class="inpengagementjul form-control form-control-sm" id="jul' + count + '" name="jul' + count + '" style="width: 100%;text-align: right" oninput="inputHarfCharPeriod(this)" onchange="calcEngagementFee(this,' + count + ')" value="' + jul + '">';
    c12.innerHTML = '<input class="inpengagementaug form-control form-control-sm" id="aug' + count + '" name="aug' + count + '" style="width: 100%;text-align: right" oninput="inputHarfCharPeriod(this)" onchange="calcEngagementFee(this,' + count + ')" value="' + aug + '">';
    c13.innerHTML = '<input class="inpengagementsep form-control form-control-sm" id="sep' + count + '" name="sep' + count + '" style="width: 100%;text-align: right" oninput="inputHarfCharPeriod(this)" onchange="calcEngagementFee(this,' + count + ')" value="' + sep + '">';
    c14.innerHTML = '<input class="inpengagementoct form-control form-control-sm" id="oct' + count + '" name="oct' + count + '" style="width: 100%;text-align: right" oninput="inputHarfCharPeriod(this)" onchange="calcEngagementFee(this,' + count + ')" value="' + oct + '">';
    c15.innerHTML = '<input class="inpengagementnov form-control form-control-sm" id="nov' + count + '" name="nov' + count + '" style="width: 100%;text-align: right" oninput="inputHarfCharPeriod(this)" onchange="calcEngagementFee(this,' + count + ')" value="' + nov + '">';
    c16.innerHTML = '<input class="inpengagementdec form-control form-control-sm" id="dec' + count + '" name="dec' + count + '" style="width: 100%;text-align: right" oninput="inputHarfCharPeriod(this)" onchange="calcEngagementFee(this,' + count + ')" value="' + dec + '">';
    c17.innerHTML = '<input class="inpengagementtotal form-control form-control-sm" id="total' + count + '" name="total' + count + '" style="width: 100%;text-align: right" oninput="inputHarfCharPeriod(this)" value="' + 0 + '" readonly>';        
    //c16.innerHTML = '<a><img src="'+ imagesUrl + "/duplicate.png" +'" id="copyBtnEngagement' + count + '" value="Copy" onclick="copyRowEngagementList(' + count + ');return;"></a>';    
    c18.innerHTML = '<input class="copyEngagementBtn btn btn-sm" type="image" src="'+ imagesUrl + "/duplicate.png" +'" id="copyBtnEngagement' + count + '" value="Copy" onclick="copyRowEngagementList(this);return false;">';
    //c17.innerHTML = '<a><img src="'+ imagesUrl + "/delete.png" +'" id="deleteBtnEngagement' + count + '" value="Copy" onclick="delRowEngagementList(this);return;"></a>';
    c19.innerHTML = '<input class="delEngagementBtn btn btn-sm" type="image" src="'+ imagesUrl + "/delete.png" +'" id="delBtnEngagement' + count + '" value="Delete" onclick="delRowEngagementList(this);return false;">';
}

function delRowEngagementList(obj) {
    delRowCommon(obj, "seqnoengagement");

    // id/name ふり直し
    var tagElements = document.getElementsByTagName("input");
    var tagSelectElements = document.getElementsByTagName("select");
    if (!tagElements)
        return false;


    reOrderElementTag(tagElements, "inpengagementtype", "type");    
    reOrderElementTag(tagSelectElements, "inpengagementdec", "dec_type");    
    reOrderElementTag(tagElements, "inpengagementlocation", "location");    
    reOrderElementTag(tagElements, "inpengagementjan", "jan");
    reOrderElementTag(tagElements, "inpengagementfeb", "feb");
    reOrderElementTag(tagElements, "inpengagementmar", "mar");
    reOrderElementTag(tagElements, "inpengagementapr", "apr");
    reOrderElementTag(tagElements, "inpengagementmay", "may");
    reOrderElementTag(tagElements, "inpengagementjun", "jun");
    reOrderElementTag(tagElements, "inpengagementjul", "jul");
    reOrderElementTag(tagElements, "inpengagementaug", "aug");
    reOrderElementTag(tagElements, "inpengagementsep", "sep");
    reOrderElementTag(tagElements, "inpengagementoct", "oct");
    reOrderElementTag(tagElements, "inpengagementnov", "nov");
    reOrderElementTag(tagElements, "inpengagementdec", "dec");
    reOrderElementTag(tagElements, "inpengagementtotal", "total");

    reOrderElementTag(tagElements, "copyEngagementBtn", "copyBtnEngagement");
    reOrderElementTag(tagElements, "delEngagementBtn", "delBtnEngagement");

    //再計算
    totalEngagementColumn();
    calc();
}

function calcEngagementFee(obj,count){
    addCommaObj(obj);
    calcEngagementFeeDetail(count);
    calc();
    /*
    //計算
    var totalObj = document.getElementById("total" + count);
    var janValue = document.getElementById("jan" + count);
    var febValue = document.getElementById("feb" + count);
    var marValue = document.getElementById("mar" + count);
    var aprValue = document.getElementById("apr" + count);
    var mayValue = document.getElementById("may" + count);
    var junValue = document.getElementById("jun" + count);
    var julValue = document.getElementById("jul" + count);
    var augValue = document.getElementById("aug" + count);
    var sepValue = document.getElementById("sep" + count);
    var octValue = document.getElementById("oct" + count);
    var novValue = document.getElementById("nov" + count);
    var decValue = document.getElementById("dec" + count);
    
    totalObj.value = parseInt(removeFormat(janValue)) + parseInt(removeFormat(febValue)) + parseInt(removeFormat(marValue)) + parseInt(removeFormat(aprValue))
            + parseInt(removeFormat(mayValue)) + parseInt(removeFormat(junValue)) + parseInt(removeFormat(julValue)) + parseInt(removeFormat(augValue)) + parseInt(removeFormat(sepValue))
            + parseInt(removeFormat(octValue)) + parseInt(removeFormat(novValue)) + parseInt(removeFormat(decValue));
    addCommaObj(totalObj);
    
    totalEngagementColumn();*/
}

function calcEngagementFeeDetail(count) {
    //計算
    var totalObj = document.getElementById("total" + count);
    var janValue = document.getElementById("jan" + count);
    var febValue = document.getElementById("feb" + count);
    var marValue = document.getElementById("mar" + count);
    var aprValue = document.getElementById("apr" + count);
    var mayValue = document.getElementById("may" + count);
    var junValue = document.getElementById("jun" + count);
    var julValue = document.getElementById("jul" + count);
    var augValue = document.getElementById("aug" + count);
    var sepValue = document.getElementById("sep" + count);
    var octValue = document.getElementById("oct" + count);
    var novValue = document.getElementById("nov" + count);
    var decValue = document.getElementById("dec" + count);

    totalObj.value = parseInt(removeFormat(janValue)) + parseInt(removeFormat(febValue)) + parseInt(removeFormat(marValue)) + parseInt(removeFormat(aprValue))
            + parseInt(removeFormat(mayValue)) + parseInt(removeFormat(junValue)) + parseInt(removeFormat(julValue)) + parseInt(removeFormat(augValue)) + parseInt(removeFormat(sepValue))
            + parseInt(removeFormat(octValue)) + parseInt(removeFormat(novValue)) + parseInt(removeFormat(decValue));
    addCommaObj(totalObj);

    totalEngagementColumn();
}

function totalEngagementColumn(){
    var objTaskTBL = document.getElementById("budget_engagement_body");
    var cnt = objTaskTBL.rows.length;
    var totalJan = 0, totalFeb = 0, totalMar = 0, totalApr = 0, totalMay = 0,
            totalJun = 0, totalJul = 0, totalAug = 0, totalSep = 0, totalOct = 0, totalNov = 0, totalDec = 0, totalGrand = 0;
        
    for (var count = 1; count <= cnt; count++) {
        var janValue = document.getElementById("jan" + count);
        var febValue = document.getElementById("feb" + count);
        var marValue = document.getElementById("mar" + count);
        var aprValue = document.getElementById("apr" + count);
        var mayValue = document.getElementById("may" + count);
        var junValue = document.getElementById("jun" + count);
        var julValue = document.getElementById("jul" + count);
        var augValue = document.getElementById("aug" + count);
        var sepValue = document.getElementById("sep" + count);
        var octValue = document.getElementById("oct" + count);
        var novValue = document.getElementById("nov" + count);
        var decValue = document.getElementById("dec" + count);
        
        totalJan += parseInt(removeComma(janValue.value));
        totalFeb += parseInt(removeComma(febValue.value));
        totalMar += parseInt(removeComma(marValue.value));
        totalApr += parseInt(removeComma(aprValue.value));
        totalMay += parseInt(removeComma(mayValue.value));
        totalJun += parseInt(removeComma(junValue.value));
        totalJul += parseInt(removeComma(julValue.value));
        totalAug += parseInt(removeComma(augValue.value));
        totalSep += parseInt(removeComma(sepValue.value));
        totalOct += parseInt(removeComma(octValue.value));
        totalNov += parseInt(removeComma(novValue.value));
        totalDec += parseInt(removeComma(decValue.value));
        
        totalGrand += parseInt(removeComma(janValue.value)) + parseInt(removeComma(febValue.value)) + parseInt(removeComma(marValue.value))
                + parseInt(removeComma(aprValue.value)) + parseInt(removeComma(mayValue.value)) + parseInt(removeComma(junValue.value))
                + parseInt(removeComma(julValue.value)) + parseInt(removeComma(augValue.value)) + parseInt(removeComma(sepValue.value))
                + parseInt(removeComma(octValue.value)) + parseInt(removeComma(novValue.value)) + parseInt(removeComma(decValue.value));
    }
    
    var totalJanObj = document.getElementById("total_jan");
    var totalFebObj = document.getElementById("total_feb");
    var totalMarObj = document.getElementById("total_mar");
    var totalAprObj = document.getElementById("total_apr");
    var totalMayObj = document.getElementById("total_may");
    var totalJunObj = document.getElementById("total_jun");
    var totalJulObj = document.getElementById("total_jul");
    var totalAugObj = document.getElementById("total_aug");
    var totalSepObj = document.getElementById("total_sep");
    var totalOctObj = document.getElementById("total_oct");
    var totalNovObj = document.getElementById("total_nov");
    var totalDecObj = document.getElementById("total_dec");
    var totalGrandObj = document.getElementById("total_grand");
    
    totalJanObj.value = totalJan;
    totalFebObj.value = totalFeb;
    totalMarObj.value = totalMar;
    totalAprObj.value = totalApr;
    totalMayObj.value = totalMay;
    totalJunObj.value = totalJun;
    totalJulObj.value = totalJul;
    totalAugObj.value = totalAug;
    totalSepObj.value = totalSep;
    totalOctObj.value = totalOct;
    totalNovObj.value = totalNov;
    totalDecObj.value = totalDec;
    totalGrandObj.value = totalGrand;
    
    addCommaObj(totalJanObj);
    addCommaObj(totalFebObj);
    addCommaObj(totalMarObj);
    addCommaObj(totalAprObj);
    addCommaObj(totalMayObj);
    addCommaObj(totalJunObj);
    addCommaObj(totalJulObj);
    addCommaObj(totalAugObj);
    addCommaObj(totalSepObj);
    addCommaObj(totalOctObj);
    addCommaObj(totalNovObj);
    addCommaObj(totalDecObj);
    addCommaObj(totalGrandObj);
}

function copyRowEngagementList(count) {
    var objId = count.id;
    var index = objId.replace("copyBtnEngagement","");
    var janValue = document.getElementById("jan" + index);
    var febValue = document.getElementById("feb" + index);
    var marValue = document.getElementById("mar" + index);
    var aprValue = document.getElementById("apr" + index);
    var mayValue = document.getElementById("may" + index);
    var junValue = document.getElementById("jun" + index);
    var julValue = document.getElementById("jul" + index);
    var augValue = document.getElementById("aug" + index);
    var sepValue = document.getElementById("sep" + index);
    var octValue = document.getElementById("oct" + index);
    var novValue = document.getElementById("nov" + index);
    var decValue = document.getElementById("dec" + index);
    var totalObj = document.getElementById("total" + index);
    
    febValue.value = janValue.value;
    marValue.value = janValue.value;
    aprValue.value = janValue.value;
    mayValue.value = janValue.value;
    junValue.value = janValue.value;
    julValue.value = janValue.value;
    augValue.value = janValue.value;
    sepValue.value = janValue.value;
    octValue.value = janValue.value;
    novValue.value = janValue.value;
    decValue.value = janValue.value;   
    
    totalObj.value = parseInt(removeFormat(janValue)) + parseInt(removeFormat(febValue)) + parseInt(removeFormat(marValue)) + parseInt(removeFormat(aprValue))
            + parseInt(removeFormat(mayValue)) + parseInt(removeFormat(junValue)) + parseInt(removeFormat(julValue)) + parseInt(removeFormat(augValue)) + parseInt(removeFormat(sepValue))
            + parseInt(removeFormat(octValue)) + parseInt(removeFormat(novValue)) + parseInt(removeFormat(decValue));
    addCommaObj(totalObj);
    
    totalEngagementColumn()
}

function setEngagementHeader() {
    var selStart = document.getElementById("start_month").value;
    var header1 = document.getElementById("header_1");
    var header2 = document.getElementById("header_2");
    var header3 = document.getElementById("header_3");
    var header4 = document.getElementById("header_4");
    var header5 = document.getElementById("header_5");
    var header6 = document.getElementById("header_6");
    var header7 = document.getElementById("header_7");
    var header8 = document.getElementById("header_8");
    var header9 = document.getElementById("header_9");
    var header10 = document.getElementById("header_10");
    var header11 = document.getElementById("header_11");
    var header12 = document.getElementById("header_12");
    
    var selYear = document.getElementById("engagement_year").value.slice(-2);
    
    var thisYear = selYear;
    var nextYear = parseInt(selYear) + 1;
    
    if(selStart == "1"){
        header1.innerHTML = "Jan-" + thisYear; header2.innerHTML = "Feb-" + thisYear; header3.innerHTML = "Mar-" + thisYear; header4.innerHTML = "Apr-" + thisYear;
        header5.innerHTML = "May-" + thisYear; header6.innerHTML = "Jun-" + thisYear; header7.innerHTML = "Jul-" + thisYear; header8.innerHTML = "Aug-" + thisYear;
        header9.innerHTML = "Sep-" + thisYear; header10.innerHTML = "Oct-" + thisYear; header11.innerHTML = "Nov-" + thisYear; header12.innerHTML = "Dec-" + thisYear;
    } else if(selStart == "2"){
        header1.innerHTML = "Feb-20"; header2.innerHTML = "Mar-" + thisYear; header3.innerHTML = "Apr-" + thisYear; header4.innerHTML = "May-" + thisYear;
        header5.innerHTML = "Jun-" + thisYear; header6.innerHTML = "Jul-" + thisYear; header7.innerHTML = "Aug-" + thisYear; header8.innerHTML = "Sep-" + thisYear;
        header9.innerHTML = "Oct-" + thisYear; header10.innerHTML = "Nov-" + thisYear; header11.innerHTML = "Dec-" + thisYear;header12.innerHTML = "Jan-" + nextYear;
    } else if(selStart == "3"){
        header1.innerHTML = "Mar-" + thisYear; header2.innerHTML = "Apr-" + thisYear; header3.innerHTML = "May-" + thisYear; header4.innerHTML = "Jun-" + thisYear;
        header5.innerHTML = "Jul-" + thisYear; header6.innerHTML = "Aug-" + thisYear; header7.innerHTML = "Sep-" + thisYear; header8.innerHTML = "Oct-" + thisYear;
        header9.innerHTML = "Nov-" + thisYear; header10.innerHTML = "Dec-" + thisYear;header11.innerHTML = "Jan-" + nextYear;header12.innerHTML = "Feb-" + nextYear;
    } else if(selStart == "4"){
        header1.innerHTML = "Apr-" + thisYear; header2.innerHTML = "May-" + thisYear; header3.innerHTML = "Jun-" + thisYear; header4.innerHTML = "Jul-" + thisYear;
        header5.innerHTML = "Aug-" + thisYear; header6.innerHTML = "Sep-" + thisYear; header7.innerHTML = "Oct-" + thisYear; header8.innerHTML = "Nov-" + thisYear;
        header9.innerHTML = "Dec-" + thisYear;header10.innerHTML = "Jan-" + nextYear;header11.innerHTML = "Feb-" + nextYear;header12.innerHTML = "Mar-" + nextYear;
    }  else if(selStart == "5"){
        header1.innerHTML = "May-" + thisYear; header2.innerHTML = "Jun-" + thisYear; header3.innerHTML = "Jul-" + thisYear; header4.innerHTML = "Aug-" + thisYear;
        header5.innerHTML = "Sep-" + thisYear; header6.innerHTML = "Oct-" + thisYear; header7.innerHTML = "Nov-" + thisYear; header8.innerHTML = "Dec-" + thisYear;
        header9.innerHTML = "Jan-" + nextYear;header10.innerHTML = "Feb-" + nextYear;header11.innerHTML = "Mar-" + nextYear;header12.innerHTML = "Apr-" + nextYear;
    } else if(selStart == "6"){
        header1.innerHTML = "Jun-" + thisYear; header2.innerHTML = "Jul-" + thisYear; header3.innerHTML = "Aug-" + thisYear; header4.innerHTML = "Sep-" + thisYear; 
        header5.innerHTML = "Oct-" + thisYear; header6.innerHTML = "Nov-" + thisYear; header7.innerHTML = "Dec-" + thisYear; header8.innerHTML = "Jan-" + nextYear;
        header9.innerHTML = "Feb-" + nextYear;header10.innerHTML = "Mar-" + nextYear;header11.innerHTML = "Apr-" + nextYear;header12.innerHTML = "May-" + nextYear;
    } else if(selStart == "7"){
        header1.innerHTML = "Jul-" + thisYear; header2.innerHTML = "Aug-" + thisYear; header3.innerHTML = "Sep-" + thisYear; header4.innerHTML = "Oct-" + thisYear;
        header5.innerHTML = "Nov-" + thisYear; header6.innerHTML = "Dec-" + thisYear; header7.innerHTML = "Jan-" + nextYear; header8.innerHTML = "Feb-" + nextYear;
        header9.innerHTML = "Mar-" + nextYear;header10.innerHTML = "Apr-" + nextYear;header11.innerHTML = "May-" + nextYear;header12.innerHTML = "Jun-" + nextYear;
    } else if(selStart == "8"){
        header1.innerHTML = "Aug-" + thisYear; header2.innerHTML = "Sep-" + thisYear; header3.innerHTML = "Oct-" + thisYear; header4.innerHTML = "Nov-" + thisYear;
        header5.innerHTML = "Dec-" + thisYear; header6.innerHTML = "Jan-" + nextYear; header7.innerHTML = "Feb-" + nextYear; header8.innerHTML = "Mar-" + nextYear;
        header9.innerHTML = "Apr-" + nextYear;header10.innerHTML = "May-" + nextYear;header11.innerHTML = "Jun-" + nextYear;header12.innerHTML = "Jul-" + nextYear;
    } else if(selStart == "9"){
        header1.innerHTML = "Sep-" + thisYear; header2.innerHTML = "Oct-" + thisYear; header3.innerHTML = "Nov-" + thisYear; header4.innerHTML = "Dec-" + thisYear;
        header5.innerHTML = "Jan-" + nextYear; header6.innerHTML = "Feb-" + nextYear; header7.innerHTML = "Mar-" + nextYear; header8.innerHTML = "Apr-" + nextYear;
        header9.innerHTML = "May-" + nextYear;header10.innerHTML = "Jun-" + nextYear;header11.innerHTML = "Jul-" + nextYear;header12.innerHTML = "Aug-" + nextYear;
    } else if(selStart == "10"){
        header1.innerHTML = "Oct-" + thisYear; header2.innerHTML = "Nov-" + thisYear; header3.innerHTML = "Dec-" + thisYear; header4.innerHTML = "Jan-" + nextYear;
        header5.innerHTML = "Feb-" + nextYear; header6.innerHTML = "Mar-" + nextYear; header7.innerHTML = "Apr-" + nextYear; header8.innerHTML = "May-" + nextYear;
        header9.innerHTML = "Jun-" + nextYear;header10.innerHTML = "Jul-" + nextYear;header11.innerHTML = "Aug-" + nextYear;header12.innerHTML = "Sep-" + nextYear;
    } else if(selStart == "11"){
        header1.innerHTML = "Nov-" + thisYear; header2.innerHTML = "Dec-" + thisYear; header3.innerHTML = "Jan-" + nextYear; header4.innerHTML = "Feb-" + nextYear;
        header5.innerHTML = "Mar-" + nextYear; header6.innerHTML = "Apr-" + nextYear; header7.innerHTML = "May-" + nextYear; header8.innerHTML = "Jun-" + nextYear;
        header9.innerHTML = "Jul-" + nextYear;header10.innerHTML = "Aug-" + nextYear;header11.innerHTML = "Aug-" + nextYear;header12.innerHTML = "Oct-" + nextYear;
    } else if(selStart == "12"){
        header1.innerHTML = "Dec-" + thisYear; header2.innerHTML = "Jan-" + nextYear; header3.innerHTML = "Feb-" + nextYear; header4.innerHTML = "Mar-" + nextYear;
        header5.innerHTML = "Apr-" + nextYear; header6.innerHTML = "May-" + nextYear; header7.innerHTML = "Jun-" + nextYear; header8.innerHTML = "Jul-" + nextYear;
        header9.innerHTML = "Aug-" + nextYear;header10.innerHTML = "Aug-" + nextYear;header11.innerHTML = "Oct-" + nextYear;header12.innerHTML = "Nov-" + nextYear;
    }            
}

function selectIsArchive(){
    var isArchive = $("#is_archive").val();
    $("#archive_date").prop('disabled', true);
    $("#sync_archive_status").prop('disabled', true);
    if(isArchive == "1"){
        $("#archive_date").prop('disabled', false);
        $("#sync_archive_status").prop('disabled', false);
    }
}

function syncArchiveStatus(){
    var projectId = document.getElementById("rec_project_id").value;    
    var harvestProjectId = document.getElementById("harvest_project_id").value;
    if(harvestProjectId == ""){
        harvestProjectId = "blank"
    }

    //error check
    var archiveDate = $("#archive_date").val();
    if(archiveDate == ""){
        showErrorToast("archive date is required");
        return;
    }

    var isArchive = $("#is_archive").val();
    if(isArchive == "1"){
        //harvestのprojectをArchivedにする
        $.ajax({
            url: "/master/project-list/sync-archive/" + projectId + "/" + harvestProjectId,
            dataType: "json",
            success: data => {                
                saveForm();
            },        
        });    
    }
}


function removeFormat(obj) {
    var retVal = 0;
    
    if(obj.value != ""){
        retVal = removeComma(obj.value);
    }
    
    return retVal;
}

function removeComma(obj){
    obj = obj.replace(/,/g,"");
    return obj;
}

function removeCommaObj(obj){
    obj.value = removeComma(obj.value);    
}

function addCommaObj(obj){
    obj.value = Number(obj.value).toLocaleString();  
}
