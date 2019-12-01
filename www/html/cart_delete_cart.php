<?php
// エラーがなければ、const.phpファイルを読み込む
require_once '../conf/const.php';
// エラーがなければ、modelにあるfunctions.phpファイルを読み込む
require_once MODEL_PATH . 'functions.php';
// エラーがなければ、modelにあるuser.phpファイルを読み込む
require_once MODEL_PATH . 'user.php';
// エラーがなければ、modelにあるitem.phpファイルを読み込む
require_once MODEL_PATH . 'item.php';
// エラーがなければ、modelにあるcart.phpファイルを読み込む
require_once MODEL_PATH . 'cart.php';

// セッションを開始する
session_start();

// ログインができていなければ、login.phpに移動する
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベース接続の設定を$dbに代入する
$db = get_db_connect();
// データベースに接続して、ユーザーネームを探して、代入する
$user = get_login_user($db);
// post送信で送られたcart_idを$cart_idに代入する
$cart_id = get_post('cart_id');

// カートの中身を削除したときのメッセージと失敗したときのエラーメッセージの表示
if(delete_cart($db, $cart_id)){
  set_message('カートを削除しました。');
} else {
  set_error('カートの削除に失敗しました。');
}

// cart.phpに移動する
redirect_to(CART_URL);