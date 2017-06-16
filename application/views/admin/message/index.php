<!-- Main content wrapper -->
<?php
$_macro = $this->data;
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
$_macro['toolbar'] = array();
$_macro['table']['filters'] = array(
	array(  'param' => 'id',
		'value' 	=>$filter['id'],
	),
	array(  'param' => 'title',
		'value' 	=>$filter['title'],
	),
    array(  'param' => 'user',
        'value' 	=>$filter['user'],
        'attr' 	=>["class"=>" autocomplete ","_url"=>$url_search_username],
    ),

	array(
		'param' => 'admin_readed','name' 	=>  lang('status'), 'type'=> 'select',
		'value' => $filter['admin_readed'],
		'values_single' =>$status_readed,'values_opts'=>array('name_prefix'=>'status_'),
	),

);

$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'user' => lang('user'),
    'title' => lang('title'),
    'content' => lang('content'),
    'status' => lang('status'),
    'created' => lang('created'),
    'action' => lang('action'),
);
$_row_user = function ($row) {
    ob_start()?>
    <?php $user = $row->user;?>
    <?php if(isset($user->name)):?>
        <b title="<?php echo $user->name; ?>" class="tipE">
            <?php echo word_limiter($user->name, 5); ?>
        </b><br/>
        <span title="<?php echo $user->username.'|'.$user->email; ?>" class="tipE">
        						<?php echo $user->username; ?><br/>
            <?php echo character_limiter_len($user->email, 30); ?><br/>
            <?php echo $user->phone; ?>
        					</span>
    <?php endif;?>
    <?php return ob_get_clean();
};
$_row_title = function ($row) {
    ob_start()?>
    <?php echo $row->title; ?>
    <?php /*if(isset($row->user_execute->username)):?>
        <p><?php echo lang('user_execute')?>: <b style="color:red"><?php echo $row->user_execute->username?></b></p>
    <?php endif;*/?>
    <?php return ob_get_clean();
};
$_row_status = function ($row) {
    ob_start()?>
    <?php
    if($row->admin_readed){
        echo '<span class="label label-info">'.lang('status_readed').'</span><br>';
        echo get_date($row->admin_readed_time,'full');
    }
    else
        echo '<span class="label ">'.lang('status_unreaded').'</span>';
    ?>
    <br>
    <?php
    if($row->admin_replyed){
        echo '<span class="label label-info">'.lang('status_replyed').'</span><br>';
        echo get_date($row->admin_replyed_time,'full');
    }
    else
        echo '<span class="label ">'.lang('status_unreplyed').'</span>';
    ?>
    <?php return ob_get_clean();
};
$_row_action = function ($row) {
    ob_start()
    ?>
    <?php if ($row->_can_view): ?>
        <a href="<?php echo $row->_url_view; ?>" title="<?php echo lang('detail'); ?>" data-width="80%" data-height="80%" class="tipS lightbox">
            <img src="<?php echo public_url('admin') ?>/images/icons/color/view.png"/>
        </a>
    <?php endif; ?>

    <?php if ($row->_can_del): ?>
        <a href="" _url="<?php echo $row->_url_del; ?>" title="<?php echo lang('delete'); ?>" class="tipS verify_action"
           notice="<?php echo lang('notice_confirm_delete'); ?>:<br><b><?php echo htmlentities($row->title); ?></b>"
            >
            <img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png"/>
        </a>

    <?php endif;  ?>


    <?php if($row->is_spam):?>
        <p>
            <a href="javascript:void(0)"style="color:red"  data-toggle="tooltip" title="<?php echo lang('reported')?>  (<?php echo $row->total_spam?> l?n)">
                <i class="fa fa-times" aria-hidden="true"></i>  <?php echo lang('reported')?> (<?php echo $row->total_spam?>)
            </a>
        </p>
    <?php endif;?>

    <?php return ob_get_clean();
};

$_rows = array();
foreach ($list as $row) {
    $r = (array)$row;
    $r['user'] = $_row_user($row);
    $r['title'] = $_row_title($row);
    $r['content'] = character_limiter_len($row->content, 20);
    $r['status'] = $_row_status($row);
    $r['created'] = $row->_created_full;
    $r['action'] = $_row_action($row);
    $_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);

