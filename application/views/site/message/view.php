<?php echo macro()->page_heading(lang('message_view')) ?>
<?php echo macro()->page_body_start() ?>
<?php view('tpl::message/_menu', ['current' => 'sended']); ?>

                     <h4><?php echo $message_receive->title?></h4>
                     <p><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo lang('created')?>: <?php echo $message_receive->_created_time; ?> | <i class="fa fa-user" aria-hidden="true"></i> <?php echo lang('sender')?>: <?php echo $message_receive->sender_name?> (<?php echo $message_receive->sender_username?>)</p>       
                	 <?php if(isset($message_receive->user_execute->username)):?>
					<p><?php echo lang('user_execute')?>: <b style="color:red"><?php echo $message_receive->user_execute->username?></b></p>
					<?php endif;?>
                	 <div>
                	    <?php echo $message_receive->content;?>
                	 </div>
                	 <div class="clear"></div>
<?php echo macro()->page_body_end() ?>