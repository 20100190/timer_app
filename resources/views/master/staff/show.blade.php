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

    <a href="{{ url("master/client") }}" title="Back"><button class="btn btn-warning btn-sm">Back</button></a>
    <!--<a href="{{ url("clien") ."/". $clien->id . "/edit" }}" title="Edit clien"><button class="btn btn-primary btn-xs">Edit</button></a>
    <form method="POST" action="/clien/{{ $clien->id }}" class="form-horizontal" style="display:inline;">
            {{ csrf_field() }}
            {{ method_field("delete") }}
            <button type="submit" class="btn btn-danger btn-xs" title="Delete User" onclick="return confirm('Confirm delete')">
            Delete
            </button>    
    </form>-->
    <br/>
    <br/>
    <!--<div class="table-responsive">      -->
    <div style="float: left;margin-right: 50px">
        <table class="table table-borderless">
            <tbody>
                <tr><th width="200px">id</th><td>{{$clien->id}} </td></tr>
                <tr><th>name</th><td>{{$clien->name}} </td></tr>
                <tr><th>fye</th><td>{{$clien->fye}} </td></tr>
                <tr><th>vic_status</th><td>{{$clien->vic_status}} </td></tr>
                <tr><th>group_companies</th><td>{{$clien->group_companies}} </td></tr>
                <tr><th>website</th><td>{{$clien->website}} </td></tr>
                <tr><th>address_us</th><td>{{$clien->address_us}} </td></tr>
                <tr><th>address_jp</th><td>{{$clien->address_jp}} </td></tr>
                <tr><th>mailing_address</th><td>{{$clien->mailing_address}} </td></tr>
                <tr><th>tel1</th><td>{{$clien->tel1}} </td></tr>
                <tr><th>tel2</th><td>{{$clien->tel2}} </td></tr>
                <tr><th>tel3</th><td>{{$clien->tel3}} </td></tr>
                <tr><th>fax</th><td>{{$clien->fax}} </td></tr>
                <tr><th>fax</th><td>{{$clien->fax}} </td></tr>                
            </tbody>
        </table>
    </div>
    <div style="float: left">
        <table class="table table-borderless">
            <tbody>                
                <tr><th>federal_id</th><td>{{$clien->federal_id}} </td></tr>
                <tr><th>state_id</th><td>{{$clien->state_id}} </td></tr>
                <tr><th>edd_id</th><td>{{$clien->edd_id}} </td></tr>
                <tr><th>note</th><td>{{$clien->note}} </td></tr>
                <tr><th>pic</th><td>{{$clien->pic}} </td></tr>
                <tr><th>nature_of_business</th><td>{{$clien->nature_of_business}} </td></tr>
                <tr><th>incorporation_date</th><td>{{$clien->incorporation_date}} </td></tr>
                <tr><th>incorporation_state</th><td>{{$clien->incorporation_state}} </td></tr>
                <tr><th>business_started</th><td>{{$clien->business_started}} </td></tr>
            </tbody>
        </table>
    </div>
   <!-- </div>-->
</div>

<!--</div>
</div>
</div>
</div>
</div>-->
@endsection
