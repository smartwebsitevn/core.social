<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Geo_zone_mod extends MY_Mod

{

	

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
	 * Tao url
	 *
	 * @param object $row
	 * @return object
	 */

	public function url($row)
	{
		$name = url_title(convert_vi_to_en($row->name));
		$row->_url_view = site_url("{$name}-geo-zone{$row->id}");
		
		return $row;
	}

	


	public function to_geo_zone_to_city( $geo_zone_id, $geo_zone_to_city )
	{
		$rela =  model('geo_zone_to_city')->get_list_rule( array( 'geo_zone_id' => $geo_zone_id ) );

		// Delete
		if( $rela )
		{
			$delete = array();
			foreach ($rela as $row) {
				if( ! empty($geo_zone_to_city) )
				{
					if( empty( $geo_zone_to_city[$row->id] ) )
						$delete[] = $row->id;
					else
						model('geo_zone_to_city')->update( $row->id, 
							array( 
								'negative' => isset($geo_zone_to_city[$row->id]['negative']) ? $geo_zone_to_city[$row->id]['negative'] : 0,
								'country_id' => $geo_zone_to_city[$row->id]['country_id'],
								'city_id' => $geo_zone_to_city[$row->id]['city_id']
							) 
						);
				}
				else
				{
					$delete[] = $row->id;
				}
			}
			
			if( count($delete) )
				model('geo_zone_to_city')->del_rows( array( "id" => $delete ) );
		}

		// Insert
		$data = array();

		// Loop all values of fields
		if( $geo_zone_to_city )
			foreach ($geo_zone_to_city as $id => $value) {
				if( $id )
				{
					$flag = true;
					if( $rela )
					{
						foreach ($rela as $row) {
							if( $id == $row->id )
							{
								$flag = false;
							}
						}
					}
					if( $flag )
					{
						$data[] = array(
							'geo_zone_id' => $geo_zone_id,
							'negative' => isset($value['negative']) ? $value['negative'] : 0,
							'country_id' => $value['country_id'],
							'city_id' => $value['city_id'],
							'created_date' => time()
						);
					}
				}
			}
	

		if( count( $data ) )
			model('geo_zone_to_city')->create_rows($data);
	}

}