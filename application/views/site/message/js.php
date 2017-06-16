<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		$('a.action_spam').click(function(){
			if(!confirm("<?php echo lang('spam_confirm')?>"))
			{
				return false;
			}
			var $t     = $(this);
			var $id    = $t.data('id');

			jQuery($t).nstUI('loadAjax', {
				url: '<?php echo site_url('message/spam')?>',
				data: {'id' : $id},
				event_complete: function(data)
				{
					alert('<?php echo lang('spam_completed')?>');
					$t.text('<?php echo lang('spamed')?>');
					$t.attr('title', '<?php echo lang('spamed')?>');
				},
				event_error: function(data)
				{

				}
			});
			return false;
		});

	});
})(jQuery);
</script>