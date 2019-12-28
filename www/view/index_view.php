<?php header(FRAME_OPTION); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print h(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  

  <div class="container">
    <h1>商品一覧</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
  
    <div class="card-deck">
      <div class="row">
      <?php foreach($items as $item){ ?>
        <div class="col-6 item">
          <div class="card h-100 text-center">
            <div class="card-header">
              <?php print h($item['name']); ?>
            </div>
            <figure class="card-body">
              <img class="card-img" src="<?php print h(IMAGE_PATH . $item['image']); ?>">
              <figcaption>
                <?php print h(number_format($item['price'])); ?>円
                <?php if($item['stock'] > 0){ ?>
                  <form action="index_add_cart.php" method="post">
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <input type="hidden" name="item_id" value="<?php print h($item['item_id']); ?>">
                    <input type="hidden" name="csrf_token" value="<?php print $token; ?>">
                  </form>
                <?php } else { ?>
                  <p class="text-danger">現在売り切れです。</p>
                <?php } ?>
              </figcaption>
            </figure>
          </div>
        </div>
      <?php } ?>
      </div>
    </div>
  </div>
  <?php echo  $num_items . '件中' . ' ' . $front_select . '-' . $behind_select . '件'; ?>
  <?php if ($now > 1) { ?>
    <a href="?page=<?php echo $now - 1 ?>">前へ</a>
  <?php } else { ?>
    <?php echo '前へ'; } ?>
  <?php for ($i=1; $i <= $page_max; $i++) { ?>
  <?php if ($i == $now) { ?>
  <?php echo $now; }else{ ?>
    <a href="?page=<?php echo $i ?>"><?php echo $i ; }?> </a>
  <?php } ?>
  <?php if ($now < $page_max) { ?>
    <a href="?page=<?php echo $now + 1 ?>">次へ</a>
  <?php } else { ?>
  <?php echo '次へ'; } ?>
</body>
</html>