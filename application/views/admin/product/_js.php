<?php //widget('admin')->upload_adv_js() ?>
<script type="text/javascript">
	(function($)
	{
		$(document).ready(function()
		{
			var type_cat_id =$('[name=type_cat_id]').val();
			load_data_types(type_cat_id);
			$(document).on('change', '[name=type_cat_id]', function () {
				var value = $(this).val();
				load_data_types(value);

			});
			// Number format
			$('.input_number').autoNumeric('init', {vMin:'0.00000000', vMax:'9999999999999999.99', aPad:false});


		});
		function load_data_types(value){
			if(value){

				$.ajax({
					type: "post",
					url: "<?php echo admin_url( t('uri')->rsegment(1) . '/get_types' ) ?>",
					data: {type_cat_id: value,product_id:<?php echo $info?$info['id']:0?>},
					success: function (output) {
						$('#data_types').html(output);
					}
				});
			}
		}
	})(jQuery);
</script>
