<?php
$asset = public_url();
$asset_theme = $asset . '/site/theme/';
//pr($menu);
?>
    <div class="hover-click"></div>
    <div class="before-nav">
        <div class="container clearfix">
            <div class="col-left">
                <p class="nav-hotline">Hotline: <?php echo widget("site")->setting("hotline") ?></p>
            </div>
            <div class="col-right">
                <?php widget("user")->account_panel() ?>
            </div>
        </div>
    </div>
    <!-- Fixed navbar -->
    <nav class="main">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo site_url() ?>">
                    <img src="<?php echo widget("site")->setting_image() ?>" alt="logo"/>
                </a>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="navbar-top">
                    <?php foreach ($menu as $i => $item):

                        $has_sub = $item->_sub ? 1 : 0;
                        $target = $item->target?' target="'.$item->target.'" ' :'';
                        ?>
                            <li class="<?php if ($item->_is_active) echo 'active'; ?>  <?php if ($has_sub) echo "dropdown" ?>  ">
                                <a  <?php echo $target ?>  href="<?php echo $item->url; ?>" <?php if ($has_sub){ ?>class="dropdown-toggle"
                                    data-toggle="dropdown"<?php } ?>>
                                    <span class="top-icon <?php echo $item->icon?$item->icon:"icon-gt" ?>"></span><?php echo $item->title ?>
                                </a>
                                <?php if ($has_sub):?>
                                    <ul class="dropdown-menu ">
                                        <?php foreach ($item->_sub as $row) { ?>
                                            <li><a href="<?php echo $row->url ?>"><?php echo $row->title ?></a></li>
                                        <?php } ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>


<?php
$trainings = model('cat')->get_type("training");
$schools = model('cat')->get_type("school");
$subjects = model('cat')->get_type("subject");
//pr($trainings);
?>
    <!-- Search  -->
    <div class="cus-search">
        <div class="container">
            <form method="get" action="<?php echo site_url("product_list") ?>">
               <!-- <p class="cus-search-title">Nhập tìm kiếm</p>-->
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <label>Hệ đào tạo</label>
                            <select name="training_id" class="form-control">
                                <?php foreach($trainings as $row): ?>
                                    <option value="<?php echo $row->id ?>"><?php echo $row->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4">
                        <div class="form-group">
                            <label>Trường</label>
                            <select name="school_id" class="form-control">
                                <?php foreach($schools as $row): ?>
                                    <option value="<?php echo $row->id ?>"><?php echo $row->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4">
                        <div class="form-group">
                            <label>Môn học</label>
                            <select name="subject_id" class="form-control">
                                <?php foreach($subjects as $row): ?>
                                    <option value="<?php echo $row->id ?>"><?php echo $row->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <button type="submit" class="cus-btn cus-btn-info">Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
<?php /* ?>
<?php $products = model("product")->get_list_show_in_menu();//pr_db($products); ?>
<ul class="nav navbar-nav">
    <?php foreach ($products as $row):
        $row=mod("product")->add_info($row);?>
        <li>
            <a href="<?php echo $row->_url_view ?>"
               title="<?php echo $row->name ?>">
                <?php echo $row->name ?></a>
        </li>
    <?php endforeach; ?>
</ul>
<?php echo $mainmenu ?>
<div class="logo pull-left">
    <a href="<?php echo site_url() ?>" class="logo"><img src="<?php echo widget('site')->info_image() ?>"
                                                         alt="logo"></a>
</div>
<?php widget("user")->account_panel() ?>
<span>Holine: <?php echo widget('site')->info("hotline") ?></span>
 <?php */ ?>