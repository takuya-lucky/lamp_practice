<?php
// エラーがなければ、const.phpファイルを読み込む
require_once '../conf/const.php';
// エラーがなければ、modelにあるfunctions.phpファイルを読み込む
require_once MODEL_PATH . 'functions.php';
// エラーがなければ、modelにあるuser.phpファイルを読み込む
require_once MODEL_PATH . 'user.php';
// エラーがなければ、modelにあるitem.phpファイルを読み込む
require_once MODEL_PATH . 'item.php';

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

// 管理者でなければ、login.pnpに移動する
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

// 非公開商品を読み込みそれを$itemsに代入する
$items = get_all_items($db);

// トークンの生成とセット
$token = get_csrf_token();

// エラーがあっても、view.phpを読み込む
include_once '../view/admin_view.php';