
<?php 
	$url_count_captcha = admin_url('read_captcha/get');
	$action_update_captcha = admin_url('read_captcha/edit');
?>

<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var $main = $('#captcha');
		
		$main.find('form').nstUI('formAction', {
			field_load: 'captcha_load',
		
			event_complete: function(data)
			{
				$main.find('form [name=key_captcha]').val(data['key_captcha']);
				$main.find('#number_captcha').html(data['number_captcha']);
				
			}
		});


		$main.nstUI({
			method:	"loadAjax",
			loadAjax: {
				url: '<?php echo $url_count_captcha; ?>',
                field: {load: 'captcha_load', show: ''},
				datatype: 'json',
				event_complete: function(data)
				{
					if (data['complete'])
					{
						$main.find('form [name=key_captcha]').val(data['key_captcha']);
						$main.find('#number_captcha').html(data['number_captcha']);
					}
				}
			}
		});	
		
	});
})(jQuery);
</script>

<div class="widget" id="captcha">
	<div class="title">
		<img src="<?php echo public_url('admin'); ?>/images/icons/dark/list.png" class="titleIcon" />
		<h6>Captcha</h6>
	</div>
	
	<table cellpadding="0" cellspacing="0" width="100%" class="sTable myTable">
			<!-- Form -->
		<thead class="filter">
			<tr>
				<td colspan="20" style="padding:10px;">
           			<form class="form" action="<?php echo $action_update_captcha; ?>" method="post">
						<label class="mr5">
							Key:
							<input type="text" name="key_captcha" value="<?php //echo $key_captcha; ?>" class="fontB" style="width:150px;" />
						</label>
						
						<input type="submit" value="OK" class="button blueB" />
					</form>
				</td>
			</tr>
		</thead>
		<tbody>
		
			<tr>
				<td>
					<div class="left">Số lần đọc Captcha:</div>
				</td>
				
				<td class="textR fontB red">
				
					<font class="f16 red ml5" id="number_captcha">---</font>
				
				
				</td>
			</tr>
	
		</tbody>
	</table>
<div id="captcha_load" class="form_load"></div>
</div>