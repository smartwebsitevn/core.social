<?php //widget('admin')->upload_adv_js() ?>
<script type="text/javascript">
	(function($)
	{
		$(document).ready(function()
		{
			// Number format
			$('.input_number').autoNumeric('init', {vMin:'0.00000000', vMax:'9999999999999999.99', aPad:false});


		});
	})(jQuery);
</script>

<script type="text/javascript">

	$('#page-wrapper').on( 'click', '#product-tabs a', function(e){
		e.preventDefault();
		$(this).tab('show');
	});


	/**
	 *
	 * Option select
	 * auto generator js
	 *
	 */
	var option = 1;
	var option_value = {};
	$('.option_select').on( 'change', function(){
		var id = 'tab-option-n' + option;
		var val = $(this).val();
		var type = $(this).find('option:selected').data('type');
		option_value['o'+option] = 1;

		/* Thêm tabs option left */
		var tab = create( 'a', {
			'href': '#'+id,
			'dataset.toggle': 'tab',
			'dataset.expanded': 'true',
			'onclick': function() {
				$('#'+id).tab('show');
			}
		} );

		// remove button
		tab.appendChild( create( 'i', {
			'className': "fa fa-minus-circle",
			'onclick': function() {
				$('#'+id).remove();
				$(tab)[0].remove();
			}
		} ) );

		// Tab text
		tab.appendChild( create( 'span', {
			innerHTML: ' ' + $(this).find('option:selected').text()
		} ) );

		$('#option_select').append( createEle('li',tab) );

		/* Thêm tabs content options right */
		var div = create( 'div', {
			className: "tab-pane",
			id: id
		} );

		// Hidden type
		var hidden = create( 'input', {
			type: "hidden",
			value: type,
			name: "option[o"+option+"][type]"
		} );
		div.appendChild(hidden);

		hidden = create( 'input', {
			type: "hidden",
			value: val,
			name: "option[o"+option+"][id]"
		} );
		div.appendChild(hidden);

		// Select require
		var select = function() {
			var require = create( 'div', { className: "form-group" } );

			var label = create( 'label', {
				className: "col-sm-3  control-label",
				innerHTML: "Yêu cầu"
			} );
			require.appendChild(label);

			var value = create( 'div', { className: "col-sm-9" } );
			var select = create( 'select', {
				name: "option[o"+option+"][required]",
				className: "form-control"
			} );

			var options = {
				0: 'Không',
				1: 'Có'
			};
			fillOption( select, options );

			value.appendChild( select );
			require.appendChild(value);
			return require;
		};

		div.appendChild( select() );


		if( type == 'select' || type == 'checkbox' || type  == 'radio' )
		{

			// Bảng option value
			(function () {

				// Tạo bảng
				var table = create( 'table', {
					id: "option-table",
					className: "table table-bordered"
				} );

				// Tạo header
				var ths = {
					0: 'Giá trị tùy chọn',
					1: 'Số lượng',
					2: 'Trừ số lượng khách đã mua',
					3: 'Giá',
					4: 'Thuộc tính điểm',
					5: 'Nặng',
					6: ''
				}
				var trh = create( 'tr', {} );
				$.each( ths, function(i,k) {
					trh.appendChild(
						create( 'th', { innerHTML: k } )
					);
				} );

				// Tạo empty row
				var trd = create( 'tr', {} ), td;
				for( var i = 0; i < 6; i++ )
				{
					trd.appendChild(
						create( 'td', {} )
					);
				}

				// Add button

				var i = create( 'i', { className: "fa fa-plus-circle" } );
				var tbody = create( 'tbody', {} );
				var cur_option = option;

				// Get list option value
				$.ajax({
					url: "<?php echo admin_url('product/loadOptionValue') ?>?option_id="+val,
					type: "GET",
					success: function(output)
					{
						var add = create( 'a', {
							href: 'javascript:;',
							className: 'btn btn-primary',
							id: 'btn-add-'+cur_option,
							onclick: function() {
								addProductOptionValue( 'o'+cur_option, 'btn-add-'+cur_option, output )
							}
						} );

						add.appendChild(i);
						trd.appendChild( createEle( 'td', add ) );

						tbody.appendChild(trh);
						tbody.appendChild(trd);
						table.appendChild(tbody);

						div.appendChild( table );

						$('#option_tabs').append(div);
					}
				});
				option++;


			})();

		}
		else
		{

			var text = function() {
				var text = document.createElement('div');
				text.className = "form-group";

				// Label
				text.appendChild(create( 'label', {
					className: "col-sm-3  control-label",
					innerHTML: "Giá trị tùy chọn"
				} ));

				var right = create( 'div', { className: "col-sm-9" } );
				var input = create( 'input', {
					type: type,
					name: "option[o"+option+"][value]",
					className: "form-control"
				} );
				right.appendChild( input );
				text.appendChild( right );
				return text;
			};
			div.appendChild( text() );

			$('#option_tabs').append(div);
			option++;
		}

	});




	/**
	 * Bảng Product option value
	 * auto generator
	 *
	 */
	function addProductOptionValue( option_id, btn_id, option_values )
	{
		// Option select
		var select = create( 'select', {
			name: 'option_value['+option_id+'][v'+option_value[option_id]+'][option_value_id]',
			className: 'form-control'
		} );

		option_values = $.parseJSON( option_values );
		var opt = create( 'option', {
			value: 0,
			text: ' - - Chưa chọn - - '
		} );
		select.appendChild( opt );
		$.each( option_values, function(i, k) {
			var opt = create( 'option', {
				value: k.id,
				text: k.name
			} );
			select.appendChild( opt );
		} );

		// Số lượng
		var input = create( 'input', {
			type: 'number',
			name: 'option_value['+option_id+'][v'+option_value[option_id]+'][quantity]',
			value: 0,
			className: 'form-control'
		} );

		// Trừ số lượng khách đã mua
		var subtract = create( 'select', {
			name: 'option_value['+option_id+'][v'+option_value[option_id]+'][subtract]',
			className: 'form-control'
		} );
		opt = create( 'option', {
			value: 1,
			text: 'Có'
		} );
		subtract.appendChild( opt );
		opt = create( 'option', {
			value: 0,
			text: 'Không'
		} );
		subtract.appendChild( opt );

		var build_prefix_input = function( id, field, prefix )
		{
			var prefix = create( 'select', {
				name: 'option_value['+option_id+'][v'+option_value[option_id]+']['+prefix+']',
				className: 'form-control'
			} );
			var opt = create( 'option', {
				value: '+',
				text: '+'
			} );
			prefix.appendChild( opt );
			opt = create( 'option', {
				value: '-',
				text: '-'
			} );
			prefix.appendChild( opt );
			var field = create( 'input', {
				type: ( field == 'price' ? 'text' : 'number' ),
				name: 'option_value['+option_id+'][v'+option_value[option_id]+']['+field+']',
				value: 0,
				className: ( field == 'price' ? 'input_number' : '' ) + ' form-control'
			} );
			var td = createEle( 'td', prefix );
			td.appendChild( field );
			return td;
		};


		// Giá
		var price = build_prefix_input( option_value[option_id], 'price', 'price_prefix' );

		// Thuộc tính điểm
		var points = build_prefix_input( option_value[option_id], 'points', 'points_prefix' );

		// Cân nặng
		var weight = build_prefix_input( option_value[option_id], 'weight', 'weight_prefix' );


		// Remove
		var remove = create( 'a', {
			href: 'javascript:;',
			className: 'btn btn-danger'
		} );
		remove.onclick = function(){
			this.parentElement.parentElement.parentElement.removeChild(this.parentElement.parentElement);
		};
		remove.appendChild(
			create( 'i', { className: "fa fa-minus-circle" } )
		);


		var tr = createEle( 'tr', createEle( 'td', select ) );
		tr.appendChild( createEle( 'td', input ) );
		tr.appendChild( createEle( 'td', subtract ) );
		tr.appendChild( price );
		tr.appendChild( points );
		tr.appendChild( weight );
		tr.appendChild( createEle( 'td', remove ) );
		$( '#' + btn_id ).parent().parent().before(tr);


		$('.input_number').autoNumeric(
			'init', {
				vMin:'0.00000000',
				vMax:'9999999999999999.99',
				aPad:false
			}
		);
		option_value[option_id]++;
	}





	/**
	 *
	 * Attribute auto generator js
	 *
	 */
	var attribute = 1;
	<?php
		$attribute_array = array();
		foreach( $attribute_groups as $ag )
		{
			$attribute_array[$ag->name] = array();
			foreach ($attributes as $a) {
				if( $a->group_id == $ag->id )
					$attribute_array[$ag->name][$a->id] = $a->name;
			}
		}
	?>
	var attributes = <?php echo json_encode($attribute_array) ?>;
	$('.attribute-add').on('click',function(){
		var table = document.getElementById('attribute-table');

		// Select attribute
		var select = create( 'select', {
			name: "attribute[a"+attribute+"][attribute]",
			className: "form-control"
		} );
		fillOption( select, attributes );

		// Attribute value
		var value = create( 'textarea', {
			className: "form-control",
			name: "attribute[a"+attribute+"][value]"
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
		tr.appendChild(createEle('td',value));
		tr.appendChild(createEle('td',remove));
		tr.dataset.id = 'a'+attribute;

		$(this).parent().parent().before(tr);
		attribute++;
	});


	/**
	 *
	 * Discount auto generator js
	 *
	 */
	var discount = 1;
	var special = 1;
	var customer_groups = { 0: 'default' };

	$('.discount-add').on('click',function(){

		// Nhom khach hang
		var select = create( 'select', { name: "discount[n"+discount+"][customer_group_id]" } );
		fillOption( select, customer_groups );

		// So luong mua
		var quantity = create( 'input', {
			type: "number",
			value: 1,
			name: "discount[n"+discount+"][quantity]"
		} );


		// Gia mua
		var price = create( 'input', {
			type: "text",
			className: "form-control input_number",
			name: "discount[n"+discount+"][price]"
		} );

		// Tu ngay
		var from = create( 'input', {
			type: "text",
			className: "date_picker mask_datess",
			name: "discount[n"+discount+"][begin_date]"
		} );

		// Den ngay
		var to = create( 'input', {
			type: "text",
			className: "date_picker mask_datess",
			name: "discount[n"+discount+"][end_date]"
		} );


		// Sap xep
		var sort = create( 'input', {
			type: "number",
			value: 0,
			name: "discount[n"+discount+"][sort]"
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
		tr.appendChild(createEle('td',quantity));
		tr.appendChild(createEle('td',price));
		tr.appendChild(createEle('td',from));
		tr.appendChild(createEle('td',to));
		tr.appendChild(createEle('td',sort));
		tr.appendChild(createEle('td',remove));
		tr.dataset.id = 'n'+discount;

		//$(this).parent().parent().before(tr);
		$("#discount-table tbody").append(tr);
		//var table = document.getElementById('discount-table');


		// Re initial
		$('.date_picker').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: 1
		});
		$('.input_number').autoNumeric('init', {vMin:'0.00000000', vMax:'9999999999999999.99', aPad:false});
		discount++;
	});



	/**
	 *
	 * Special auto generator js
	 *
	 */
	$('.special-add').on('click',function(e){
		var table = document.getElementById('special-table');

		// Nhom khach hang
		var select = document.createElement('select');
		select.name = "special[n"+special+"][customer_group_id]";

		fillOption( select, customer_groups );

		// Gia mua
		var price = create( 'input', {
			type: "text",
			className: "form-control input_number",
			name: "special[n"+special+"][price]"
		} );


		// Tu ngay
		var from = create( 'input', {
			type: "text",
			className: "date_picker mask_datess",
			name: "special[n"+special+"][begin_date]"
		} );

		// Den ngay
		var to = create( 'input', {
			type: "text",
			className: "date_picker mask_datess",
			name: "special[n"+special+"][end_date]"
		} );


		// Sap xep
		var sort = document.createElement('input');
		sort.type = "number";
		sort.value = 0;
		sort.name = "special[n"+special+"][sort]";

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

		// Them row
		var tr = createEle('tr', createEle('td',select));
		tr.appendChild(createEle('td',price));
		tr.appendChild(createEle('td',from));
		tr.appendChild(createEle('td',to));
		tr.appendChild(createEle('td',sort));
		tr.appendChild(createEle('td',remove));
		tr.dataset.id = 'n'+special;

		//e.target.parentElement.parentElement.parentElement.insertBefore(tr, e.target.parentElement.parentElement);
		$("#special-table tbody").append(tr);

		// Re initial
		$('.date_picker').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: 1
		});
		$('.input_number').autoNumeric('init', {vMin:'0.00000000', vMax:'9999999999999999.99', aPad:false});
		special++;
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