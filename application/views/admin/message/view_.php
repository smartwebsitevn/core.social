 <div class="widget mg0 form_load" id="main_popup" style="position:relative;width:920px">

	<div class="title">
		<img src="<?php echo public_url('admin'); ?>/images/icons/dark/cart.png" alt="" class="titleIcon" />
		<h6><?php echo lang('message_view'); ?></h6>
	</div>
	
	<div class="body">
	 <h5><?php echo $message->title?></h5>  
     <p><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo lang('created')?>: <?php echo $message->_created_time; ?></p>       
	 
	 <div style="padding:10px 0px">
	    <?php echo $message->content;?>
	 </div>
	 <div class="clear"></div>
	 <hr>
 <?php /* ?>

	 <h5 style="margin-top:10px"><?php echo lang('receive')?></h5>

	  <?php foreach ($receives as $receive):?>
       <?php $status = ($receive->readed > 0) ? 'readed' : 'not_readed'?>
       <?php $title = ($receive->readed > 0) ? lang('readed').' lúc '. get_date($receive->readed , 'full') : lang('not_readed')?>
       
       <a  data-toggle="tooltip" title="<?php echo $title?>"><b><i aria-hidden="true" class="fa fa-user"></i> <?php echo $receive->receive_username?></b>: <span class="<?php echo $status?>"><?php echo lang($status)?></span></a> |
    <?php endforeach;?>
 					<?php */ ?>

    <div class="clear"></div>
	</div>
</div>

 