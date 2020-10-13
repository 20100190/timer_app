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
</script>
<div style="margin-left: 20px;margin-top: 20px">
<!--<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">               
                <div class="panel-body">-->

                    <a href="{{ url("master/staff/create") }}" class="btn btn-primary btn-sm" title="Add New clien" @if($isEdit != 1) style="visibility : collapse" @endif>
                        Add New
                    </a>

                    <form method="GET" action="{{ url("master/staff") }}" accept-charset="UTF-8" class="navbar-form navbar-right" role="search">
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
                        <table id="xxx" class="table table-borderless" style="font-family: Source Sans Pro;font-size: 14px">
                            <thead>                                
                                <tr>
                                    <th>ID</th>
                                    <th>Employee No</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Initial</th>
                                    <th>Department</th>
                                    <th>Title</th>
                                    <th>Billing Title</th>
                                    <th>Rate</th>
                                    <th>Extension</th>
                                    <th>Email</th>
                                    <th>Cell Phone</th>
                                    <th>Status</th>
                                    <th>Default Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clien as $item)

                                <tr>

                                    <td>{{ $item->id}} </td>

                                    <td>{{ $item->employee_no}} </td>

                                    <td>{{ $item->first_name}} </td>

                                    <td>{{ $item->last_name}} </td>

                                    <td>{{ $item->initial}} </td>

                                    <td>{{ $item->department}} </td>

                                    <td>{{ $item->title}} </td>

                                    <td>{{ $item->billing_title}} </td>
                                    
                                    <td style="text-align: right">{{ $item->rate}} </td>
                                    
                                    <td style="text-align: right">{{ $item->extension}} </td>
                                    
                                    <td>{{ $item->email}} </td>
                                    
                                    <td>{{ $item->cell_phone}} </td>
                                    
                                    <td>{{ $item->status}} </td>
                                    
                                    <td>{{ $item->default_role}} </td>
                                        
                                    @if($isEdit == 1)
                                    <td><a href="{{ url("/master/staff/" . $item->id . "/edit") }}" title="Edit staff"><button class="btn btn-xs" style="background-color: transparent;" ><img src="{{asset("image/pencil.png")}}" /></button></a></td>
                                    <td>
                                        <form method="POST" action="/master/staff/{{ $item->id }}" class="form-horizontal" style="display:inline;">
                                            {{ csrf_field() }}

                                            {{ method_field("DELETE") }}
                                            <button type="submit" class="btn btn-xs" style="background-color: transparent;" title="Delete User" onclick="return confirm('Confirm delete')">
                                                <img src="{{asset("image/delete.png")}}" />
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                        <div class="pagination-wrapper"> {!! $clien->appends(["search" => Request::get("search")])->render() !!} </div>
                    </div>
</div>

                <!--</div>
            </div>
        </div>
    </div>
</div>-->
@endsection
