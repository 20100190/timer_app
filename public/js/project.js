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
    var taskNameObj = '<input class="inpname form-control form-control-sm" type="text" id="task_name' + count + '" name="task_name' + count + '" value="' + name + '" style="width: 100%" readonly>';
    var taskInfo = JSON.parse(document.getElementById("task_info").value);
    if (isNew) {
        taskNameObj = '<input type="text" name="task_name' + count + '" id="task_name' + count + '" class="inpname form-control form-control-sm" list="samplelist">';
        taskNameObj += '<datalist id="samplelist">';
        for (tCnt = 0; tCnt < taskInfo.length; tCnt++) {
            taskNameObj += '<option value="' + taskInfo[tCnt].name + '"></option>';
        }
        taskNameObj += '</datalist>';
    }

    // 各列にスタイルを設定
    c1.style.cssText = "text-align:center;vertical-align: middle ";
    c5.style.cssText = "visibility: collapse";
    c6.style.cssText = "visibility: collapse";
    
    // 各列に表示内容を設定
    c1.innerHTML = '<span class="seqno-task">' + count + '</span>';
    //c2.innerHTML = '<input class="inpname form-control form-control-sm" type="text"   id="task_name' + count + '" name="task_name' + count + '" value="' + name + '" style="width: 100%">';
    c2.innerHTML = taskNameObj;
    c3.innerHTML = '<img src="'+ imagesUrl + "/arrow.png" +'" style="width: 20px;height: 20px;margin-top: 8px">';
    c4.innerHTML = '<input class="delbtn btn btn-sm" type="image" src="'+ imagesUrl + "/delete.png" +'" id="delBtnTask' + count + '" value="Delete" onclick="deleteRow(this)">';
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
    c5.innerHTML = '<input class="inprate form-control form-control-sm" type="text" onchange="calc()" id="rate' + count + '" name="rate' + count + '" value="0" style="text-align: right;width: 100%" readonly>';
    c6.innerHTML = '<input class="inpbudget form-control form-control-sm" type="text" id="budget' + count + '" name="budget' + count + '" value="0" style="text-align: right;width: 100%"  readonly>';
    //c7.innerHTML = '<input class="edtBudgetBtn btn btn-success btn-sm" type="button" id="edtBtn' + count + '" value="確定" onclick="editRowBudgetList(this)">';
    c7.innerHTML = '<input class="delBudgetBtn btn btn-sm" type="image" src="'+ imagesUrl + "/delete.png" +'" id="delBtnBudget' + count + '" value="Delete" onclick="delRowBudgetList(this)">';

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
    c5.innerHTML = '<input class="inprate form-control form-control-sm" type="text" onchange="calc()" id="rate' + count + '" name="rate' + count + '" value="' + staffRate + '" style="text-align: right;width: 100%" readonly>';
    c6.innerHTML = '<input class="inpbudget form-control form-control-sm" type="text" id="budget' + count + '" name="budget' + count + '" value="0" style="text-align: right;width: 100%"  readonly>';
    //c7.innerHTML = '<input class="edtBudgetBtn btn btn-success btn-sm" type="button" id="edtBtn' + count + '" value="確定" onclick="editRowBudgetList(this)">';
    c8.innerHTML = '<input class="delBudgetBtn btn btn-sm" type="image" src="'+ imagesUrl + "/delete.png" +'" id="delBtnBudget' + count + '" value="Delete" onclick="delRowBudgetList(this)">';

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
    for (var cnt = 1; cnt <= count; cnt++) {
        document.getElementById("budget" + cnt).value = parseInt(document.getElementById("hours" + cnt).value) * parseInt(document.getElementById("rate" + cnt).value)
        total += parseInt(document.getElementById("budget" + cnt).value);

    }

    document.getElementById("total_budget").innerHTML = total;
    
    var engTotal = (parseInt(document.getElementById("engagement_fee").value) * parseInt(document.getElementById("engagement_monthly").value)) + parseInt(document.getElementById("adjustments").value);
    document.getElementById("engagement_total").innerHTML = 0;
    if (!isNaN(engTotal)) {
        document.getElementById("engagement_total").innerHTML = engTotal;//(parseInt(document.getElementById("engagement_fee").value) * parseInt(document.getElementById("engagement_monthly").value)) + parseInt(document.getElementById("adjustments").value);
    }
    
    var defTotal = parseInt(document.getElementById("engagement_total").innerHTML) - total;
    document.getElementById("defference").innerHTML = 0;
    if (!isNaN(defTotal)) {
        document.getElementById("defference").innerHTML = defTotal;//parseInt(document.getElementById("engagement_total").innerHTML) - total;
    }
    
    var realization = (new Decimal(parseInt(document.getElementById("engagement_total").innerHTML)).div(total).times(100).toFixed(1));
    document.getElementById("realization").innerHTML = "0%";
    if (!isNaN(realization)) {
        document.getElementById("realization").innerHTML = realization + "%";
    }
}

function loadTask() {

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

        //project
        //初期化
        document.getElementById("starts_on").value = "";
        document.getElementById("ends_on").value = "";
        document.getElementById("engagement_fee").value = 0;
        document.getElementById("engagement_monthly").value = 0;
        document.getElementById("adjustments").value = 0;
        document.getElementById("billable").selectedIndex = 0;
        document.getElementById("note").value = "";
        document.getElementById("pic").selectedIndex = 0;
        document.getElementById("fye").selectedIndex = 0;

        if (data.project !== null) {
            if (data.project.start != "") {
                var startArray = data.project.start.split("-");
                document.getElementById("starts_on").value = startArray[1] + "/" + startArray[2] + "/" + startArray[0];
            }
            if (data.project.end != "") {
                var endArray = data.project.end.split("-");
                document.getElementById("ends_on").value = endArray[1] + "/" + endArray[2] + "/" + endArray[0];
            }
            document.getElementById("engagement_fee").value = data.project.engagement_fee_unit;
            document.getElementById("engagement_monthly").value = data.project.invoice_per_year;
            document.getElementById("adjustments").value = data.project.adjustments;
            //document.getElementById("billable").selectedIndex = data.project.billable;
            $("#billable").val(data.project.billable);  
            document.getElementById("note").value = data.project.note;
            //document.getElementById("pic").selectedIndex = data.project.pic - 1;
            $("#pic").val(data.project.pic);            
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

        //計算実行
        calc();


    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
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
    
    var staffArray = [];
    var errorText = "";
    var isStaffError = false;
    var isHoursError = false;
    var isTaskError = false;
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
    
    if(isTaskError){
        errorText += "Task field is required.<br>";
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
    while(str.match(/[^0-9.]/))
    {
        str=str.replace(/[^0-9.]/,"");
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


