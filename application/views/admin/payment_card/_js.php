
<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var main = $('#form');
		
	
		// Them thuoc tinh cho san pham
		if (!main.find('#content_add_account').html())
		{
			create_form_account(1);
		}
		
		var add_account = main.find('#add_account');
		add_account.click(function()
		{
			var account = $(this).attr('_part');
			account = parseInt(account)+1;
			$(this).attr('_part', account);	
			create_form_account(account);
			
			return false;
		});
        
		/**
		 * Tao form nhap gia cau tra loi
		 */
		function create_form_account(total)
		{
			// Lay obj danh sach hien tai
			var list = main.find('#content_add_account');
			list.find('input').each(function()
			{
				$(this).attr('value', $(this).val());
			});
			
			// Lay html temp
			var temp = main.find('#poll_account_temp').html();
			temp = temp_set_value(temp, {param_name: 'name'});
			
			// Tao danh sach tuong ung
			var list_html = '';
			for (var i = 1; i <= total; i++)
			{
				var html = list.find('#account_'+i).parent().html();
				html = (!html) ? temp_set_value(temp, {account:i}) : html;
				list_html += '<div>'+html+'</div>';
			}
			
			// Cap nhat html
			list.html(list_html).show();
		} 
	});
})(jQuery);

/**
 * xoa form nhap gia cau tra loi
 */
function del_account(id)
{
	jQuery('#account_'+id).parent().remove();
	
	var add_account = $('#add_account');
	account = add_account.attr('_part');
	account = parseInt(account)-1;
	add_account.attr('_part', account);
}
</script>