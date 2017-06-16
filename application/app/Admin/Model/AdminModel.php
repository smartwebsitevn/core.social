<?php namespace App\Admin\Model;

class AdminModel extends \Core\Base\Model
{
	protected $table = 'admin';

	/**
	 * Tao admin url
	 *
	 * @param string $action
	 * @param array  $opt
	 * @return string
	 */
	public function adminUrl($action, array $opt = [])
	{
		switch ($action)
		{
			case 'view':
			{
				return parent::adminUrl('edit', $opt);
			}
		}

		return parent::adminUrl($action, $opt);
	}

}