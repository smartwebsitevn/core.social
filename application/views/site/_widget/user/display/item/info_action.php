<!--<div class="item-header ">
    <?php /*widget('recruit')->action_favorite($row,$user) */ ?>
    <?php /*widget('recruit')->action_share($row) */ ?>
    <?php /*widget('recruit')->action_close() */ ?>
</div>
-->
<span class="dropdown">
         <a href="#0" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
             <i class="fa fa-angle-down" aria-hidden="true"></i>
         </a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li><a title="Thông tin liên hệ " class=" do_action" data-url="<?php echo $row->_url_view_profile ?>">Thông
                    tin liên hệ</a></li>
            <li><a title="Nhắn tin" href="<?php echo $row->_url_message ?>">Nhắn tin</a></li>
        </ul>
</span>