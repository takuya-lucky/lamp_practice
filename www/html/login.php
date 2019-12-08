<?php
// エラーがなければ、const.phpを読み込む
require_once '../conf/const.php';
// エラーがなければ、modelファイルのfunction.phpを読み込む
require_once MODEL_PATH . 'functions.php';

// セッションを開始する
session_start();

// ログインが出来ていれば、index.phpに移動する
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// トークンの生成とセット
$token = get_csrf_token();

// エラーがあっても、login_view.phpに接続する
include_once '../view/login_view.php';