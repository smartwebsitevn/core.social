
<?php
	$_id = '_'.random_string('unique');
	
	$list = explode("\n", $widget->setting['content']);
	$list = array_filter($list);
	$list = json_encode($list);
?>

<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		$('#<?php echo $_id; ?>').typed({
			strings: <?php echo $list; ?>,
			typeSpeed: 30,
			backDelay: 500,
			loop: true,
			contentType: 'html', // or text
			// defaults to false for infinite loop
			loopCount: false,
			callback: function() {},
			resetCallback: function() {}
		});
	});
})(jQuery);
</script>


<div class="row">
	<div class="bdashed">
		<div class="new">
			<div id="<?php echo $_id; ?>"></div>
		</div>
	</div>
</div>

	