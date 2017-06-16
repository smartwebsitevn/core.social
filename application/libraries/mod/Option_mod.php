<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Option_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 * 
	 * @param object $row
	 * @return object
	 */

	public function add_info($row)
	{

		if (isset($row->image_name))
		{
			t('load')->helper('file');
			$row->_image = file_get_image_from_name( $row->image_name, public_url('img/no_image.png') );
		}

		return $row;
	}

	/**
	 * 
	 * Action update & insert 
	 * relationship with option_values
	 * @param  [int] 	$option_id			Id of option
	 * @param  [array] 	$option_values    	Rows of option_values
	 * 
	 */
	public function to_option_value( $option_id, $option_values )
	{
		$rela =  model('option_value')->get_list_rule( array( 'option_id' => $option_id ) );

		// Delete & update
		if( $rela )
		{
			$delete = array();
			foreach ($rela as $row) 
			{
				if( ! empty($option_values) )
				{
					if( isset( $option_values[$row->id] ) && trim($option_values[$row->id]['value']) )
					{
						
						model('option_value')->update( $row->id, 
							array( 
								'name' => $option_values[$row->id]['value'],
								'sort_order' => $option_values[$row->id]['sort']
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
				model('option_value')->del_rows( array( "id" => $delete ) );
		}


		// Loop all values of fields
		if( $option_values )
		foreach ($option_values as $key => $value) {
			if( ltrim( $key, 'n' ) != $key )
			{
				$data = array(
					'option_id' => $option_id,
					'name' => $option_values[$key]['value'],
					'sort_order' => $option_values[$key]['sort']
				);
				// Lay thong tin image
				$image = $this->_get_avatar($option_values[$key]['image_id']);
				if ($image)
				{
					$data['image_id']	= $image->id;
					$data['image_name']	= $image->file_name;
				}

				$option_value_id = 0;
				model('option_value')->create($data, $option_value_id);

				$this->file_model->update_table_id_of_mod('option_value', $option_values[$key]['image_id'], $option_value_id);

			}
		}

	}



	/**
	 * Lay image
	 */
	function _get_avatar($id)
	{
		$this->load->model('file_model');
		$image = $this->file_model->get_info_of_mod( 'option_value', $id, 'image', 'id, file_name' );
		
		return $image;
	}
	


}