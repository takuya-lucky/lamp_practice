<?php header(FRAME_OPTION); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print h(STYLESHEET_PATH . 'admin.css'); ?>">
</head>
<body>
  <?php 
  include VIEW_PATH . 'templates/header_logined.php'; 
  ?>

  <div class="container">
    <h1>購入明細</h1>

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

      <table class="table table-bordered text-center">
        <thead class="thead-light">
        <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
        </tr>
        <?php foreach($detail as $value) { ?>
            <tr>
                <td><?php print h($value['history_id']); ?></td>
                <td><?php print h($value['created']); ?></td>
                <td><?php print h(number_format($total_price)); ?></td>
            </tr>
        <?php break; } ?>
          <tr>
            <th>商品名</th>
            <th>購入時の商品価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($detail as $value) { ?>
            <tr>
                <td><?php print h($value['name']); ?></td>
                <td><?php print h($value['purchased_price']); ?></td>
                <td><?php print h($value['amount']); ?></td>
                <td><?php print h(number_format($value['purchased_price'] * $value['amount'])); ?></td>
            </tr>
        <?php } ?>
        </tbody>
      </table>
  </div>
</body>
</html>