    <?php //echo $mainmenu ?>
    <?php
  //  t("lang")->load('movie');
    $url = site_url('movie_list');
    $url_clip = site_url('movie_list/clip');
    $cats = model('cat')->get_type('cat');
    $cat_clips = model('cat')->get_type('clip');
    $cat_shows = model('cat')->get_type('show');
    //$cat_collections = model('cat')->get_type('cat_collection');
    $countrys = model('cat')->get_type('country');
    ?>
    <ul>
        <li>
            <a href="javascript:void(0)">
                <i class="fa fa-film" aria-hidden="true"></i>
                <span><?php echo lang("menu_movie"); ?></span>
            </a>
            <span class="arrow click-parent"></span>
            <ul class="sub-menu">
                <?php foreach ($cats as $c): ?>
                    <li>
                        <a href="<?php echo $url . '?cat=' . $c->id ?>">
                            <span><?php echo $c->name ?></span>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0)">
                <i class="fa fa-caret-square-o-right" aria-hidden="true"></i>
                <span><?php echo lang("menu_clip"); ?></span>
            </a>
            <span class="arrow click-parent"></span>
            <ul class="sub-menu">
                <?php foreach ($cat_clips as $c): ?>
                    <li>
                        <a href="<?php echo $url_clip . '?cat_clip=' . $c->id ?>">
                            <span><?php echo $c->name ?></span>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0)">
                <i class="fa fa-video-camera" aria-hidden="true"></i>
                <span><?php echo lang("menu_show"); ?></span>
            </a>
            <span class="arrow click-parent"></span>
            <ul class="sub-menu">
                <?php foreach ($cat_shows as $c): ?>
                    <li>
                        <a href="<?php echo $url_clip . '?cat_show=' . $c->id ?>">
                        <span><?php echo $c->name ?></span>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0)">
                <i class="fa fa-globe" aria-hidden="true"></i>
                <span><?php echo lang("menu_country"); ?></span>
            </a>
            <span class="arrow click-parent"></span>
            <ul class="sub-menu">
                <?php foreach ($countrys as $c): ?>
                    <li>
                        <a href="<?php echo $url . '?country=' . $c->id ?>">
                            <span><?php echo $c->name ?></span>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0)">
                <i class="fa fa-calendar" aria-hidden="true"></i>
                <span><?php echo lang("menu_year"); ?></span>
            </a>
            <span class="arrow click-parent"></span>
            <?php
            $now = getdate();
            ?>
            <ul class="sub-menu">
                <?php foreach (range($now["year"] - 10, $now["year"]) as $v): ?>
                    <li>
                        <a href="<?php echo $url . '?year=' . $v ?>">
                            <span><?php echo lang("year"); ?> <?php echo $v ?></span>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        </li>
        <li>
            <div class="icon icone-fl">
                <a href="<?php echo $url . '?type=1' ?>">
                    <i class="fa fa-leaf" aria-hidden="true"></i>
                    <span><?php echo lang("menu_movie_movies"); ?></span>
                </a>
            </div>
        </li>
        <li>
            <div class="icon icone-fb">
                <a href="<?php echo $url . '?type=2' ?>">
                    <i class="fa fa-th" aria-hidden="true"></i>
                    <span><?php echo lang("menu_movie_series"); ?></span>
                </a>
            </div>

        </li>

        <li>
            <a href="<?php echo $url . '?order=view_total|desc' ?>">
                <i class="fa fa-eye" aria-hidden="true"></i>
                <span><?php echo lang("menu_movie_viewest"); ?></span>
            </a>
        </li>

        <?php /* ?>
          <li>
            <a href="<?php echo $url . '?cinema=1' ?>">

                <i class="fa fa-video-camera" aria-hidden="true"></i>
                <span>Phim Chiếu Rạp</span>
            </a>
        </li>
				  		<li>
									<a href="">
										<i class="fa fa-th" aria-hidden="true"></i>
										<span>Phim mới</span>
									</a>
								</li>

						<li>
							<a href="<?php echo $url.'?has_interpret=1' ?>">
								<i class="fa fa-volume-up" aria-hidden="true"></i>
								<span>Phim Thuyết Minh</span>
							</a>
						</li>
 						<?php */ ?>

    </ul>