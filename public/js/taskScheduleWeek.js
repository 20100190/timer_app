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

    $('#project').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableFiltering: true,
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

    $('#project').multiselect('deselectAll', false);
    $('#project').multiselect('updateButtonText');

    $('#pic').multiselect('deselectAll', false);
    $('#pic').multiselect('updateButtonText');

    $('#sel_staff').multiselect('deselectAll', false);
    $('#sel_staff').multiselect('updateButtonText');

    document.getElementById("filter_date").value = "";
    document.getElementById("filter_to").value = "";
    
    document.getElementById("status").options[0].selected = true;
}

function loadNextWeek(){
    //next week set
    var dateFromObj = document.getElementById("filter_date").value;
    var dateFrom = new Date(dateFromObj);//.toLocaleString("en-US");
    dateFrom.setDate(dateFrom.getDate() + 7).toLocaleString("en-US");
    
    document.getElementById("filter_date").value = (dateFrom.getMonth() + 1).toString().padStart(2,"0") + "/" + dateFrom.getDate().toString().padStart(2,"0") + "/" + dateFrom.getFullYear();
    
    loadTaskScheduleData();
}


function loadPreviousWeek(){
    //previous week set
    var dateFromObj = document.getElementById("filter_date").value;
    var dateFrom = new Date(dateFromObj);//.toLocaleString("en-US");
    dateFrom.setDate(dateFrom.getDate() - 7).toLocaleString("en-US");
    
    document.getElementById("filter_date").value = (dateFrom.getMonth() + 1).toString().padStart(2,"0") + "/" + dateFrom.getDate().toString().padStart(2,"0") + "/" + dateFrom.getFullYear();
    
    loadTaskScheduleData();
}

function weekNameEng($weekNo){
    $weekName = "";
    if($weekNo == "0"){
        $weekName = "Sun";
    }else if($weekNo == "1"){
        $weekName = "Mon";
    }else if($weekNo == "2"){
        $weekName = "Tue";
    }else if($weekNo == "3"){
        $weekName = "Wed";
    }else if($weekNo == "4"){
        $weekName = "Thu";
    }else if($weekNo == "5"){
        $weekName = "Fri";
    }else if($weekNo == "6"){
        $weekName = "Sat";
    }

    return $weekName;
}


function loadTaskScheduleData() {

    var client = setDelimiter($("#client").val());
    var project = setDelimiter($("#project").val());
    var pic = setDelimiter($("#pic").val());
    var staff = setDelimiter($("#sel_staff").val());
    var dateFrom = "blank";
    var dateTo = "blank";
    var status = "blank";
    var compStatus = "blank";
    if(document.getElementById("comp_status").value != ""){
        compStatus = document.getElementById("comp_status").value;
    }
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
        url: "/test3/getTaskScheduleWeekData/" + client + "/" + pic + "/" + staff + "/" + dateFrom + "/" + project + "/" + status + "/" + compStatus + "/",
        beforeSend: function () {
            //処理中           
            jQuery('#loader-bg').show();
        },
    }).success(function (data) {
        clearAllList();

        //headerセット
        document.getElementById("head_sun").innerHTML = data.headerWeek["1"];
        document.getElementById("head_mon").innerHTML = data.headerWeek["2"];
        document.getElementById("head_tue").innerHTML = data.headerWeek["3"];
        document.getElementById("head_wed").innerHTML = data.headerWeek["4"];
        document.getElementById("head_thu").innerHTML = data.headerWeek["5"];
        document.getElementById("head_fri").innerHTML = data.headerWeek["6"];
        document.getElementById("head_sat").innerHTML = data.headerWeek["7"];

        document.getElementById("head_sun_week").innerHTML = weekNameEng(data.headerWeek["a"]);
        document.getElementById("head_mon_week").innerHTML = weekNameEng(data.headerWeek["b"]);
        document.getElementById("head_tue_week").innerHTML = weekNameEng(data.headerWeek["c"]);
        document.getElementById("head_wed_week").innerHTML = weekNameEng(data.headerWeek["d"]);
        document.getElementById("head_thu_week").innerHTML = weekNameEng(data.headerWeek["e"]);
        document.getElementById("head_fri_week").innerHTML = weekNameEng(data.headerWeek["f"]);
        document.getElementById("head_sat_week").innerHTML = weekNameEng(data.headerWeek["g"]);

        //sort
        data.taskSchedule.sort(function(a, b){
            //due_date昇順
            if (a.due_date > b.due_date) return 1;
            if (a.due_date < b.due_date) return -1;
            //task昇順
            if (a.task > b.task) return 1;
            if (a.task < b.task) return -1;
        });


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
            var colNo = data.taskSchedule[cnt].col_no;
            var task = data.taskSchedule[cnt].task;
            insertPhase1Row(cnt,dueDate,name,description,projectName,client,phase,user,status,clientId,projectId,memo,colNo,task);
        }
        
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

function insertPhase1Row(cnt,dueDate,name,description,projectName,client,phase,user,status,clientId,projectId,memo,colNo,task) {
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
    var c13 = row.insertCell(12);

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
    c12.style.cssText = "vertical-align: middle";
    c13.style.cssText = "white-space:pre-wrap; word-wrap:break-word;";
   
    var linkStr = '<a href="master/work-list/' + clientId + "/" + projectName + "/" + m + '" target="_blank"><img src="' + imagesUrl + "/view.png" + '"></a>';
    if(phase == "To Do List"){
        linkStr = '<a href="master/to-do-list/' + description +  '/edit-todo' + '" target="_blank"><img src="' + imagesUrl + "/view.png" + '"></a>';
    }
   
    // 各列に表示内容を設定
    c1.innerHTML = linkStr;//'<a href="master/work-list/' + clientId + "/" + projectName + "/" + m + '" target="_blank"><img src="' + imagesUrl + "/view.png" + '"></a>';
    c2.innerHTML = '<span>' + client + '</span>';
    c3.innerHTML = '<span>' + projectName + '</span>';
    c4.innerHTML = '<span>' + phase + '</span>';
    c5.innerHTML = '<span>' + user + '</span>';    
    

    //sun to sat
    if(colNo == 1){
       c6.innerHTML = '<span>' + task + '</span>';
    }

    if(colNo == 2){
        c7.innerHTML = '<span>' + task + '</span>';    
    }
    
    if(colNo == 3){
        c8.innerHTML = '<span>' + task + '</span>';
    }

    if(colNo == 4){
        c9.innerHTML = '<span>' + task + '</span>';
    }

    if(colNo == 5){
        c10.innerHTML = '<span>' + task + '</span>';
    }

    if(colNo == 6){
        c11.innerHTML = '<span>' + task + '</span>';
    }

    if(colNo == 7){
        c12.innerHTML = '<span>' + task + '</span>';
    }
    
    
    
    //c11.innerHTML = '<span>' + "" + '</span>';

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


