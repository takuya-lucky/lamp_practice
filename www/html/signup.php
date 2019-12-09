<?php
// エラーがなければ、const.phpを読み込む
require_once '../conf/const.php';
// エラーがなければ、modelファイルのfunctions.phpを読み込む
require_once MODEL_PATH . 'functions.php';

// セッションを開始する
session_start();

// ログインができていれば、index.phpに行く
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// トークンの生成とセット
$token = get_csrf_token();

// エラーがあっても、signup_viwe.phpを読み込む
include_once '../view/signup_view.php';