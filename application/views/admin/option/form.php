<?php
$_macro = $this->data;

/* Tabs links */
$_macro['toolbar_sub'] = $this->_toolbar;


/* Truyền dữ liệu cho form */
$info = isset($info) ? (array)$info : null;
$_macro['form']['data'] = $info;


/* Name is required */
$_macro['form']['rows'][] = array(
	'param' => 'name',
	'req' 	=> true,
);

$_macro['form']['rows'][] = array(
  'param' => 'type',
  'type' => 'select2',
  'value' => $info['type'],
  'values' => $this->_type,
  'req'   => true
);


$_macro['form']['rows'][] = array(
	'param' => 'sort_order',
	'value' => $info['sort_order'],
	'type' => 'spinner'
);

/* Hide this record */
$_macro['form']['rows'][] = array(
	'param' 	=> 'status',
	'name'		=> lang('status'),
	'type' 		=> 'bool_status',
	'value'		=> (isset($info['status']) ? $info['status'] : 1)
);



$table = '';
if( isset($option_values) && count($option_values) )
{
  foreach ($option_values as $row) 
  {
    $arrs = array(
      'mod' => 'single',
      'file_type' => 'image',
      'status' => config('file_public', 'main'),
      'table' => 'option_value',
      'table_id' => $row->id,
      'table_field' => 'image',
      'resize' => TRUE,
      'thumb' => TRUE,
      'url_update' => current_url().'?act=update_avatar'
    );
    
    $table .= '
      <tr>
        <td><input type="text" name="option_value['.$row->id.'][value]" class="form-control" value="'.$row->name.'" /></td>
        <td>'.t('widget')->admin->upload($arrs, array(), true).'</td>
        <td><input type="number" name="option_value['.$row->id.'][sort]" value="'.$row->sort_order.'" /></td>
        <td><a href="javascript:;" class="btn btn-danger" onclick="$(this).parent().parent().remove();"><i class="fa fa-minus-circle"></i></a></td>
      </tr>
    ';
  }
}

$_macro['form']['rows'][] = array(
  'type'  => 'custom',
  'html'  => '

  <table id="option-table" class="table table-bordered">
    <tr>
      <th>Giá trị tên tùy chọn</th>
      <th width="240px">Hình ảnh</th>
      <th width="120px">Thứ tự</th>
      <th width="80px"></th>
    </tr>
    '.$table.'
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td><a class="btn btn-primary option-add" href="javascript:;"><i class="fa fa-plus-circle"></i></a></td>
    </tr>
  </table>
  '
);


echo macro('mr::advForm')->page($_macro);
?>

<script type="text/javascript">


  var option_value = 1;
  $('.option-add').on('click',function(e){
    var table = document.getElementById('option-table');
    var btn = $(this);
    
    var text = document.createElement('input');
    text.type = "text";
    text.name = "option_value[n"+option_value+"][value]";
    text.className = "form-control";

    var sort = document.createElement('input');
    sort.type = "number";
    sort.value = 0;
    sort.name = "option_value[n"+option_value+"][sort]";

    // Remove
    var remove = document.createElement('a');
    remove.href = 'javascript:;';
    remove.className = 'btn btn-danger';
    remove.onclick = function(){
      this.parentElement.parentElement.parentElement.removeChild(this.parentElement.parentElement);
    };
    var i = document.createElement('i');
    i.className = "fa fa-minus-circle";
    remove.appendChild(i);





      // Get upload
    var upload = '';
    var div = document.createElement('div');

    $.ajax({
      url: "<?php echo admin_url('option/loadUpload') ?>?option_value_id=n"+option_value,
      type: "GET",
      success: function(output)
      {
        div.innerHTML = output;

        // Them row
        var tr = createEle('tr', createEle('td',text));
        tr.appendChild(createEle('td',div));
        tr.appendChild(createEle('td',sort));
        tr.appendChild(createEle('td',remove));
        tr.dataset.id = 'n'+option_value;

        btn.parent().parent().before( tr );

        option_value++;
      }
    });

  });



  function createEle( ele, child )
  {
    var td = document.createElement(ele);
    td.appendChild(child);
    return td; 
  }
</script>