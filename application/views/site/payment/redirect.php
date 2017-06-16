
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Redirecting ...</title>
	
	<style>
	html {
		width: 100%;
		height: 100%;
	}
	body {
		margin: 0px;		
		width: 100%;
		height: 100%;			
		font-size: 12px;
		color: #444;
		font-family: Arial, Helvetica, sans-serif;
		position: relative;
	}
	div.content {
		width: 100%;
		top: 40%;
		position: absolute;
		margin-top: auto;
		text-align: center;
	}
	div.content .icon {
		display: block;
		width: 128px;
		height: 15px;
		margin: 0 auto;
		background: url("<?php echo public_url('site'); ?>/css/img/loaders/loader12.gif") no-repeat center; 
	}
	</style>
	
	<script type="text/javascript" src="<?php echo public_url('js') ?>/jquery/jquery.min.js"></script>
</head>

<body>
	<div class="content">
		<span class="icon"></span>
		<?php $this->load->view($temp); ?>
	</div>
</body>

</html>
