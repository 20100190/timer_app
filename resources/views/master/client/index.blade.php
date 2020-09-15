@extends('layouts.main')
@section("content")
<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#loader-bg').hide();

        $('#task_table').tablesorter({
            widgets: ['zebra'],
            widgetOptions: {
                zebra: ["normal-row", "alt-row"]
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

    <a href="{{ url("master/client/create") }}" class="btn btn-primary btn-sm" title="Add New clien" style="float: left;margin-top: 7px">
        Add New
    </a>

    <form method="GET" action="{{ url("master/client") }}" accept-charset="UTF-8" class="navbar-form" style="margin-left: 575px;float: left;" role="search">
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
                    <th class="table-sticky-locklist" style="width: 350px">Client Name</th>
                    <th class="table-sticky-locklist" style="width: 200px">FYE</th>
                    <th class="table-sticky-locklist" style="width: 150px">VIC Status</th>   
                    <th class="table-sticky-locklist" style="width: 350px">Group Companies</th>   
                    <th class="table-sticky-locklist" style="width: 150px">In-Charge</th> 
                    <th class="table-sticky-locklist" style="width:40px"> </th>
                    <th class="table-sticky-locklist" style="width:40px"> </th>
                    <th class="table-sticky-locklist" style="width:40px"> </th>
                </tr>
            </thead>
            <tbody>
                @foreach($client as $item)

                <tr>

                    <td>{{ $item->id}} </td>

                    <td>{{ $item->name}} </td>

                    <td>{{ $item->fye}} </td>

                    <td>{{ $item->vic_status}}</td>

                    <td>{{ $item->group_companies}}</td>

                    <td>{{ $item->initial}}</td>

                    <td><a href="{{ url("/master/client/" . $item->id) }}" title="Show task"><button class="btn btn-xs" style="background-color: transparent;" ><img src="{{asset("image/view.png")}}" /></button></a></td>
                    <td><a href="{{ url("/master/client/" . $item->id . "/edit") }}" title="Edit task"><button class="btn btn-xs" style="background-color: transparent;" ><img src="{{asset("image/pencil.png")}}" /></button></a></td>
                    <td>
                        <form method="POST" action="/master/client/{{ $item->id }}" class="form-horizontal" style="display:inline;">
                            {{ csrf_field() }}

                            {{ method_field("DELETE") }}
                            <button type="submit" class="btn btn-xs" style="background-color: transparent;" title="Delete Client" onclick="return confirm('Confirm delete')">
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
