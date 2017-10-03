<?php
	require_once 'curl.php';
	
	function call_payment(array $payments)
	{
		$payments = array_sort_rand($payments);
		foreach ($payments as $p)
		{
			$f = __DIR__."/../payment/{$p}.php";
			if (file_exists($f))
			{
				require $f;
			}
		}
	}
	
	function call_cronjob($payment, array $acts)
	{
		foreach ($acts as $act)
		{
			echo get($act.'/'.$payment).PHP_EOL;
		}
	}
	
	function array_sort_rand(array $arr)
	{
		$arr_r	= array();
		$total 	= count($arr);
		for ($i = 1; $i <= $total; $i++)
		{
			$k = array_rand($arr);
			$arr_r[] = $arr[$k];
			unset($arr[$k]);
		}
		
		return $arr_r;
	}
	