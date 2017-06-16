<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var main = $('#form');
		
		// Tabs
		//main.contentTabs();
		
		// Toggle maintenance
		toggle_status_content('maintenance', 'maintenance_notice');

		// Toggle deposit
		toggle_status_content('deposit_status', 'deposit_setting');

 		// Toggle upload_server_status
		toggle_status_content('upload_server_status', 'upload_server_status_notice');

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