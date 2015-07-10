<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;

class BulkController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('bulk.index');
    }

    public function store(Request $request)
    {
        $bulk = new Bulk;

        $file = $request->file('file');
        $fileName = $request->input('file_name');
        $fileName = $fileName ?: $file->getClientOriginalName();

        $bulk->file_name = $fileName;

        if (!$file->getClientMimeType() == "text/csv" || !$file->getClientOriginalExtension() == "csv")
        {
            $bulk->result = "Invalid File Type";
            $bulk->mime_type = $file->getClientMimeType();
            $bulk->file_extension = $file->getClientOriginalExtension();
            $bulk->save();

            Flash::error('File type invalid.  Please use a CSV file format.');
            return redirect()->back();
        }

        $bulk->process_id = $fileName . '-' . Carbon::now()->timestamp;

        $file->move(storage_path() . '/uploaded_files/',$fileName);
        Flash::success("File loaded successfully!  Check the Bulk Process table for progress on $bulk->process_id.");
        return redirect()->back();

    }
}
