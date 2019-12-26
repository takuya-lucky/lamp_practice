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

// 公開商品の読み込み
$items = get_open_items($db);

// 現在のページ数の取得
$now = get_now_page();

// 商品の取得
$num_items = get_count_items($db);

// 全てのデータを表示するのに必要なページ数(商品数/ページに表示できる商品数)
$page_max = ceil($num_items / PAGE_VIEW_MAX);

// 表示のないページにアクセスの防止
if ($now > $page_max) {
  set_error('不正なアクセスです');
  redirect_to(HOME_URL);
}

// 現在のページの表示の最初を出力
$front_select = get_front_select();

// 現在のページの表示の最後を出力
$behind_select = get_behind_select($num_items);

// トークンの生成とセット
$token = get_csrf_token();

// エラーがなければ、index_view.phpファイルを読み込む
include_once '../view/index_view.php';