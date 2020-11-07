$(document).ready(function () {
    jQuery('#loader-bg').hide();
  
    var buttonWidth = "400px";
    var buttonWidth2 = "150px";
    $('#client').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });

    $('#pic').multiselect({
        buttonWidth: buttonWidth2,
        enableFiltering: true,
        maxHeight: 600,
        includeSelectAllOption: true,
    });

    $('#sel_staff').multiselect({
        buttonWidth: buttonWidth2,
        enableFiltering: true,
        maxHeight: 400,
        includeSelectAllOption: true,
    });

    $('.datepicker1').datepicker({
        format: "mm/dd/yyyy",
        language: "en",
        autoclose: true,
        orientation: 'bottom left'
    });    
    
});

function clearInputFilter() {
    $('#client').multiselect('deselectAll', false);
    $('#client').multiselect('updateButtonText');

    $('#pic').multiselect('deselectAll', false);
    $('#pic').multiselect('updateButtonText');

    $('#sel_staff').multiselect('deselectAll', false);
    $('#sel_staff').multiselect('updateButtonText');

    document.getElementById("filter_date").value = "";
    document.getElementById("filter_to").value = "";
    
    document.getElementById("status").options[0].selected = true;
}

function loadTaskScheduleData() {

    var client = setDelimiter($("#client").val());
    var pic = setDelimiter($("#pic").val());
    var staff = setDelimiter($("#sel_staff").val());
    var dateFrom = "blank";
    var dateTo = "blank";
    var status = "blank";
    if(document.getElementById("status").value != ""){
        status = document.getElementById("status").value;
    }
    if(document.getElementById("filter_date").value != ""){
        var t = document.getElementById("filter_date").value.split("/");
        dateFrom = t[2] + t[0] + t[1];
    }
    if(document.getElementById("filter_to").value != ""){
        var t = document.getElementById("filter_to").value.split("/");
        dateTo = t[2] + t[0] + t[1];
    }
    
   
    $.ajax({
        url: "/test3/getTaskScheduleData/" + client + "/" + pic + "/" + staff + "/" + dateFrom + "/" + dateTo + "/" + status + "/",
    }).success(function (data) {
        clearAllList();
        for (var cnt = 0; cnt < data.taskSchedule.length; cnt++) {
            var dueDate = "";
            if(data.taskSchedule[cnt].due_date != null){
                dueDate = convDateFormat(data.taskSchedule[cnt].due_date);
            }
            var name = data.taskSchedule[cnt].task;
            var description = data.taskSchedule[cnt].description;
            var projectName = data.taskSchedule[cnt].project_name;
            var client = data.taskSchedule[cnt].client_name;
            var phase = data.taskSchedule[cnt].phase_name;
            var user = data.taskSchedule[cnt].user;
            var status = data.taskSchedule[cnt].status;
            var clientId = data.taskSchedule[cnt].client_id;
            var projectId = data.taskSchedule[cnt].project_id;
            insertPhase1Row(cnt,dueDate,name,description,projectName,client,phase,user,status,clientId,projectId);
        }
        
        $('#task_schedule').tablesorter({
            widgets: ['zebra'],
            widgetOptions: {
                zebra: ["normal-row", "alt-row"]
            }
        });


    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    });
}

function insertPhase1Row(cnt,dueDate,name,description,projectName,client,phase,user,status,clientId,projectId) {
    // 最終行に新しい行を追加
    var phase1_tbody = document.getElementById("task_schedule_body");
    var bodyLength = phase1_tbody.rows.length;
    var count = bodyLength + 1;
    var row = phase1_tbody.insertRow(bodyLength);


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

    c1.style.cssText = "vertical-align: middle";
    c2.style.cssText = "vertical-align: middle";
    c4.style.cssText = "vertical-align: middle";
    c5.style.cssText = "vertical-align: middle";
    c6.style.cssText = "vertical-align: middle";
    c7.style.cssText = "vertical-align: middle";
    c8.style.cssText = "vertical-align: middle";
    c9.style.cssText = "vertical-align: middle";
    c10.style.cssText = "vertical-align: middle";
   
   
    // 各列に表示内容を設定
    c1.innerHTML = '<span>' + parseInt(cnt + 1) + '</span>';
    c2.innerHTML = '<a href="master/work-list/' + clientId + "/" + projectName + '" target="_blank"><img src="' + imagesUrl + "/view.png" + '"></a>';
    c3.innerHTML = '<span>' + user + '</span>';    
    c4.innerHTML = '<span>' + dueDate + '</span>';
    c5.innerHTML = '<span>' + client + '</span>';
    c6.innerHTML = '<span>' + projectName + '</span>';
    c7.innerHTML = '<span>' + status + '</span>';
    c8.innerHTML = '<span>' + phase + '</span>';
    c9.innerHTML = '<span>' + name + '</span>';
    c10.innerHTML = '<span>' + description + '</span>';

}

function convDateFormat(value) {
    var valueArray = value.split("-");
    return valueArray[1] + "/" + valueArray[2] + "/" + valueArray[0];
}

function clearAllList() {
    var table = document.getElementById("task_schedule");    
    //Label初期化
    
    //List初期化
    while (table.rows[ 1 ])
        table.deleteRow(1);

}

function setDelimiter(obj) {
    var str = "";
    if (obj == null) {
        str = "blank";
    } else {
        for (var s = 0; s < obj.length; s++) {
            str += obj[s];
            if (s != obj.length - 1) {
                str += ",";
            }
        }
    }
    return str;
}


