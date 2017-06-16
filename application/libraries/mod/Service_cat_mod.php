<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Service_cat_mod extends MY_Mod
{
    public function url($row)
    {
        $row->_url_view = site_url("loai-dich-vu/" . $row->seo_url.'-c'. $row->id);

        return $row;
    }
    /**
     * Them cac thong tin phu vao thong tin cua product
     */
    function add_info($row)
    {
        $row = parent::add_info($row);

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

        $sub = $this->_model()->filter_get_list(
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