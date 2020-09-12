@extends('layouts.main')
@section("content")
<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#loader-bg').hide();
    });
</script>
<div style="margin-left: 20px;margin-top: 20px">
    <!--<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">                            
                    <div class="panel-body">-->
    <a href="{{ url("master/task") }}" title="Back"><button class="btn btn-primary btn-sm">Back</button></a>
    <br />
    <br />

    @if ($errors->any())
    <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif

    <form method="POST" action="/master/task/{{ $task->id }}" class="form-horizontal">
        {{ csrf_field() }}
        {{ method_field("PUT") }}

        <div class="form-group">
            <label for="id" class="col-md-1 control-label">ID: </label>
            <div class="col-md-3"><span style="vertical-align: middle">{{$task->id}}</span></div>            
        </div>        
        <div class="form-group">
            <label for="name" class="col-md-1 control-label">Tasks: </label>
            <div class="col-md-3">
                <input class="form-control" name="name" type="text" id="name" value="{{$task->name}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="project_type" class="col-md-1 control-label">Project Type: </label>
            <div class="col-md-3">
                <input class="form-control" name="project_type" type="text" id="project_type" value="{{$task->project_type}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="is_standard" class="col-md-1 control-label">Standard Task: </label>
            <div class="col-md-3" style="margin-top: 5px">
                <input name="is_standard" type="checkbox" id="is_standard" @if($task->is_standard == "TRUE") checked="checked" @endif>
            </div>            
        </div>
        
        <div class="form-group">
            <div class="col-md-1"></div>
            <div class="col-md-4">
                <input class="btn btn-primary" type="submit" value="Update">
            </div>
        </div>   
    </form>

</div>
<!--</div>
</div>
</div>
</div>
</div>-->
@endsection
