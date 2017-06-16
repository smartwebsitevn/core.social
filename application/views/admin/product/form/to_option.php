<?php
$option_selected = $info['option_selected'];
$option_value_selected = $info['option_value_selected'];

$option_selected_tabs_html = '';
$option_selected_content_html = '';

if (!empty($option_selected))
    foreach ($option_selected as $row) {
        $option_name = '';
        $option_type = '';
        foreach ($options as $row2) {
            if ($row2->id == $row->option_id) {
                $option_name = $row2->name;
                $option_type = $row2->type;
                break;
            }
        }

        $option_selected_tabs_html .=
            '<li>
			<a href="#tab-option' . $row->id . '" data-toggle="tab" data-expanded="true">
				<i class="fa fa-minus-circle" onclick="$(this).parent().parent().remove(); $(\'#tab-option' . $row->id . '\').remove()"></i><span> ' . $option_name . '</span>
			</a>
		</li>';

        // option value
        if (in_array($option_type, array('radio', 'select', 'checkbox'))) {
            $table = '';
            if ($option_value_selected)
                foreach ($option_value_selected as $selValue) {
                    if ($selValue->product_option_id == $row->id) {
                        $select = '';
                        foreach ($option_values as $value) {
                            if ($value->option_id == $selValue->option_id)
                                $select .= '<option value="' . $value->id . '" ' . ($value->id == $selValue->option_value_id ? 'selected' : '') . ' >' . $value->name . '</option>';
                        }
                        $table .= '<tr>
							<td>
								<select name="option_value[' . $row->id . '][' . $selValue->id . '][option_value_id]" class="form-control">
									<option value="0"> - - Chưa chọn - - </option>
									' . $select . '
								</select>
							</td>
							<td>
								<input type="number" name="option_value[' . $row->id . '][' . $selValue->id . '][quantity]" class="form-control" value="' . $selValue->quantity . '" />
							</td>
							<td>
								<select name="option_value[' . $row->id . '][' . $selValue->id . '][subtract]" class="form-control">
									<option value="1">Có</option>
									<option value="0" ' . ($selValue->subtract == 0 ? 'selected' : '') . '>Không</option>
								</select>
							</td>
							<td>
								<select name="option_value[' . $row->id . '][' . $selValue->id . '][price_prefix]" class="form-control">
									<option value="+">+</option>
									<option value="-" ' . ($selValue->price_prefix == '-' ? 'selected' : '') . '>-</option>
								</select>
								<input type="text" name="option_value[' . $row->id . '][' . $selValue->id . '][price]" class="input_number form-control" value="' . $selValue->price . '" />
							</td>
							<td>
								<select name="option_value[' . $row->id . '][' . $selValue->id . '][points_prefix]" class="form-control">
									<option value="+">+</option>
									<option value="-" ' . ($selValue->points_prefix == '-' ? 'selected' : '') . '>-</option>
								</select>
								<input type="number" name="option_value[' . $row->id . '][' . $selValue->id . '][points]" class="form-control" value="' . $selValue->points . '" />
							</td>
							<td>
								<select name="option_value[' . $row->id . '][' . $selValue->id . '][weight_prefix]" class="form-control">
									<option value="+">+</option>
									<option value="-" ' . ($selValue->weight_prefix == '-' ? 'selected' : '') . '>-</option>
								</select>
								<input type="number" name="option_value[' . $row->id . '][' . $selValue->id . '][weight]" class="form-control" value="' . $selValue->weight . '" />
							</td>
							<td>
								<a href="javascript:;" class="btn btn-danger" onclick="$(this).parent().parent().remove();"><i class="fa fa-minus-circle"></i></a>
							</td>
						</tr>';
                    }
                }

            if ($table) {
                $arrs = array();
                foreach ($option_values as $value) {
                    if ($value->option_id == $row->option_id)
                        $arrs[] = $value;
                }
                $table = '
				<script type="text/javascript">
				var option_value_objects_' . $row->id . ' = \'' . json_encode($arrs) . '\';
				</script>
				<table id="option-table-' . $row->id . '" class="table table-bordered">
					<tbody>
						<tr>
							<th>Giá trị tùy chọn</th>
							<th>Số lượng</th>
							<th>Giá theo số lượng</th>
							<th>Giá</th>
							<th>Thuộc tính điểm</th>
							<th>Nặng</th>
							<th></th>
						</tr>
						' . $table . '
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>
								<a href="javascript:;" class="btn btn-primary" id="btn-add-' . $row->id . '"
									onclick="addProductOptionValue( ' . $row->id . ', \'btn-add-' . $row->id . '\', option_value_objects_' . $row->id . ' )">
									<i class="fa fa-plus-circle"></i>
								</a>
							</td>
						</tr>
					</tbody>
				</table>';
            }
        } else {
            $table = '<div class="form-group">
				<label class="col-sm-3  control-label">Giá trị tùy chọn</label>
				<div class="col-sm-9">
					<input type="text" name="option[' . $row->id . '][value]" class="form-control" value="' . $row->value . '" />
				</div>
			</div>';
        }

        $option_selected_content_html .= '
			<div class="tab-pane" id="tab-option' . $row->id . '">
				<input type="hidden" value="' . $option_type . '" name="option[' . $row->id . '][type]">
				<input type="hidden" value="' . $row->option_id . '" name="option[' . $row->id . '][id]">
				<div class="form-group">
					<label class="col-sm-3  control-label">Yêu cầu</label>
					<div class="col-sm-9">
						<select name="option[' . $row->id . '][required]" class="form-control">
							<option value="0">Không</option>
							<option value="1" ' . ($row->required ? 'selected' : '') . '>Có</option>
						</select>
					</div>
				</div>
				' . $table . '
			</div>
		';

    }

?>

<div role="tabpanel" class="tab-pane" id="option">
    <div class="row mb20">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked" id="option_select">
                <?php echo $option_selected_tabs_html ?>
            </ul>
            <select class="option_select form-control">
                <option value="">-- Chưa chọn --</option>
                <?php
                $type = '';
                foreach ($options as $row) {
                // Open group
                if( $type != $row->type )
                {
                if( $type != '' )
                {
                ?>
                </optgroup>
                <?php
                }
                ?>
                <optgroup label="<?php echo $row->type ?>">
                    <?php
                    }

                    ?>
                    <option value="<?php echo $row->id ?>" data-type="<?php echo $row->type ?>">
                        <?php echo $row->name ?>
                    </option>
                    <?php

                    $type = $row->type;
                    }
                    if ($type)
                    {
                    ?>
                </optgroup>
                <?php
                }
                ?>
            </select>
        </div>
        <div class="col-md-9 tab-content  p10" id="option_tabs">
            <?php echo $option_selected_content_html ?>
        </div>
    </div>
</div>