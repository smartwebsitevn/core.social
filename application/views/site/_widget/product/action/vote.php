<?php if ($can_do): ?>
    <span  class="action_vote_group">
          <a href="#0" title='' class="do_action <?php if ($voted) echo $voted->like?'on':''; ?>"
             data-action="toggle"
             data-group="action_vote_group"
             data-url-on="<?php echo $url_like ?>"
             data-url-off="<?php echo $url_like_del ?>"
             data-title-on='Hủy cộng điểm<?php //echo lang("action_vote_del") ?>'
             data-title-off='Cộng điểm<?php //echo lang("action_favorite") ?>'
             data-class-on="active"
              >
              <i class="pe-7s-up-arrow"></i>
          </a>
          <a href="#0" title='' class="do_action <?php if ($voted) echo $voted->dislike?'on':''; ?>"
             data-action="toggle"
             data-group="action_vote_group"
             data-url-on="<?php echo $url_dislike ?>"
             data-url-off="<?php echo $url_dislike_del ?>"
             data-title-on='Hủy trừ điểm<?php //echo lang("action_vote_del") ?>'
             data-title-off='Trừ điểm<?php //echo lang("action_favorite") ?>'
             data-class-on="active"
              >
              <i class="pe-7s-bottom-arrow"></i>
          </a>
    </span>
    <span   class="points"><b id="<?php echo $info->id?>_vote_points" ><?php echo number_format($info->vote_total) ?></b> <?php echo lang("count_point") ?> </span>

<?php endif; ?>
