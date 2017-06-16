<?php  echo macro()->page_heading($info->name)?>
<?php echo macro()->page_body_start()?>

<div class="aboutus-info-main">
    <?php t('view')->load('tpl::aboutus/_common/info_main') ?>
</div>
<div class="row">
    <div class="col-md-8">
        <?php t('view')->load('tpl::aboutus/_common/info') ?>
    </div>
    <div class="col-md-4">
        <?php t('view')->load('tpl::aboutus/_common/same_cat') ?>
    </div>
</div>

<?php echo macro()->page_body_end()  ?>
