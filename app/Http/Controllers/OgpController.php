<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class OgpController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Json返却お試し
     *
     * @return \Illuminate\Http\Response
     */

    public function getList()
    {
        // TODO:DB入れたら、DBからデータ取得するようにする
        $file_path = public_path() . '/data/article.json';
        $retData = file_get_contents($file_path);

        return json_decode($retData, true);
//        return response()->json(['name' => 'Abigail', 'state' => 'CA']);
    }
}
