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
    <a href="{{ url("master/staff") }}" title="Back"><button class="btn btn-primary btn-sm">Back</button></a>
    <br />
    <br />

    @if ($errors->any())
    <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif


    <form method="POST" action="/master/staff/store" class="form-horizontal">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="employee_no" class="col-md-1 control-label">employee_no: </label>
            <div class="col-md-3">
                <input class="form-control" name="employee_no" type="text" id="employee_no" value="{{old('employee_no')}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="first_name" class="col-md-1 control-label">first_name: </label>
            <div class="col-md-3">
                <input class="form-control" name="first_name" type="text" id="first_name" value="{{old('first_name')}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="last_name" class="col-md-1 control-label">last_name: </label>
            <div class="col-md-3">
                <input class="form-control" name="last_name" type="text" id="last_name" value="{{old('last_name')}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="initial" class="col-md-1 control-label">initial: </label>
            <div class="col-md-3">
                <input class="form-control" name="initial" type="text" id="initial" value="{{old('initial')}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="department" class="col-md-1 control-label">department: </label>
            <div class="col-md-3">
                <input class="form-control" name="department" type="text" id="department" value="{{old('department')}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="title" class="col-md-1 control-label">title: </label>
            <div class="col-md-3">
                <input class="form-control" name="title" type="text" id="title" value="{{old('title')}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="billing_title" class="col-md-1 control-label">billing_title: </label>
            <div class="col-md-3">
                <input class="form-control" name="billing_title" type="text" id="billing_title" value="{{old('billing_title')}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="rate" class="col-md-1 control-label">rate: </label>
            <div class="col-md-3">
                <input class="form-control" name="rate" type="text" id="rate" value="{{old('rate')}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="extension" class="col-md-1 control-label">extension: </label>
            <div class="col-md-3">
                <input class="form-control" name="extension" type="text" id="extension" value="{{old('extension')}}">
            </div>             
        </div>
        <div class="form-group">
            <label for="email" class="col-md-1 control-label">email: </label>
            <div class="col-md-3">
                <input class="form-control" name="email" type="text" id="email" value="{{old('email')}}">
            </div>
        </div>
        <div class="form-group">
            <label for="cell_phone" class="col-md-1 control-label">cell_phone: </label>
            <div class="col-md-3">
                <input class="form-control" name="cell_phone" type="text" id="cell_phone" value="{{old('cell_phone')}}">
            </div>
        </div>
        <div class="form-group">
            <label for="status" class="col-md-1 control-label">status: </label>
            <div class="col-md-3">
                <!--<input class="form-control" name="status" type="text" id="status" value="{{old('status')}}">-->
                <select class="form-control" name="status" id="status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="default_role" class="col-md-1 control-label">default_role: </label>
            <div class="col-md-3">
                <input class="form-control" name="default_role" type="text" id="default_role" value="{{old('default_role')}}">
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
