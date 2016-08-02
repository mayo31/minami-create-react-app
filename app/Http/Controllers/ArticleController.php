<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\Article;
use App\Comment;
use DB;
use Log;
use File;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // タグの指定がある場合は、タグの条件を付与する
        $article = Article::select('article.id',
            'article.title',
            'article.url',
            'article.description',
            'article.image',
            'article.user_id',
            'users.id as user_id',
            'users.name')
            ->join('users', 'users.id', '=', 'article.user_id')
            ->orderBy('article.id', 'desc');
        if (isset($request['tag']) && !empty($request['tag'])) {
            $data = $article->join('tag_mapping', 'tag_mapping.article_id', '=', 'article.id')
                ->where('tag_mapping.tag_id', '=', $request['tag'])
                ->get();
        } else {
            $data = $article->get();
        }

        Log::info(print_r($data, true));

        $res = array();
        foreach ($data as $val) {
            // 記事のタグ取得
            $tag_info = DB::table('tag')
                ->join('tag_mapping', 'tag.id', '=', 'tag_mapping.tag_id')
                ->where('tag_mapping.article_id', '=', $val->id)
                ->get();

            $tag_array = array();
            foreach ($tag_info as $tag) {
                $tmp_tag = array('name' => $tag->name,
                                  'id'   => $tag->tag_id);
//                'tagUrl' => '/tag/' . $tag->tag_id);
                $tag_array[] = $tmp_tag;
            }

            // 記事のコメント取得
            $comment_info = Comment::select('comment.comment',
                'comment.id',
                'comment.user_id',
                'comment.created_at',
                'users.name')
                ->join('users', 'users.id', '=', 'comment.user_id')
                ->where('comment.article_id', '=', $val->id)
                ->get();

            $comment_array = array();
            foreach ($comment_info as $comment) {

                $iconName = $comment->user_id . '.png';
                
                // ユーザーアイコンの判定…画像がなければデフォルトのアイコン画像を返す
                if (!File::isFile('../public/images/account/' . $iconName)) {
                    $iconName = 'default.png';
                }

                $tmp_comment = array('author'      => $comment->name,
                                     'authorImage' => '/images/account/' . $iconName,
                                     'id'          => $comment->id,
                                     'comment'     => $comment->comment,
                                     'date'        => date('Y/m/d H:i', strtotime($comment->created_at)));
                $comment_array[] = $tmp_comment;
            }

            $tmp = array('articleID'           => $val->id,
                          'articleTitle'       => $val->title,
                          'articleUrl'         => $val->url,
                          'articleDescription' => $val->description,
                          'articleImage'       => $val->image,
                          'userId'             => $val->user_id,
                          'userName'           => $val->name,
                          'userIcon'           => '/images/account/' . $val->user_id . '.png',
                          'tagData'            => $tag_array,
                          'commentData'        => $comment_array);
            $res[] = $tmp;
        }

        return response()->json($res);
//        return 'DB data = ' . print_r($data);
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

        // 記事のURLを受け取って情報を取得
        $url  = 'http://api.hitonobetsu.com/ogp/analysis?url=' . $request['url'];

        $data = file_get_contents($url);

        $data = (array)json_decode($data);

        $param = array();
        $param['title']       = isset($data['title']) ? $data['title'] : '';
        $param['url']         = isset($data['url']) ? $data['url'] : '';
        $param['description'] = isset($data['description']) ? $data['description'] : '';
        $param['image']       = isset($data['image']) ? $data['image'] : '/images/noimage.png';
        $param['user_id']     = $user_id;
        Log::info(print_r($param, true));

        $insert_res = Article::create(
            $param
        );
        $article_id = $insert_res->id;

        if (!isset($request['tag']) || empty($request['tag'])) {
            return 'OK article id = ' . $article_id;
        }

        foreach($request['tag'] as $tag){
            // 既に登録されているタグかどうかをチェック
            $tag_info = DB::table('tag')
                ->where('name' ,'=', $tag)
                ->first();
            Log::info(print_r($tag_info, true));

            if (empty($tag_info)) {
                $tag_id = DB::table('tag')->insertGetId(
                    array('name' => mb_convert_encoding($tag, "UTF-8"))
                );
            } else {
                $tag_id = $tag_info->id;
                Log::info(print_r($tag_info, true));
            }

            DB::table('tag_mapping')->insert(
                array('article_id' => (int)$article_id, 'tag_id' => (int)$tag_id)
            );
        }
        return 'OK article id = ' . $article_id;
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
        // ユーザー情報（ログインしていない場合は認証エラー）
        if (Auth::guest()) {
            return '401';
        }
        // 認証チェック（自分が投稿したもののみ削除可能）
        $article = Article::where('user_id', Auth::user()->id)
                ->where('id', $id)
                ->get();
        if (count($article) === 0) {
            return '404';
        }

        Article::where('id', $id)->delete();
        DB::table('tag_mapping')->where('article_id', $id)->delete();
        
        return $id . ' is deleted.';
    }
}
