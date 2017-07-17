<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Type_cat_mod extends MY_Mod
{
    public function url($row)
    {
        if(isset($row->seo_url) && $row->seo_url)

        $row->_url_view = site_url("danh-sach-type/" . $row->seo_url.'-c'. $row->id);

        return $row;
    }
     public function create_filter(array $fields, &$input = array())
    {
        $filter = parent::create_filter($fields, $input);
        return $filter;
    }


    /**
     * Them cac thong tin phu vao thong tin cua product
     */
    function add_info($row)
    {
        $row = parent::add_info($row);
        $row = $this->add_info_types($row);
        return $row;
    }
    public function add_info_types($row)
    {
        $types = model('type')->filter_get_list(['cat_id'=>$row->id], ['select'=>'id,name,image_id,image_name,seo_url']);
        if ($types) {
            foreach($types as $type){
                $type_items = model('type_item')->filter_get_list(['type_id'=>$type->id], ['select'=>'id,name,image_id,image_name,seo_url']);
                $type->items =$type_items;
            }
        }
        $row->{"_types"} = $types;
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