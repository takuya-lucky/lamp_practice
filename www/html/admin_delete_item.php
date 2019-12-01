<?php
// エラーがなければ、const.phpを読み込む
require_once '../conf/const.php';
// エラーがなければ、modelファイルの中のfunctions.phpファイルを読み込む
require_once MODEL_PATH . 'functions.php';
// エラーがなければ、modelファイルの中のuser.phpファイルを読み込む
require_once MODEL_PATH . 'user.php';
// エラーがなければ、modelファイルの中のitem.phpファイルを読み込む
require_once MODEL_PATH . 'item.php';

// セッションを開始する
session_start();

// ログインができていなければ、login.phpに移動する
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続する設定を変数$dbに代入する
$db = get_db_connect();

// データベースに接続し、userテーブルにあるuserデータに合致するユーザーネームを$userに代入する
$user = get_login_user($db);

// 管理者でなければ、login.phpに移動する
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

// post送信で送られたらitem_idを変数$item_idに代入する
$item_id = get_post('item_id');

// item_idが消去したい商品と合致していればその商品を削除するメッセージを出す。違う場合はエラーを出す。
if(destroy_item($db, $item_id) === true){
  set_message('商品を削除しました。');
} else {
  set_error('商品削除に失敗しました。');
}


// 商品管理ページに移動する
redirect_to(ADMIN_URL);