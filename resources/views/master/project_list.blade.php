@extends('layouts.main')
@section("content")
<style type="text/css">
    .fixed-header {
        position: sticky;
        top:0;
        z-index: 1
    }
</style>
<script type="text/javascript">
$(document).ready(function () {
    jQuery('#loader-bg').hide();
    
    var buttonWidth = "400";
    
    $('#client').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });
    
    $('#project').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });
    $('#status').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,        
    });
    
    $('#xxx').tablesorter({
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
    var setHt = windowHt - 270;
    if(addHeight != ""){
        setHt += addHeight;
    }
    $('#xxx').parent().css('max-height', setHt);      
}


function approveProject(obj,projectId){
    obj.style.cssText = "background-color: #DCDCDC";
    obj.disabled = true;
    
    $.ajax({
        url: "project-list/save/" + projectId,
        dataType: "json",
        success: data => {
            Swal.fire({
                position: 'top',
                icon: 'success',
                title: 'Approved',
                showConfirmButton: false,
                timer: 1500
            });
        },        
    });    
}

function clearFilter() {
    var clientSelectedValue = document.getElementById("client").value;
    var projectSelectedValue = document.getElementById("project").value;
    var groupSelectedValue = document.getElementById("status").value;
    $('#client').multiselect('deselect', clientSelectedValue);
    $('#client').multiselect('select', "");
    $('#project').multiselect('deselect', projectSelectedValue);
    $('#project').multiselect('select', "");
    $('#status').multiselect('deselect', groupSelectedValue);
    $('#status').multiselect('select', "");
}

function closeOverrall() {
    var imagesUrl = '{{ URL::asset('/image') }}';
    var acWidth = document.getElementById("filter_left").style.height;
    var btnObj = document.getElementById("btn_open_close");
    var closeArea = document.getElementById("close_area");
    
    if (acWidth == "30px") {
        btnObj.src = imagesUrl + "/close.png"
        document.getElementById("filter_left").style.height = "180px";  
        closeArea.style.height = "150px";  
        //document.getElementById("btn_open_close").style.cssText = "margin-top: 50px";   
        
        document.getElementById("filter_left").style.visibility = "visible";
        document.getElementById("add_new").style.visibility = "visible";
        setHeight("");
        
    } else {
        btnObj.src = imagesUrl + "/open.png"
        document.getElementById("filter_left").style.height = "30px";
        //document.getElementById("btn_open_close").style.cssText = "margin-top: 0px";     
        closeArea.style.height = "30px";  
        document.getElementById("filter_left").style.visibility = "hidden";  
        document.getElementById("add_new").style.visibility = "hidden";
        setHeight(140);
    }   
    
}

function loadData(){
    var client = $("#client").val();
    var project = $("#project").val();
    var status = $("#status").val();
    
    if(client == ""){
        client = "blank";
    }
    if(project == ""){
        project = "blank";
    }
    if(status == ""){
        status = "blank";
    }

    $.ajax({
        url: "/master/project-list/" + client + "/" + project + "/" + status + "/",
    }).done(function (data) {        
        $("#project-list-body").empty();
        
        for (var cnt = 0; cnt < data.listData.length; cnt++) {
            insertProjectListRow(data.listData[cnt]["client_id"], data.listData[cnt]["project_id"], data.listData[cnt]["client_name"], data.listData[cnt]["project_name"], data.listData[cnt]["is_approval"]);
        }      
        
        $('#xxx').trigger("update");
   

    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    });
}

function insertProjectListRow(clientId, projectId, clientName, projectName, status) {
    // 最終行に新しい行を追加
    var specific_tbody = document.getElementById("project-list-body");
    var bodyLength = specific_tbody.rows.length;
    var count = bodyLength + 1;
    var row = specific_tbody.insertRow(bodyLength);
    var isApprove = document.getElementById("is-approve").value;

    // 列の追加
    var c1 = row.insertCell(0);
    var c2 = row.insertCell(1);
    var c3 = row.insertCell(2);
    var c4 = row.insertCell(3);
    var c5 = row.insertCell(4);

    // 各列に表示内容を設定
    c1.innerHTML = '<a href="project/' + clientId + '/' + projectName + '"' + ' target="_blank"><img src="' + '{{ URL::asset('/image') }}' + '/view.png"></a>';
    c2.innerHTML = '<span>' + projectId + '</span>';
    c3.innerHTML = '<span>' + clientName + '</span>';
    c4.innerHTML = '<span>' + projectName + '</span>';   
    if(isApprove == 1){
        if(status != 1){
            c5.innerHTML = '<button class="btn btn-xs btn-primary" style="width: 61px" onclick="approveProject(this,' + projectId + ')">Approve</button>';
        }else {        
            c5.innerHTML = '<button class="btn btn-xs btn-primary" style="width: 61px;background-color: #DCDCDC" onclick="approveProject(this,' + projectId + ')" disabled>Approved</button>';
        }        
    }
    
}

/*
function setProjectData(){
    
    var client = $('#client').val();
    if(client == ""){
        client = "blank";
    }    
    
    $.ajax({
        url: "/project/data/" + client + "/",
    }).done(function (data) {        
        $('#project').children().remove();
        var project = document.getElementById('project');
        document.createElement('option')
        var option = document.createElement('option');
        option.setAttribute('value', "blank");
        option.innerHTML = "&nbsp;";
        project.appendChild(option);
        for(var i = 0; i < data.projectData.length; i++){
            var option = document.createElement('option');
            option.setAttribute('value', data.projectData[i].project_name);
            option.innerHTML = data.projectData[i].project_name;
            project.appendChild(option);
        };
        
        $('#project').multiselect('rebuild');    

    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    });    
    
}
*/
</script>
<div id="div1" style="margin-left: 20px;margin-top: 20px">
    <!--<form method="GET" action="{{ url("master/project-list") }}" accept-charset="UTF-8" role="search">-->
    <div id="filter_left" style="float: left;height: 180px;margin-bottom: 0px">
        <div class="row entry-filter-bottom" style="zoom: 100%">
            <div class="col col-md-2" >
                <span class="line-height">Client</span>
            </div>
            <div class="col col-md-8">
                <select id="client" name="client" class="form-control select2" data-display="static" onchange="setProjectData(false)">    
                    <option value="">&nbsp;</option>
                    @foreach ($clientList as $clients)
                    <option value="{{$clients->id}}">{{$clients->name}}</option>
                    @endforeach
                </select>
            </div>
            
        </div>

        <div class="row entry-filter-bottom" style="zoom: 100%">
            <div class="col col-md-2">
                <span class="line-height">Project</span>
            </div>
            <div class="col col-md-1">
                <select id="project" name="project" style="width: 200px" class="form-control">     
                    <option value="">&nbsp;</option>
                    @foreach ($projectList as $projects)
                    <option value="{{$projects->project_name}}">{{$projects->project_name}}</option>
                    @endforeach
                </select>
            </div>                
        </div>

        <div class="row entry-filter-bottom" style="zoom: 100%">
            <div class="col col-md-2">
                <span class="line-height">Status</span>
            </div>
            <div class="col col-md-1">
                <select id="status" name="status" style="width: 200px" class="form-control">     
                    <option value="">&nbsp;</option>
                    <option value="1">Approved</option>  
                    <option value="0">Unapproved</option>  
                </select>
            </div>                
        </div>
        <!--<div class="input-group">       
            <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">
                    <span>Search</span>
                </button>
            </span>
        </div>-->
        <div class="row entry-filter-bottom">                           
            <div class="col col-md-2" >
                <input type="button" id="clear" class="btn btn-default" value="Clear" onclick="clearFilter()" style="background-color: white;width: 150px;margin-left: 109px">
            </div>
            <div class="col col-md-1" style="margin-left: 180px;" >
                <!--<button class="btn btn-primary" type="submit" style="width: 150px">
                    <span>Search</span>
                </button>-->
                <input class="btn btn-primary" type="button" value="Search" style="width: 150px" onclick="loadData()">
            </div>            
        </div>
    </div>
    
    <div id="close_area" style="float: left;">
        <input type="image" id="btn_open_close" src="{{ URL::asset('/image') }}/close.png" onclick="closeOverrall();return;" style="height: 20px;width: 20px;margin-left: 115px;margin-top: 5px">
       <br><br><br><br><br>       
       <a href="{{ url("/master/project/") }}" class="btn btn-primary" target="_blank" type="button" id="add_new" style="margin-top: 10px;margin-left: 50px;width: 100px" onclick="">Add New</a>
    </div>
<!--</form>-->
    

<!--<br/>
<br/>-->

<div style="clear: both"></div>
<div class="table-responsive" style="height: 3000px">    
    <table id="xxx" class="table table-borderless" style="font-family: Source Sans Pro;font-size: 14px;width: 800px">
        <thead>                                
            <tr>
                <th class="fixed-header" style="width: 50px">Link</th>
                <th class="fixed-header" style="width: 100px;">Project ID</th>
                <th class="fixed-header" style="width: 200px;">Client</th>
                <th class="fixed-header" style="width: 200px;">Project</th>                
                
                <th class="fixed-header" style="width: 50px;text-align: center">@if($isApprove == 1) Approve @else &nbsp; @endif</th>                                    
               
            </tr>
        </thead>
        <tbody id="project-list-body"></tbody>
    </table>

</div>
            <input type="hidden" id="is-approve" name="is-approve" value="{{$isApprove}}">
</div>

                <!--</div>
            </div>
        </div>
    </div>
</div>-->
@endsection
