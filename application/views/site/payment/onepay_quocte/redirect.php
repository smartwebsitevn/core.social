<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>Terms And Conditions - Payment</title>
	
	<style>
	@charset "utf-8";
	/* CSS Document */

	*{
		margin:0;
		padding:0;
	}
	a{
		text-decoration:none;
	}
	.fl{
		float:left;
	}
	.fr{
		float:right;
	}
	#wrapper{
		width:980px;
		margin:50px auto;
	}
	.box{
		height:200px;
		overflow-y: scroll;
		margin:10px 0;
	}
	.box .title{
		font-weight:bold;
		text-align:center;
	}
	.box .cont{
		margin:10px;
	}
	.chk_1{
		text-align:center;
	}
	.chk_1 a{
	}
	.btn_1{
		text-align:center;
		margin:10px 0;
	}
	.btn_1 a{
		background: #3E76AF;
		padding:5px 10px;
		text-transform:uppercase;
		color:#FFF;
		font-weight:bold;
	}
	.btn_1 a:hover{
		opacity:0.8;
		color:#000;
	}
	.security{
		text-align:center;
		overflow:hidden;
	}
	.security .img_1 img{
		width:50px; 
		height:20px;
	}
	label{
		cursor: pointer;
	}
	</style>
	
	<script type="text/javascript" src="<?php echo public_url('js') ?>/jquery/jquery.min.js"></script>
	
	<script type="text/javascript">
	(function($)
	{
		$(document).ready(function()
		{
			$('#act_pay').click(function()
			{
				if ($('input[name=rule]').is(':checked'))
				{
					return true;
				}
				
				alert('You must agreed with terms and conditions.');
				return false;
			});
		});
	})(jQuery);
	</script>

</head>

<body>
	<div id="wrapper">
    	<h3>Terms And Conditions:</h3>
        <div class="box">
        	<div class="title">Terms And Conditions:</div>
            <div class="cont"><?php echo site_get_info('onepay_rule'); ?></div>
        </div>
        
        <div class="chk_1"><label><input name="rule" type="checkbox"> I agreed with terms and conditions</div>
        <div class="btn_1"><a href="<?php echo $url_payment; ?>" id="act_pay">check out today</a></div>

    </div>
</body>
</html>
