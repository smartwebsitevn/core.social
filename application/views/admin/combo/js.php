<style>
.form_module{
	display:none;
}
#price_times_content .form-group .col-sm-3{
	width:50%;
}
#price_times_content .form-group .col-sm-9{
	width:50%;
}
</style>
<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var main = $('#form');

		var payment_type = main.find('select[name=payment_type]').val();
		show_payment_type(payment_type);
		function show_payment_type(payment_type)
		{
			if(payment_type == 'free')
			{
				main.find('.price').hide();
			}else if(payment_type == 'onetime'){
				main.find('#price_content').show();
				main.find('#price_times_content').hide();
			}else if(payment_type == 'recurring'){
				main.find('#price_content').hide();
				main.find('#price_times_content').show();
			}else{
				main.find('.price').hide();
		    }
		}	
		main.find('select[name=payment_type]').change(function(){
			 show_payment_type($(this).val());
	    });

	   
	
	});
})(jQuery);


</script>