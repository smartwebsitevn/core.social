<?php 
function get_repeat_css($repeat)
{
	$style = 'repeat';
	switch ($repeat)
	{
		case 'none':
			{
				$style = 'no-repeat';
				break;
			}
		case 'all':
			{
				$style = 'repeat';
				break;
			}	
		case 'x':
			{
				$style = 'repeat-x';
				break;
			}
		case 'y':
			{
				$style = 'repeat-y';
				break;
			}		
	}
	return $style;
}
?>

<style type="text/css">

<?php
if(isset($configs['body'])):
$body = $configs['body'];
?>
#main{
	<?php if($body->type == 'image'){?>
	background:url(<?php echo $body->value?>) <?php echo get_repeat_css($body->repeat)?> !important;
	<?php }else{?>
	background:<?php echo $body->value?> !important;
	<?php }?>
}
<?php endif; ?>

<?php 
if(isset($configs['menu_header'])):
$menu_header = $configs['menu_header'];
?>
#header{
	<?php if($menu_header->type == 'image'){?>
	background:url(<?php echo $menu_header->value?>) <?php echo get_repeat_css($menu_header->repeat)?> !important;
	<?php }else{?>
	background:<?php echo $menu_header->value?> !important;
	<?php }?>
}
<?php endif;?>


<?php 
if(isset($configs['menu_footer'])):
$menu_footer = $configs['menu_footer'];
?>
#footer {
	<?php if($menu_footer->type == 'image'){?>
	background:url(<?php echo $menu_footer->value?>) <?php echo get_repeat_css($menu_footer->repeat)?> !important;
	<?php }else{?>
	background:<?php echo $menu_footer->value?> !important;
	<?php }?>
}
<?php endif;?>

<?php 
if(isset($configs['box_main'])):
$box_main = $configs['box_main'];
?>
.thong-tin .panel .panel-heading, .dau-gia .title{
	<?php if($box_main->type == 'image'){?>
	background:url(<?php echo $box_main->value?>) <?php echo get_repeat_css($box_main->repeat)?> !important;
	<?php }else{?>
	background:<?php echo $box_main->value?> !important;
	<?php }?>
}
<?php endif;?>

<?php 
if(isset($configs['box_sidebar'])):
$box_sidebar = $configs['box_sidebar'];
?>
.sidebar .panel .panel-heading{
	<?php if($box_sidebar->type == 'image'){?>
	background:url(<?php echo $box_sidebar->value?>) <?php echo get_repeat_css($box_sidebar->repeat)?> !important;
	<?php }else{?>
	background:<?php echo $box_sidebar->value?> !important;
	<?php }?>
}
<?php endif;?>

</style>
