<?php
// エラーがなければ、functions.phpを読み込む
require_once 'functions.php';
// エラーがなければ、db.phpを読み込む
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

// 購入履歴・詳細を作るためのレコードを取り出す。管理者ユーザーの場合は全ての履歴の閲覧が可能。他のユーザーは自身の履歴のみ閲覧可能。履歴は8回分の注文ずつ表示する。
function get_purchase_histories($db, $user,$now) {
  $front_select = ($now - SELECT_START_PAGE_NUMBER) * PAGE_VIEW_MAX;
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
    LIMIT
      :start_select, :MAX
    ";
    $params[':start_select'] = $front_select;
    $params[':MAX'] = PAGE_VIEW_MAX;
    // dd($params);
    return fetch_all_query($db, $sql, $params);
  }

// 購入履歴の数の取得
  function get_count_histories($db, $user) {
      $sql = "
      SELECT
        count(*) as histories_total
      FROM 
        order_histories
      ";
      if(is_admin($user) === false){
        $sql .="
        WHERE
          order_histories.user_id = :user_id
        ";
        $params['user_id'] = $user['user_id'];
      }
      $result = fetch_query($db, $sql, $params);
      return $result['histories_total'];
    }
