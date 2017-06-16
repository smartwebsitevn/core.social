<form class="frorm-range form_action" method="POST"	action="<?php echo site_url ( 'question_answer' )?>">
	<div class="form-group">
		<textarea class="form-control" name="question"	cols="10" rows="3"></textarea>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-danger btn-xs img-rounded">Gửi câu hỏi</button><br/>
		<div name="user_error" class="error"></div>
		<div name="question_error" class="error"></div>
	</div>
	</form>

	
	
	<div class="ask-list-group">
<?php foreach ($list as $row):// pr($row);?>
                            
<div class="ask-list-item">
		<p><span class="text-danger">
		<strong><?php echo $row->user_name?>:</strong></span> 
		<!--<spanclass="label label-warning">VIP</span>-->
		 <?php echo $row->question?>
		</p>
		<p>Trả lời: <span><?php echo ($row->answer != '') ? '<span style="background:#5cb85c;display:inline-block;padding:0px 3px;color:#fff;border-radius:3px">Admin</span> '.handle_content($row->answer, 'output') : '0';?></span></p>
		<?php /*?>
		<button class="btn btn-default btn-xs ask-answer-btn"
			data-question="571">Trả lời: <?php echo ($row->answer != '') ? handle_content($row->answer, 'output') : '0';?>
		</button>
		<?php */?>
		</div>                            
<?php endforeach;?>                            
  	</div>                           