<?php echo macro()->page_heading($combo->name) ?>
<?php echo macro()->page_body_start(); //pr($combo);?>
<form class="form-horizontal">
    <div class="combo-header clearfix">
        <div class="col-xl-2 col-lg-2 col-md-12 col-xs-12 combo-img">
            <img class="img-fluid "
                 src="<?php echo $combo->image->url_thumb; ?>"
                 alt="Guitar đệm hát" title="Guitar đệm hát"></div>
        <!--end .img-->
        <div class="col-xl-10 col-lg-10 col-md-12 col-xs-12 text">
            <div class="total clearfix">
                <!--end .price-->
                    <span class="f20 red">
                        <span class="combo-mb"> <?php echo lang('price') ?>: </span><?php echo $combo->_price_total ?>
                    </span>
                <!--end .preferential-->
            </div>
            <p class="text-justify">
                <?php echo html_entity_decode($combo->description); ?>
            </p> <!--end .text-->

            <!--end .button-->
        </div>
        <!--end .info-->
        <div class="combo-main clearfix">
            <?php if ($services["courses"]): ?>
              <!--  <div class="col-xl-3 col-lg-12 col-md-12 col-xs-12 mt20">
                    <h3 >Gồm <?php /*echo count($services["courses"]) */?> khóa học</h3>
                </div>-->
                <div class="clearfix "></div>
                <table class="table table-bordered mt40">
                    <thead>
                    <th class="col-lg-7 col-xs-12 combo-title">
                        <p><?php echo lang('course_combo') ?></p>
                    </th>
                    <!--end .title-->
                    <th class="col-lg-5 col-xs-12 combo-teacher">
                        <p><?php echo lang('author') ?></p>
                    </th>
                    <!--end .teacher-->
                    </thead>
                    <tbody>
                    <?php foreach ($services["courses"] as $row): //pr($row);?>
                        <tr>
                            <td class="col-lg-7 col-xs-12 combo-title">
                                <h5>
                                    <a href="<?php echo $row->_url_view ?>"
                                       target="_blank"> <?php echo $row->name ?></a>
                                </h5>
                            </td>
                            <!--end .title-->

                            <td class="col-lg-5 col-xs-12 combo-teacher">
                                <p><span class="combo-mb"><?php echo $row->_author_name ?></p>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>


            <?php if ($services["lessons"]): ?>
                <!--<div class="col-xl-3 col-lg-12 col-md-12 col-xs-12 mt20">
                    <h3 >Gồm <?php /*echo count($services["lessons"]) */?> bài học</h3>
                </div>-->
                <table class="table table-bordered mt40">
                    <thead>
                    <th class="col-lg-7 col-xs-12 combo-title">
                        <p><?php echo lang('lesson_combo') ?></p>
                    </th>
                    <!--end .title-->
                    <th class="col-lg-5 col-xs-12 combo-teacher">
                        <p><?php echo lang('author') ?></p>
                    </th>
                    <!--end .teacher-->
                    </th>
                    </thead>
                    <tbody>
                    <?php foreach ($services["lessons"] as $row): //pr($row);?>
                        <tr>
                            <td class="col-lg-7 col-xs-12 combo-title">
                                <h5>
                                    <a href="<?php echo $row->_url_view ?>"
                                       target="_blank"> <?php echo $row->name ?></a>
                                </h5>
                            </td>
                            <!--end .title-->

                            <td class="col-lg-5 col-xs-12 combo-teacher">
                                <p><?php echo $row->_author_name ?></p>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <div class=" clearfix">
                <a class="btn btn-default pull-right"
                   href="<?php echo $combo->_url_buy ?>"><?php echo lang('buy_combo') ?></a>
            </div>

        </div>

        <!--end total-->
    </div>


</form>

<?php echo macro()->page_body_end() ?>
