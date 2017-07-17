<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Type_table_model extends MY_Model
{
    public $table = 'type_table';
    public $order = array( array('type_id', 'desc'));
    public $fields_filter = array(
        'type_id','type_item_id','type_cat_id',
        'table','table_id'
    );

    /**
     * Filter handle
     *
     */
    function _filter_get_where(array $filter)
    {
        $where = parent::_filter_get_where($filter);
        //pr($filter);
        foreach ($this->fields_filter as $key) {
            if (isset($filter[$key]) && $filter[$key] != -1) {
                //echo '<br>key='.$key.', v='.$filter[$key];
                $this->_filter_parse_where($key, $filter);
            }
        }

        return $where;
    }

}