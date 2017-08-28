<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Type_widget extends MY_Widget
{
    /**
     * Ham khoi dong
     */
    function __construct()
    {
        $this->lang->load('site/' . $this->_get_mod());
    }


    /**===============================
     * HANDLE FILTER
     * =================================*/
    function filter_types($type_cat_id, $filter = array(), $temp = '', $temp_options = array())
    {
        $types = model('type')->filter_get_list(['cat_id' => $type_cat_id], ['select' => 'id,name,image_id,image_name,seo_url']);
        if (!$types) return;

       /* $types_values=[];
        if($product_id){
            $types_values = model('type_table')->filter_get_list(['type_cat_id'=>$type_cat_id,'table_id'=>$product_id,'table_'=>'product']);
        }*/

        foreach ($types as $type) {
            $type_items = model('type_item')->filter_get_list(['type_id' => $type->id], ['select' => 'id,name,image_id,image_name,seo_url']);
            $type->items = $type_items;
        }

        //== lay ra thong so loc da luu
        $filter_input = mod('type')->sess_data_get('list_filter_input');

        //pr($filter_input);
        if ($filter_input) {
            $filter = array_merge($filter, $filter_input);
        }

        $this->data['types'] = $types;
        $this->data['filter'] = $filter;

        $temp = (!$temp) ? 'types' : $temp;
        $temp = 'tpl::_widget/type/filter/filter_' . $temp;
        // Su ly hien thi temp hay tra ve du lieu
        return $this->_display_temp($temp, $temp_options);

    }


}