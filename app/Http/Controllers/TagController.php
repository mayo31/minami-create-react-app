<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use DB;
use Request;

class TagController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function index()
    {
        $data = DB::table('tag')
            ->orderBy('id', 'desc')
            ->get();

        $res = array();
        foreach ($data as $item) {
            $tmp['id']   = $item->id;
            $tmp['name'] = $item->name;
            $tmp['url']  = '/tag/' . $item->id;

            $res[] = $tmp;
        }
        return response()->json($res);
    }

    public function show()
    {
        return 'tag show';
    }

    public function store()
    {
        $input = Request::only('tag_name', 'article_id');
        return 'tag store' . $input['tag_name'];
    }
    public function update($val)
    {
        return $val . ' tag update';
    }

}
