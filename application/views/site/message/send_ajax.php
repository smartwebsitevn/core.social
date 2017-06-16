<script type="text/javascript">
$(document).ready(function(){
	// Form handle
	var $this = $('.form_action');
	$this.nstUI('formAction', {
		field_load: $this.attr('_field_load'),
		event_complete: function(data)
		{
			$('#content_ajax').html('<h1><?php echo lang('notice_message_send_success')?></h1>');
			return false;
		},
		event_error: function(data)
		{
			// Reset captcha
			if (data['security_code'])
			{
				var captcha = $this.find('img[_captcha]').attr('id');
				if (captcha)
				{
					change_captcha(captcha);
				}
			}
		},
	});

	$('#user_look_message li a').click(function(){
		$('#user_look_message li a').removeClass('active');
		$(this).addClass('active');
		
		jQuery(this).nstUI('loadAjax', {
			url: $(this).attr('href'),
			data: {'user_look' : '1'},
			event_complete: function(data)
			{
				$('#content_ajax').html(data);
			},
			event_error: function(data)
			{

			}
		});
		return false;
    });

	// Lightbox
	$('.lightbox').nstUI('lightbox');
	
});
</script>


 <div class="not-front  user-detail">
    <header id="header">
        <div class="container">
                <div style="margin-bottom:10px" class="col-xs-12 col-sm-3 col-lg-3">
                    <?php view('site/_widget/user/header', array('user' => $user))?>  
                </div>
                
                <div class="col-xs-12 col-sm-9 col-lg-9">
                    <div class="views-model">
                        <a class="btn btn-info btn-lg close_colobox" style="padding-top:10px;height:45px">Close</a>
                    </div>
                </div>
        </div>
    </header>
    
   <section class="main-content">
           <div class="container">
               <div class="content-wrap" style="min-height:200px">
                     <div class="transaction-password form-account form_edit">
                               <div class="step-top">
                                    <ul id="user_look_message">
                                       <li><a  class="active"  href="<?php echo site_url('message/send/'.$user->id)?>" ><?php echo lang('message_send')?></a></li>        
                                       <li><a href="<?php echo site_url('message/inbox/'.$user->id)?>"><?php echo lang('message_inbox')?></a></li>
                                       <li><a href="<?php echo site_url('message/index/'.$user->id)?>"><?php echo lang('message_sended')?></a></li>   
                                       
                                    </ul>
                               </div>
                               
                             <div id="content_ajax">
                                   <?php view('site/message/user_look/send', $this->data)?>
                             </div>
                	<div class="clear"></div>
                	
                	
               </div>
          </div>
      </div>
 </section> 	
</div>	