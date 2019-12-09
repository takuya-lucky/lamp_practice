<?php 
// エラーがなければ、functions.phpファイルを読み込む
require_once 'functions.php';
// エラーがなければ、db.phpのファイルを読み込む
require_once 'db.php';

// select文でcartsテーブルとitemsテーブルを結合している(item_idを基に)。返り値・戻り値はuser_idを基にテーブルのレコードを読み取り、カートに入っている商品
function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = :user_id
  ";
  $params = array(':user_id' => $user_id);
  return fetch_all_query($db, $sql, $params);
}

// select文でcartsテーブルとitemsテーブルを結合している(item_idを基に)。返り値・戻り値はuser_idとitem_idを基に、fetch_queryで一致するもの。
function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = :user_id
    AND
      items.item_id = :item_id
  ";
  $params = array(':user_id' => $user_id, 'item_id' => $item_id);
  return fetch_query($db, $sql, $params);
}

// 返り値・戻り値はget_user_cartの結果を基に、一致するものがあれば、update_cart_amountの処理。一致するものがなければ、insert_cartの処理。
function add_cart($db, $item_id, $user_id) {
  $cart = get_user_cart($db, $item_id, $user_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

// insert文で、新たにカートに商品を1つ追加する。(注文数1)。返り値・戻り値は商品の追加。
function insert_cart($db, $item_id, $user_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(:item_id, :user_id, :amount)
  ";
  $params = array(':item_id' => $item_id, ':user_id' => $user_id, 'amount' => $amount);
  return execute_query($db, $sql, $params);
}

// update文で$amountの数分カートにある商品を増やす。返り値・戻り値はcart_idを基にamountを増やすこと。
function update_cart_amount($db, $cart_id, $amount){
    $sql = "
    UPDATE 
      carts 
    SET 
      amount = :amount
    WHERE 
      cart_id = :cart_id
    LIMIT 1
    ";
    $params = array(':amount' => $amount, 'cart_id' => $cart_id);

    return execute_query($db, $sql, $params);
  }

  // delete文でカートの商品をcart_idを基に削除する。返り値・戻り値はテーブルからの削除処理。
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";
  $params = array('cart_id' => $cart_id);
  return execute_query($db, $sql, $params);
}

// 在庫の数が注文数を超えていないかの確認。返り値は購入後カート内商品の削除処理を行う。
function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  
  delete_user_carts($db, $carts[0]['user_id']);
}

// delete文で、カート内の商品を削除する。返り値・戻り値は商品の削除
function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = :user_id
  ";
  $params = array(':user_id' => $user_id);
  return execute_query($db, $sql, $params);
}

// 会計金額の計算を行っている。返り値・戻り値は値段×購入数
function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

// 商品が購入できるかの確認の処理を行っている。カートの中に商品があるかどうか、公開商品かどうか、注文数が在庫数を超えていないかどうかを確認し、全て条件を満たせばtrue、1つでも満たせなければ、falseが返り値・戻り値となる。
function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

