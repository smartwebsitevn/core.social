<div role="tabpanel" class="tab-pane" id="attribute">
    <table id="attribute-table" class="table table-bordered">
        <tr>
            <th>Thuộc tính</th>
            <th>Giá trị</th>
            <th></th>
        </tr>
        <?php
        if (!empty($attribute_selected))
            foreach ($attribute_selected as $row) {
                ?>
                <tr>
                    <td>
                        <select name="attribute[<?php echo $row->id ?>][attribute]" class="form-control">
                            <option value="0"> - - Chưa chọn --</option>
                            <?php
                            foreach ($attribute_groups as $g) {
                                ?>
                                <optgroup label="<?php echo $g->name ?>">
                                    <?php
                                    foreach ($attributes as $a)
                                        if ($a->group_id == $g->id) {
                                            ?>
                                            <option
                                                value="<?php echo $a->id ?>" <?php echo($a->id == $row->attribute_id ? 'selected' : '') ?> ><?php echo $a->name ?></option>
                                            <?php
                                        }
                                    ?>
                                </optgroup>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <textarea name="attribute[<?php echo $row->id ?>][value]"
                                  class="form-control"><?php echo $row->value ?></textarea>
                    </td>
                    <td>
                        <a href="javascript:;" class="btn btn-danger" onclick="$(this).parent().parent().remove();"><i
                                class="fa fa-minus-circle"></i></a>
                    </td>
                </tr>
                <?php
            }

        ?>
        <tr>
            <td></td>
            <td></td>
            <td><a class="btn btn-primary attribute-add" href="javascript:;"><i class="fa fa-plus-circle"></i></a></td>
        </tr>
    </table>
</div>