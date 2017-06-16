<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Voucher_model extends MY_Model
{

    var $table = 'voucher';

    /*
     * ------------------------------------------------------
     *  Main handle
     * ------------------------------------------------------
     */
    /**
     * Tim kiem du lieu
     */
    function _search($field, $key)
    {
        switch ($field) {
            case 'name': {
                $this->db->where('MATCH(voucher.name) AGAINST(' . $this->db->escape($key) . ')');
                break;
            }
            case 'commment': {
                $this->db->where('MATCH(voucher.commment) AGAINST(' . $this->db->escape($key) . ')');
                break;
            }
        }
    }

    /**
     * Filter handle
     */
    function _filter_get_where(array $filter)
    {
        $where = parent::_filter_get_where($filter);
        foreach (array('type') as $p) {
            $f = (in_array($p, array(''))) ? $p . '_id' : $p;
            $f = $this->table . '.' . $f;
            $this->_filter_set_where($filter, $p, $f, $where);
        }
        if (isset($filter['key'])) {
            $where['voucher.key'] = trim($filter['key']);
        }

        if (isset($filter['commission'])) {
            $_id=$filter['commission'];
            if(filter_var($_id, FILTER_VALIDATE_EMAIL)){
                $info =model('user')->get_id(['email'=>$_id]);
                if($info){
                    $where['voucher.user_id'] =$info;
                }
            }
            else{
                $info =model('admin')->get_id(['username'=>$_id]);
                if($info){
                    $where['voucher.admin_id'] =$info;
                }
            }
        }

        if (isset($filter['expired']))
        {
            if ($filter['expired'] == 'expired'  )
                $where[$this->table.'.expired <='] = now();
            else
                $where[$this->table.'.expired >'] = now();
        }

        if (isset($filter['name'])) {
            $this->search('voucher', 'name', $filter['name']);
        }
        if (isset($filter['comment'])) {
            $this->search('voucher', 'comment', $filter['comment']);
        }

        foreach (array('status') as $f) {
            if (isset($filter[$f])) {
                $v = ($filter[$f]);//? 'on' : 'off';
                if (is_numeric($v))
                    $v = $v ? 1: 0;
                else{
                    if ($v == 'used'  )
                        $v = 1;
                    else
                        $v = 0;
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
       // pr($where);
        return $where;
    }

}

?>