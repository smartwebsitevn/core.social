<?php namespace App\Product\Library\Provider;

use Core\Support\OptionsAccess;

class Card extends OptionsAccess
{
	protected $config = [

		'code' => [
			'required' => true,
			'cast' => 'string',
		],

		'serial' => [
			'required' => true,
			'cast' => 'string',
		],

		'expire' => [
			'required' => true,
			'cast' => 'string',
		],

	];
}