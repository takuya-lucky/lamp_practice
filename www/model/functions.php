<?php
// 変数等の中身を確認する
function dd($var){
  var_dump($var);
  exit();
}

// $urlにあるページのURLに移動する
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

// $_getに中身が入っているかの確認を行う。返り値・戻り値は送信したデータか空。
function get_get($name, $default = ''){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return $default;
}

// $_postに中身が入っているかの確認を行う。返り値・戻り値は送信したデータか空。
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}

// $_FILESに中身が入っているかの確認を行う。返り値・戻り値は送信したデータか空。
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}

// $_SESSIONに中身が入っているかの確認を行う。返り値・戻り値は送信したデータか空。
function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}

// $_SESSIONにある変数を代入する。返り値は$_SESSINO['name']
function set_session($name, $value){
  $_SESSION[$name] = $value;
}

// $_SESSIONに$errorを代入する。返り値は$_SESSION['_error']
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

// $_SESSIONに_errorsが入っているかの確認を行い。なければ、配列を返り値・戻り値とし、その後、$_SESSIONに空の配列を入れる。返り値・戻り値は$errors
function get_errors(){
  $errors = get_session('__errors');
  if($errors === ''){
    return array();
  }
  set_session('__errors',  array());
  return $errors;
}

// $_SESSIONにエラーが入っているかの確認を行う。返り値・戻り値はtureまたはfalse
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== COUNT_CHECK_NUMBER;
}

// $_SESSIONに$messageを代入する。返り値は$_SESSION['_message']
function set_message($message){
  $_SESSION['__messages'][] = $message;
}

// $_SESSIONに_messageが入っているかの確認を行い。なければ、配列を返り値・戻り値とし、その後、$_SESSIONに空の配列を入れる。返り値・戻り値は$message
function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}

// $_SESSIONにuser_id入っているかの判定を行う。返り値・戻り値はtrueまたはfalse
function is_logined(){
  return get_session('user_id') !== '';
}

// アップロードするファイルの名前を得る。返り値・戻り値はランダムな文字列+ファイルの形式
function get_upload_filename($file){
  if(is_valid_upload_image($file) === false){
    return '';
  }
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}

// hashでuniqidで生成した13文字の値を不可逆性の値に変換をして、base_convartで中身の基数を16進数から36進数に変換をして、substrで0番目（一番最初）から20番目（$lengthで指定）を取り出す
function get_random_string($length = TOKEN_LENGTH){
  return substr(base_convert(hash('sha256', uniqid()), BASE_16, BASE_32), GET_TOKEN_START_NUMBER, $length);
}

// アップロードした画像の保存先の指定
function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

// アップロードした画像を削除する
function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}

// 文字（数字）の長さの指定。
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

// 正規表現による検証。英数字一文字以上の入力。
function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

// 正規表現による検証。正の整数1以上もしくは0を入力する。
function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

// 正規表現によるマッチング。あっているかどうかを検証。正しい場合は1を返す。
function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}

// アップロードする画像の判定。ファイルの拡張子が正しいかどうか判定する。
function is_valid_upload_image($image){
  if(is_uploaded_file($image['tmp_name']) === false){
    set_error('ファイル形式が不正です。');
    return false;
  }
  $mimetype = exif_imagetype($image['tmp_name']);
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}

// htmlにおける特殊文字をエスケープするためのユーザー定義関数
function h ($string) {
  return htmlspecialchars($string, ENT_QUOTES, 'utf-8');
}

//トークンを作る処理。get_random_stringから48の文字・数字の不可逆性の値を取得して、セッションに保存する
function get_csrf_token() {
  $token = get_random_string(48);
  set_session('csrf_token', $token);
  return $token;
}

//トークンを判定する処理。セッションに保存されているトークンと同一のものかどうか判定する
function is_valid_csrf_token($token){
  if ($token === '' || $token !== get_session('csrf_token')) {
    return false;
  } 
  return true;
}

// 不正なアクセスを検知した場合のエラー処理 
function validate_csrf_token() {
  $token = get_post('csrf_token');
  if (is_valid_csrf_token($token) === false) {
    set_error('不正なアクセスです。');
    redirect_to(LOGIN_URL);
  } 
}

// ページ数の取得
function get_now_page() {
  if(get_get('page') === '') {
    return 1;
  }
  if(is_positive_integer(get_get('page')) === false) {
    set_error('不正なアクセスです。');
    redirect_to(HOME_URL);
  }
  return get_get('page');
}

// 現在のページの最初の番号を出す
function get_front_select() {
  $now = get_now_page();
  return ($now - 1) * PAGE_VIEW_MAX + 1;
}

// 現在のページの最後の番号を出す
function get_behind_select($page_number) {
  $behind_select = get_front_select($page_number) + PAGE_VIEW_MAX - 1;
  if ($behind_select > $page_number) {
    return $page_number;
  } 
  return $behind_select;
}