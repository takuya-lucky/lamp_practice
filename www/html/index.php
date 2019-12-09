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

// 公開商品を$itemsの中に代入する
$items = get_open_items($db);

// トークンの生成とセット
$token = get_csrf_token();

// エラーがなければ、index_view.phpファイルを読み込む
include_once '../view/index_view.php';