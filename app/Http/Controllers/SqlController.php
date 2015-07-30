<?php

namespace App\Http\Controllers;

use App\Services\SqlSelect;
use App\Sql;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SqlController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('sql.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $sql = $request->input('sqlStatement');

        Sql::firstOrCreate([
            'sql' => $sql
        ]);

        $sqlSelect = new SqlSelect($sql);
        $data = $sqlSelect->executeQuery();
        $format = $sqlSelect->parseSql();

        return view('sql.index',compact('data','format','sql'));
    }

    public function history()
    {
        $sqls = Sql::all();

        return view('sql.history', compact('sqls'));

    }
}
