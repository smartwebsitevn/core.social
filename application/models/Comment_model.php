<?php

class Comment_model extends MY_Model
{
    var $table = 'comment';

    function get_product_list($input = array(), $lang_id = NULL)
    {
        if (!isset($input['select']) || !$input['select']) {
            $input['select'] = 'comment.*,';
            $input['select'] .= 'product.name as product_name,';
            $input['select'] .= 'user.name as user_name,user.email as user_email';
        }

        $this->_get_list_set_input($input);

        $this->db->from('comment');
        $this->db->join('product', 'product.id = comment.table_id', 'left');
        $this->db->join('user', 'user.id = comment.user_id', 'left');

        $query = $this->db->get();

        return $query->result();
    }


    function get_lesson_list($input = array(), $lang_id = NULL)
    {
        if (!isset($input['select']) || !$input['select']) {
            $input['select'] = 'comment.*,';
            $input['select'] .= 'lesson.name as lesson_name,';
            $input['select'] .= 'user.name as user_name,user.email as user_email';
        }

        $this->_get_list_set_input($input);

        $this->db->from('comment');
        $this->db->join('lesson', 'lesson.id = comment.table_id', 'left');
        $this->db->join('user', 'user.id = comment.user_id', 'left');

        $query = $this->db->get();

        return $query->result();
    }


    /*
     * ------------------------------------------------------
     *  Filter Handle
     * ------------------------------------------------------
     */
    function _filter_get_where($filter)
    {
        $where = parent::_filter_get_where($filter);
        foreach (array('id',
                 ) as $p) {
            $f = (in_array($p, array())) ? $p . '_id' : $p;
            $f = $this->table . '.' . $f;
            //$m = (in_array($p, array('created'))) ? 'range' : '';
            $this->_filter_set_where($filter, $p, $f, $where);
        }

        if (isset($filter['parent_id'])) {
            $where['comment.parent_id'] = $filter['parent_id'];
        }

        if (isset($filter['rate'])) {
            $where['comment.rate'] = $filter['rate'];
        }

        if (isset($filter['table_id'])) {
            $where['comment.table_id'] = $filter['table_id'];
        }

        if (isset($filter['table_name'])) {
            $where['comment.table_name'] = $filter['table_name'];
        }
        if (isset($filter['table_name_not_site'])) {
            $where['comment.table_name !='] = 'site';
        }
        if (isset($filter['user_id'])) {
            $where['comment.user_id'] = $filter['user_id'];
        }

        if (isset($filter['type'])) {
            $where['comment.type'] = $filter['type'];
        }

        if (isset($filter['parent'])) {
            $where['comment.parent_id'] = $filter['parent'];
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

    function filter_get_list($filter, $input = array())
    {
        $input['where'] = $this->_filter_get_where($filter);
      //  pr($input);
        if (isset($filter['table_name'])) {
            if ($filter['table_name'] == 'product')
                return $this->get_product_list($input);
            elseif ($filter['table_name'] == 'lesson')
                return $this->get_lesson_list($input);
        }
        return $this->get_list($input);
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