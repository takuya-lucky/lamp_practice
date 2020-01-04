<?php
require_once 'functions.php';
require_once 'db.php';

// 購入明細の記録
function insert_purchase_detail($db, $history_id, $carts) {
    $make_detail = true;
    foreach($carts as $cart) {
      $sql = "
      INSERT INTO
        order_details(history_id, item_id, amount, purchased_price)
      VALUES (:history_id, :item_id, :amount, :purchased_price)
        ";
      $params = array(':history_id' => $history_id,':item_id' => $cart['item_id'],  ':amount' => $cart['amount'], ':purchased_price' => $cart['price']);
      execute_query($db, $sql, $params);
    }
    if (has_error() === true) {
      $make_detail = false;
    }
    return $make_detail;
  }
  
  // 購入明細を取得する。管理者のみ全てのユーザーの明細を取得できる。
  function get_purchase_details($db, $history) {
    $sql = "
      SELECT
        items.item_id,
        items.name,
        order_histories.history_id,
        order_histories.created,
        order_histories.user_id,
        order_details.amount,
        order_details.purchased_price
      FROM
        order_details
      JOIN
        items
      ON
        order_details.item_id = items.item_id
      JOIN
        order_histories
      ON
        order_details.history_id = order_histories.history_id
      WHERE
        order_details.history_id = :history_id
        ";
    $params = array(':history_id' => $history);
    return fetch_all_query($db, $sql, $params);
  }

  // 商品の購入金額の計算を行い、その結果を返り値・戻り値とする
  function sum_purchased($detail){
    $total_price = 0;
    foreach($detail as $details){
      $total_price += $details['purchased_price'] * $details['amount'];
    }
    return $total_price;
  }