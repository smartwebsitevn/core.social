<?php namespace App\StockCard\Library;

use App\StockCard\StockCardFactory;
use PHPExcel_IOFactory;

class CardImportParser
{
	/**
	 * Kieu phan tich
	 *
	 * @var string
	 */
	protected $driver;

	/**
	 * Danh sach cac bien cua card
	 *
	 * @var array
	 */
	protected $card_params = ['code', 'serial', 'expire'];


	/**
	 * Khoi tao doi tuong
	 *
	 * @param string $driver Kieu phan tich ('file' || 'text')
	 */
	public function __construct($driver)
	{
		$this->driver = $driver;

		$this->card_params = StockCardFactory::service()->config('card_params');
	}

	/**
	 * Thuc hien phan tich
	 *
	 * @param mixed $input
	 * @return array
	 */
	public function parse($input)
	{
		$cards = [];

		foreach ($this->parseInput($input) as $line)
		{
			$card = $this->parseLine($line);

			if ($this->validCard($card))
			{
				$cards[] = $card;
			}
		}

		return $cards;
	}

	/**
	 * Phan tich input
	 *
	 * @param string $input
	 * @return array
	 */
	protected function parseInput($input)
	{
		$method = 'parse'.studly_case($this->driver).'Input';

		return method_exists($this, $method) ? $this->{$method}($input) : [];
	}

	/**
	 * Phan tich file input
	 *
	 * @param string $input
	 * @return array
	 */
	protected function parseFileInput($input)
	{
		return PHPExcel_IOFactory::load($input)->getActiveSheet()->toArray();
	}

	/**
	 * Phan tich text input
	 *
	 * @param string $input
	 * @return array
	 */
	protected function parseTextInput($input)
	{
		$lines = explode("\n", $input);

		foreach ($lines as &$line)
		{
			$line = trim($line);
			$line = preg_replace('#\t#', ' ', $line);
			$line = preg_replace('#\s+#', ' ', $line);
			$line = explode(' ', $line);
		}

		return $lines;
	}

	/**
	 * Phan tich input line
	 *
	 * @param array $line
	 * @return array
	 */
	protected function parseLine(array $line)
	{
		$card = [];

		foreach ($this->card_params as $i => $param)
		{
			$card[$param] = array_get($line, $i);
		}

		return $card;
	}

	/**
	 * Kiem tra card
	 *
	 * @param array $card
	 * @return bool
	 */
	protected function validCard(array $card)
	{
		return array_get($card, 'code') && array_get($card, 'serial');
	}

}