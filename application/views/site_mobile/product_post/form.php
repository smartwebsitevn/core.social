<div class="product-info-main mt10">
    <div class="row">
        <div class="col-md-12 ">
            <form class="form" id="form" method="post" action="<?php echo $action; ?>"
                  enctype="multipart/form-data">
                <?php if ($act == 'add'): ?>
                    <input type="hidden" name="is_draft" value="0">
                <?php else: ?>
                    <?php if ($info['is_draft']): ?>
                        <input type="hidden" name="is_draft" value="1">
                    <?php endif; ?>
                <?php endif; ?>
                <?php t('view')->load('tpl::product_post/form/general') ?>

                <div class="form-actions text-right p20 mb40">
                    <?php if (!$info): ?>
                        <a _submit="1" class="btn btn-default act-do-submit" data-draft='0'>Đăng ngay</a>
                        <a _submit="1" class="btn btn-outline act-do-submit " data-draft='1'>Lưu thành bản nháp</a>
                    <?php elseif ($info['is_draft']): // neu la ban nhap hoac tin mau thi cho phep chuyen sang ban tin?>
                        <a _submit="1" class="btn btn-default act-do-submit" data-draft='0'>Đăng tin</a>
                        <a _submit="1" class="btn btn-outline act-do-submit " data-draft='1'>Lưu thành bản nháp</a>
                    <?php else: ?>
                        <a _submit="1"
                           class="btn btn-default act-do-submit">   <?php echo ($info["expired"] <= now()) ? "Đăng lại tin" : "Cập nhập tin"; ?></a>
                    <?php endif; ?>
                    <a href="<?php echo $user->_url_my_page ?>" class="btn btn-link">Thoát và không lưu</a>


                </div>
            </form>

        </div>
    </div>
</div>
<?php t('view')->load('tpl::product_post/form/modal_youtube') ?>
<?php t('view')->load('tpl::product_post/form/modal_link') ?>

<?php t('view')->load('tpl::product_post/_js') ?>
