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

// history_idを変数に代入する
$history_id = get_get('history_id');
// 代入したhistory_idが入っているかの検証
if ($history_id === '') {
  set_error('不正なアクセスです。');
  redirect_to(HOME_URL);
} 

// 購入明細の読み込み
$details = get_purchase_details($db, $history_id);

// 購入明細を見るユーザーが明細に登録されているユーザーと合致しているかの確認
if ($details[0]['user_id'] !== $user['user_id'] && is_admin($user) === false) {
  set_error('不正なアクセスです。');
  redirect_to(HISTORY_URL);
}

// 合計金額を出す
$total_price = sum_purchased($details);

// エラーがあっても、view.phpを読み込む
include_once '../view/detail_view.php';