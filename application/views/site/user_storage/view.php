<?php //pr($filter);
$path_icon= public_url('site/theme/img/icon')
?>
<div class="page-header-wrapper">
    <div class="container">
        <div class="pull-left  breadcrumb">
            <span class="ket-qua-job">
                <a href="#0">Quản lý việc làm</a> <span>&gt;</span>
                <a href="<?php echo site_url('cancidate_company/view') ?>">Nhà tuyển dụng đã xem hồ sơ của tôi</a> <span>|</span>
                <span class="resultTotalList"><?php echo number_format($total_rows) ?></span> <?php echo lang("result")?>
            </span>
        </div>

    </div>
</div>
<div class="page-title-wrapper">
    <div class="container clearfix">
        <h1 class="title-page">Nhà tuyển dụng đã xem hồ sơ của tôi </h1>
        <!--<div class="dropdown search-dropdown">
            <div aria-expanded="true" aria-haspopup="true" data-toggle="dropdown" type="button" class="dropdown-toggle">
                <span class="search-rendered">Tất cả nhà tuyển dụng đã xem hồ sơ của tôi (30)</span>
                <span class="search-caret"></span>
            </div>

            <ul aria-labelledby="dLabel" class="dropdown-menu">
                <li class="search-results">
                    <a class="search-results-option" href="#">Any category</a>
                </li>
                <li class="search-results">
                    <a class="search-results-option" href="#">Any category</a>
                </li>
                <li class="search-results">
                    <a class="search-results-option" href="#">Any category</a>
                </li>
            </ul>
        </div>-->
    </div>
</div>

<?php  widget('company')->filter_top() ?>
<div class="columns container clearfix">

    <div class="columns-main ajaxContentList">
        <?php  widget('company')->display_list($list) ?>

    </div>

    <div class="sidebar sidebar-right ajaxContentAjax">
        <?php widget('company')->list_ads() ?>
    </div>

</div>
