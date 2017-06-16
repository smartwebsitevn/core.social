<?php
$_macro = $this->data;

/* Tabs links */
//$_macro['toolbar_sub'] = $this->_toolbar;


/* Truyền dữ liệu cho form */
$info = isset($info) ? (array)$info : null;
$_macro['form']['data'] = $info;

/* Name is required */
$_macro['form']['rows'][] = array(
	'param' => 'name',
	'req' 	=> true,
);
$_macro['form']['rows'][] = array(
	'param' => 'description'
);


$_macro['form']['rows'][] = array(
	'type' 	=> 'custom',
	'html' 	=> $this->load->view('admin/tax_class/_form/_to_rate', array( 
		'_to_rate' => $info['_to_rate'],
		'rates' => $rates
	), true)
);
$_macro['form']['rows'][] = array(
	'type' 	=> 'custom',
	'html' 	=> '</div>
	</div>
	</div>'
);


echo macro('mr::advForm')->page($_macro);
?>
<script type="text/javascript">
	var _to_rate = 1;
	var rates = <?php echo json_encode( array_gets( $rates, array('id', 'name') ) ) ?>;

	$('._to_rate-add').on('click',function(){
		var table = document.getElementById('_to_rate-table');
		
		// Mức thuế
		var select = create( 'select', { name: "_to_rate[n"+_to_rate+"][rate_id]" } );
		fillOption( select, rates );

		// Sap xep
		var piority = create( 'input', {
			type: "number",
			value: 0,
			name: "_to_rate[n"+_to_rate+"][piority]"
		} );
		

		// Remove
		var remove = create( 'a', {
			href: 'javascript:;',
			className: 'btn btn-danger'
		} );
		remove.onclick = function(){
			this.parentElement.parentElement.parentElement.removeChild(this.parentElement.parentElement);
		};
		var i = create( 'i', { className: "fa fa-minus-circle" } );
		remove.appendChild(i);

		// Them row
		var tr = createEle('tr', createEle('td',select));
		tr.appendChild(createEle('td',piority));
		tr.appendChild(createEle('td',remove));
		tr.dataset.id = 'n'+_to_rate;

		$(this).parent().parent().before(tr);
		_to_rate++;
	});

	function fillOption( ele, obj )
	{
		$.each( obj, function(i,k) {
			if( typeof k == 'object' )
			{
				var optgroup = create( "optgroup", {
					label: i
				} );
				$.each( k, function( k1, k2 ) {
					var opt = create( "option", {
						value: k1,
						text: k2
					} );
					optgroup.appendChild( opt );
				} );
			    ele.appendChild(optgroup);
			}
			else
			{
				var opt = create( "option", {
					value: i,
					text: k
				} );
			    ele.appendChild(opt);
			}

			
		} );
	}

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

	function createEle( ele, child )
	{
		var td = document.createElement(ele);
		if( child )
			td.appendChild(child);
		return td; 
	}
</script>

