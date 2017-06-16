<?php class Tag_value_model extends MY_Model
{

    var $table = 'tag_value';


    /*
    * ------------------------------------------------------
    *  Filter Handle
    * ------------------------------------------------------
    */

    function filter_get_where($filter)
    {
        $where = array();
        if (isset($filter['type'])) {
            $where['tag_value.table'] = $filter['type'];
        }
        if (isset($filter['field'])) {
            $where['tag_value.table_field'] = $filter['field'];
        }
        if (isset($filter['id!'])) {
            $where['tag_value.table_id !='] = $filter['id!'];
        }

        if (isset($filter['tag_id'])) {
            $where['tag_value.tag_id'] = $filter['tag_id'];
        }

        return $where;
    }

    function filter_get_list($filter, $input)
    {
        $input['where'] = $this->filter_get_where($filter);
        return $this->get_list($input);
    }

    function filter_get_total($filter)
    {
        $where = $this->filter_get_where($filter);

        return $this->get_total($where);
    }

}
	