
<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var $main = $('#main_content');
		
		var handler = {

			init: function()
			{
				$main.find('form').nstUI('formAction', {
					event_complete: function(data)
					{
						handler.success({
							type: data['data']['type'],
							amount: data['data']['_amount'],
							amount_discount: data['data']['_amount_discount'],
						});
					},
				});
			},
			
			success: function(data)
			{
				alert(this.success_message(data));

				this.reset();
			},

			success_lang: "Thẻ {type} {amount} của bạn đã nạp thành công.\nBạn đã được cộng {amount_discount} vào tài khoản",
			
			success_message: function(data)
			{
				var result = this.success_lang;
				
				$.each(['type', 'amount', 'amount_discount'], function(i, p)
				{
					result = result.replace('{'+p+'}', data[p]);
				});

				return result;
			},

			reset: function()
			{
				$.each(['code', 'serial'], function(i, p)
				{
					$main.find('form [name='+p+']').val('');
				});
			},
			
		};

		
		handler.init();
		
	});
})(jQuery);
</script>
