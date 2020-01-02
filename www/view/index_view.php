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
    並べ替え
    <form action="index.php" method="get" name="items_order">
      <select name="change_position" id="change_position">
        <option value="new_item" <?php if ($change_position === 'new_item') { print h('selected');}?>>新着順</option>
        <option value="cheap_item" <?php if ($change_position === 'cheap_item') { print h('selected');}?>>価格の安い順</option>
        <option value="expensive_item"<?php if ($change_position === 'expensive_item') { print h('selected');}?>>価格の高い順</option>
      </select>
    </form>
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
                <?php if($item['stock'] > CHECK_ITEM_STOCK){ ?>
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
      <footer>
        <div class="col-6 item">
            <div class="card h-100 text-center">
              <div class="card-header">
              <h2>人気ランキング</h2>
                <?php  foreach ($sale_ranking as $value) { ?>
                  <?php print h($value['name']); ?>
                  <figure class="card-body">
                    <img class="card-img" src="<?php print h(IMAGE_PATH . $value['image']); ?>">
                  </figure>
                <?php } ?>
              </div>
            </div>
        </div>
      </footer>
      </div>
    </div>
  </div>
  <?php echo  h($num_items . '件中' . ' ' . $front_select . '-' . $behind_select . '件'); ?>

  <?php if ($now > SELECT_START_PAGE_NUMBER) { ?>
    <a href="?page=<?php echo h($now - SELECT_START_PAGE_NUMBER) ?>&change_position=<?php echo h($change_position)?>">前へ</a>
  <?php } else { ?>
    前へ
  <?php } ?>

  <?php for ($i=1; $i <= $page_max; $i++) { ?>
    <?php if ($i == $now) { ?>
      <?php echo h($now); ?>
    <?php } else { ?>
      <a href="?page=<?php echo h($i) ?>&change_position=<?php echo h($change_position)?>"><?php echo h($i) ; ?> </a>
    <?php } ?>
  <?php } ?>

  <?php if ($now < $page_max) { ?>
    <a href="?page=<?php echo h($now + SELECT_START_PAGE_NUMBER) ?>&change_position=<?php echo h($change_position)?>">次へ</a>
  <?php } else { ?>
    次へ
  <?php } ?>
  <script src="assets/js/index.js"></script>
</body>
</html>