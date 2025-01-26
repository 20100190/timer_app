<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\InvoiceFile;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store(Request $request) {
        $record = Invoice::create($request->only(['date', 'client', 'project', 'type', 'amount', 'billable']));

        if($request->hasfile('files')) {
            foreach($request->file('files') as $file) {
                $path = $file->store('public/files');
                $fileType = $file->getClientOriginalExtension() === 'pdf' ? 'pdf' : 'image';
                
                InvoiceFile::create([
                    'invoice_id' => $record->id,
                    'file_path' => $path,
                    'file_type' => $fileType
                ]);
            }
        }

        return back()->with('success', 'Data has been uploaded.');
    }
}
