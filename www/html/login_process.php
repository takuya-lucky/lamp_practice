<?php
// エラーがなければ、const.phpに接続する
require_once '../conf/const.php';
// エラーがなければ、modelファイルのfunctions.phpに接続する
require_once MODEL_PATH . 'functions.php';
// エラーがなければ、modelファイルのuser.phpに接続する
require_once MODEL_PATH . 'user.php';

// セッションを開始する
session_start();

// ログインができていなければ、ログインページへ移動する
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// post送信が行われる前にセッショントークンによる適正ユーザーの判定
validate_csrf_token();

// post送信で送られてきた'name'を$nameに代入する
$name = get_post('name');

// post送信で送られてきた'password'を$passwordに代入する
$password = get_post('password');

// データベースに接続する設定を$dbに代入する
$db = get_db_connect();

// $userにユーザー情報を代入する
$user = login_as($db, $name, $password);
// ユーザー情報が取れなかったときにエラーを出す

if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

// ログインしたというメッセージを出す
set_message('ログインしました。');

// ユーザー情報が管理者の場合はadmin.php、通常のユーザーの場合にはindex.phpに移動する
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
redirect_to(HOME_URL);