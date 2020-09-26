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
        includeSelectAllOption: true,
    });
    
    $('#project').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        enableFiltering: true,
        includeSelectAllOption: true,
    });
    $('#status').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,        
    });
    
    $('#xxx').tablesorter({
        widgets: ['zebra'],
        widgetOptions : {
            zebra : [ "normal-row", "alt-row" ]
        }
    });   
});


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
</script>
<div style="margin-left: 20px;margin-top: 20px">
<!--<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">               
                <div class="panel-body">-->

<form method="GET" action="{{ url("master/project-list") }}" accept-charset="UTF-8" role="search">
    <div id="filter_left" style="float: left;height: 120px;margin-bottom: 50px">
        <div class="row entry-filter-bottom" style="zoom: 100%">
            <div class="col col-md-2" >
                <span class="line-height">Client</span>
            </div>
            <div class="col col-md-3">
                <select id="client" name="client" class="form-control select2" data-display="static">    
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
                <input type="button" class="btn btn-default" value="Clear" onclick="clearFilter()" style="background-color: white;width: 150px;margin-left: 109px">
            </div>
            <div class="col col-md-1" style="margin-left: 180px;" >
                <button class="btn btn-primary" type="submit" style="width: 150px">
                    <span>Search</span>
                </button>
            </div>
        </div>
    </div>

    
</form>

<div style="clear:both">

<br/>
<br/>


<div class="table-responsive">
    <table id="xxx" class="table table-borderless" style="font-family: Source Sans Pro;font-size: 14px;width: 800px">
        <thead>                                
            <tr>
                <th style="width: 100px">Project ID</th>
                <th style="width: 200px">Client</th>
                <th style="width: 200px">Project</th>
                <th style="width: 50px">Link</th>
                @if($isApprove == 1)
                <th style="width: 50px">Approve</th>                                    
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($client as $item)

            <tr>

                <td>{{ $item->project_id}} </td>

                <td>{{ $item->client_name}} </td>

                <td>{{ $item->project_name}} </td>
                
                <td><a href="project/{{$item->client_id}}/{{ $item->project_name}}" target="_blank"><img src="{{URL::asset('/image')}}/view.png"></a></td>                

                @if($isApprove == 1)
                <td><button class="btn btn-xs btn-primary" onclick="approveProject(this,{{ $item->project_id}})" @if($item->is_approval == 1) style="background-color: #DCDCDC" disabled @endif>Approve</button></td>
                @endif
            </tr>

            @endforeach
        </tbody>
    </table>

</div>
</div>

                <!--</div>
            </div>
        </div>
    </div>
</div>-->
@endsection
