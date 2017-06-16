<?php echo macro()->page_heading(lang("cart")) ?>
<?php echo macro()->page_body_start() ?>
  <?php widget("product")->cart();?>
<?php echo macro()->page_body_end() ?>
