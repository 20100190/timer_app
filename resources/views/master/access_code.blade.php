@extends('layouts.main')
@section("content")
<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#loader-bg').hide();
    });
</script>


<form method="POST" enctype="multipart/form-data" id="taskEnter" name="taskEnter" autocomplete="off" action="/master/save_access_code">
{{ csrf_field() }}
<input type="text" id="access_code" name="access_code" value="{{$access_code}}">
<input type="text" id="access_token" name="access_token" value="{{$token}}">

<input type="submit" value="aaa">
</form>
<script>
    var imageUrl = '{{ URL::asset('/image') }}';
</script>

@endsection