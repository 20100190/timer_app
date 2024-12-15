@extends('layouts.main')
@section('content') 

<table class="table" id="domain_list" style="width: 500px; margin-left: 20px">
    <thead>
        <tr style="height: 50px">
            <th width="50px">No</th>
            <th width="150px">Client</th>
            <th width="150px">Domain</td>
            <th width="80px">Registerd?</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Test Client1</td>
            <td>@123.co.jp</td>    
            <td><input class="btn btn-primary inpcontactjp" type="button" id="is_registered" name="is_registered" value="Registered" style="width: 100%"></td>    
        </tr>
        <tr>
            <td>2</td>
            <td>Test Client2</td>            
            <td>@34567.co.jp</td>
            <td><input class="btn btn-primary inpcontactjp" type="button" id="is_registered" name="is_registered" value="Not Registered" style="width: 100%"></td>    
        </tr>        
    </tbody>
</table>

<table class="table" id="email_list" style="width: 900px; margin-left: 20px">
    <thead>
        <tr style="height: 50px">
            <th width="50px">No</th>
            <th width="150px">Client</th>
            <th width="150px">Contact Person</td>
            <th width="80px">Title</td>
            <th width="150px">Contact Person日本語</td>
            <th width="150px">Telephone</td>
            <th width="150px">Cell Phone</td>
            <th width="150px">FAX</td>
            <th width="80px">Email</td>
            <th width="80px">Regisetered</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Test Client1</td>
            <td>Taro Yamada</td>    
            <td>Mr.</td>    
            <td>山田</td>
            <td>111-1111-1111</td>    
            <td>222-1111-1111</td> 
            <td>333-1111-1111</td> 
            <td>abc@gmail.com</td> 
            <td><input class="btn btn-primary inpcontactjp" type="button" id="is_registered" name="is_registered" value="Registered" style="width: 100%"></td>    
        </tr>
           
    </tbody>
</table>

<script>
    // "global" vars, built using blade
    var imagesUrl = '{{ URL::asset('/image') }}';
    $('.datepicker1').datepicker({
        format: "mm/dd/yyyy",
        language: "en",
        autoclose: true,
        orientation: 'bottom left'
    });
</script>
<script src="{{ asset('js/syncTool.js') }}"></script>
@endsection