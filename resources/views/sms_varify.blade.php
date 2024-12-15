@extends('layouts.main')
@section("content")
<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#loader-bg').hide();
    });
</script>


<form method="POST" enctype="multipart/form-data" id="taskEnter" name="taskEnter" autocomplete="off" action="/sms_varify">
    {{ csrf_field() }}
    <input type="text" id="email" name="email" value="">
    <input type="button" id="send_code" name="send_code" value="send code">

    <input type="submit" value="aaa">
</form>
<script>
    var imageUrl = '{{ URL::asset('/image') }}';
</script>

@endsection