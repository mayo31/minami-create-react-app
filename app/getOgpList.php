<?php
/*
  OGPの情報を取得する
  取得対象URLの一覧はlist.jsonで管理
*/

define('DATA_PATH',    '../public/data/');	// データディレクトリ
require_once('./lib/opengraph-master/OpenGraph.php');

class getOgpList {

	public static function main() {

		$res = array();

		// 一覧に表示する記事の情報を取得する
		$list_json    = file_get_contents(DATA_PATH . 'list.json', false);
		$article_list = json_decode($list_json, true)['article_list'];
		foreach ($article_list as $key => $val) {
			$article_data = array();
			
			$graph = OpenGraph::fetch($val['url']);
			$article_data['articleTitle']       = $graph->title;
			$article_data['articleUrl']         = $val['url'];
			$article_data['articleDescription'] = $graph->title;
			$article_data['articleImage']       = $graph->image;
			
			$res[] = $article_data;
		}

    header( 'Content-Type: text/javascript; charset=utf-8' );
    echo json_encode($res);
		return;
	}
}

$obj = new getOgpList();
$obj->main();

?>
