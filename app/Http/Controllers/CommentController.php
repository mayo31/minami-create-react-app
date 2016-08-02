<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\Comment;
use DB;
use Log;
use File;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // コメント
        if (isset($request['article_id']) && !empty($request['article_id'])) {
            $data = Comment::where('article_id', '=', $request['article_id'])
                ->get();
        } elseif (isset($request['user_id']) && !empty($request['user_id'])) {
            $data = Comment::where('user_id', '=', $request['user_id'])
                ->get();
        } else {
            return '400';
        }
        if (count($data) === 0) {
            return 'no data';
        }
        
        $res = array();
        foreach ($data as $val) {
            $tmp = array('articleId' => $val->article_id,
                         'userId'    => $val->user_id,
                         'comment'   => $val->comment);
            $res[] = $tmp;
        }

        return response()->json($res);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return 'create';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ユーザー情報（ログインしていない場合は認証エラー）
        if (Auth::guest()) {
            return '401';
        }
        $user_id = Auth::user()->id;

        $param = array();
        
        $param['comment']    = isset($request['comment']) ? $request['comment'] : '';
        $param['article_id'] = isset($request['article_id']) ? $request['article_id'] : '';
        $param['user_id']    = $user_id;
        Log::info(print_r($param, true));

        $insert_res = Comment::create(
            $param
        );

        if (!isset($insert_res->id) || empty($insert_res->id)) {
            return 'NG comment failed ';
        }

        return 'OK comment_id = ' . $insert_res->id;
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
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
