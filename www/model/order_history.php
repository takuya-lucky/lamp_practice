<?php
require_once 'functions.php';
require_once 'db.php';

// 購入履歴の作成
function make_purchase_history($db, $user) {
  $sql = "
  INSERT INTO
    order_histories(user_id)
  VALUES (:user_id)
    ";
  $params = array('user_id' => $user);
  return execute_query($db, $sql, $params); 
}

// 購入履歴・詳細を作るためのレコードを取り出す。管理者ユーザーの場合は全ての履歴の閲覧が可能。他のユーザーは自身の履歴のみ閲覧可能。
function get_purchase_history($db, $user) {
    $sql = "
    SELECT
      order_details.history_id,
      order_histories.created,
      sum(order_details.purchased_price * order_details.amount) as total
    FROM 
      order_histories
    JOIN
      users
    ON 
      order_histories.user_id = users.user_id
    JOIN
      order_details
    ON
      order_histories.history_id = order_details.history_id
      ";
    $params = array();
    if(is_admin($user) === false){
      $sql .= "
      WHERE
        order_histories.user_id = :user_id
      ";
      $params[':user_id'] = $user['user_id'];
    }
    $sql .= "
    GROUP BY
     order_details.history_id
    ORDER BY
      created DESC
    ";
    return fetch_all_query($db, $sql, $params);
  }