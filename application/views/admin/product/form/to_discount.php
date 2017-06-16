<div role="tabpanel" class="tab-pane" id="discount">
    <table id="discount-table" class="table table-bordered">
        <thead>

        <tr>
            <th>Nhóm khách hàng</th>
            <th>Số lượng</th>
            <th>Giá 1 sản phẩm</th>
            <th>Từ ngày</th>
            <th>Đến ngày</th>
            <th>Sắp xếp</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php
        $discount=$info['discount'];

        if (!empty($discount))
            foreach ($discount as $row) {
                ?>
                <tr>
                    <td>
                        <select name="discount[<?php echo $row->id ?>][customer_group_id]">
                            <option value="0">default</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="discount[<?php echo $row->id ?>][quantity]"
                               value="<?php echo $row->quantity ?>"/>
                    </td>
                    <td>
                        <input type="text" class="form-control input_number"
                               name="discount[<?php echo $row->id ?>][price]" value="<?php echo $row->price ?>"/>
                    </td>
                    <td>
                        <input type="text" class="date_picker mask_datess"
                               name="discount[<?php echo $row->id ?>][begin_date]"
                               value="<?php echo date('d-m-Y', $row->begin_date) ?>">
                    </td>
                    <td>
                        <input type="text" class="date_picker mask_datess"
                               name="discount[<?php echo $row->id ?>][end_date]"
                               value="<?php echo date('d-m-Y', $row->end_date) ?>">
                    </td>
                    <td>
                        <input type="number" name="discount[<?php echo $row->id ?>][sort]"
                               value="<?php echo $row->sort ?>"/>
                    </td>
                    <td>
                        <a href="javascript:;" class="btn btn-danger" onclick="$(this).parent().parent().remove();">
                            <i class="fa fa-minus-circle"></i>
                        </a>
                    </td>
                </tr>
                <?php
            }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><a class="btn btn-primary discount-add" href="javascript:;"><i class="fa fa-plus-circle"></i></a></td>
        </tr>
        </tfoot>
    </table>
</div>