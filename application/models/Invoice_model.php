<?php

class Invoice_model extends MY_Model
{
	public $table = 'invoice';

	public $select = 'invoice.*';

	public $join_sql = [
		'tran' => 'invoice.id = tran.invoice_id',
		'user' => 'invoice.user_id = user.id',
	];

	public $relations = [
		'tran' => 'many',
		'user' => 'one',
	];

}