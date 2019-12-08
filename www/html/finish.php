<?php
// エラーがなれればconst.phpに接続する
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

// ログインが出来ていなければ、ログインページへ移動する
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続する為の情報を$dbに代入する
$db = get_db_connect();
// データベースを参照して、userテーブルに登録されているuser情報を$userに代入する(user_idに合致するものを)
$user = get_login_user($db);

// $userの中のuser_idを基にそのユーザーのカート情報を$cartsに代入する
$carts = get_user_carts($db, $user['user_id']);

// 商品購入の処理を行い、エラーがあれば、cart.phpに移動する
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 

// カート内の商品の値段の総合計を$total_priceに代入する
$total_price = sum_carts($carts);

// エラーがあってもfinish_view.phpを読み込む
include_once '../view/finish_view.php';