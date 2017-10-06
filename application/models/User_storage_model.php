<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_storage_model extends MY_Model
{
    public $table = 'user_storage';
    public $order = array( array('id', 'desc'));
    public $fields = array(
        //== Info core
        'status',
        //== Info main
        'title',  'content',
        //== Info thuoc tinh bool
        'user_id',
        //== Info kkac
        //'icon_fa',
    );
    public $fields_filter = array(
        'user_id','table_id','table','action','readed','admin_readed',
        //== core
        'title', '%title',  'BINARY title',
        'id','!id','id_gt', 'id_gte', 'id_lt', 'id_lte',
        'status','deleted','created', 'created_to',
    );
    public $fields_type_content = array( 'content');
    public $actions_row = array('view', 'del', );
    public $actions_list = array('del');



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

        //=== Su ly loc theo ngay tao
        //  1: tu ngay  - den ngay
        if (isset($filter['created']) && isset($filter['created_to'])) {
            $where[$this->table . '.created >='] = is_numeric($filter['created']) ? $filter['created'] : get_time_from_date($filter['created']);
            $where[$this->table . '.created <='] = is_numeric($filter['created_to']) ? $filter['created_to'] : get_time_from_date($filter['created_to']) + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
        } //2: tu ngay
        elseif (isset($filter['created'])) {
            $where[$this->table . '.created >='] = is_numeric($filter['created']) ? $filter['created'] : get_time_from_date($filter['created']);
        } //3: den ngay
        elseif (isset($filter['created_to'])) {
            $where[$this->table . '.created <='] = is_numeric($filter['created_to']) ? $filter['created_to'] : get_time_from_date($filter['created_to']) + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
        }

        // hien thi san pham phia ngoai
        if (isset($filter['show'])) {
           // $where[$this->table . '.status'] = '1';
        }

        return $where;
    }

}