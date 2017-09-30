<div class="item-config ">
    <a class="btn btn-outline btn-xs  act-notify-modal"   data-content="Bạn không đủ tiền trong tài khoản để thực hiện hành động này"  title="Đẩy lên Top " href="#0" >Đẩy lên Top</a>
   <span class="item-action-share dropdown">
         <a class="btn btn-link btn-sm dropdown" href="#0" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
             <i class="pe-7s-config"></i>
         </a>

        <ul class="dropdown-menu dropdown-menu-right">
            <?php if (in_array($row_status, array('status-expried'))): ?>
                <li><a title="Đăng lại tin " href="<?php echo $row->_url_reup ?>">Đăng lại tin</a></li>

            <?php else: ?>
                <li><a title="Chỉnh sửa tin " href="<?php echo $row->_url_user_edit ?>">Chỉnh sửa tin</a></li>
            <?php endif; ?>

            <?php if (!in_array($row_status, array('status-expried', 'status-pending','status-draft'))): ?>
                <?php if ($row->status): ?>
                    <li><a title="Ẩn tin " class=" do_action" data-url="<?php echo $row->_url_status_hide ?>"
                           data-action="confirm"> Ẩn tin</a></li>
                <?php else: ?>
                    <li><a title="Hiện tin " class=" do_action" data-url="<?php echo $row->_url_status_show ?>"
                           data-action="confirm"> Hiện tin</a></li>
                <?php endif; ?>
            <?php endif; ?>


            <?php //if (!in_array($row_status, array('hoat-dong', 'tuyen-gap'))): ?>
                <li><a title="Xóa tin" class=" do_action" data-url="<?php echo $row->_url_user_del ?>" data-action="confirm">Xóa
                        tin</a></li>
            <?php //endif; ?>

        </ul>
    </span>
</div>