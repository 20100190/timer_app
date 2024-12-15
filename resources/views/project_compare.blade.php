@extends('layouts.main')
@section('content') 

<div style="margin-left: 20px">
    <input type="button" value="get" onclick="getData()">
</div>
   
<script>
    // "global" vars, built using blade
    var imagesUrl = "{{ URL::asset('/image')}}";

    $(document).ready(function () {
        jQuery('#loader-bg').hide();
      
    });

    function getData(){
        $.ajax({
            url: "/project-compare/getdata",    
            dataType: "json",
            success: data => {
            }     
        });
    }
</script>
<!--<script src="{{ asset('js/phaseEntry.js') }}"></script>-->
@endsection