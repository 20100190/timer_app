@extends('layouts.main')
@section("content")
<script type="text/javascript">
$(document).ready(function () {
    jQuery('#loader-bg').hide();
    
    $('#task_table').tablesorter({
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
</script>
<div style="margin-left: 20px;margin-top: 20px">
<!--<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">               
                <div class="panel-body">-->

                    <a href="{{ url("master/task/create") }}" class="btn btn-primary btn-sm" title="Add New clien" style="float: left;margin-top: 7px">
                        Add New
                    </a>

                    <form method="GET" action="{{ url("master/task") }}" accept-charset="UTF-8" class="navbar-form" style="margin-left: 575px;float: left;" role="search">
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
                    
                    <div style="clear: both"></div>


                    <div class="table-responsive" style="height: 700px">
                        <table id="task_table" class="table table-borderless" style="font-family: Source Sans Pro;font-size: 14px;width: 900px">
                            <thead>                                
                                <tr>
                                    <th class="table-sticky-locklist" style="width: 70px">ID</th>
                                    <th class="table-sticky-locklist" style="width: 350px">Tasks</th>
                                    <th class="table-sticky-locklist" style="width: 200px">Project Type</th>
                                    <th class="table-sticky-locklist" style="width: 150px">Standard Task</th>   
                                    <th class="table-sticky-locklist" style="width: 50px"></th>   
                                    <th class="table-sticky-locklist" style="width: 50px"></th>   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($task as $item)

                                <tr>

                                    <td>{{ $item->id}} </td>

                                    <td>{{ $item->name}} </td>

                                    <td>{{ $item->project_type}} </td>

                                    <td><input type="checkbox" @if($item->is_standard == "TRUE") checked="checked" @endif disabled></td>
                                    <td><a href="{{ url("/master/task/" . $item->id . "/edit") }}" title="Edit task"><button class="btn btn-xs" style="background-color: transparent;" ><img src="{{asset("image/pencil.png")}}" /></button></a></td>
                                    <td>
                                        <form method="POST" action="/master/task/{{ $item->id }}" class="form-horizontal" style="display:inline;">
                                            {{ csrf_field() }}

                                            {{ method_field("DELETE") }}
                                            <button type="submit" class="btn btn-xs" style="background-color: transparent;" title="Delete Task" onclick="return confirm('Confirm delete')">
                                                <img src="{{asset("image/delete.png")}}" />
                                            </button>
                                        </form>
                                    </td>
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
