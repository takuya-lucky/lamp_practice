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

// データベースに接続する設定を$dbに代入する
$db = get_db_connect();
// データベースに接続して、ユーザーネームを探して、$userに代入する
$user = get_login_user($db);

// post送信が行われる前にセッショントークンによる適正ユーザーの判定
validate_csrf_token();

// post送信で送られたcart_idを$cart_idに代入する
$cart_id = get_post('cart_id');
// poar送信で送られたamountを$amountに代入する
$amount = get_post('amount');

// カートの中身の更新の際のメッセージの表示とエラーの表示
if(update_cart_amount($db, $cart_id, $amount)){
  set_message('購入数を更新しました。');
} else {
  set_error('購入数の更新に失敗しました。');
}

// cart.phpに移動する
redirect_to(CART_URL);