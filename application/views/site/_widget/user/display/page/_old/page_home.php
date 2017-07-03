<?php
//pr($list_feature);
?>
<div class="topic-cards">
    <div class="container">
        <div class="tab-content">
            <ul class="cards featured-cards">
                <?php foreach ($list_feature as $row): ?>
                    <li>
                        <a href="<?php echo $row->_url_view; ?>" title="<?php echo $row->name; ?>">
                                    <span class="card-img" style="background-image: url(<?php echo $row->banner->url; ?>)">
										<span class="card-info">
											<i class="icon"
                                               style="background-image: url(<?php echo $row->icon->url_thumb; ?>)"></i>
											<span class="title"><?php echo $row->name; ?></span>
										</span>
                                    </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php view('tpl::_widget/user/display/list/list_home', array('list' => $list)); ?>
            <?php if (isset($url_more) && $url_more): ?>
                <a class="explore-all-btn" href="<?php echo $url_more ?>"> Browse users<i
                        class="fa fa-angle-double-right" aria-hidden="true"></i></a>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="clearfix"></div>