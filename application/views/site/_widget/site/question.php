
<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var main = $('.tl_intro_faq');
		main.find('a.faq_toggle').click(function(){
			var id = $(this).attr('data-faq');
			$('#'+id).slideToggle();
			return false;
	    });
	});
})(jQuery);
</script>



<div class="tl_intro_faq" style="width: 100%; max-width: 800px">
      <?php foreach ($list as $k => $row):?>
         
            <div class="tl_intro_faq_title">
	            <span class="tl_intro_faq_num"><?php echo $k+1?></span> 
	            <a data-faq="faq_<?php echo $k+1?>" class="faq_toggle" href="#"><?php echo $row->title?></a>
            </div>
            
            <div id="faq_<?php echo $k+1?>" class="tl_intro_faq_content">
               <?php echo $row->content?>
            </div> 
      
      <?php endforeach;?>
</div>