<?php
// エラーがなければ、const.phpを読み込む
require_once '../conf/const.php';
// エラーがなければ、modelファイルにあるfunctions.phpを読み込む
require_once MODEL_PATH . 'functions.php';
// エラーがなければ、modelファイルにあるuser.phpを読み込む
require_once MODEL_PATH . 'user.php';
// エラーがなければ、modelファイルにあるitem.phpを読み込む
require_once MODEL_PATH . 'item.php';

// セッションを開始する
session_start();

// ログインができていなければ、login.phpに移動する
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースへの接続の設定を変数$dbに代入する
$db = get_db_connect();

// データベースに接続して、ユーザーネームを代入する
$user = get_login_user($db);

// 管理者でなければ、login.phpに移動する
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// post送信で送られたnameを変数$nameに代入する
$name = get_post('name');
// post送信で送られたpriceを変数$priceに代入する
$price = get_post('price');
// post送信で送られたstatusを変数$statusに代入する
$status = get_post('status');
// post送信で送られたstockを変数$stockに代入する
$stock = get_post('stock');
// post送信されたstockが正の整数であるかを判定する
if(is_positive_integer($stock) === false) {
  set_error('在庫数は正の整数で入力を行ってください。');
  redirect_to(ADMIN_URL);
}

// post送信で送られたimageを変数$imageに代入する
$image = get_file('image');

// 商品登録に成功したときに、メッセージを出し、失敗したときにエラーを出す。
if(regist_item($db, $name, $price, $stock, $status, $image)){
  set_message('商品を登録しました。');
}else {
  set_error('商品の登録に失敗しました。');
}

// 商品管理ページに移動する
redirect_to(ADMIN_URL);