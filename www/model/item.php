<?php
// エラーがなければ、functions.phpファイルを読み込む
require_once 'functions.php';
// エラーがなければ、db.phpファイルを読み込む
require_once 'db.php';

// DB利用

// itemsテーブルにあるレコードをitem_idに基づいて一列取り出す。
function get_item($db, $item_id){
  $sql = "
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
    WHERE
      item_id = :item_id
  ";
  $params = array(':item_id' => $item_id);
  return fetch_query($db, $sql, $params);
}

// 全ての商品の取得(is_openがfalse、trueによって取得商品が変わる)
function get_items($db, $is_open = false){
  // 現在のページ数の取得
  $now = get_now_page();
  $start_select = ($now - 1) * 8;
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status,
      created
    FROM
      items
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = :status
      ORDER BY
        created desc
      LIMIT
        :start_select, :MAX
    ';
    $params = array(':status' => '1', ':start_select' => $start_select, 'MAX' => PAGE_VIEW_MAX);
  } 
  return fetch_all_query($db, $sql, $params);
}

// get_itemsを実行する($is_open = false)
function get_all_items($db){
  return get_items($db);
}

// get_itemsを実行する($is_open = true)
function get_open_items($db){
  return get_items($db, true);
}

// 商品を登録する際の値がそれぞれ変数に適切に入っているかの確認。
function regist_item($db, $name, $price, $stock, $status, $image){
  $filename = get_upload_filename($image);
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

// トランザクションを行い、insert_itemとsave_imageが実行できれば、コミットを行う。
function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  $db->beginTransaction();
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    && save_image($image, $filename)){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
  
}

// 商品を登録する
function insert_item($db, $name, $price, $stock, $filename, $status){
  $status_value = PERMITTED_ITEM_STATUSES[$status];
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES(:name, :price, :stock, :filename, :status_value);
  ";
  $params = array(':name' => $name , ':price' => $price, ':stock' => $stock, ':filename' => $filename, ':status_value' => $status_value);
  return execute_query($db, $sql, $params);
}

// 商品の公開・非公開状態の更新
function update_item_status($db, $item_id, $status){
  $sql = "
    UPDATE
      items
    SET
      status = :status
    WHERE
      item_id = :item_id
    LIMIT 1
  ";
  $params = array(':status' => $status, ':item_id' => $item_id);
  return execute_query($db, $sql, $params);
}

// 商品の在庫の数の更新
function update_item_stock($db, $item_id, $stock){
  $sql = "
    UPDATE
      items
    SET
      stock = :stock
    WHERE
      item_id = :item_id
    LIMIT 1
  ";
  $params = array(':stock' => $stock, ':item_id' => $item_id);
  return execute_query($db, $sql, $params);
}

// 商品を削除するかどうかの判定を行う。ただし、トランザクションを用いて、商品を削除するのを不適切な処理で実行しようとした場合にはrollbackするようにしてある。
function destroy_item($db, $item_id){
  $item = get_item($db, $item_id);
  // dd($item);
  if($item === false){
    return false;
  }
  $db->beginTransaction();
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}

// 商品を削除する
function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = :item_id
    LIMIT 1
  ";
  $params = array(':item_id' => $item_id);
  return execute_query($db, $sql, $params);
}


// 非DB
// 商品を公開状態にする
function is_open($item){
  return $item['status'] === 1;
}

// 商品の名前、価格、在庫、ファイルの名前、公開状態が適切になっているかの確認を行う。
function validate_item($name, $price, $stock, $filename, $status){
  $is_valid_item_name = is_valid_item_name($name);
  $is_valid_item_price = is_valid_item_price($price);
  $is_valid_item_stock = is_valid_item_stock($stock);
  $is_valid_item_filename = is_valid_item_filename($filename);
  $is_valid_item_status = is_valid_item_status($status);

  return $is_valid_item_name
    && $is_valid_item_price
    && $is_valid_item_stock
    && $is_valid_item_filename
    && $is_valid_item_status;
}

// 商品の名前が既定のルールで入力されているかの確認
function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}

// 商品の価格が適切に入力されているかの確認
function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

// 商品の在庫の数が適切に入力されているかの確認
function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

// 商品の画像名が適切かどうかの確認
function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}

// 商品の公開状態が公開か非公開かの判定
function is_valid_item_status($status){
  $is_valid = true;
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}

// 全ての商品の取得
function get_count_items($db){
  $sql = '
    SELECT
      count(*) as total
    FROM
      items
    WHERE 
      status = :status
  ';
  $params = array(':status' => '1');
  $count_item = fetch_query($db, $sql, $params);
  return $count_item['total'];
}

// 商品の並び替え
function sort_items($db, $is_open = true, $change_position = 'new_item') {
 // 現在のページ数の取得
 $now = get_now_page();
 $start_select = ($now - 1) * PAGE_VIEW_MAX;
 $params = array();
 $sql = '
   SELECT
     item_id, 
     name,
     stock,
     price,
     image,
     status,
     created
   FROM
     items
 ';
  if ($is_open === true) {
    $sql .='
    WHERE
      status = :status
    ';
    $params['status'] = '1';
  }
  $orders = array(
    'new_item' => 'ORDER BY created DESC',
    'cheap_item' => 'ORDER BY price ASC',
    'expensive_item' => 'ORDER BY price DESC'
  );
  if (isset($orders[$change_position]) === false) {
    set_error('不正なアクセスです');
    redirect_to(HOME_URL);
  }
  $sql .= $orders[$change_position];
  $sql .='
  LIMIT
    :start_select, :MAX
  ';
  $params[':start_select'] = $start_select;
  $params[':MAX'] = PAGE_VIEW_MAX;
  return fetch_all_query($db, $sql, $params);
}

// 商品の売り上げ数の上位三つの取得
function sale_ranking($db) {
  $sql = '
  SELECT
    order_details.item_id,
    sum(amount) as sale_total_amount,
    items.name,
    items.image
  FROM
    order_details
  JOIN
    items
  ON
    order_details.item_id = items.item_id
  GROUP BY
    item_id
  ORDER BY
    sale_total_amount desc
  LIMIT
    3
  ';
  return fetch_all_query($db, $sql);
}