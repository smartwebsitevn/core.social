<?php

$_macro = $this->data;

/* Tabs links */

/*$_macro['toolbar_sub'] = array(
    array('url' => admin_url('country'), 'title' => lang('country_info') ),
    array('url' => admin_url('city'), 'title' => lang('city_info'), ),
    array('url' => admin_url('geo_zone'), 'title' => lang('geo_zone_info'), 'attr'=>array('class'=>'active'),),
);*/



/* Truyền dữ liệu cho form */
$info = isset($info) ? (array)$info : null;

$_macro['form']['data'] = $info;



/* Hotel name is required */
$_macro['form']['rows'][] = array(
	'param' => 'name',
	'placeholder' => lang('geo_zone_name'),
	'req' 	=> true,
);

/* Hotel name is required */
$_macro['form']['rows'][] = array(
	'param' => 'description',
	'placeholder' => lang('geo_zone_description'),
	'name' => lang('geo_zone_description'),
	'req' 	=> true,
);

$html = '';
if(! empty($geo_zone_to_city) )
{
	foreach ($geo_zone_to_city as $row) {
		$country_html = '';
		foreach ($country as $sub) {
			$country_html .= '<option value="'.$sub->id.'"  '.( $sub->id==$row->country_id ? 'selected' : '' ).'>'.$sub->name.'</option>';
		}


		$city_html = '<option value="0">'. lang('all_zone') .'</option>';
		if( $row->country_id )
		{
			foreach ($city as $sub) {
				if( $sub->country_id == $row->country_id )
					$city_html .= '<option value="'.$sub->id.'"  '.( $sub->id==$row->city_id ? 'selected' : '' ).'>'.$sub->name.'</option>';
			}
		}

		$html .= '<tr>
			<td>
				<select class="country" name="geo_zone_to_city['.$row->id.'][country_id]" data-step="'.$row->id.'" >
					' . $country_html . '
				<select>
			</td>
			<td>
				<select class="city-'.$row->id.'" name="geo_zone_to_city['.$row->id.'][city_id]" >
					' . $city_html . '
				</select>
			</td>
			<td>
				<input type="checkbox" class="negative-'.$row->id.'" name="geo_zone_to_city['.$row->id.'][negative]" '.($row->negative?'checked':'').' value="1" >
			</td>
			<td><button type="button" class="btn btn-danger" ><i class="fa fa-minus-circle"></i></button></td>
		</tr>';
	}
}

$_macro['form']['rows'][] = array(
	'type' => 'ob',
	'value' => '<table id="zone-to-geo-zone" class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<td>'. lang('country_name') .'</td>
			<td>'. lang('city_name') .'</td>
			<td>'. lang('negative') .'</td>
			<td>&nbsp;</td>
		</tr>
	</thead>
	<tbody>
		' . $html . '
		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>
			<button type="button" class="btn btn-primary" id="insert-button" ><i class="fa fa-plus-circle"></i></button>
		</td>
		</tr>
	</tbody>
</table>
',
);

echo macro('mr::advForm')->page($_macro);

?>
<script type="text/javascript">
	country = '<?php echo addslashes( json_encode($country) ) ?>';
	step = 0;
	$('#insert-button').on('click', function(){
		var data = $.parseJSON( country );
		var select_country = create( 'select', {
			name: 'geo_zone_to_city[n_' + step + '][country_id]',
			className: 'country'
		} );
		$.each(data, function(i,k){
			var option_country = create( 'option', {
				value: k.id,
				innerHTML: k.name
			} );
			select_country.appendChild(option_country);
		});

		var select_city = create( 'select', {
			name: 'geo_zone_to_city[n_' + step + '][city_id]',
			className: 'city'
		} );
		var option_city = create( 'option', {
			value: 0,
			innerHTML: '<?php echo lang('all_zone') ?>'
		} );
		select_city.appendChild(option_city);

		var negative = create( 'input', {
			type: 'checkbox',
			name: 'geo_zone_to_city[n_' + step + '][negative]',
			value: 1
		} );

		var button = create( 'button', {
			type: 'button',
			className: 'btn btn-danger'
		} );
		var icon = create( 'i', {
			className: 'fa fa-minus-circle'
		} );
		button.appendChild(icon);

		var tr = create( 'tr', {} );
		tr.appendChild( createAppend( 'td', select_country ) );
		tr.appendChild( createAppend( 'td', select_city ) );
		tr.appendChild( createAppend( 'td', negative ) );
		tr.appendChild( createAppend( 'td', button ) );

		$(this).parent().parent().before(tr);
		step++;
	});

	$('#zone-to-geo-zone').on('click', '.btn-danger', function(){
		$(this).parent().parent().remove();
	});

	$('#zone-to-geo-zone').on('change', '.country', function(){
		var parent = $(this).parent().parent();
		var val = $(this).val();

		$.ajax({
            type: "post",
            url: "<?php echo admin_url( t('uri')->rsegment(1) .'/loadCity' ) ?>",
            data: { 
                value: val
            },
            success: function(output){
                var data = $.parseJSON( output );
                var city = parent.find('.city');

                city.find('option').remove();
                var option_city = create( 'option', {
					value: 0,
					innerHTML: '<?php echo lang('all_zone') ?>'
				} );
				city.append(option_city);
                $.each(data, function(i, k){
                    var option_city = create( 'option', {
						value: k.id,
						innerHTML: k.name
					} );
					city.append(option_city);
                });
            }
        });
	});

	function create( ele, data )
	{
		var ele = document.createElement(ele);
		$.each( data, function( i, k ) {
			if( i.indexOf('.') !== -1 )
			{
				var tmp = i.split('.');
				ele[tmp[0]][tmp[1]] = k;
			}
			else
			{
				ele[i] = k;
			}
		} );
		return ele;
	}

	function createAppend( ele, child )
	{
		var td = document.createElement(ele);
		if( child )
			td.appendChild(child);
		return td; 
	}
</script>