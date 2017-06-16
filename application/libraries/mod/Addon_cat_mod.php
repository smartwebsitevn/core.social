<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Addon_cat_mod extends MY_Mod
{
    public function url($row)
    {
        return $row;
    }
     public function create_filter(array $fields, &$input = array())
    {
        $filter = parent::create_filter($fields, $input);
        /*if ($filter)
            foreach ($filter as $key=>$v) {
                if (in_array($key, ['price', 'price_gt', 'price_lt', 'price_gte', 'price_lte'])){
                    $v = currency_handle_input($filter[$key]);
                    $filter[$key]=$input[$key]=$v;
                }
            }
            */
        //pr($filter);
        return $filter;
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