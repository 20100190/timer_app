$(document).ready(function () {
    jQuery('#loader-bg').hide();
  
    var buttonWidth = "400px";
    var buttonWidth2 = "150px";
    $('#client').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });

    $('#pic').multiselect({
        buttonWidth: buttonWidth2,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        maxHeight: 600,
        includeSelectAllOption: true,
    });

    $('#fye').multiselect({
        buttonWidth: buttonWidth2,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        maxHeight: 600,
        includeSelectAllOption: true,
    });

    $('#sel_staff').multiselect({
        buttonWidth: buttonWidth2,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        maxHeight: 400,
        includeSelectAllOption: true,
    });

    $('.datepicker1').datepicker({
        format: "mm/dd/yyyy",
        language: "en",
        autoclose: true,
        orientation: 'bottom left'
    });    
    
    $('#task_schedule').tablesorter({
        widgets: ['zebra'],
        widgetOptions: {
            zebra: ["normal-row", "alt-row"]
        }
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
    var fye = setDelimiter($("#fye").val());
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
    
    var phase = "blank";
    if(document.getElementById("phase_status").value != ""){
        phase = document.getElementById("phase_status").value;
    }

    var group = "blank";
    if(document.getElementById("group").value != ""){
        group = document.getElementById("group").value;
    }
   
    $.ajax({
        url: "/test3/getTaskScheduleData/" + client + "/" + pic + "/" + staff + "/" + dateFrom + "/" + dateTo + "/" + status + "/" + phase + "/" + fye + "/" + group,
        beforeSend: function () {
            //処理中           
            jQuery('#loader-bg').show();
        },
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
            var memo = data.taskSchedule[cnt].memo;
            var fye = data.taskSchedule[cnt].fye;
            insertPhase1Row(cnt,dueDate,name,description,projectName,client,phase,user,status,clientId,projectId,memo,fye);
        }
        
        /*$('#task_schedule').tablesorter({
            widgets: ['zebra'],
            widgetOptions: {
                zebra: ["normal-row", "alt-row"]
            }
        });*/
    $("#task_schedule").trigger("update");

    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    }).done(function () {                  
        jQuery('#loader-bg').hide();
    });
}

function insertPhase1Row(cnt,dueDate,name,description,projectName,client,phase,user,status,clientId,projectId,memo,fye) {
    // 最終行に新しい行を追加
    var phase1_tbody = document.getElementById("task_schedule_body");
    var bodyLength = phase1_tbody.rows.length;
    var count = bodyLength + 1;
    var row = phase1_tbody.insertRow(bodyLength);

    //group
    var m = "";
    if(dueDate != ""){
        m = parseInt(dueDate.split("/")[0]);
    }

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

    c1.style.cssText = "vertical-align: middle";
    c2.style.cssText = "vertical-align: middle";
    c3.style.cssText = "vertical-align: middle";
    c4.style.cssText = "vertical-align: middle";
    c5.style.cssText = "vertical-align: middle";
    c6.style.cssText = "vertical-align: middle";
    c7.style.cssText = "vertical-align: middle";
    c8.style.cssText = "vertical-align: middle";
    c9.style.cssText = "vertical-align: middle";
    c10.style.cssText = "vertical-align: middle";
    c11.style.cssText = "vertical-align: middle";
    c12.style.cssText = "white-space:pre-wrap; word-wrap:break-word;";

    var linkStr = '<a href="master/work-list/' + clientId + "/" + projectName + "/" + m + '" target="_blank"><img src="' + imagesUrl + "/view.png" + '"></a>';
    if(phase == "To Do List"){
        linkStr = memo + '<img src="' + imagesUrl + "/view.png" + '"></a>';
    }
   
   
    // 各列に表示内容を設定
    c1.innerHTML = '<span>' + parseInt(cnt + 1) + '</span>';
    c2.innerHTML = linkStr;//'<a href="master/work-list/' + clientId + "/" + projectName + "/" + m + '" target="_blank"><img src="' + imagesUrl + "/view.png" + '"></a>';
    c3.innerHTML = '<span>' + user + '</span>';    
    c4.innerHTML = '<span>' + dueDate + '</span>';
    c5.innerHTML = '<span>' + fye + '</span>';
    c6.innerHTML = '<span>' + client + '</span>';
    c7.innerHTML = '<span>' + projectName + '</span>';
    c8.innerHTML = '<span>' + status + '</span>';
    c9.innerHTML = '<span>' + phase + '</span>';
    c10.innerHTML = '<span>' + name + '</span>';
    c11.innerHTML = '<span>' + description + '</span>';
    c12.innerHTML = '<span>' + memo + '</span>';

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


