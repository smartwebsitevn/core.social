<?php $_id = '_' . random_string('unique'); ?>

<div role="tabpanel" class="tab-pane" id="<?php echo $_id ?>">
    <table id="addon" class="table table-bordered">
        <thead>
        <tr>
            <th>Phụ mục</th>
            <th>Giá Tiền</th>
            <th>Sắp xếp</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php
        $values = $info['_addons'];
        if (!empty($values))
            foreach ($values as $row) {
                ?>
                <tr>
                    <td>
                        <select name="addon[<?php echo $row->id ?>][id]" class=" form-control">
                            <option value=""></option>
                            <?php foreach ($addons as $s) : ?>
                                <option value="<?php echo $s->id; ?>"  <?php echo($s->id == $row->addon_id ? 'selected' : '') ?>><?php echo $s->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="addon[<?php echo $row->id ?>][price_prefix]" class="form-control pull-left" style="width:50px">
                            <option value="+">+</option>
                            <option value="-" <?php echo  (isset($row->price_prefix) && $row->price_prefix == '-')?'selected':'' ?>>-</option>
                        </select>
                        <input type="text" class="form-control input_number" style="width:150px"
                               name="addon[<?php echo $row->id ?>][price]" value="<?php echo $row->price ?>"/>
                    </td>
                    <td>

                        <input type="number" name="addon[<?php echo $row->id ?>][sort]"
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
            <td><a class="btn btn-primary act_addon_add" href="javascript:;"><i class="fa fa-plus-circle"></i></a></td>
        </tr>
        </tfoot>
    </table>
    <table id="addon_temp" class="hide">
        <tr>
            <td>
                <select {param_name}="addon[{count}][id]" class=" form-control">
                    <option value=""></option>
                    <?php foreach ($addons as $s) : ?>
                        <option value="<?php echo $s->id; ?>"><?php echo $s->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <select  {param_name}="addon[{count}][price_prefix]" class="form-control pull-left" style="width:50px">
                    <option value="+">+</option>
                    <option value="-">-</option>
                </select>
                <input type="text" class="form-control input_number" style="width:150px"
                       {param_name}="addon[{count}][price]"/>
            </td>
            <td>
                <input type="number" {param_name}="addon[{count}][sort]"/>
            </td>
            <td>
                <a href="javascript:;" class="btn btn-danger" onclick="$(this).parent().parent().remove();">
                    <i class="fa fa-minus-circle"></i>
                </a>
            </td>
        </tr>
    </table>
</div>


<script type="text/javascript">


    (function ($) {
        $(document).ready(function () {
            var $main = $('#<?php echo $_id; ?>');
            // du lieu da chon
            var $data_selected = $main.find('#addon tbody');
            // du lieu mau
            var $data_form = $main.find('#addon_temp tbody');
            var form = {
                total: <?php echo count($values) ?>,
                update: function () {
                    form.total = form.total + 1;
                    // su ly thay the
                    var html = $data_form.html();
                    html = temp_set_value(html, {param_name: 'name'});
                    html = temp_set_value(html, {count: "n" + form.total});
                    // Cap nhat html
                    $data_selected.append(html).show();
                },
                init: function () {

                    if (form.total == 0)
                        form.update()

                    $main.find('.act_addon_add').click(function () {

                        for (i = 1; i <= 1; i++)
                            form.update()
                    });
                },

            };
            form.init();

        });

    })(jQuery);

    function act_addon_del($this) {
        if (confirm("Confirm Deleted?") == true) {
            $($this).parents('li.link_item_wrap').remove();
        }
    }
</script>
<!--  Temp -->
