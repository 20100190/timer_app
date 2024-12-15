
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

function saveDetail() {

    var clientObj = $("#client").val();
    var projectObj = $("#project").val();
    var preparerObj = $("#preparer").val();
    
    //var params = $("form").serialize();
   
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/master/to-do-list-entry/test3",
        type: "POST",
        //data: params,
        data: {
            "client" : clientObj,
            "project" : projectObj,
            "preparer" : setDelimiter(preparerObj),
        },
        timeout: 10000,
        beforeSend: function (xhr, settings) {
            $("#savingSpinner").css("visibility", "visible");
            $("#taskEnter").find(':input').attr('disabled', true);
            $("#btn_save").attr('disabled', true);

        },
        complete: function (xhr, textStatus) {
            $("#savingSpinner").css("visibility", "hidden");
            $("#taskEnter").find(':input').attr('disabled', false);
            $("#taskEnter").find(':input').removeAttr('disabled');
            $("#btn_save").attr('disabled', false);
            $("#btn_save").removeAttr('disabled');

            showToast();
        },
        success: function (result, textStatus, xhr) {

        },
        error: function (data) {
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

function getErrorText() {
    var errorText = "";
    var isClientError = false;
    var isProjectNameError = false;
    var isTaskError = false;
    var isRequestorError = false;

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
                option.setAttribute('value', data.projectData[i].task_id);
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
    const start_time = document.getElementById("start_time").value.substr(0, 5);
    const duration = Number(document.getElementById("duration").value)*60;
    var date = new Date(start_date[2] + "/" + start_date[0] + "/" + start_date[1] + " " + start_time);

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

const accessToken = "eyJ0eXAiOiJKV1QiLCJub25jZSI6IkloQnVpMnFSY3Q1cDY1WUxMa240b09ZSjlQSGpYTDNrRmxWTkdMMHVxeGMiLCJhbGciOiJSUzI1NiIsIng1dCI6IjJaUXBKM1VwYmpBWVhZR2FYRUpsOGxWMFRPSSIsImtpZCI6IjJaUXBKM1VwYmpBWVhZR2FYRUpsOGxWMFRPSSJ9.eyJhdWQiOiJodHRwczovL2dyYXBoLm1pY3Jvc29mdC5jb20iLCJpc3MiOiJodHRwczovL3N0cy53aW5kb3dzLm5ldC9iZGJiNWNkNS1kYzVhLTRjMTktODIwNi0wZGZhZTI4ZWNhOGUvIiwiaWF0IjoxNjYzOTE1NTQwLCJuYmYiOjE2NjM5MTU1NDAsImV4cCI6MTY2MzkxOTY2MiwiYWNjdCI6MCwiYWNyIjoiMSIsImFpbyI6IkFWUUFxLzhUQUFBQWllVmRzVVdKQW9xQk8ramFZNkdwSVYzeXRoSWZWRXQ5cTZWd2lZK09VRGkvSXhDWTF6RWUwbWVnb2VmSVVPTXp6aUt2TmdGZXplaWVvU0hFWUhMdm5GZFJ4WFFoaVdnamdVQldZOTR4N3JnPSIsImFtciI6WyJwd2QiLCJtZmEiXSwiYXBwX2Rpc3BsYXluYW1lIjoidGVzdGFwcCIsImFwcGlkIjoiMGMzMzEzNjgtY2MyYy00YzgwLWI3M2UtOTdmMjZmM2FkYWQ3IiwiYXBwaWRhY3IiOiIxIiwiZmFtaWx5X25hbWUiOiJZb3NoaW1hdHN1IiwiZ2l2ZW5fbmFtZSI6IlRha2FoaXJvIiwiaWR0eXAiOiJ1c2VyIiwiaXBhZGRyIjoiMTE5LjE3MC4xMjUuMjAwIiwibmFtZSI6IlRha2FoaXJvIFlvc2hpbWF0c3UiLCJvaWQiOiI3ZDdhODMzYy0wMDZhLTQ4OTktODZkOS05ZGJiNjlhYTQ4MDQiLCJwbGF0ZiI6IjMiLCJwdWlkIjoiMTAwMzIwMDBEN0NCQTIyOCIsInJoIjoiMC5BVmtBMVZ5N3ZWcmNHVXlDQmczNjRvN0tqZ01BQUFBQUFBQUF3QUFBQUFBQUFBQlpBTmsuIiwic2NwIjoiQ2FsZW5kYXJzLlJlYWQgQ2FsZW5kYXJzLlJlYWRXcml0ZSBVc2VyLlJlYWQgVXNlci5SZWFkV3JpdGUuQWxsIHByb2ZpbGUgb3BlbmlkIGVtYWlsIiwic2lnbmluX3N0YXRlIjpbImttc2kiXSwic3ViIjoiaEtHdUtUN2ZIcVBZcU00TkNDTlV1RFhjOXNEeGdMV25lQ3I3V0dRYnlsMCIsInRlbmFudF9yZWdpb25fc2NvcGUiOiJOQSIsInRpZCI6ImJkYmI1Y2Q1LWRjNWEtNGMxOS04MjA2LTBkZmFlMjhlY2E4ZSIsInVuaXF1ZV9uYW1lIjoidGFrYWhpcm95QHRvcGMudXMiLCJ1cG4iOiJ0YWthaGlyb3lAdG9wYy51cyIsInV0aSI6IlJHRjNIOE5SbVVhdlpDeERLaXdSQUEiLCJ2ZXIiOiIxLjAiLCJ3aWRzIjpbImYyZWY5OTJjLTNhZmItNDZiOS1iN2NmLWExMjZlZTc0YzQ1MSIsImYyOGExZjUwLWY2ZTctNDU3MS04MThiLTZhMTJmMmFmNmI2YyIsImZlOTMwYmU3LTVlNjItNDdkYi05MWFmLTk4YzNhNDlhMzhiMSIsIjY5MDkxMjQ2LTIwZTgtNGE1Ni1hYTRkLTA2NjA3NWIyYTdhOCIsIjYyZTkwMzk0LTY5ZjUtNDIzNy05MTkwLTAxMjE3NzE0NWUxMCIsIjI5MjMyY2RmLTkzMjMtNDJmZC1hZGUyLTFkMDk3YWYzZTRkZSIsImI3OWZiZjRkLTNlZjktNDY4OS04MTQzLTc2YjE5NGU4NTUwOSJdLCJ4bXNfc3QiOnsic3ViIjoicUx3V0h2NHo1bWpSY1pPcl9oRnJTMS1sQzBiUC1BYm9LUFB0RzNoTVlnQSJ9LCJ4bXNfdGNkdCI6MTU5NTU0MjYwMX0.o0IareD5g2i6QcywWp1VK_M-HppQk_QZHUd_iL6PAXU3v2vxnoPP2lfEzIKK_m3PPLcu8oSeW9P6XSLw-lKCDzCdYWgArKlwURTcxHgd6OfIvkdSJxXGnOeekjDcTHlaIZonPOMiMzl_L1gyqK_EmeSkn3CjfKZawZOtFVcl8O3WM59s_yUhyMDZkYIuVivpRX_VyZbchphOV_EIj4TXABoJs0KqR2DLrdeLYpkp389Fnr17WOqivRE_-Ry-K-UnWCqg0VkYybQEAv751FR6HiDZj2unLmiUHA0DeGGx1gkZsez7WXKVcHKLpxaEuDl5sCG_NbENtVCAD0UKtqJe4g";

function updateCalendarDetail(calendarId){
    const token = accessToken;
    const client = document.getElementById("client").value;
    const project = document.getElementById("project").value;
    const task = document.getElementById("task").value;
    const requestor = document.getElementById("requestor").value;
    const preparer = document.getElementById("preparer").value;
    const optional = document.getElementById("optional").value;
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
    //var url = "https://graph.microsoft.com/v1.0/me/calendar/events";
    var url = "https://graph.microsoft.com/v1.0/users/7d7a833c-006a-4899-86d9-9dbb69aa4804/events/" + calendarId;
    /*var data = {
        "subject": task,
        "body": {
            "contentType": "HTML",
        },
        "start": {
            "dateTime": start,
            "timeZone": "Pacific Standard Time"
        },
        "end": {
            "dateTime": end,
            "timeZone": "Pacific Standard Time"
        },
    }*/
    var data = {        
        "subject": task,
        "body": {
            "contentType": "HTML",
        },
        "start": {
            "dateTime": start,
            "timeZone": "Pacific Standard Time"
        },
        "end": {
            "dateTime": end,
            "timeZone": "Pacific Standard Time"
        },
    }

    fetch(url, {
        "method": "PATCH",
        "body": JSON.stringify(data),
        "headers": {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
        }
    })
}

async function sendCalendar() {
    var aaa = await sendCalendarDetail();
    document.getElementById("calendar_id").value = aaa.id;    
}

async function updateCalendar(){
    var calendarId = document.getElementById("calendar_id").value;
    if(calendarId != ""){
        updateCalendarDetail(calendarId);
    }
}

async function sendCalendarDetail() {
    const token = accessToken;
    const client = document.getElementById("client").value;
    const project = document.getElementById("project").value;
    const task = document.getElementById("task").value;
    const requestor = document.getElementById("requestor").value;
    const preparer = document.getElementById("preparer").value;
    const optional = document.getElementById("optional").value;
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
    //var url = "https://graph.microsoft.com/v1.0/me/calendar/events";
    var url = "https://graph.microsoft.com/v1.0/users/7d7a833c-006a-4899-86d9-9dbb69aa4804/events/";
    var data = {
        "subject": task,
        "body": {
            "contentType": "HTML",
        },
        "start": {
            "dateTime": start,
            "timeZone": "Pacific Standard Time"
        },
        "end": {
            "dateTime": end,
            "timeZone": "Pacific Standard Time"
        },
    }
    
    var abr = await fetch(url, {
        "method": "POST",
        "body": JSON.stringify(data),
        "headers": {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
        }
    });

    return await abr.json();
   
}