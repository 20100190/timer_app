@extends('layouts.app') <!-- assuming you have a main layout file -->

@section('content')
<div class="container mt-4">
    <h2>Expenses</h2>
    <button class="btn btn-primary mb-3">+ New Expense</button>

    <form action="{{ route('invoices.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-row">
            <div class="col-md-3 mb-3">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="col-md-3 mb-3">
                <label for="client_id">Client</label>
                <input type="text" class="form-control" id="client_id" name="client_id" required>
            </div>
            <div class="col-md-3 mb-3">
                <label for="project_id">Project</label>
                <input type="text" class="form-control" id="project_id" name="project_id" required>
            </div>
            <div class="col-md-3 mb-3">
                <label for="type">Type</label>
                <select class="form-control" id="type" name="type">
                    <option value="Meals">Meals</option>
                    <option value="Travel">Travel</option>
                    <option value="Supplies">Supplies</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label for="amount">Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="col-md-2 mb-3">
                <label for="billable">Billable</label>
                <select class="form-control" id="billable" name="billable">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="receipt">Receipt</label>
                <input type="file" class="form-control-file" id="receipt" name="receipt" multiple>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>

    <h4 class="mt-5">Unattached Receipts</h4>
    <div>
        <!-- Placeholder for drag & drop or file upload section -->
        <input type="file" class="form-control-file" multiple>
    </div>
</div>
@endsection