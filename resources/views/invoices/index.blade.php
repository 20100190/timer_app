@extends('layouts.app')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>
    .form-row {
    display: flex;  /* Sets the display to flex to align children in a row */
    justify-content: space-between;  /* Distributes space between the children */
    align-items: center;  /* Vertically aligns the children in the middle */
    width: 100%;  /* Takes the full width */
    height: auto;  /* Takes the height of the content */
}

    .form-group {
    flex: 1;  /* Allows each group to grow and take equal space */
    padding: 0 2px;  /* Adds padding on both sides */
    margin: 0 1px;  /* Optional: Adds margin for spacing between elements */
    min-height: 50px;  /* Minimum height applied to all form groups */
}

    input, select {
    width: 100%;  /* Forces elements to expand and fill their parent container */
    padding: 8px;  /* Adds padding inside inputs for better text visibility */
    margin: 4px 0;  /* Optional: Adds margin around inputs */
}

    button.btn {
        width: auto;
    }

    #file-preview {
    display: none;  /* Establishes a flex container */
    flex-wrap: nowrap;  /* Prevents flex items from wrapping, adjust as needed */
    align-items: flex-start;  /* Aligns items to the start of the flex line */
    justify-content: flex-start;  /* Aligns items to the start of the main axis */
    overflow-x: auto;  /* Allows horizontal scrolling if elements exceed container width */
    border: 1px solid #ccc;
    padding: 10px;
    margin: 10px 0;
}

.file-preview-item {
    flex: 0 1 auto;  /* Do not grow, can shrink, and basis is the size of content */
    margin-left: 20px;  /* Adds some space between items */
}

.file-preview-item img {
    max-width: 200px;  /* Limits image width */
    max-height: 100px;  /* Limits image height */
    width: auto;  /* Maintains aspect ratio */
    height: auto;  /* Maintains aspect ratio */
}

#invoice-form {
    display: none;
}

</style>

@section('content')

<div class="container">
    <button id="invoicetoggleFormButton" class="btn btn-primary">+ New Expense</button>
    <form class="invoice-form" id="invoice-form" action="{{ route('invoice.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Form row for Date, Client, and Project -->
        <div class="form-row">
            <div class="form-group">
                <label>Date:</label>
                <input id="date" type="date" name="date" required>
            </div>
            <div class="form-group">
                <label>Client:</label>
                <select id="clientSelect" name="client_id" required>
                    <option value="">Select a Client</option>
                    <!-- Populate with clients -->
                </select>
            </div>
            <div class="form-group">
                <label>Project:</label>
                <select id="projectSelect" name="project_id" required>
                    <option value="">Select a Project</option>
                    <!-- Populate with projects -->
                </select>
            </div>
            <div class="form-group">
                <label>Type:</label>
                <input type="text" name="type" required maxlength="255">
            </div>
            <div class="form-group">
                <label>Amount:</label>
                <input type="number" name="amount" required step="0.01">
            </div>
            <div class="form-group">
                <label>Billable:</label>
                <select name="billable" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

        </div>

        <!-- Form row for Receipt Upload -->
        <div class="form-row">
            <div class="form-group">
                <label>Receipt:</label>
                <input type="file" id="files" name="files[]" multiple required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            <!-- Hidden field for User ID -->
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        </div>
    </form>

     <!-- Drag & Drop File Upload Area 
     <div class="drag-drop-area">
            <input type="file" id="files" name="files[]" multiple required style="display: none;">
            <label for="files" style="cursor: pointer;">Drag files here or click to upload</label>
    </div>
    -->

    <!-- Preview section -->
    <div id="file-preview" class="form-row">
        <!-- Previews will be shown here -->
    </div>


    <table id="invoicesTableContainer" border="1" style="width:100%; margin-top: 20px;">
        <thead>
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th>Project</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Billable</th>
                <th>Files</th>
                <th>Reported</th>
                <th>Invoiced</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->date }}</td>
                    <td>{{ $invoice->client->name }}</td>
                    <td>{{ $invoice->project->project_name }}</td>
                    <td>{{ $invoice->type }}</td>
                    <td>{{ $invoice->amount }}</td>
                    <td>{{ $invoice->billable ? 'Yes' : 'No' }}</td>
                    <td>
                        @foreach ($invoice->files as $file)
                            <a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ $file->file_path }}</a><br>
                        @endforeach
                    </td>
                    <td>{{ $invoice->reported ? 'Yes' : 'No' }}</td>
                    <td>{{ $invoice->invoiced ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>




</div>

<script src="{{ asset('js/invoiceFormHadeling.js') }}"></script>

@endsection