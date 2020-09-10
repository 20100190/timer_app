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

    <form method="POST" action="/master/staff/{{ $clien->id }}" class="form-horizontal">
        {{ csrf_field() }}
        {{ method_field("PUT") }}

        <div class="form-group">
            <label for="id" class="col-md-1 control-label">ID: </label>
            <div class="col-md-3"><span style="vertical-align: middle">{{$clien->id}}</span></div>            
        </div>
        <div class="form-group">
            <label for="employee_no" class="col-md-1 control-label">Employee No: </label>
            <div class="col-md-3">
                <input class="form-control" name="employee_no" type="text" id="employee_no" value="{{$clien->employee_no}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="first_name" class="col-md-1 control-label">First Name: </label>
            <div class="col-md-3">
                <input class="form-control" name="first_name" type="text" id="first_name" value="{{$clien->first_name}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="last_name" class="col-md-1 control-label">Last Name: </label>
            <div class="col-md-3">
                <input class="form-control" name="last_name" type="text" id="last_name" value="{{$clien->last_name}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="initial" class="col-md-1 control-label">Initial: </label>
            <div class="col-md-3">
                <input class="form-control" name="initial" type="text" id="initial" value="{{$clien->initial}}">
            </div>           
        </div>
        <div class="form-group">
            <label for="department" class="col-md-1 control-label">Department: </label>
            <div class="col-md-3">
                <input class="form-control" name="department" type="text" id="department" value="{{$clien->department}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="title" class="col-md-1 control-label">Title: </label>
            <div class="col-md-3">
                <input class="form-control" name="title" type="text" id="title" value="{{$clien->title}}">
            </div>           
        </div>
        <div class="form-group">
            <label for="billing_title" class="col-md-1 control-label">Billing Title: </label>
            <div class="col-md-3">
                <!--<input class="form-control" name="billing_title" type="text" id="billing_title" value="{{$clien->billing_title}}">-->
                <select class="form-control" name="billing_title" id="billing_title">
                    <option value="Partner" @if($clien->billing_title == "Partner") selected @endif>Partner</option>
                    <option value="Senior Manager" @if($clien->billing_title == "Senior Manager") selected @endif>Senior Manager</option>
                    <option value="Manager" @if($clien->billing_title == "Manager") selected @endif>Manager</option>
                    <option value="Experienced Senior" @if($clien->billing_title == "Experienced Senior") selected @endif>Experienced Senior</option>
                    <option value="Senior" @if($clien->billing_title == "Senior") selected @endif>Senior</option>
                    <option value="Experienced Staff" @if($clien->billing_title == "Experienced Staff") selected @endif>Experienced Staff</option>
                    <option value="Staff" @if($clien->billing_title == "Staff") selected @endif>Staff</option>                    
                    <option value="HR" @if($clien->billing_title == "HR") selected @endif>HR</option>                    
                    <option value="Accounting Assistant" @if($clien->billing_title == "Accounting Assistant") selected @endif>Accounting Assistant</option>                    
                </select>
            </div>            
        </div>
        <div class="form-group">
            <label for="rate" class="col-md-1 control-label">Rate: </label>
            <div class="col-md-3">
                <input class="form-control" name="rate" type="text" id="rate" value="{{$clien->rate}}">
            </div>            
        </div>
        <div class="form-group">
            <label for="extension" class="col-md-1 control-label">Extension: </label>
            <div class="col-md-3">
                <input class="form-control" name="extension" type="text" id="extension" value="{{$clien->extension}}">
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-md-1 control-label">Email: </label>
            <div class="col-md-3">
                <input class="form-control" name="email" type="text" id="email" value="{{$clien->email}}">
            </div>
        </div>
        <div class="form-group">
            <label for="cell_phone" class="col-md-1 control-label">Cell Phone: </label>
            <div class="col-md-3">
                <input class="form-control" name="cell_phone" type="text" id="cell_phone" value="{{$clien->cell_phone}}">
            </div>
        </div>
        <div class="form-group">
            <label for="status" class="col-md-1 control-label">Status: </label>
            <div class="col-md-3">
                <!--<input class="form-control" name="status" type="text" id="status" value="{{$clien->status}}">-->
                <select class="form-control" name="status" id="status">
                    <option value="Active" @if($clien->status == "Active") selected @endif>Active</option>
                    <option value="Inactive" @if($clien->status == "Inactive") selected @endif>Inactive</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="default_role" class="col-md-1 control-label">Default Role: </label>
            <div class="col-md-3">
                <input class="form-control" name="default_role" type="text" id="default_role" value="{{$clien->default_role}}">
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
