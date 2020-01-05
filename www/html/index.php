<?php
// エラーがなければ、const.phpに接続する
require_once '../conf/const.php';
// エラーがなければ、functions.phpに接続する
require_once '../model/functions.php';
// エラーがなければ、user.phpに接続する
require_once '../model/user.php';
// エラーがなければ、item.phpに接続する
require_once '../model/item.php';

// セッションの開始
session_start();

// ログインが出来ていなければ、ログインページへ移動する
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続する設定を代入する
$db = get_db_connect();
// ユーザー情報をデータベースに接続して、$userに代入する
$user = get_login_user($db);

// 現在のページ数の取得
$now = get_now_page();

// 並び替えの要素を代入する
$sort_item = get_get('sort_item','new_item');

// 並び替えによる商品一覧の取得
$items = sort_items($db,true,$sort_item);

// 全公開商品の取得
$num_items = get_count_open_items($db);

// 全てのデータを表示するのに必要なページ数(商品数/ページに表示できる商品数)
$page_max = ceil($num_items / PAGE_VIEW_MAX);

// 表示のないページにアクセスの防止
if ($now > $page_max) {
  set_error('不正なアクセスです');
  redirect_to(HOME_URL);
}

// 商品の人気(売り上げ)ランキングの実装
$sale_ranking = sale_ranking($db);

// 現在のページの表示の最初を出力
$current_page_start_num = get_current_page_start_num();

// 現在のページの表示の最後を出力
$current_page_end_num = get_current_page_end_num($num_items);

// トークンの生成とセット
$token = get_csrf_token();

// エラーがなければ、index_view.phpファイルを読み込む
include_once '../view/index_view.php';