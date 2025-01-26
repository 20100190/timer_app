@extends('layouts.app')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>
    .form-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }
    .form-group {
        flex: 1;
        padding: 0 10px;
    }
    .form-group:first-child, .form-group:last-child {
        padding-left: 0;
        padding-right: 0;
    }
    label {
        display: block;
        margin-bottom: 5px;
    }
    input[type="date"], input[type="number"], input[type="text"], select, input[type="file"] {
        width: 100%;
    }
    .container {
        max-width: 960px;
        margin: auto;
    }
    button.btn {
        width: auto;
    }

    .file-preview-item {
    margin: 10px;
    display: inline-block;
    text-align: center;
    }

    .file-preview-item img {
        max-width: 100px;
        max-height: 100px;
        display: block;
        margin: 0 auto;
    }

</style>

@section('content')

<div class="container">
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
        </div>

        <!-- Form row for Type, Amount, and Billable -->
        <div class="form-row">
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

                <!-- Add JS for drag & drop functionality -->
            </div>
            <!-- Hidden field for User ID -->
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>

    <!-- Preview section -->
    <div id="file-preview" class="form-row">
        <!-- Previews will be shown here -->
    </div>
</div>

<script src="{{ asset('js/invoiceFormHadeling.js') }}"></script>

@endsection