<?php
	$_row_action = function($row)
	{
		ob_start();?>

		<?php if ($row->_can_view): ?>
			<a href="<?php echo $row->_url_view; ?>" class="lightbox load_uri"
			><?php echo lang('detail'); ?></a>
		<?php endif; ?>
		
		<?php return ob_get_clean();
	};
	
	$_form_bank = function() use ($banks)
	{
		$list = $banks;
		
		//array_unshift($list, (object) array('id' => '', 'name' => ''));
		
		return array_pluck($list, 'name', 'id');
	};
	
	$_form_status = function() use ($statuss)
	{
		$result = array();
		
		foreach ($statuss as $v)
		{
			$result[$v] = lang('status_'.$v);
		}
		
		return $result;
	};
	
	
	$_macro = $this->data;
	$_macro['toolbar'] = array();
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	
	
	$_macro['table']['filters'][] = array(
		'param' 	=> 'id',
		'value' 	=> $filter['id'],
	);
	
	$_macro['table']['filters'][] = array(
		'param' 	=> 'bank',
		'type' 		=> 'select',
		'value' 	=> $filter['bank'],
		'values' 	=> $_form_bank(),
	);
	
	$_macro['table']['filters'][] = array(
		'param' 	=> 'acc_id',
		'value' 	=> $filter['acc_id'],
	);
	
	$_macro['table']['filters'][] = array(
		'param' 	=> 'status',
		'type' 		=> 'select',
		'value' 	=> $filter['status'],
		'values' 	=> $_form_status(),
	);
     $_macro['table']['filters'][] = array(
		'param' 		=> 'sp',
		'type' 		=> 'sp',
	);
	$_macro['table']['filters'][] = array(
		'param' 	=> 'created',
		'type' 		=> 'date',
		'name' 		=> lang('from_date'),
		'value' 	=> $filter['created'],
	);
	
	$_macro['table']['filters'][] = array(
		'param' 	=> 'created_to',
		'type' 		=> 'date',
		'name' 		=> lang('to_date'),
		'value' 	=> $filter['created_to'],
	);
	
	
	$_macro['table']['columns'] = array(
		'id' 		=> lang('id'),
		'bank'		=> lang('bank'),
		'sender_acc_id'		=> lang('sender_acc_id'),
		'sender_acc_name'	=> lang('sender_acc_name'),
		'receiver_acc_id'	=> lang('receiver_acc_id'),
		'receiver_acc_name'	=> lang('receiver_acc_name'),
		'amount'	=> lang('amount'),
		'status'	=> lang('status'),
		//'user'		=> lang('user'),
		'created'	=> lang('created'),
		'action' 	=> lang('action'),
	);
	
	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['user'] 		= t('html')->a(admin_url('user')."?id={$row->user_id}", $row->user->email, array('target' => 'target'));
		$r['bank'] 		= $row->bank_name;
		$r['amount'] 	= $row->_amount;
		$r['status'] 	= macro()->status_color($row->_status);
		$r['created'] 	= $row->_created;
		$r['action'] 	= $_row_action($row);
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);