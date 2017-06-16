<!-- Common view -->
<?php echo macro()->page(); ?>

<!-- Main content wrapper -->
<div class="wrapper">
	<div class="widget">
	
		<div class="title">
			<span class="titleIcon"><input type="checkbox" id="titleCheck" name="titleCheck" /></span>
			<h6>
				<?php 
					echo lang('title_list');
				?>
			</h6>
		 	<div class="num f12"><?php echo lang('total'); ?>: <b><?php echo $pages_config['total_rows']; ?></b></div>
		</div>
		
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable mTable myTable" id="checkAll">
			
			<thead class="filter"><tr><td colspan="10">
				<form class="list_filter form" action="<?php echo $action; ?>" method="get">
					<table cellpadding="0" cellspacing="0" width="100%"><tbody>
						<tr><td>
							<div class="row">
								<label class="mr5"><?php echo lang('id'); ?></label>
								<input name="id" value="<?php echo $filter['id']; ?>" type="text" style="width:70px;" />
							</div>
							
							<div class="row">
								<label class="mr5"><?php echo lang('title'); ?></label>
								<input name="title" value="<?php echo $filter['title']; ?>" type="text" style="width:150px;" />
							</div>
							
						    <div class="row">
								<label class="mr5"><?php echo lang('user'); ?></label>
								<input name="user" value="<?php echo $filter['user']; ?>" placeholder="Nhập tài khoản thành viên" class="autocomplete" _url="<?php echo $url_search_username; ?>"  type="text" style="width:150px;" />
							</div>

								<div class="row">
									<label class="mr5"><?php echo lang('status'); ?></label>
									<select name="admin_readed">
										<option value=""></option>
										<?php foreach ($status_readed as $v): ?>
											<option value="<?php echo $v; ?>" <?php echo form_set_select($v, $filter['admin_readed']); ?>>
												<?php echo lang('status_'.$v); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>

								<?php /*  ?>
							<div class="row">
								<label class="mr5"><?php echo lang('type'); ?></label>
								<select name="type">
									<option value=""></option>
									<?php foreach ($types as $v): ?>
										<option value="<?php echo $v; ?>" <?php echo form_set_select($v, $filter['type']); ?>>
											<?php echo lang('type_'.$v); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
								<?php */ ?>

								<div class="row">
								<input type="submit" class="button blueB" value="<?php echo lang('search')?>" />
								<input type="reset" class="basic" value="Reset" onclick="window.location.href = '<?php echo $action; ?>'; ">
							</div>
						</td></tr>
					</tbody></table>
				</form>
			</td></tr></thead>
			
			<thead>
				<tr>
					<td style="width:10px;"><img src="<?php echo public_url('admin'); ?>/images/icons/tableArrows.png" /></td>
					<td><?php echo lang('id'); ?></td>
					<td><?php echo lang('user'); ?></td>
					<?php /* ?>
					<td><?php echo lang('receive'); ?></td>
					<?php  */?>

					<td><?php echo lang('title'); ?></td>
					<td><?php echo lang('content'); ?></td>
					<td><?php echo lang('status'); ?></td>
					<td><?php echo lang('created'); ?></td>
					<td><?php echo lang('action'); ?></td>
				</tr>
			</thead>

 			<tfoot class="auto_check_pages">
				<tr>
					<td colspan="10">
						<?php if (count($actions)): ?>
							<div class="list_action itemActions">
								<select name="action" class="left mr10">
									<option value=""><?php echo lang('select_action'); ?></option>
									<?php foreach ($actions as $a => $u): ?>
										<option value="<?php echo $u; ?>"><?php echo lang('action_'.$a); ?></option>
									<?php endforeach; ?>
								</select>
								
								<a href="#submit" id="submit" class="button blueB">
									<span class="white"><?php echo lang('button_submit'); ?></span>
								</a>
							</div>
						<?php endif; ?>
						
						<?php $this->widget->admin->pages($pages_config); ?>
					</td>
				</tr>
			</tfoot>
			
			<tbody class="list_item">
			<?php foreach ($list as $row): ?>
				<tr>
					<td><input type="checkbox" name="id[]" value="<?php echo $row->id; ?>" /></td>
					
					
					<td class="textC"><?php echo $row->id; ?></td>
					
					<td>
					     <?php $user = $row->user;?>
					     <?php if(isset($user->name)):?>
						   <b title="<?php echo $user->name; ?>" class="tipE">
    					 	<?php echo word_limiter($user->name, 5); ?> (<b style="color:red"><?php echo lang('user_level_'.$user->level)?></b>)
    					   </b><br/>
        					<span title="<?php echo $user->username.'|'.$user->email; ?>" class="tipE">
        						<?php echo $user->username; ?><br/>
        						<?php echo character_limiter_len($user->email, 30); ?><br/>
        						<?php echo $user->phone; ?>
        					</span>
    					<?php endif;?>
					</td>
				<?php /* ?>

					<td>
					    <?php foreach ($row->receives as $receive):?>
					       <?php $status = ($receive->readed > 0) ? 'readed' : 'not_readed'?>
					       <?php $title = ($receive->readed > 0) ? lang('readed').' lúc '. get_date($receive->readed , 'full') : lang('not_readed')?>
					       
					       <p  data-toggle="tooltip" style="margin-bottom:2px" title="<?php echo $title?>"><b><?php echo $receive->receive_username?></b>: <span class="<?php echo $status?>"><?php echo lang($status)?></span></p>
					    <?php endforeach;?>
					</td>
 					<?php */ ?>

					<td>
						<?php echo $row->title; ?>
						<?php if(isset($row->user_execute->username)):?>
    					<p><?php echo lang('user_execute')?>: <b style="color:red"><?php echo $row->user_execute->username?></b></p>
    					<?php endif;?>
					</td>
					
					<td class=""><?php echo character_limiter_len($row->content, 20); ?></td>
					<td class="textC">
						<?php
						if($row->admin_readed){
							echo '<b class="green">'.lang('status_readed').'</b><br>';
							echo get_date($row->admin_readed_time,'full');
						}
						else
							echo '<b class="red">'.lang('status_unreaded').'</b>';
						?>
					</td>
					<td class="textC">
						<?php echo $row->_created_time; ?>
					</td>
				
				
					<td class="option">	
						
							<a href="<?php echo $row->_url_view; ?>" title="<?php echo lang('view'); ?>" class="tipS lightbox">
								<img src="<?php echo public_url('admin') ?>/images/icons/color/view.png" />
							</a>
						
						
							<a href="" _url="<?php echo $row->_url_del; ?>" title="<?php echo lang('delete'); ?>" class="tipS verify_action" 
								notice="<?php echo lang('notice_confirm_delete'); ?>:<br><b><?php echo $row->title; ?></b>"
							>
								<img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png" />
							</a>
						    
						     <?php if($row->is_spam):?>
    					      <p>
        					      <a href="javascript:void(0)"style="color:red"  data-toggle="tooltip" title="<?php echo lang('reported')?>  (<?php echo $row->total_spam?> lần)">
        					       <i class="fa fa-times" aria-hidden="true"></i>  <?php echo lang('reported')?> (<?php echo $row->total_spam?>)
        					      </a>
    					       </p>
    					      <?php endif;?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			
		</table>
	</div>
	
</div>