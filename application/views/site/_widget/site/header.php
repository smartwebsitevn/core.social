<?php
$asset = public_url();
$asset_theme = $asset . '/site/theme/';

?>

<div class="c_header c_header--desktop ud-angular-loader js-hellobar has-hello-bar white">
        <div class="c_header__inner">
            <div class="c_header__logo-container">
                <a class="c_header__logo-wrap"  href="<?php echo site_url() ?>">
                    <img class="udemy-logo"  src="<?php echo widget("site")->setting_image() ?>" class="img-responsive"  alt="logo" />
                </a>
            </div>
            <div class="c_header__left">
                <button class="c_header__mobile-bt mobile-bt--overflow udi udi-menu"></button>
                <div id="dropdownButton" class="dropdown dropdown--topics">
                    <div class="dropdown__toggle">
						<span class="dropdown__main-link">
							Browser
							<span class="caret"></span>
						</span>
                    </div>
                    <div class="dropdown__menu">
                        <?php   echo  model('product_cat')->get_tree(); ?>
                    </div>
                </div>
                <div class="c_header__search search-open">
                    <button class="c_header__mobile-bt mobile-bt--search udi udi-search"></button>
                    <div class="quick-search__form h100p">
                        <form id="searchbox" class="h100p ng-pristine ng-valid ng-valid-maxlength">
                            <input type="text" name="q" value="" placeholder="Search for cours" class="quick-search__input ng-pristine ng-valid ng-valid-maxlength ng-touched user-success">
                            <button type="submit" class="udi udi-search quick-search__btn"></button>
                        </form>
                    </div>
                    <button class="mobile-bt--search-cancel">Cancel</button>
                </div>
            </div>
            <div class="c_header__mobile-spacer">&nbsp;</div>
            <div class="c_header__right">
                <?php widget("user")->account_panel("panel_st1") ?>
            </div>
        </div>
        <nav class="overflow-nav">
            <div class="overflow-nav--main">
                <ul class="overflow-nav__links">
                    <li class="menu__link menu__link--bottom">
                        <a href="javascript:void(0)">
                            <span class="menu__title">Login</span>
                        </a>
                    </li>
                    <li class="menu__link menu__link--bottom">
                        <a href="javascript:void(0)">
                            <span class="menu__title">Sign up</span>
                        </a>
                    </li>
                    <li class="menu__link menu__link--bottom">
                        <a href="#">
                            <span class="menu__title">Become an Instructor</span>
                        </a>
                    </li>


                    <li class="menu__link menu__link--bottom">
                        <a href="#">
                            <span class="menu__title">Udemy for Business</span>
                        </a>
                    </li>

                    <li class="menu__link menu__link--bottom">
                        <a href="#">
                            <span class="menu__title">Help</span>
                        </a>
                    </li>
                    <li class="menu__link menu__link--bottom">
                        <div class="tooltip-container tooltip-container-responsive" data-purpose="site-language-list-link">
                            <a class="menu__title">Site</a>
                            <div class="tooltip tooltip-neutral bottom">
                                <ul class="lang-dropdown dropdown-menu tooltip-inner" data-purpose="site-language-list">
                                    <li data-purpose="site-language-de">
                                        <a href="/de/">Deutsch</a>
                                    </li>
                                    <li data-purpose="site-language-es">
                                        <a href="/es/">Español</a>
                                    </li>
                                    <li data-purpose="site-language-fr">
                                        <a href="/fr/">Français</a>
                                    </li>
                                    <li data-purpose="site-language-pt">
                                        <a href="/pt/">Português</a>
                                    </li>
                                    <li data-purpose="site-language-jp">
                                        <a href="/jp/">日本語</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="c_header__overlay"></div>
    </div>
<div class="c_link-bar">
        <div class="container oh">
            <?php   echo  model('product_cat')->get_tree2(); ?>
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
 <?php */?>