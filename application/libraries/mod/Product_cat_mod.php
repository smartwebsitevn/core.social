<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Product_cat_mod extends MY_Mod
{

	/**
	 * Them cac thong tin phu
	 * 
	 * @param object $row
	 * @return object
	 */

	public function add_info($row)
	{
		$row =parent::add_info($row);
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
	 * Tao url
	 *
	 * @param object $row
	 * @return object
	 *
	 **/
	public function url( $row )
	{
		//$slug = url_title(convert_vi_to_en($row->SEOurl ?: $row->name));
		//$row->_url_view = site_url('danh-sach-san-pham/'.$row->seo_url.'-'.$row->id);// link nay khi loc ajax thi khong dc
		$row->_url_view = site_url('danh-sach-san-pham').'?cat_id='.$row->id;
		return $row;
	}



	/**
	 * Get all child ids
	 * @param  [type] $parent [description]
	 * @return [type]         [description]
	 * 
	 */
	public function get_child_ids( $parent )
	{
		if( is_array($parent) )
			$ids = $parent;
		else 
			$ids = array( $parent );
		
		$sub = model('product_cat')->filter_get_list( 
			array( 
				'parent_id' => $parent,
				'show' => 1, 
                'order_by' => 'sort ASC, id ASC' 
            ) 
		);
		
		if( $sub )
			foreach ($sub as $row) 
			{
				$tmp = $this->get_child_ids( $row->id );
				if( $tmp )
					$ids = array_merge($ids, $tmp);
			}
		
		return $ids;
	}
}