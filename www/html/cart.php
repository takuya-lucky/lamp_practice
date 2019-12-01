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

// データベースの接続の設定を$dbに代入する
$db = get_db_connect();
// データベースに接続して、ユーザーネームを探して、代入する
$user = get_login_user($db);

// 特定のユーザーがカートに入れた商品を$cartsに代入する
$carts = get_user_carts($db, $user['user_id']);

// $cartsの中の合計金額を代入する
$total_price = sum_carts($carts);

// エラーが起きてもview.phpを読み込む
include_once '../view/cart_view.php';