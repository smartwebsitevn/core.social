
<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		$('form').submit();
	});
})(jQuery);
</script>

<h2>Redirecting to wmtransfer.com</h2>

<form action="<?php echo $action; ?>" method="post">
	<?php foreach ($params as $p => $v): ?>
		<input type="hidden" name="<?php echo $p; ?>" value="<?php echo $v; ?>"/>
	<?php endforeach; ?>
</form>