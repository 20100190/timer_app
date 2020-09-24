@extends('layouts.main')
@section("content")
<script type="text/javascript">
$(document).ready(function () {
    jQuery('#loader-bg').hide();
    
    $('#xxx').tablesorter({
        widgets: ['zebra'],
        widgetOptions : {
            zebra : [ "normal-row", "alt-row" ]
        }
    });
    /*
    var x = document.getElementById("xxx");
    var rowIndex = 0;
    for(let row of x.rows){
        if(rowIndex % 2 != 0){
            for(let cell of row.cells){                     
                cell.style.cssText = "background-color: #EAEAEA"; 
            }        
        }
        rowIndex += 1;
    }*/
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
</script>
<div style="margin-left: 20px;margin-top: 20px">
<!--<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">               
                <div class="panel-body">-->

                    <form method="GET" action="{{ url("master/project-list") }}" accept-charset="UTF-8" class="navbar-form navbar-right" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">
                                    <span>Search</span>
                                </button>
                            </span>
                        </div>
                    </form>


                    <br/>
                    <br/>


                    <div class="table-responsive">
                        <table id="xxx" class="table table-borderless" style="font-family: Source Sans Pro;font-size: 14px;width: 800px">
                            <thead>                                
                                <tr>
                                    <th style="width: 100px">Project ID</th>
                                    <th style="width: 200px">Client</th>
                                    <th style="width: 200px">Project</th>
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
