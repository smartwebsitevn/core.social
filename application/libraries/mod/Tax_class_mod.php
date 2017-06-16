<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Tax_class_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 * 
	 * @param object $row
	 * @return object
	 */

	public function add_info($row)
	{
		
		$row->_to_rate = model('tax_class_to_rate')->get_list_rule( array( 'class_id' => $row->id ) );
		return $row;
	}

	

	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 * 
	 * @param object $row
	 * @param string $action
	 * @return boolean
	 */

	public function can_do($row, $action)
	{
		$result = parent::can_do($row, $action);

		return $result;
	}



	/**
	 * 
	 * Action update & insert
	 * relationship with rate
	 * @param  [int] 	$class_id		Id of tax class
	 * @param  [array] 	$to_rate    	relate to rate rows
	 * 
	 */
	public function to_rate( $class_id, $to_rate )
	{
		$rela =  model('tax_class_to_rate')->get_list_rule( array( 'class_id' => $class_id ) );

		// Delete & update
		if( $rela )
		{
			$delete = array();
			foreach ($rela as $row) 
			{
				if( ! empty($to_rate) )
				{
					if( isset( $to_rate[$row->id] ) )
					{
						model('tax_class_to_rate')->update( $row->id, 
							array( 
								'rate_id' => $to_rate[$row->id]['rate_id'],
								'piority' => $to_rate[$row->id]['piority']
							) 
						);
					}
					else
					{
						$delete[] = $row->id;
					}
				}
				else
				{
					$delete[] = $row->id;
				}
			}
			
			if( count($delete) )
				model('tax_class_to_rate')->del_rows( array( "id" => $delete ) );
		}

		// Insert
		$data = array();

		// Loop all values of fields
		if( $to_rate )
		foreach ($to_rate as $key => $value) {
			if( substr( $key, 0, 1 ) == 'n' )
			{
				$data[] = array(
					'class_id' => $class_id,
					'rate_id' => $to_rate[$key]['rate_id'],
					'piority' => $to_rate[$key]['piority']
				);
			}
		}
	

		if( count( $data ) )
			model('tax_class_to_rate')->create_rows($data);
	}

}