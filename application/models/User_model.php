<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model
{
    public $table = 'user';

    public $select = 'user.*';

    public $timestamps = true;

    public $join_sql = [
        'user_group' => 'user.user_group_id = user_group.id',
        'purse' => 'user.id = purse.user_id',
    ];

    public $relations = [
        'user_group' => 'one',
        'purse' => 'many',
    ];

    public $_password_lenght = 6;

    public $_info_key = array('name', 'username', 'phone', 'email',);
    public $_info_genneral = array(
        'title', 'first_name',/*'last_name',*/
        'birthday', 'gender',
        /*'phone2',*/
        'fax', 'yahoo', 'skype', 'website', 'address', 'desc',
        //'profession','languages','passport',/*'tax_number',*/
        //'country','city','district','state','postcode',

    );

    public $_info_social = array('facebook', 'twitter', 'googleplus', 'linkedin', 'youtube', 'instagram');
    public $_info_id = [];//array('id_number','id_place','id_date'/*,'id_image_front','id_image_back'*/);
    public $_info_card = [];//array(/*'card_bank_id',*/'card_bank_name','card_bank_branch',		'card_account_name','card_account_number','card_atm_number');

    public $fields = array(
        'adsed', 'adsed_begin', 'adsed_end', 'adsed_order', 'adsed_option',
        'is_feature', 'is_new', 'is_special',//'is_live',


    );
    public $fields_filter = array(
        //== price
        'cat_id', 'country', 'city', 'working_country', 'working_city',
        'job',
        'vote_total', '!vote_total', 'vote_total_gt', 'vote_total_gte', 'vote_total_lt', 'vote_total_lte',
        'point_total', '!point_total', 'point_total_gt', 'point_total_gte', 'point_total_lt', 'point_total_lte',

        'verify', 'user_group', 'user_group_id', 'gender', 'birthday_year', 'subject_id',
        'user_affiliate_id',
        //== core
        'name', '%name', 'BINARY name',
        'id', '!id', 'id_gt', 'id_gte', 'id_lt', 'id_lte',
        'seo_url', 'BINARY seo_url',
        'is_feature', 'is_new', 'is_live', 'is_special',
        'status', 'created', 'created_to',
    );
    public $fields_type_relation_cat = array('type');
    public $fields_type_relation_cat_multi = array('job');

    // Cac table thong tin thanh phan
    var $table_info_adv = array(
        'job', 'job_cat', 'welfare',
    );
    var $table_info_adv_key = 'user_id';

    // Kieu tim kiem cua cac table thong tin thanh phan (VD: $table_info_search_mod = array('table_1' => 'equal', 'table_2' => 'range', ...)). Mac dinh la 'equal'
    public $table_info_adv_search_mod = array();


    /*
     * ------------------------------------------------------
     *  Main Handle
     * ------------------------------------------------------
     */
    /**
     * Filter handle
     */
    public function _filter_get_where(array $filter)
    {
        $where = parent::_filter_get_where($filter);
        //pr($this->fields_filter );
        foreach ($this->fields_filter as $key) {
            if(in_array($key,['created','created_to'])) continue;

            if (isset($filter[$key]) && $filter[$key] != -1) {
                //echo '<br>key='.$key.', v='.$filter[$key];
                if (in_array($key, ['working_city', 'job'])) {
                    $key2 = 'FIND ' . $key;
                    $filter[$key2] = $filter[$key];
                   // unset($filter[$key]);
                    //echo $key;				pr($filter);
                    $key = $key2;
                }

                $this->_filter_parse_where($key, $filter);
            }
        }
        //=== Su ly loc theo time
        $where= $this->_filter_parse_time_where($filter,$where );

       // pr($filter);

        // kiem tra quang cao
        if (isset($filter['adsed']) && $filter['adsed']) {
            $now = now();
            //$where['ads_status'] = 1;$where['ads_expired >='] = now();
            $this->db->where('adsed = 1 && (adsed_begin <' . $now . ' && ' . $now . '< adsed_end)');
        }


        //pr($filter);
        // -=Modified=-
        if (isset($filter['!id'])) {
            if (is_array($filter['!id'])) {
                $this->db->where_not_in('id', $filter['!id']);
            } else {
                $this->db->where("id !=", $filter['!id']);
            }
        }

        if (isset($filter['key'])) {
            $v = $this->db->escape_like_str($filter['key']);

            $this->db->where("(
				( user.email LIKE '%{$v}%' ) OR
				( user.username LIKE '%{$v}%' ) OR
				( user.phone LIKE '%{$v}%' )
			)");
        }

        if (isset($filter['key_full'])) {
            $v = $this->db->escape_like_str($filter['key_full']);

            $this->db->where("(
				( user.name LIKE '%{$v}%' ) OR
				( user.email LIKE '%{$v}%' ) OR
				( user.username LIKE '%{$v}%' ) OR
				( user.phone LIKE '%{$v}%' )
			)");
        }
        if (isset($filter['email'])) {
            $this->search('user', 'email', $filter['email']);
        }

        if (isset($filter['name'])) {
            $this->search('user', 'name', $filter['name']);
        }

        if (isset($filter['blocked'])) {
            $status = ($filter['blocked']) ? 'yes' : 'no';
            $where['user.blocked'] = config('verify_' . $status, 'main');
        }


        if (isset($filter['balance'])) {
            if ($filter['balance']) {
                $where['user.balance_decode >'] = 0;
            } else {
                $where['user.balance_decode <='] = 0;
            }
        }



        if (isset($filter['show'])) {
            $where[$this->table.'.blocked'] = '0';
            //$where[$this->table.'.verify'] = 1;
        }

        // ko lay voi user hien thoi
        if (isset($filter['me'])) {
            $where['user_id !='] = $filter['me'];
        }
        return $where;
    }

    /**
     * Tim kiem
     */
    public
    function _search($field, $key)
    {
        switch ($field) {
            case 'email': {
                $this->db->like('user.email', $key);
                break;
            }
            case 'name': {
                $this->db->like('user.name', $key);
                break;
            }
        }
    }


    /*
     * ------------------------------------------------------
     *  Balance handle
     * ------------------------------------------------------
     */

    /**
     * Lay balance cua user
     */
    public
    function balance_get($id)
    {
        $user = $this->get_info($id, 'balance');

        if (empty($user->balance)) {
            return 0;
        }

        $balance = $this->balance_encrypt('decode', $id, $user->balance);

        return $balance;
    }


    /**
     *  Tang so du
     *
     * @param int $id
     * @param float $amount
     * @return float    So du cua user sau khi thuc hien thay doi
     */
    public
    function balance_plus($id, $amount)
    {
        $balance_bf = $this->balance_get($id);

        $amount = (float)$amount;
        $balance_af = $balance_bf;
        if ($amount) {
            $balance_af = $balance_bf + $amount;

            $this->_balance_set($id, $balance_bf, $balance_af, $amount, '+');
        }

        return $balance_af;
    }

    /**
     * Giam  so du
     *
     * @param int $id
     * @param float $amount
     * @return float    So du cua user sau khi thuc hien thay doi
     */
    public
    function balance_minus($id, $amount)
    {
        $balance_bf = $this->balance_get($id);

        $amount = (float)$amount;

        $balance_af = $balance_bf;
        if ($amount) {
            $balance_af = $balance_bf - $amount;

            $this->_balance_set($id, $balance_bf, $balance_af, $amount, '-');
        }

        return $balance_af;
    }

    /**
     * Cap nhat balance cua user
     */
    private
    function _balance_set($id, $balance_bf, $balance_af, $amount, $change)
    {
        $data = array();
        $data['balance'] = $this->balance_encrypt('encode', $id, $balance_af);
        $data['balance_decode'] = $balance_af;
        $this->update($id, $data);
        if (config('log_user_balance')) {
            model('log_user_balance')->log($id, $balance_bf, $balance_af, $amount, $change);
        }
    }

    /**
     * Xu ly ma hoa balance cua user
     *
     * @param string $act
     * @param int $id
     * @param float $balance
     * @return float
     */
    function balance_encrypt($act, $id, $balance)
    {
        $this->load->library('encrypt');

        // Tao key ma hoa
        $key = config('encryption_key', '') . $id;

        // Ma hoa
        if ($act == 'encode') {
            $balance = floatval($balance);
            $balance = $this->encrypt->encode($balance, $key);
        } // Giai ma
        elseif ($act == 'decode') {
            $balance = $this->encrypt->decode($balance, $key);

            // Neu balance sau khi giai ma khong phai la dang float
            /* if ( ! preg_match('/^-?[0-9]+\.?[0-9]*$/', $balance))
            {
                $balance = 0;
            } */

            $balance = floatval($balance);
        }

        return $balance;
    }


    /*
     * ------------------------------------------------------
     *  Other fun
     * ------------------------------------------------------
     */
    /**
     * Lay cac cong thanh toan user duoc phep su dung (kem theo amount duoc thanh toan trong 1 ngay)
     */
    public
    function get_payments($id)
    {
        // Tai file thanh phan
        $this->load->model('user_group_model');

        // Lay user_group_id
        $user_group_id = 0;
        $user = $this->get_info($id, 'user_group_id, payments');
        if ($user) {
            $user_group_id = $user->user_group_id;
        } else {
            $user_group_client = $this->user_group_model->get_type('client', 'id');
            $user_group_id = $user_group_client->id;
        }

        // Lay payments cua user_group_id
        $payments = $this->user_group_model->get_payments($user_group_id);

        // Cap nhat amount cua payment duoc set rieng cho user
        if ($user) {
            $user->payments = @unserialize($user->payments);
            foreach ($payments as $payment => $amount) {
                if (isset($user->payments[$payment])) {
                    $payments[$payment] = floatval($user->payments[$payment]);
                }
            }
        }

        return $payments;
    }

    /**
     * Lay ten cua user
     */
    public
    function get_name($id)
    {
        if (!$id) {
            return lang('customer');
        }

        $user = $this->get_info($id, 'name');

        return ($user) ? $user->name : '';
    }

    /**
     * Lay tong so thanh vien theo trang thai xac thuc
     */
    public
    function get_total_verify($status)
    {
        $filter['verify'] = config('user_verify_' . $status, 'main');

        return $this->filter_get_total($filter);
    }

    /**
     * Tim user tu key
     *
     * @param string $key
     * @return false|object
     */
    public
    function find_user($key)
    {
        if (!$key) return false;

        $query = $this->db->where('id', $key)
            ->or_where('email', $key)
            ->or_where('username', $key)
            ->or_where('phone', $key)
            ->limit(1)
            ->get('user');

        return $query->num_rows() ? $query->row() : false;
    }

    /**
     * Kiem tra co ton tai user tuong ung voi key hay khong
     *
     * @param string $key
     * @return boolean
     */
    public
    function has_user($key)
    {
        return $this->find_user($key, 'id') ? true : false;
    }

    /**
     * Gan action
     *
     * @param int $id
     * @param string $action
     */
    public
    function set_action($id, $action)
    {
        $this->update($id, array(
            'action' => $action,
            'action_time' => now(),
        ));
    }


    /**=================================================
     * Notice HANDEL
     * ===================================================*/
    function notice_setting_set($user_id, $data)
    {
        model('company_notice_setting')->set($user_id, $data);
    }

    function notice_setting_get($user_id, $field = '')
    {
        return model('company_notice_setting')->get($user_id, $field);
    }

    /**=================================================
     * INFO HANDEL
     * ===================================================*/
    /**
     * Luu thong tin
     */
    function info_set($info, $table_id, $info_values, array $attributes = array())
    {

        // Xoa cac du lieu cu
        $this->info_del($info, $table_id);

        // Loai bo cac gia tri trung nhau
        $info_values = (!is_array($info_values)) ? array($info_values) : $info_values;
        $info_values = array_unique($info_values);

        // Tao du lieu moi
        foreach ($info_values as $v) {
            // Neu khong ton tai gia tri
            $v = trim($v);
            if (!strlen($v)) continue;

            // Them vao data
            $data = $attributes;
            $data[$this->table_info_adv_key] = $table_id;
            $data[$info . '_id'] = $v;
            $this->db->insert($this->table . '_i_' . $info, $data);
        }

        // Goi ham callback cua table_model
        $where = array();
        $where[$this->table_key] = $table_id;

        $data = array();
        $data[$info] = $info_values;

        $this->{$this->table . '_model'}->_event_change('update', array($where, $data));
    }

    /**
     * Xoa thong tin
     */
    function info_del($info, $table_id, $info_id = NULL)
    {
        $where = array();
        $where[$this->table_info_adv_key] = $table_id;
        if ($info_id !== NULL) {
            $where[$info . '_id'] = $info_id;
        }

        $this->db->where($where);
        $this->db->delete($this->table . '_i_' . $info);
    }

    /**
     * Lay thong tin
     */
    function info_get($info, $table_id)
    {
        $where = array();
        $where[$this->table_info_adv_key] = $table_id;
        $this->db->where($where);

        $this->db->select($info . '_id');
        $query = $this->db->get($this->table . '_i_' . $info);

        $values = array();
        foreach ($query->result() as $row) {
            $values[] = $row->{$info . '_id'};
        }

        return $values;
    }

    /**
     * Lay danh sach thong tin (Bao gom ca id va name)
     */
    //$info_join duoc su dung trong truong hop bang chua thong tin khong cung ten voi bang info da khai bao
    function info_get_list($info, $table_id, $select = '', $info_join = null)
    {
        $where = array();
        $where[$this->table_info_adv_key] = $table_id;
        $this->db->where($where);

        $select = (!$select) ? "{$info}.id, {$info}.name" : $select;
        $this->db->select($select);

        $this->db->from($this->table . '_i_' . $info);
        if ($info_join)
            $this->db->join($info_join, "{$this->table}_{$info}.{$info}_id = {$info_join}.id", 'left');
        else
            $this->db->join($info, "{$this->table}_{$info}.{$info}_id = {$info}.id", 'left');
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Kiem tra $info_id co ton tai trong danh sach thong tin cua $table_id hay khong
     * @param string $info Ten thong tin
     * @param int $table_id Table id
     * @param int $info_id Id cua thong tin can kiem tra
     */
    function info_exists($info, $table_id, $info_id)
    {
        $where = array();
        $where[$this->table_info_adv_key] = $table_id;
        $where[$info . '_id'] = $info_id;
        $this->db->where($where);

        if ($this->db->get($this->table . '_i_' . $info)->num_rows()) {
            return TRUE;
        }

        return FALSE;
    }

    //============
    public function followers($user_id)
    {
        $input['select'] = 'user.*';
        $input['where']['us.action'] = 'subscribe';
        $input['where']['us.table'] = 'user';
        $input['where']['us.user_id'] = $user_id;
        $input['where']['us.deleted'] = 0;
        $input['join'] = array(array('user_storage us', 'us.table_id = user.id'));
        $filter = array();
        $filter['show'] =1;
        return $this->filter_get_list($filter,$input);

    }
    public function followers_by($user_id)
    {
        $input['select'] = 'user.*';
        $input['where']['us.action'] = 'subscribe';
        $input['where']['us.table'] = 'user';
        $input['where']['us.table_id'] = $user_id;
        $input['where']['us.deleted'] = 0;
        $input['join'] = array(array('user_storage us', 'us.user_id = user.id'));
        $filter = array();
        $filter['show'] =1;
        return $this->filter_get_list($filter,$input);
    }
}