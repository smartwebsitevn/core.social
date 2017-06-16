<?php  echo macro()->page_heading(lang($class))?>
<?php echo macro()->page_body_start()?>
<?php  widget("download")->_list([],'',true)?>
<?php echo macro()->page_body_end()  ?>
