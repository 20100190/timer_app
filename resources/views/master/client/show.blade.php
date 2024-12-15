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

    <a href="{{ url("master/client") }}" title="Back"><button class="btn btn-primary btn-sm">Back</button></a>
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
            <thead><tr style="height: 37px"></tr></thead>
            <tbody>
                <tr><th width="200px">ID</th><td>{{$clien->id}} </td></tr>
                <tr><th>Name</th><td>{{$clien->name}} </td></tr>
                <tr><th>FYE</th><td>{{$clien->fye}} </td></tr>
                <tr><th>Vic Status</th><td>{{$clien->vic_status}} </td></tr>
                <tr><th>Group Companies</th><td>{{$clien->group_companies}} </td></tr>
                <tr><th>Website</th><td>{{$clien->website}} </td></tr>
                <tr><th>Address US</th><td>{{$clien->address_us}} </td></tr>
                <tr><th>Address JP</th><td>{{$clien->address_jp}} </td></tr>
                <tr><th>Mailing Address</th><td>{{$clien->mailing_address}} </td></tr>
                <tr><th>Tel1</th><td>{{$clien->tel1}} </td></tr>
                <tr><th>Tel2</th><td>{{$clien->tel2}} </td></tr>
                <tr><th>Tel3</th><td>{{$clien->tel3}} </td></tr>
                <tr><th>Fax</th><td>{{$clien->fax}} </td></tr>
                <tr><th>Fax</th><td>{{$clien->fax}} </td></tr>  
                @if($isApprove == 1)                
                <tr><th>
                    @if($clien->is_approve == 1)                    
                    <button style="margin-top: 40px;background-color: #DCDCDC" class="btn btn-primary btn-sm" onclick="approve(this, {{$clien->id}})">Unapprove</button>
                    @else    
                    <button style="margin-top: 40px" class="btn btn-primary btn-sm" onclick="approve(this, {{$clien->id}})">Approve</button>        
                    @endif                    
                </th></tr>
                @endif
            </tbody>
        </table>
    </div>
    <div style="float: left;margin-right: 50px">
        <table class="table table-borderless">
            <thead><tr style="height: 37px"></tr></thead>
            <tbody>                
                <tr><th>Federal ID</th><td>{{$clien->federal_id}} </td></tr>
                <tr><th>State ID</th><td>{{$clien->state_id}} </td></tr>
                <tr><th>Edd ID</th><td>{{$clien->edd_id}} </td></tr>
                <tr><th>Note</th><td>{{$clien->note}} </td></tr>
                <tr><th>PIC</th><td>{{$clien->initial}} </td></tr>
                <tr><th>Nature of Business</th><td>{{$clien->nature_of_business}} </td></tr>
                <tr><th>Incorporation Date</th><td>{{$clien->incorporation_date}} </td></tr>
                <tr><th>Incorporation State</th><td>{{$clien->incorporation_state}} </td></tr>
                <tr><th>Business Started</th><td>{{$clien->business_started}} </td></tr>
                <tr><th>Status</th><td>@if($clien->is_archive == 1) Archived @else Active @endif </td></tr>
                <tr><th>harvest_client_id</th><td>{{$clientHarvest->id}}</td></tr>
            </tbody>
        </table>
    </div>
    <!-- </div>-->
    <div style="width: 800px;float: left">
        <table class="table">
            <thead>
                <tr>
                    <th colspan="8" class="project-font-size" style="font-size: 20px">Contact Person</th>
                </tr>
                <tr>
                    <th class="project-font-size" style="text-align:center; width:40px;">No</th>
                    <th class="project-font-size" style="width: 200px">Contact Person</th>
                    <th class="project-font-size" style="width: 120px">Title</th>
                    <th class="project-font-size" style="width: 200px">Contact Person 日本語</th>
                    <th class="project-font-size" style="width: 100px">TelePhone</th>
                    <th class="project-font-size" style="width: 100px">Cell Phone</th>
                    <th class="project-font-size" style="width: 100px">FAX</th>
                    <th class="project-font-size" style="width: 100px">Email</th>
                </tr> 
            </thead>
            <tbody id="contact_person_body">
                @foreach($contactPerson as $items)
                <tr>
                    <td style="text-align: center"><span style="vertical-align: middle">{{$items->id}}</span></td>
                    <td><span style="vertical-align: middle">{{$items->person}}</span></td>
                    <td><span style="vertical-align: middle">{{$items->person_title}}</span></td>
                    <td><span style="vertical-align: middle">{{$items->person_jp}}</span></td>
                    <td><span style="vertical-align: middle">{{$items->tel}}</span></td>
                    <td><span style="vertical-align: middle">{{$items->mobile_phone}}</span></td>
                    <td><span style="vertical-align: middle">{{$items->fax}}</span></td>
                    <td><span style="vertical-align: middle">{{$items->email}}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="float: left;margin-top: 20px;margin-bottom: 20px">            
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="3" class="project-font-size" style="font-size: 20px">US Shareholders</th>
                    </tr>
                    <tr>
                        <th class="project-font-size" style="text-align:center; width:40px;">No</th>
                        <th class="project-font-size" style="width: 250px">Name</th>
                        <th class="project-font-size" style="width: 50px">%</th>                   
                    </tr> 
                </thead>
                <tbody id="us_shareholder_body">
                    @foreach($usShareholders as $items)
                    <tr>
                        <td style="text-align: center"><span style="vertical-align: middle">{{$items->id}}</span></td>
                        <td><span style="vertical-align: middle">{{$items->name}}</span></td>
                        <td style="text-align: right"><span style="vertical-align: middle">{{$items->percent}}%</span></td>                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="float: left;margin-top: 20px;margin-left: 110px">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="3" class="project-font-size" style="font-size: 20px">Foreign Shareholders</th>
                    </tr>
                    <tr>
                        <th class="project-font-size" style="text-align:center; width:40px;">No</th>
                        <th class="project-font-size" style="width: 250px">Name</th>
                        <th class="project-font-size" style="width: 50px">%</th>                   
                    </tr> 
                </thead>
                <tbody id="foreign_shareholder_body">
                    @foreach($foreignShareholders as $items)
                    <tr>
                        <td style="text-align: center"><span style="vertical-align: middle">{{$items->id}}</span></td>
                        <td><span style="vertical-align: middle">{{$items->name}}</span></td>
                        <td style="text-align: right"><span style="vertical-align: middle">{{$items->percent}}%</span></td>                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="width: 340px">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="3" class="project-font-size" style="font-size: 20px">Officers</th>
                    </tr>
                    <tr>
                        <th class="project-font-size" style="text-align:center; width:40px;">No</th>
                        <th class="project-font-size" style="width: 200px">Name</th>
                        <th class="project-font-size" style="width: 100px">Title</th>                   
                    </tr> 
                </thead>
                <tbody id="officers_body">
                    @foreach($officers as $items)
                    <tr>
                        <td style="text-align: center"><span style="vertical-align: middle">{{$items->id}}</span></td>
                        <td><span style="vertical-align: middle">{{$items->name}}</span></td>
                        <td><span style="vertical-align: middle">{{$items->title}}</span></td>                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--</div>
</div>
</div>
</div>
</div>-->
@endsection
