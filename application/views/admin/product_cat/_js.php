
<script type="text/javascript">
	(function($)
	{
		$(document).ready(function()
		{
			var main = $('#form');
			// Toggle maintenance
			//toggle_status_content('user_options[status]', 'user_options_status_content');
			/**
			 * Hien thi content theo param value
			 */
			function toggle_status_content(param, content)
			{
				toggle_status_content_handle(param, content);

				main.find('input[name='+param+']').change(function()
				{
					toggle_status_content_handle(param, content);
				});
			}

			function toggle_status_content_handle(param, content)
			{
				var status = (main.find('input[name='+param+']:checked').val() == '1') ? true : false;
				var content = main.find('#'+content);

				if (status)
				{
					content.slideDown(function(){ $(this).show(); });
				}
				else
				{
					content.slideUp(function(){ $(this).hide(); });
				}
			}

		});
	})(jQuery);
</script>