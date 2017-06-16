<div class="views-header">
    <h2 class="pull-left"><span>Ý kiến đánh giá của học viên</span></h2>

    <div class="box-cau-hoi pull-right">
        <a href="<?php echo site_url('page/faq') ?>">Những câu hỏi thường gặp</a>
    </div>
</div>
<div class="clearfix"></div>
<div class="views-content">
    <?php $this->load->view('tpl::question_answer/_common/list', array('list' => $list)); ?>

    <div class="clearfix"></div>
    <?php  if(count($list)>=$widget->setting['total']): ?>
        <div class="comment-more">
            <a href="<?php echo site_url("question_answer") ?>">Xem thêm bình luận</a>
        </div>
    <?php endif;  ?>
</div>