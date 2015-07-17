<?php

namespace App\Http\Controllers;

use App\Bulk;
use App\Jobs\EraseTrustList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Keboola\Csv\CsvFile;

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
        $bulks = Bulk::all();

        return view('bulk.index', compact('bulks'));
    }

    public function create()
    {
        return view('bulk.create');
    }

    public function store(Request $request)
    {

        $file = $request->file('file');
        $fileName = $request->input('file_name');
        $fileName = $fileName ?: $file->getClientOriginalName();

        $bulk = Bulk::create([
            'file_name' => $fileName
        ]);

        if ($file->getClientMimeType() != "text/csv" && $file->getClientOriginalExtension() != "csv")
        {
            $bulk->result = "Invalid File Type";
            $bulk->mime_type = $file->getClientMimeType();
            $bulk->file_extension = $file->getClientOriginalExtension();
            $bulk->save();

            Flash::error('File type invalid.  Please use a CSV file format.');
            return redirect()->back();
        }

        $csvFile = new CsvFile($file);

        foreach($csvFile as $row)
        {
            $indexArray[] = $row;
        }

        for($i=0;$i<count($indexArray);$i++)
        {
            $eraserArray[$i]['MAC'] = $indexArray[$i][0];
            $eraserArray[$i]['TLE'] = $indexArray[$i][1];
            $eraserArray[$i]['BULK_ID'] = $bulk->id;
        }

        $this->dispatch(
            new EraseTrustList($eraserArray)
        );

        $bulk->result = "Processed";
        $bulk->mime_type = $file->getClientMimeType();
        $bulk->file_extension = $file->getClientOriginalExtension();
        $bulk->process_id = $fileName . '-' . Carbon::now()->timestamp;
        $bulk->save();

        $file->move(storage_path() . '/uploaded_files/',$fileName);

        Flash::success("File loaded successfully!  Check the Bulk Process table for progress on $bulk->process_id.");

        $bulks = Bulk::all();
        return view('bulk.index', compact('bulks'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Bulk $bulk)
    {
        return view('bulk.show', compact('bulk'));
    }
}
