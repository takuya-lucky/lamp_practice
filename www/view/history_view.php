<?php header(FRAME_OPTION); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print h(STYLESHEET_PATH . 'admin.css'); ?>">
</head>
<body>
  <?php 
  include VIEW_PATH . 'templates/header_logined.php'; 
  ?>

  <div class="container">
    <h1>購入履歴</h1>

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

      <table class="table table-bordered text-center">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>該当の注文の合計金額</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($histories as $value) { ?>
            <tr>
                <td><?php print h($value['history_id']); ?></td>
                <td><?php print h($value['created']); ?></td>
                <td><?php print h(number_format($value['total'])); ?>
                <form method="get" action="detail.php">
                    <input type="submit" value="購入明細表示" class="btn btn-info">
                    <input type="hidden" name="history_id" value="<?php print h($value['history_id']); ?>">
                </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
      </table>
      <?php echo  $num_histories . '件中' . '&nbsp;' . $front_select . '-' . $behind_select . '軒'; ?>
      <?php if ($now > 1) { ?>
        <a href="history.php?page=<?php echo $now - 1 ?>">前へ</a>
      <?php } else { ?>
        <?php echo '前へ'; } ?>
      <?php for ($i=1; $i <= $page_max; $i++) { ?>
      <?php if ($i == $now) { ?>
      <?php echo $now; }else{ ?>
        <a href="?page=<?php echo $i ?>"><?php echo $i ; }?> </a>
      <?php } ?>
      <?php if ($now < $page_max) { ?>
        <a href="history.php?page=<?php echo $now + 1 ?>">次へ</a>
      <?php } else { ?>
        <?php echo '次へ'; } ?>
  </div>
</body>
</html>