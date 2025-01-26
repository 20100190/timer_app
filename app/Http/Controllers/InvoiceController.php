<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\InvoiceFile;
use App\Client;
use App\Project;
use App\Task;
use App\TaskName;
use App\User;
use App\UserTasks;
use Carbon\Carbon;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('invoices.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|int',
        'client_id' => 'required|int',
        'project_id' => 'required|int',
        'type' => 'required|string|max:255',
        'amount' => 'required|numeric',
        'billable' => 'required|boolean',
        'date' => 'required|date'
    ]);

    $invoice = Invoice::create($validated);

    // Log the files received from the request
    \Log::info($request->file('files'));
    
    $filesData = [];

    if ($request->hasFile('files')) {
        foreach ($request->file('files') as $file) {
            $path = $file->store('public/invoices'); // Storing the file in storage/app/public/invoices
            $fileRecord = $invoice->files()->create([
                'file_path' => $path,
                'file_type' => $file->getClientMimeType()
            ]);
            $filesData[] = [
                'path' => $path,
                'type' => $file->getClientMimeType()
            ];
        }
    }

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'invoice' => [
                'date' => $invoice->date,
                'type' => $invoice->type,
                'amount' => $invoice->amount,
                'billable' => $invoice->billable ? 'Yes' : 'No',
                'files' => $filesData
            ]
        ]);
    }

    return redirect()->route('invoice.index')->with('success', 'Invoice and files saved successfully!');
}
     
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
