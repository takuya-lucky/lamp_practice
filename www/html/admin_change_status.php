<?php
// エラーがなければ、const.phpを読み込む
require_once '../conf/const.php';
// エラーがなければ、modelファイルの中のfunction.phpを読み込む
require_once MODEL_PATH . 'functions.php';
// エラーがなければ、modelファイルの中のuser.phpファイルを読み込む
require_once MODEL_PATH . 'user.php';
// エラーがなければ、modelファイルの中のitem.phpファイルを読み込む
require_once MODEL_PATH . 'item.php';

// セッションの開始
session_start();

// ログイン処理が行われてなければ、ログインページに移動する
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースへの接続の設定を変数$dbに代入
$db = get_db_connect();

// user_idをデータベースに接続して、判定しその値（user名）を$userに代入する
$user = get_login_user($db);

// ユーザーのタイプ（管理者か利用者）の設定ができていなれければログインページに移動する
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$item_id = get_post('item_id');
$changes_to = get_post('changes_to');

if($changes_to === 'open'){
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
}else if($changes_to === 'close'){
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
}else {
  set_error('不正なリクエストです。');
}


redirect_to(ADMIN_URL);