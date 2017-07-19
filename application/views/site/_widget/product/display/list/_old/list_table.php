<?php if (isset($list) && $list): ?>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th style="width: 2%">Stt</th>
                <th style="width: 60%">Tên khóa</th>
                <th style="width: 15%">Giá</th>
                <th>Đánh giá</th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1; foreach ($list as $row): //pr($row);
                ?>
                <tr >
                    <td><?php echo $i++; ?></td>
                    <td>
                        <a href="<?php echo $row->_url_view; ?>" class="mask"><?php echo $row->name; ?></a>
                        <?php view('tpl::_widget/product/display/item/info_cat', array('row' => $row)); ?>
                    </td>
                    <td><span class="label label-success"><?php echo $row->_price ?></span></td>
                    <td> <?php view('tpl::_widget/product/display/item/info_rate', array('row' => $row)); ?></td>
                </tr>

            <?php endforeach; ?>
            </tbody>
        </table>
        <div style="height: 40px"></div>
    </div>
<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>