<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\User;
use DB;
use Log;
use File;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // ユーザー情報
        if (isset($request['id']) && !empty($request['id'])) {
            $data = User::where('id', '=', $request['id'])
                ->first();
        } else {
            return '404';
        }
        if (count($data) === 0) {
            return '404';
        }
        
        $iconName = $data->id . '.png';
        
        // ユーザーアイコンの判定…画像がなければデフォルトのアイコン画像を返す
        if (!File::isFile('../public/images/account/' . $iconName)) {
            $iconName = 'default.png';
        }
        
        $res = array('userId'             => $data->id,
                     'userName'           => $data->name,
                     'userIcon'           => '/images/account/' . $iconName);

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
        $called = app()->make('App\\Http\\Controllers\\Auth\\AuthController');
        $user   = $called->create($request->toArray());
        return $user;
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
