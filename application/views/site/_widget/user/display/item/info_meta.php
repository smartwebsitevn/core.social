<?php if (isset($row->_working_city) && $row->_working_city): ?>
    <span class="place"> <i
            class="pe-7s-map-marker"></i> <b><?php echo $row->_working_city_name . ', ' . $row->_working_country_name ?></b></span><br>
<?php endif; ?>
<span  class="posts"> <b><?php echo number_format($row->post_total) ?></b> <?php echo lang("count_post") ?></span>

<span class="points"> <b><?php echo number_format($row->point_total) ?></b> <?php echo lang("count_point") ?></span>
<span
    class="follows"> <b><?php echo number_format($row->follow_total) ?></b> <?php echo lang("count_follow") ?></span>

