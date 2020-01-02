<?php
// 実行されているスクリプトのあるmodelファイルへの接続経路の設定 
define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/');
// 実行されているスクリプトのあるviewファイルへの接続経路の設定
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view/');

// 画像ファイルへの接続経路
define('IMAGE_PATH', '/assets/images/');
// cssへの接続経路
define('STYLESHEET_PATH', '/assets/css/');
// 実行されているスクリプトのあるassetsファイルへの接続経路への設定
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets/images/' );

// mysqlのホスト名の設定
define('DB_HOST', 'mysql');
// データベース名の設定
define('DB_NAME', 'sample');
// データベースのユーザー名の設定
define('DB_USER', 'testuser');
// データベースのパスワードの設定
define('DB_PASS', 'password');
// データベースの文字列の設定
define('DB_CHARSET', 'utf8');

// ユーザー登録のページの設定
define('SIGNUP_URL', '/signup.php');
// ログインページの設定
define('LOGIN_URL', '/login.php');
// ログアウトページの設定
define('LOGOUT_URL', '/logout.php');
// ホームページの設定
define('HOME_URL', '/index.php');
// 商品一覧ページの設定
define('CART_URL', '/cart.php');
// 購入結果ページの設定
define('FINISH_URL', '/finish.php');
// 商品管理ページの設定
define('ADMIN_URL', '/admin.php');
// 購入履歴ページの設定
define('HISTORY_URL', '/history.php');
// 購入明細ページの設定
define('DETAIL_URL', '/detail.php');

// 正規表現の設定。英数字での1文字以上の入力を条件とする(大文字・小文字は問わない)。英数字以外の入力は受け付けない。
define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');
// 正規表現の設定。正の整数が1以上または0が入力されていることを条件とする。数字以外の入力は受け付けない。
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');

// ユーザーネームの登録の際の最小文字数の設定
define('USER_NAME_LENGTH_MIN', 6);
// ユーザーネームの登録の際の最大文字数の設定
define('USER_NAME_LENGTH_MAX', 100);
// パスワードの登録の際の最小文字数の設定
define('USER_PASSWORD_LENGTH_MIN', 6);
// パスワードの登録の際の最大文字数の設定
define('USER_PASSWORD_LENGTH_MAX', 100);

// 管理ユーザーの設定
define('USER_TYPE_ADMIN', 1);
// 通常のユーザーの設定
define('USER_TYPE_NORMAL', 2);

// 商品の最小文字数の設定
define('ITEM_NAME_LENGTH_MIN', 1);
// 商品の最大文字数の設定
define('ITEM_NAME_LENGTH_MAX', 100);

// 商品の公開時のステータスの設定
define('ITEM_STATUS_OPEN', 1);
// 商品非公開時のステータスの設定
define('ITEM_STATUS_CLOSE', 0);

// 商品の公開・非公開時の設定
define('PERMITTED_ITEM_STATUSES', array(
  'open' => 1,
  'close' => 0,
));

// 使用できる画像の種類の設定
define('PERMITTED_IMAGE_TYPES', array(
  IMAGETYPE_JPEG => 'jpg',
  IMAGETYPE_PNG => 'png',
));

// フレーム内の全てのページの読み込みを禁止する
define('FRAME_OPTION', 'X-Frame-Options: DENY');

// 購入履歴でページに表示する最大数
define('PAGE_VIEW_MAX', 8);

// cookieを削除するために利用する値
define('DELETE_COOKIE_TIME', 42000);

// 合計金額の初期値
define('DEFAULT_PRICE', 0);

// カートの中身の商品の有無
define('DEFAULT_CART_AMOUNT', 0);

// 在庫数の有無
define('CHECK_ITEM_STOCK', 0);

// カートの商品の追加
define('ADD_CART_ITEM', 1);

// count関数の判定の際に利用する数字
define('COUNT_CHECK_NUMBER', 0);

// トークンの長さ
define('TOKEN_LENGTH', 20);

// トークンの取得を何文字目から行うかの数字
define('GET_TOKEN_START_NUMBER', 0);

// 16進数
define('BASE_16', 16);

// 32進数
define('BASE_32', 32);

// 商品の表示をページ数-1番目から行うための定数
define('SELECT_START_PAGE_NUMBER', 1);