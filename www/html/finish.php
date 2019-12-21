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
// エラーがなければ、modelにあるorder_history.phpファイルを読み込む
require_once MODEL_PATH . 'order_history.php';
// エラーがなければ、modelにあるorder_detail.phpファイルを読み込む
require_once MODEL_PATH . 'order_detail.php';

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

// トランザクションの開始
$db->beginTransaction();

// 購入履歴の保存
$make_history = make_purchase_history($db, $user['user_id']);
// history_idをorder_historyから取得する
$history_id = $db->lastInsertID('history_id');
// 購入明細の保存
$make_detail =  make_purchase_detail($db, $history_id, $carts); 

// 商品購入の処理を行い、エラーがあれば、cart.phpに移動する。購入履歴の保存・購入明細の保存の処理で、成功しているときのみcommitを行う。失敗の場合はエラーメッセージの挿入。
if(purchase_carts($db, $carts) === false && $make_history === false && $make_detail === false){
  $db->rollBack();
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} else {
  $db->commit();
}

// カート内の商品の値段の総合計を$total_priceに代入する
$total_price = sum_carts($carts);

// エラーがあってもfinish_view.phpを読み込む
include_once '../view/finish_view.php';