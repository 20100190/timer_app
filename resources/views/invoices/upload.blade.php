<form action="{{ route('upload.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    Date: <input type="date" name="date"><br>
    Client: <input type="text" name="client"><br>
    Project: <input type="text" name="project"><br>
    Type: <input type="text" name="type"><br>
    Amount: <input type="text" name="amount"><br>
    Billable: <input type="checkbox" name="billable" value="1"><br>
    Attach Files:
    <input type="file" name="files[]" multiple><br>
    <input type="submit" value="Upload">
</form>
