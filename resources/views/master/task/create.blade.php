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


    <form method="POST" action="/master/task/store" class="form-horizontal">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="name" class="col-md-1 control-label">Tasks: </label>
            <div class="col-md-3">
                <input class="form-control" name="name" type="text" id="name" value="{{old('name')}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="project_type" class="col-md-1 control-label">Project Type: </label>
            <div class="col-md-3">
                <input class="form-control" name="project_type" type="text" id="project_type" value="{{old('project_type')}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="is_standard" class="col-md-1 control-label">Standard Task: </label>
            <div class="col-md-3">
                <input name="is_standard" type="checkbox" id="is_standard">
            </div>            
        </div>
        
        
        <div class="form-group">
            <div class="col-md-offset-1 col-md-4">
                <input class="btn btn-primary" type="submit" value="Create">
            </div>
        </div>     
    </form>


    <!--</div>
</div>
</div>
</div>
</div>-->
</div>
@endsection
