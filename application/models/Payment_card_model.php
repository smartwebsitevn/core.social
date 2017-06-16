<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'models/core/Core_providers_model.php';

class Payment_card_model extends Core_providers_model
{
	protected $model = 'payment_card';
}