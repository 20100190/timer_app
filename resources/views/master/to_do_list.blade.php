@extends('layouts.main')
@section("content")
<script type="text/javascript">
$(document).ready(function () {
    jQuery('#loader-bg').hide();

    var buttonWidth = "400";

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
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });

    $('#pic').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 600,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });

    $('#staff').multiselect({
        buttonWidth: 200,
        maxHeight: 400,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });

    $('#asignee').multiselect({
        buttonWidth: 200,
        maxHeight: 400,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });

    $('.datepicker1').datepicker({
        format: "mm/dd/yyyy",
        language: "en",
        autoclose: true,
        orientation: 'bottom left'
    });    

    $('#todo_list').tablesorter({
        widgets: ['zebra'],
        widgetOptions : {
            //scroller_height: setHeight(true),
            zebra : [ "normal-row", "alt-row" ]
        }
    });   
    setHeight("");
});

$(window).resize(function() {    
    setHeight("");
});

function setHeight(addHeight){    
    var windowHt = $(window).height();
    var setHt = windowHt - 150;
    if(addHeight != ""){
        setHt += addHeight;
    }
    $('#todo_list').parent().css('max-height', setHt);      
}

function clearInputFilter() {
    var clientSelectedValue = document.getElementById("client").value;
    var projectSelectedValue = document.getElementById("project").value;

    $('#client').multiselect('deselect', clientSelectedValue);
    $('#client').multiselect('select', "");
    $('#project').multiselect('deselect', projectSelectedValue);
    $('#project').multiselect('select', "");
    $('#pic').multiselect('deselectAll', false);
    $('#pic').multiselect('updateButtonText');
    $('#staff').multiselect('deselectAll', false);
    $('#staff').multiselect('updateButtonText');

    document.getElementById("filter_date").value = "";
    document.getElementById("filter_to").value = "";
}
function loadTodoListData() {
    var client = $("#client").val();
    var project = $("#project").val();
    var pic = setDelimiter($("#pic").val());
    var staff = setDelimiter($("#staff").val());
    var asignee = setDelimiter($("#asignee").val());
    var dateFrom = "blank";
    var dateTo = "blank";
    var status = "blank";

    if (client == "") {
        client = "blank";
    }
    if (project == "") {
        project = "blank";
    }
    if (asignee == "") {
        asignee = "blank";
    }
    if (document.getElementById("filter_date").value != "") {
        var t = document.getElementById("filter_date").value.split("/");
        dateFrom = t[2] + t[0] + t[1];
    }
    if (document.getElementById("filter_to").value != "") {
        var t = document.getElementById("filter_to").value.split("/");
        dateTo = t[2] + t[0] + t[1];
    }

    if($("#progress").val() != ""){
        status = $("#progress").val();
    }

    $.ajax({
        url: "/toDoList/getListData/" + client + "/" + project + "/" + pic + "/" + staff + "/" + dateFrom + "/" + dateTo +"/" + asignee + "/" + status + "/",
        beforeSend: function() {
            jQuery('#loader-bg').show();
        }

    }).done(function (data) {
        clearAllList();
        for (var cnt = 0; cnt < data.todoList.length; cnt++) {
            var id = data.todoList[cnt].id;
            var client = data.todoList[cnt].client;
            var project = data.todoList[cnt].project;
            var task = data.todoList[cnt].task;
            var requestor = data.todoList[cnt].requestor;
            var preparer = data.todoList[cnt].preparer;
            var optional = "";
            if(data.todoList[cnt].optional){
                optional = data.todoList[cnt].optional;
            }
            var start_time = data.todoList[cnt].start_time;
            var duration = data.todoList[cnt].duration;
            var end_time = data.todoList[cnt].end_time;
            var progress = data.todoList[cnt].progress;
            var location = "";
            if(data.todoList[cnt].location){
                location = data.todoList[cnt].location;
            }
            var memo = "";
            if(data.todoList[cnt].memo){
                memo = data.todoList[cnt].memo;
            }
            var pic = data.todoList[cnt].pic;

            insertTodoListRow(id, client, project, task, requestor, preparer, optional, start_time, duration, end_time, progress, location, memo,pic);
        }                
        
        jQuery("#loader-bg").hide();
        $("#todo_list").trigger("update");
        

    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        console.log("XMLHttpRequest :" + XMLHttpRequest.status);
        console.log("textStatus     :" + textStatus);
        console.log("errorThrown    :" + errorThrown.message);
    })
}

function insertTodoListRow(id, client, project, task, requestor, preparer, optional, start_time, duration, end_time, progress, location, memo,pic) {
    var todoList_tbody = document.getElementById("todo_list_body");
    var bodyLength = todoList_tbody.length;
    var count = bodyLength + 1;
    var row = todoList_tbody.insertRow(bodyLength);

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
    c13.style.cssText = "white-space:pre-wrap; word-wrap:break-word;";
    c14.style.cssText = "vertical-align: middle;horizontal-align: middle";
    c15.style.cssText = "vertical-align: middle;horizontal-align: middle";

    // 各列に表示内容を設定
    c1.innerHTML = '<span>' + client + '</span>';
    c2.innerHTML = '<span>' + project + '</span>';
    c3.innerHTML = '<span>' + pic + '</span>';
    c4.innerHTML = '<span>' + task + '</span>';    
    c5.innerHTML = '<span>' + requestor + '</span>';
    c6.innerHTML = '<span>' + preparer + '</span>';
    c7.innerHTML = '<span>' + optional + '</span>';
    c8.innerHTML = '<span>' + start_time + '</span>';
    c9.innerHTML = '<span>' + duration + '</span>';
    c10.innerHTML = '<span>' + end_time + '</span>';
    c11.innerHTML = '<span>' + progress + '</span>';
    c12.innerHTML = '<span style="display: inline-block;width: 100px;">' + location + '</span>';
    c13.innerHTML = '<span style="display: inline-block;width: 400px;">' + memo + '</span>'; 
    c14.innerHTML = '<a href="/master/to-do-list/' + id + '/edit-todo" target="_blank"><button class="btn btn-xs" style="backgroud-color: transparent;"><img src="{{asset("image/pencil.png")}}" /></button></a>'
    
    c15.innerHTML = '<form method="POST" action="/master/to-do-list/' + id + '" class="form-horizontal" style="display:inline;">{{ csrf_field() }}{{ method_field("DELETE") }}<button type="submit" class="btn btn-xs" style="background-color: transparent;" title="Delete Client" onclick="return confirm(' + "'Confirm delete'" + ')"><img src="{{asset("image/delete.png")}}" /></button></form>';
    

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


function clearAllList() {
    var table = document.getElementById("todo_list");    
    //Label初期化
    
    //List初期化
    while (table.rows[ 1 ]) {
        table.deleteRow(1);
    }
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


</script>
<div style="margin-left: 20px">
    <div id="filter_area" style="margin-top: 30px;">
        <div id="filter_left" style="float: left;height: 200px;margin-bottom: 30px">
            <!--Add-new button-->
            <div class="row entry-filter-bottom">
                <div class="col col-md-3" style="margin-left: 60px;">
                    <a href="{{ url("/master/to-do-list-entry/") }}" class="btn btn-primary" target="_blank" type="button" id="add_new" style="margin-top: 10px;margin-left: 50px;width: 120px" onclick="">Add New</a>
                </div>
            </div>
            <!--Client-->    
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-2" >
                    <span class="line-height">Client</span>
                </div>
                <div class="col col-md-1">
                    <select id="client" name="client" class="form-control select2" data-display="static" onchange="setProjectIDData(false)">
                        <option value="">&nbsp;</option>
                        @foreach ($clientList as $clients)
                        <option value="{{$clients->id}}">{{$clients->name}}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!--Project-->
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-2">
                    <span class="line-height">Project</span>
                </div>
                <div class="col col-md-1">
                    <select id="project" name="project" style="width: 200px" class="form-control">     
                        <option value="">&nbsp;</option>
                        @foreach ($projectList as $projects)
                        <option value="{{$projects->id}}">{{$projects->project_name}}</option>
                        @endforeach
                    </select>
                </div>  
            </div>

            <!--PIC-->
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-2">
                    <span class="line-height">PIC</span>
                </div>
                <div class="col col-md-1">                    
                    <select id="pic" name="pic" multiple="multiple" class="form-control">                            
                        @foreach ($picData as $pics)                    
                        <option value="{{$pics->id}}">{{$pics->initial}}</option>
                        @endforeach
                    </select>                                
                </div>
            </div>

            <div class="row entry-filter-bottom">
                <div class="col col-md-2">
                    <!--Clear button-->
                    <input type="button" class="btn btn-default" value="Clear" onclick="clearInputFilter()"  style="background-color: white;width: 150px;margin-left: 109px">
                </div>
                <div class="col col-md-2" style="margin-left: 180px;">
                    <!--Search button-->
                    <input class="btn btn-primary" type="button" value="Search" style="width: 150px" onclick="loadTodoListData()">
                </div>
            </div>
        </div>

            
        <div id="filter_left" style="float: left;height: 200px;margin-bottom: 30px;margin-left: 0px">
            <br><br>
            <!--Staff-->
            <div class="row entry-filter-bottom" style="zoom: 100%;margin-top:15px">
                <div class="col col-md-5">
                    <span class="line-height">Requestor</span>
                </div>
                <div class="col col-md-1">                    
                    <select id="staff" name="staff" multiple="multiple" class="form-control">                            
                        @foreach ($staff as $staffs)                    
                        <option value="{{$staffs->id}}">{{$staffs->initial}}</option>
                        @endforeach
                    </select>                                
                </div>
            </div>
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-5">
                    <span class="line-height">Asignee</span>
                </div>
                <div class="col col-md-1">                    
                    <select id="asignee" name="asignee" multiple="multiple" class="form-control">                            
                        @foreach ($staff as $staffs)                    
                        <option value="{{$staffs->id}}">{{$staffs->initial}}</option>
                        @endforeach
                    </select>                                
                </div>
            </div>
            <!--Date From-->
            <div class="row entry-filter-bottom">
                <div class="col col-md-5">
                    <span class="line-height">Date From</span>
                </div>
                <div class="col col-md-1">
                    <input type="text" style="width: 150px;" class="form-control datepicker1" id="filter_date" placeholder="mm/dd/yyyy" value="" autocomplete="off">
                </div>
            </div>
            <!--Date To-->
            <div class="row entry-filter-bottom">
                <div class="col col-md-5">
                    <span class="line-height">Date To</span>
                </div>
                <div class="col col-md-1">
                    <input type="text" style="width:150px;" class="form-control datepicker1" id="filter_to" name="filter_to" placeholder="mm/dd/yyyy" value="" autocomplete="off">                            
                </div>                 
            </div>        
        </div>  

        <div id="filter_left" style="float: left;height: 200px;margin-bottom: 30px;margin-left: 100px">
            <br><br>
            <div class="row entry-filter-bottom" style="margin-top:15px">
                <div class="col col-md-5">
                    <span class="line-height">Progress</span>
                </div>
                <div class="col col-md-1">
                    <select id="progress" name="progress" class="form-control" style="width: 150px">
                        <option value=""></option>                                
                        <option value="imcomplete">Imcomplete</option>
                        <option value="completed">Completed</option>                            
                    </select>
                </div>                 
            </div>  
        </div>
    </div>

    

    <table id="todo_list" class="table">
        <thead>
            <tr>
                <th style="width: 200px">Client Name</th>
                <th style="width: 150px">Project Name</th>
                <th style="width: 150px">PIC</th>
                <th style="width: 200px">Task</th>                
                <th style="width: 60px">Requestor</th>
                <th style="width: 60px">Asignee</th>
                <th style="width: 100px">Optional Personnel</th>
                <th style="width: 100px">Start Time</th>
                <th style="width: 50px">Duration</th>
                <th style="width: 100px">End Time</th>
                <th style="width: 50px">Progress</th>
                <th style="width: 70px">Location</th>
                <th style="width: 350px">Memo</th>
                <th style="width: 40px">  </th>
            </tr>
        </thead>
        <tbody id="todo_list_body"></tbody>
    </table>
</div>
<script>
    var imageUrl = '{{ URL::asset('/image') }}';
</script>

@endsection