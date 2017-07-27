<div class="product-info-main mt20">
    <div class="row">
        <div class="col-md-8 col-sm-12 col-xs-12  ">
            <form class="form form_action " id="form" method="post" action="<?php echo $action; ?>"  enctype="multipart/form-data">
                <?php t('view')->load('tpl::product_post/form/general') ?>

                <div class="form-actions text-right p20 mb40">
                    <?php if (!$info ): ?>
                        <a _submit="1" class="btn btn-default act-do-submit" data-draft='0'>Đăng ngay</a>
                        <a _submit="1" class="btn btn-outline act-do-submit " data-draft='1'>Lưu thành bản nháp</a>
                    <?php elseif ($info['draft'] || $info['form']): // neu la ban nhap hoac tin mau thi cho phep chuyen sang ban tin?>
                        <a _submit="1" class="btn btn-default act-do-submit" data-draft='0'>Chuyển thành tin đăng tuyển</a>
                        <a _submit="1" class="btn btn-outline act-do-submit " data-draft='1'>Lưu thành bản nháp</a>
                    <?php else: ?>
                        <a _submit="1" class="btn btn-default act-do-submit">   <?php  echo  ($info["expired"] <= now())?"Đăng lại tin":"Cập nhập tin" ;?></a>
                    <?php endif; ?>
                    <a href="<?php echo $user->_url_my_page ?>" class="btn btn-link">Thoát và không lưu</a>



                </div>
            </form>

        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <?php t('view')->load('tpl::product_post/form/author') ?>
        </div>

    </div>
</div>


<script type="text/javascript" src="<?php echo public_url('js') ?>/ckeditor/ckeditor.js"></script>
<?php t('view')->load('tpl::product_post/_js') ?>
