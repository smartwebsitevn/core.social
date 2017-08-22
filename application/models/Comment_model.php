<?php

class Comment_model extends MY_Model
{
    var $table = 'comment';



    /*
     * ------------------------------------------------------
     *  Filter Handle
     * ------------------------------------------------------
     */
    function _filter_get_where($filter)
    {
        $where = parent::_filter_get_where($filter);
        foreach (array(
                     'id','id_lte','id_lt',
                     'id_gte', 'id_gt',
                     'parent_id','rate','table_id','table_name','user_id','type','readed','featured'
                 ) as $key) {
            if (isset($filter[$key]) && $filter[$key] != -1) {
                //echo '<br>key='.$key.', v='.$filter[$key];
                $this->_filter_parse_where($key, $filter);
            }
        }


        if (isset($filter['table_name_not_site'])) {
            $where['comment.table_name !='] = 'site';
        }
        //== Thuoc tinh bool dang so - chuoi
        foreach (array('status','readed') as $f) {
            if (isset($filter[$f])) {
                $v = ($filter[$f]);//? 'on' : 'off';
                if (is_numeric($v))
                    $v = $v ? 1: 0;
                else{
                    if ($v == 'off' || $v == 'no' )
                        $v = 0;
                    else
                        $v = 1;
                }
                // echo 'status_' . $v;
                $where[$this->table . '.' . $f] = $v;
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


        // Hien thi ra phia nguoi dung
        if (isset($filter['show'])) {
            $where[$this->table . '.status'] = 1;
        }
        return $where;
    }


    /*
     * ------------------------------------------------------
     *  Other Fun
     * ------------------------------------------------------
     */
    /**
     * Lay tong so phan hoi chua duoc duyet
     */
    function get_total_unread()
    {
        $filter['status'] = FALSE;

        return $this->filter_get_total($filter);
    }
}

?>