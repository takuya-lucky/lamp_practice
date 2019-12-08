<?php
// エラーがなければ、const.phpを読み込む
require_once '../conf/const.php';
// エラーがなければ、modelファイルのfunctions.phpを読み込む
require_once MODEL_PATH . 'functions.php';
// エラーがなければ、modelファイルのuser.phpを読み込む
require_once MODEL_PATH . 'user.php';

// セッションを開始する
session_start();

// ログインが行われていれば、index.phpに移動する
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// post送信が行われる前にセッショントークンによる適正ユーザーの判定
validate_csrf_token();

// post送信で送られてきた'name'を$nameに代入する
$name = get_post('name');
// post送信で送られてきた'password'を$passwordに代入する
$password = get_post('password');
// post送信で送られてきた'password_confirmation'を$password_confirmationに代入する
$password_confirmation = get_post('password_confirmation');

// データベースに接続する設定を$dbに代入する
$db = get_db_connect();

// try・catch文を使いデータベースのエラーに対応する
try{
  // $resultにユーザー情報の登録情報を入れる
  $result = regist_user($db, $name, $password, $password_confirmation);
  // $resultがfalseなら、ユーザー登録はできず、signup.phpに移動する
  if( $result=== false){
    set_error('ユーザー登録に失敗しました。');
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  set_error('ユーザー登録に失敗しました。');
  redirect_to(SIGNUP_URL);
}
// ユーザー登録完了のメッセージを表示する
set_message('ユーザー登録が完了しました。');

// $userにユーザー情報を代入する
login_as($db, $name, $password);

// index.phpに移動する
redirect_to(HOME_URL);