<div class="video">
    <?php echo widget("product")->player($info->link_data,['image_url'=>$info->banner->url],'player') ?>
</div>
<?php /* ?>
<h3 class="detail-price text-center mobile">500.000đ</h3>
<div class="widget-detail mobile">
    <a href="#" class="title1">Đăng ký</a>
    <p class="infor infor-hour">Thời lượng: 2 giờ</p>

    <p class="infor infor-level">Trình độ: Chuyên sâu</p>

    <p class="infor infor-lesson">Bài học: 59 bài</p>

    <p class="infor infor-certificate">Cấp chứng nhận hoàn thành</p>

    <div class="share">
        <img src="images/share.png"/>
    </div>
</div>
 <?php */ ?>

<ul class="nav nav-tabs">
    <li class="active"><a href="#menu0">GIỚI THIỆU</a></li>
    <li><a href="#menu1">NỘI DUNG HỌC</a></li>
    <li><a href="#menu2">GIẢNG VIÊN</a></li>
    <li><a href="#menu3">BÌNH LUẬN</a></li>
</ul>
<div id="menu0" class="detail-area clearfix">
    <?php t('view')->load('tpl::product/_common/info_intro') ?>
</div>
<?php if ($groups): ?>
    <div id="menu1" class="detail-area clearfix">
        <?php t('view')->load('tpl::product/_common/info_progress') ?>
    </div>
<?php endif; ?>

<?php if (isset($info->_author) && $info->_author): ?>
    <div id="menu2" class="detail-area clearfix">
        <?php t('view')->load('tpl::product/_common/info_author') ?>
    </div>
<?php endif; ?>

<div id="menu3" class="detail-area clearfix">
    <?php t('view')->load('tpl::product/_common/info_comment') ?>
    <?php /* ?>
    <h2>Bình luận</h2>
    <div class="detail-area-content">
        <p class="count-comment">0 bình luận</p>
        <form class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-1">
                    <img src="images/logo-tiki2.png" />
                </div>
                <div class="col-sm-11">
                    <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Thêm bình luận ...">
                </div>
            </div>
        </form>
    </div>
     <?php */ ?>

</div>