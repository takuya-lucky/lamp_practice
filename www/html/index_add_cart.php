<?php
// エラーがなければ、const.phpに接続する
require_once '../conf/const.php';
// エラーがなければ、modelファイルのfunctions.phpに接続する
require_once MODEL_PATH . 'functions.php';
// エラーがなければ、modelファイルのuser.phpに接続する
require_once MODEL_PATH . 'user.php';
// エラーがなければ、modelファイルのitem.phpに接続する
require_once MODEL_PATH . 'item.php';
// エラーがなければ、modelファイルのcart.phpに接続する
require_once MODEL_PATH . 'cart.php';

// セッションを開始する
session_start();

// ログインができていなければ、ログインページへ移動する
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続する設定を$dbに代入する
$db = get_db_connect();
// ログインしているユーザー情報を$userにデータベースにあるテーブル情報から引き出して代入する
$user = get_login_user($db);

// post送信が行われる前にセッショントークンによる適正ユーザーの判定
validate_csrf_token();

// post送信でitem_idが送られてきたら、$item_idに代入する
$item_id = get_post('item_id');

// カート情報の更新を行い、実行できた場合と出来なかった場合にメッセージを表示する
if(add_cart($db,$user['user_id'], $item_id)){
  set_message('カートに商品を追加しました。');
} else {
  set_error('カートの更新に失敗しました。');
}

// index.phpに移動する
redirect_to(HOME_URL);