<?php
// エラーがなければ、const.phpファイルを読み込む
require_once '../conf/const.php';
// エラーがなければ、modelにあるfunctions.phpファイルを読み込む
require_once MODEL_PATH . 'functions.php';
// エラーがなければ、modelにあるuser.phpファイルを読み込む
require_once MODEL_PATH . 'user.php';
// エラーがなければ、modelにあるitem.phpファイルを読み込む
require_once MODEL_PATH . 'item.php';
// エラーがなければ、modelにあるorder_history.phpファイルを読み込む
require_once MODEL_PATH . 'order_history.php';
// エラーがなければ、modelにあるorder_detail.phpファイルを読み込む
require_once MODEL_PATH . 'order_detail.php';

// セッションを開始する
session_start();

// ログインができていなければ、login.phpに移動する
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースへの接続の設定$dbに代入する
$db = get_db_connect();
// データベースに接続して、ユーザーデータを探して、代入する
$user = get_login_user($db);

// 現在のページ数の取得
$now = get_now_page();

// 購入履歴の読み込み
$histories = get_purchase_histories($db, $user,$now);

// 購入履歴数の取得
$num_histories = get_count_histories($db,$user);

// 全てのデータを表示するのに必要なページ数(注文履歴数数/ページに表示できる注文履歴)
$page_max = ceil($num_histories / PAGE_VIEW_MAX);
if ($now > $page_max) {
  set_error('不正なアクセスです');
  redirect_to(HISTORY_URL);
}
// 現在のページの表示の最初を出力
$front_select = get_front_select($num_histories);

// 現在のページの表示の最後を出力
$behind_select = get_behind_select($num_histories);

// エラーがあっても、view.phpを読み込む
include_once '../view/history_view.php';