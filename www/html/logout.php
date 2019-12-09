<?php
// エラーがなければ、const.phpを読み込む
require_once '../conf/const.php';
// エラーがなければ、modelファイルのfunctions.phpを読み込む
require_once MODEL_PATH . 'functions.php';

// セッションを開始する
session_start();
// $_SESSIONに空の配列を代入する
$_SESSION = array();

// セッションクッキーの情報を$paramsに代入する
$params = session_get_cookie_params();
// cookieの削除
setcookie(session_name(), '', time() - 42000,
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);
session_destroy();

// ログインページへ移動する
redirect_to(LOGIN_URL);