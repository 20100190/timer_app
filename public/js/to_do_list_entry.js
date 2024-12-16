
function saveForm() {
    //エラーチェック
    var errorText = getErrorText();
    if (errorText != "") {
        showErrorToast(errorText);
        return;
    }

    $("#taskEnter").find('input, textarea, select, button').prop('disabled', false);

    saveDetail();
}

function saveDetail() {

    var clientObj = $("#client").val();
    var projectObj = $("#project").val();
    var taskObj = $("#task").val();
    var requestorObj = $("#requestor").val();
    var preparerObj = $("#preparer").val();
    var optionalObj = $("#optional").val();
    var start_dateObj = $("#start_date").val();
    var start_timeObj = $("#start_time").val();
    var durationObj = $("#duration").val();
    var end_timeObj = $("#end_time").val();
    var progressObj = $("#progress").val();
    var locationObj = $("#location").val();
    var memoObj = $("#memo").val();
    var calcPacificTimeObj = $("#calc_pacific_time").val();
    var toDoListId = $("#to_do_list_id").val();
    var addBudgetHours = $("#add_budget_hours").val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/master/to-do-list-entry/test3",
        type: "POST",
        data: {
            "client" : clientObj,
            "project" : projectObj,
            "task" : taskObj,
            "requestor" : requestorObj,
            "preparer" : setDelimiter(preparerObj),
            "optional" : setDelimiter(optionalObj),
            "start_date" : start_dateObj,
            "start_time" : start_timeObj,
            "duration" : durationObj,
            "end_time" : end_timeObj,
            "progress" : progressObj,
            "location" : locationObj,
            "calc_pacific_time" : calcPacificTimeObj,
            "memo" : memoObj,
            "to_do_list_id" : toDoListId,
            "add_budget_hours" : addBudgetHours,
        },
        timeout: 10000,
        beforeSend: function (xhr, settings) {
            $("#savingSpinner").css("visibility", "visible");
            $("#taskEnter").find(':input').attr('disabled', true);
            $("#btn_save").attr('disabled', true);
            jQuery('#loader-bg').show();

        },
        complete: function (xhr, textStatus) {
            $("#savingSpinner").css("visibility", "hidden");
            $("#taskEnter").find(':input').attr('disabled', false);
            $("#taskEnter").find(':input').removeAttr('disabled');
            $("#btn_save").attr('disabled', false);
            $("#btn_save").removeAttr('disabled');

            showToast();

            //登録後、再度saveするとupdateにするため
            //set evend id
            if(textStatus != "error"){
                document.getElementById("to_do_list_id").value = xhr.responseText;
                //clientとprojectをreadonlyに
                var address = location.href;
                if(address.indexOf("edit-todo") === -1){
                    $("#client").multiselect('disable');
                    $("#project").multiselect('disable'); 
                }

                location.href = location.protocol + "//" + location.hostname + "/master/to-do-list/" + xhr.responseText + "/edit-todo";
    
            }else{
                showErrorToast("Failed to add to calendar.");
            }
                        
            jQuery("#loader-bg").hide();

            /*setTimeout(function(){                
                window.close();
            },1500);*/
            
        },
        success: function (result, textStatus, xhr) {
            //jQuery("#loader-bg").hide();
        },
        error: function (data) {
            console.debug(data);
        }
    });
}
function setDelimiter(obj) {
    var str = "";
    if (obj == null) {
        str = null;
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

function getErrorText() {
    var errorText = "";
    var isClientError = false;
    var isProjectNameError = false;
    var isTaskError = false;
    var isRequestorError = false;
    var isDateError = false;
    var isPreparerError = false;
    
    //Check
    var client = document.getElementById("client").value;
    if (client == "") {
        isClientError = true;
    }
    var project = document.getElementById("project").value;
    if (project == "") {
        isProjectNameError = true;
    }
    var task = document.getElementById("task").value;
    if (task == "") {
        isTaskError = true;
    }
    var requestor = document.getElementById("requestor").value;
    if (requestor == "28") {
        isRequestorError = true;
    }
    var date = document.getElementById("start_date").value;
    if (date == "") {
        isDateError = true;
    }

    var preparerObj = document.getElementById("preparer").value;
    if (preparerObj == "") {
        isPreparerError = true;
    }   
   
    //Generate error message
    if (isClientError) {
        errorText += "Client is required.<br>";
    }
    if (isProjectNameError) {
        errorText += "Project is required.<br>";
    }
    if (isTaskError) {
        errorText += "Task is required.<br>";
    }
    if (isRequestorError) {
        errorText += "Requestor is required.<br>"
    }
    if (isDateError) {
        errorText += "Start date is required.<br>"
    }
    if (isPreparerError) {
        errorText += "Asignee is required.<br>"
    }
      
    return errorText;

}

function setProjectIDData(isMulti) {
    
    var client = $('#client').val();
    if (client == "") {
        client = "blank";
    }

    $.ajax({
        url: "/project/data/" + client + "/",
    }).done(function (data) {
        $('#project').children().remove();
        var project = document.getElementById('project');
        if (!isMulti) {
            document.createElement('option')
            var option = document.createElement('option');
            option.setAttribute('value', "blank");
            option.innerHTML = "&nbsp;";
            project.appendChild(option);
        }

        for (var i = 0; i < data.projectData.length; i++) {
            if (data.projectData[i].project_name != null) {
                var option = document.createElement('option');
                option.setAttribute('value', data.projectData[i].id);
                option.innerHTML = data.projectData[i].project_name;
                project.appendChild(option);
            }
        };

        $("#project").multiselect('rebuild');
    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error');
        console.log("XMLHttpRequest:" + XMLHttpRequest.status);
        console.log("textStatus  :" + textStatus);
        console.log("errorThrown  :" + errorThrown.message);
    })
}


function setTaskIDData(isMulti) {

    var client = $('#project').val();
    if (client == "") {
        client = "blank";
    }
    $.ajax({
        url: "/project/data/task/" + client + "/",
    }).done(function (data) {
        $('#task').children().remove();
        var project = document.getElementById('task');
        if (!isMulti) {
            document.createElement('option')
            var option = document.createElement('option');
            option.setAttribute('value', "blank");
            option.innerHTML = "&nbsp;";
            project.appendChild(option);
        }
        for (var i = 0; i < data.projectData.length; i++) {
            if (data.projectData[i].name != null) {
                var option = document.createElement('option');
                option.setAttribute('value', data.projectData[i].id);
                option.innerHTML = data.projectData[i].name;
                project.appendChild(option);
            }
        };
        $("#task").multiselect('rebuild');
    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error');
        console.log("XMLHttpRequest:" + XMLHttpRequest.status);
        console.log("textStatus  :" + textStatus);
        console.log("errorThrown  :" + errorThrown.message);
    })
}


function setEndTime() {
    const start_date = document.getElementById("start_date").value.split("/");
    const start_time = document.getElementById("start_time").value;
    const duration = Number(document.getElementById("duration").value)*60;

    if(start_date == ""){
        return;
    }

    var date = new Date(start_date[2] + "/" + start_date[0] + "/" + start_date[1] + " " + start_time);

    var startMinute = date.toLocaleTimeString('en-us');
    var startMinuteTime = startMinute.split(":00 ");
    var startMinutePst = startMinuteTime[0];
    if (startMinutePst.length == 4) {
        startMinutePst = "0" + startMinutePst;
    }
    //document.getElementById("calc_pacific_time").value = startMinutePst + " " + startMinuteTime[1];

    date.setMinutes(date.getMinutes() + duration);
    var us_time = date.toLocaleString('en-us');
    var edate = us_time.split(", ")
    var end_month = edate[0].split("/")[0];
    if (Number(end_month) < 10) {
        end_month = "0" + end_month;
    }
    var end_day = edate[0].split("/")[1];
    if (Number(end_day) < 10) {
        end_day = "0" + end_day;
    }
    var end_year = edate[0].split("/")[2];
    var etime = edate[1].split(":00 ");
    var end_time = etime[0];
    if (end_time.length == 4) {
        end_time = "0" + end_time;
    }
    document.getElementById("end_time").value = end_month + "/" + end_day + "/" + end_year + "  " + end_time + " " + etime[1];
}

/**
function sendCalendar() {
    const tokens = getToken();
    const access_token = tokens["access_token"];
    const refresh_token = tokens["refresh_token"];
    const client = document.getElementById("client").value;
    const project = document.getElementById("project").value;
    const task = document.getElementById("task").value;
    const requestor = document.getElementById("requestor").value;
    const preparerList = setDelimiter($("#preparer").val());
    const optionalList = setDelimiter($("#optional").val());
    const start_date = document.getElementById("start_date").value.split("/");
    const start_time = document.getElementById("start_time").value.substr(0, 5);
    var start = start_date[2] + "-" + start_date[0] + "-" + start_date[1] + "T" + start_time;
    const duration = document.getElementById("duration").value;
    var end_time = new Date(start_date[2] + "/" + start_date[0] + "/" + start_date[1] + " " + start_time + ":00");
    end_time.setMinutes(end_time.getMinutes() + Number(duration)*60);
    const location = document.getElementById("location").value;
    const progress = document.getElementById("progress").value;
    const memo = document.getElementById("memo").value;
    var y = end_time.toLocaleString('ja-JP-u-ca-japanese').substr(-8, 8);
    var yy = end_time.toLocaleString('ja-JP-u-ca-japanese').substr(-7, 7);
    var z = y.substr(0, 2);
    if (z < 10) {
        var et = '0' + yy;
    } else {
        var et = y
    }
    var end = start_date[2] + "-" + start_date[0] + "-" + start_date[1] + "T" + et;
    var datas = loadTodoListEntryData(client, project, task, requestor, preparerList, optionalList);
    var url = "https://graph.microsoft.com/v1.0/me/calendar/events";
    var title = datas.client_name + " " + datas.project_name + " " + datas.task_name;

    //preparerとoptionalの情報をセット
    
    var attendee = [];
    var preparer_names = datas.preparer_name.split(",");
    var preparer_mails = datas.preparer_email.split(",");
    var optional_names = datas.optional_name.split(",");
    var optional_mails = datas.optional_email.split(",");
    for (var i = 0; i < preparer_names.length; i++) {
        var p = {
            "emailAddress": {
                "address": preparer_mails[i],
                "name": preparer_names[i]
            },
            "type": "required"
        };
        attendee.push(p);
    }
    for (var i = 0; i < optional_names.length; i++) {
        var o = {
            "emailAddress": {
                "address": optional_mails[i],
                "name": optional_names[i]
            },
            "type": "optional"
        };
        attendee.push(o);
    }

   var attendee = [{
            "emailAddress": {
                "address": "takahiroy@topc.us",
                "name": "Takahiro Yoshimatsu"
            },
            "type": "required"
        }];

    var data = {
        "subject": title,
        "body": {
            "contentType": "HTML",
            "content": memo
        },
        "start": {
            "dateTime": start,
            "timeZone": "Pacific Standard Time"
        },
        "end": {
            "dateTime": end,
            "timeZone": "Pacific Standard Time"
        },
        "location": {
            "displayName" : location
        },
        "attendees": attendee,
    }

    //カレンダーに追加
    fetch(url, {
        "method": "POST",
        "body": JSON.stringify(data),
        "headers": {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + access_token
        }
    })
}
*/

//それぞれ、idからname, initial, emailを引っ張てくる
/**
function loadTodoListEntryData(client_id, project_id, task_id, requestor_id, preparer_idList, optional_idList) {
    dataDic = {};
    
    $.ajax({
        url: "/toDoListEntry/getTodoListEntryData/" + client_id + "/" + project_id + "/" + task_id + "/" + requestor_id + "/" + preparer_idList + "/" + optional_idList + "/",
        async: false,

    }).done(function (data) {
        var client_name = data.todoListEntryData.client;
        dataDic.client_name = client_name;
        var project_name = data.todoListEntryData.project;
        dataDic.project_name = project_name
        var task_name = data.todoListEntryData.task;
        dataDic.task_name = task_name;
        var requestor_name = data.todoListEntryData.requestor_name;
        dataDic.requestor_name = requestor_name;
        var requestor_email = data.todoListEntryData.requestor_email;
        dataDic.requestor_email = requestor_email;
        var preparer_name = data.todoListEntryData.preparer_name;
        dataDic.preparer_name = preparer_name;
        var preparer_email = data.todoListEntryData.preparer_email;
        dataDic.preparer_email = preparer_email;
        var optional_name = data.todoListEntryData.optional_name;
        dataDic.optional_name = optional_name;
        var optional_email = data.todoListEntryData.optional_email;
        dataDic.optional_email = optional_email;

    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        console.log("XMLHttpRequest :" + XMLHttpRequest.status);
        console.log("textStatus     :" + textStatus);
        console.log("errorThrown    :" + errorThrown.message);
    });
    return dataDic
}


function getToken() {
    var access_token = "";
    var refresh_token = "";
    $.ajax({
        url: "/toDoListEntry/getToken/",
        async: false
    }).done(function (ary) {
        var token_type = ary.token_type;
        access_token = ary["access_token"];
        refresh_token = ary["refresh_token"];
        
        //console.log(ary);
    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error');
        console.log("XMLHttpRequest :" + XMLHttpRequest.status);
        console.log("textStatus     :" + textStatus);
        console.log("errorThrown    :" + errorThrown.message);
    });
    return {"access_token": access_token, "refresh_token": refresh_token};
    
}
*/