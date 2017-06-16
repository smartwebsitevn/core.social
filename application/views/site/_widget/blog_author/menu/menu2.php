<?php $url = site_url('movie_list');
$cats = model('cat')->get_type('cat');
$countrys = model('cat')->get_type('country');
if(t('session')->userdata('__admin_id')) {
    // pr($cats);
}
?>
<ul class="ui-menu">
    <li>
        <div class="icon icone-fl">
            <a href="<?php echo $url.'?type=1' ?>">
                <i class="icone-f"></i>
                <span>Phim Lẻ</span>
            </a>
        </div>
    </li>
    <li>
        <div class="icon icone-fb">
            <a href="<?php echo $url.'?type=2' ?>">
                <i class="icone-f"></i>
                <span>Phim Bộ</span>
            </a>
        </div>

    </li>
    <li class="top">
        <a href="javascript:void(0)">
            <i class="fa fa-life-ring" aria-hidden="true"></i>
            <span>Thể Loại</span>
        </a>
        <ul class="submenu">
            <?php foreach($cats as $c):
                if($c->is_anime) continue;
                ?>
                <li>
                    <a href="<?php echo $url.'?cat='. $c->id ?>">
                        <span><?php echo $c->name ?></span>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </li>
    <li class="top">
        <a href="javascript:void(0)">
            <i class="fa fa-globe" aria-hidden="true"></i>
            <span>Quốc Gia</span>
        </a>
        <ul class="submenu">
            <?php foreach($countrys as $c): ?>
                <li>
                    <a href="<?php echo $url.'?country='. $c->id ?>">
                        <span><?php echo $c->name ?></span>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </li>
    <li class="top">
        <a href="javascript:void(0)">
            <i class="fa fa-calendar" aria-hidden="true"></i>
            <span>Năm Phát Hành</span>
        </a>
        <?php
        $now = getdate();
        ?>
        <ul class="submenu">
            <?php foreach(range($now["year"]-5,$now["year"]) as $v): ?>
                <li>
                    <a href="<?php echo $url.'?year='. $v ?>">
                        <span><?php echo $v ?></span>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </li>
    <li>
        <div class="icon icone-ftv">
            <a href="<?php echo site_url('movie_list').'?cat=36' ?>">
                <i class="icone-f"></i>
                <span>Show</span>
            </a>
        </div>
    </li>
    <li class="top">
        <a href="javascript:void(0)">
            <i class="fa fa-film" aria-hidden="true"></i>
            <span>Anime</span>
        </a>
        <ul class="submenu">
            <?php foreach($cats as $c):
                if(!$c->is_anime) continue;
                ?>
                <li>
                    <a href="<?php echo $url.'?cat='. $c->id ?>">
                        <span><?php echo $c->name ?></span>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </li>
    <li>
        <a href="<?php echo site_url('movie_list/top') ?>">

            <i class="fa fa-trophy" aria-hidden="true"></i>
            <span>Bảng Xếp Hạng</span>
        </a>
    </li>
    <li>
        <a href="<?php echo site_url('news') ?>">

            <i class="fa fa-newspaper-o" aria-hidden="true"></i>
            <span>Tin Tức</span>
        </a>
    </li>
    <?php
    $menu_other = mod('menu')->get("menu_khac");
    if($menu_other): ?>
        <li class="bottom">
            <a href="javascript:void(0)" class="has-submenu">
                <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                <span>Khác</span>
            </a>

            <ul class="submenu">
                <?php foreach($menu_other as $m): //pr($m);                ?>
                    <li> <a href="<?php echo $m->url?>"> <span><?php echo $m->title ?></span> </a> </li>
                <?php endforeach ?>
            </ul>

        </li>
    <?php endif; ?>
</ul>