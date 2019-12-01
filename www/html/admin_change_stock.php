<?php
// エラーがなければ、const.phpファイルを読み込む
require_once '../conf/const.php';
// エラーがなければ、modelファイルの中のfunctions.phpファイルを読み込む
require_once MODEL_PATH . 'functions.php';
// エラーがなければ、modelファイルの中のuser.phpファイルを読み込む
require_once MODEL_PATH . 'user.php';
// エラーがなければ、modelファイルの中のitem.phpファイルを読み込む
require_once MODEL_PATH . 'item.php';

// セッションを開始する
session_start();

// ログインができていなければ、ログインページに移動する
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースへの接続の設定を変数$dbに代入する
$db = get_db_connect();

// データベースのユーザーデータテーブルを確認して、該当ユーザーがいればuser_idを読み込み、$userに代入する
$user = get_login_user($db);

// ユーザーのタイプが管理者でなければ、login.phpに移動する
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

// post送信で送信されたitem_idの値を変数$item_idにを代入する
$item_id = get_post('item_id');
// post送信で送信されたstockの値を変数$stockに代入する
$stock = get_post('stock');

// post送信されたstockが正の整数であるかを判定する
if(is_positive_integer($stock) === false) {
  set_error('在庫数は正の整数で入力を行ってください。');
  redirect_to(ADMIN_URL);
}

// 在庫数の変更が出来た場合・出来なかった場合のメッセージ処理及びエラー処理
if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');
} else {
  set_error('在庫数の変更に失敗しました。');
}

// 商品管理のページに戻る
redirect_to(ADMIN_URL);