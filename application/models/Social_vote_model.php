<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Social_vote_model extends MY_Model
{
    public $table = 'social_vote';
    public $fields_filter = array(
        //== core
        'id','!id','id_gt', 'id_gte', 'id_lt', 'id_lte',
        'table_name','table_id',
        'status','created', 'created_to',
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
                $this->_filter_parse_where($key, $filter);
            }
        }
        return $where;
    }

}