<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Product_model extends MY_Model
{
    public $table = 'product';
   // public $order = array( array('sort_order', 'asc'), array('product.id', 'desc'));
    public $order = array( array('product.id', 'desc'));
    public $translate_auto = TRUE;
    public $translate_fields = array(
        'name',
        'description',
        'technical',
        'note',
        'seo_title',
        'seo_description'
    );

    public $fields = array(
        //== Info core
        'status',  /* 'created',  'updated',*/  'sort_order',// 'expired', 'expired_option',
        //== Info main
        'name', 'price',
        'price_prefix','price_suffix',
        'price_is_contact',   'price_is_auction','price_is_auction_data',

        'model','link',
        'video', 'brief', 'description',     'technical', 'note',    'tags',

        'quantity', 'point',
        //'warranty',     'promotion',
        'taxclass', 'shipping', 'weight', 'dimension',

        //== Info lien ket bang khac
        'cat_id','manufacture_id', 'country_id',
        'stock_id', 'warranty_id',
        'type_cat_id',  'user_id',
        //== Info thuoc tinh bool
       // 'comment_allow',  'comment_fb_allow',
        'has_voucher', //  'has_combo',
        'is_feature', 'is_new',   // 'is_draft', 'is_alway_in_stock','is_in_menu','is_soon', 'is_live',  'is_slide',

        // == Info seo
        'seo_title',   'seo_url',    'seo_description',  'seo_keywords',
        //== Info kkac
        'count_view',
        'icon_fa',
        //'user_id' ,
        //'user_options' ,
        //'affiliate_options' ,
    );
    public $fields_filter = array(
        'point_total','!point_total','point_total_gt', 'point_total_gte', 'point_total_lt', 'point_total_lte',

        //== price
        'price','price_gt', 'price_lt','price_gte', 'price_lte',
        'price_is_contact',   'price_is_auction',
        //== cat
        'cat_id', 'author_id', 'user_id',
        'manufacture_id',    'stock_id', 'warranty_id','country_id',
        'type_cat_id',
        //== attr
        'has_voucher', 'has_combo',

        //== core
        'name', '%name',  'BINARY name',
        'id','!id','id_gt', 'id_gte', 'id_lt', 'id_lte',
        'seo_url',  'BINARY seo_url',
        'is_feature', 'is_new', 'is_soon',   'is_sellbest', 'is_alway_in_stock', 'is_live',  'is_slide',  'is_in_menu', 'is_show',
        'is_draft','is_form','is_lock' ,'deleted',
        'status',
        'created', 'created_to',
    );
    public $fields_rule = array(
        'name' => 'required',
        'type_cat_id' => ['type_cat_id','required|callback__check_type_cat_id'],

        //'description' => 'required',
    );

    public $fields_type_currency = array( 'price',  );
    public $fields_type_image = array(/*'avatar',*/ 'image', 'banner', 'icon');

    public $fields_type_content = array('brief', 'description', 'technical' ,'note',);
    public $fields_type_list_json = array('link_data','common_data', 'stats_data','price_is_auction_data');
    //public $fields_list_comma = array('common_data', 'stats_data');
    // cac thuoc tinh lien ket voi bang cat (chu y: chi de ten fiel ko co id)
    public $fields_type_relation_cat = array( 'warranty_id', 'stock_id',  );

    public $actions_row = array('edit', 'del', 'feature', 'feature_del', 'translate');
    public $actions_list = array('del');


    /*
     * ------------------------------------------------------
     *  Main handle
     * ----------------------------------------------------
     */
    function get_list_show_in_menu($filter = [])
    {
        $filter['status'] = 1;
        $filter['is_in_menu'] = 1;
        $input = array();
        $input['order'] = array('sort_order', 'asc');
        return $this->filter_get_list($filter, $input);
    }

    /**
     * Filter handle
     *
     */
    function _filter_get_where(array $filter)
    {
        $where = parent::_filter_get_where($filter);
        foreach ($this->fields_filter as $key) {
            if(in_array($key,['created','created_to'])) continue;
            if (isset($filter[$key]) && $filter[$key] != -1) {
                //echo '<br>key='.$key.', v='.$filter[$key];
                $this->_filter_parse_where($key, $filter);
            }
        }
        //=== Su ly loc theo time
        $where= $this->_filter_parse_time_where($filter,$where );
        //pr($filter,0);

        foreach ($this->fields_type_image as $f) {
            if (isset($filter[$f])) {
                $where[$this->table . '.' . $f . '_id >'] = '0';
            }
        }

        if (isset($filter['discount'])) {
            $where[$this->table . '.discount >'] = '0';
        }

        if (isset($filter['types'])) {
            $this->db->join('type_table type', 'type.table_id = product.id', 'iner');
            $this->db->group_by($this->table . '.id');
            ///$this->db->having('COUNT(*) = '.count($filter['types']));
            $where['type.table'] = 'product';

           /* foreach($filter['types'] as $k=>$v) {
               // $this->db->where( 'type.type_id',$k );
                $this->db->or_where( 'type.type_item_id',$v );
                //$this->db->or_where( 'type.type_item_id',$v );
            }*/
            $this->db->where_in(  'type.type_item_id', $filter['types'] );

        }


        // loc cac cot salary
        foreach(array('price_range') as $p) {
            $v= (isset($filter[$p]) && $filter[$p])?$filter[$p]:null;
            $p = str_replace('_range','',$p);
            if($v){
                $value = array();
                if(is_array($v)) {
                    foreach($v as $row){
                        $range = array();
                        if ($row->from > 0) {
                            $range[] =  $p . ' >= '.$row->from;
                        }
                        if ($row->to > 0) {
                            $range[] = $p . ' <= '.$row->to;
                        }
                        if($range)
                            $value[] = implode(') or (', $range);
                    }
                } else {
                    $range = array();
                    if ($v->from > 0) {
                        $range[] =  $p . ' >= '.$v->from;
                    }
                    if ($v->to > 0) {
                        $range[] = $p . ' <= '.$v->to;
                    }
                    if($range)
                        $value[] = implode(') and (', $range);
                }
                if($value)
                    $this->db->where('(('.implode(') or (', $value).'))');
            }
        }
       // pr($filter);
        // tim by owner
        /*if (isset($filter['user_id'])) {
            $this->search($this->table, 'user_id', $filter['user_id']);
        }*/

        //=== Su ly loc theo ngay tao
        //  1: tu ngay  - den ngay
       /* if (isset($filter['created']) && isset($filter['created_to'])) {
            $where[$this->table . '.created >='] = is_numeric($filter['created']) ? $filter['created'] : get_time_from_date($filter['created']);
            $where[$this->table . '.created <='] = is_numeric($filter['created_to']) ? $filter['created_to'] : get_time_from_date($filter['created_to']);// + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
        } //2: tu ngay
        elseif (isset($filter['created'])) {
            $where[$this->table . '.created >='] = is_numeric($filter['created']) ? $filter['created'] : get_time_from_date($filter['created']);
        } //3: den ngay
        elseif (isset($filter['created_to'])) {
            $where[$this->table . '.created <='] = is_numeric($filter['created_to']) ? $filter['created_to'] : get_time_from_date($filter['created_to']);// + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
        }*/

        // hien thi san pham phia ngoai
        if (isset($filter['show'])) {
            $where[$this->table.'.is_draft'] = 0;// ko hien tin nhap
            $where[$this->table.'.is_form'] = 0;// ko hien  tin mau
            $where[$this->table.'.is_lock'] = 0; // ko hien  tin da bi khoa
            $where[$this->table.'.deleted'] = 0; // ko hien  tin da xoa tam
            $where[$this->table.'.status'] = 1; // chi hien tin la cong bo
            $where[$this->table.'.verified >'] = 0; // chi hien tin da xac thuc
            //$where[$this->table.'.expired >='] = now();// chi hien tin con han dang
            //$where[$this->table.'.point_total >'] = -10; // ko hien  tin co diem -
            //$this->db->where('(point_total + point_fake) > -10');

        }
        return $where;
    }


    /**
     * Tao filter tu input
     */
    function filter_create($fields, &$input = array())
    {
        /* Lay gia tri cua filter dau vao */
        $input = array();
        foreach ($fields as $f) {
            $v = $this->input->get($f);
            $v = security_handle_input($v, in_array($f, array()));

            // Bỏ dấu , khi lọc theo giá
            if ($v && in_array($f, array('price_gt', 'price_lt'))) {
                $tmp = urldecode($v);
                $tmp = str_replace(',', '', $tmp);
                $input[$f] = (double)$tmp;
                continue;
            }


            $input[$f] = $v;
        }


        /* Tao bien filter */
        $filter = array();
        $query = url_build_query($input, TRUE);
        foreach ($query as $f => $v) {
            if ($v === NULL) continue;

            $filter[$f] = $v;
        }


        return $filter;
    }

    function get_streamline_post($user_id)
    {
        $this->db->select('min(created) min, max(created) max');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1, 0);
        $query = $this->db->get($this->table);
        if ($query->num_rows()) {
            $row = $query->row();
            return $row;
        }
        return FALSE;
    }
    function get_streamline_save($user_id)
    {
        $this->db->select('min(created) min, max(created) max');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1, 0);
        $query = $this->db->get($this->table.'_to_favorite');
        if ($query->num_rows()) {
            $row = $query->row();
            return $row;
        }
        return FALSE;
    }

    /**
     * Tim kiem du lieu
     */
    /*function _search($field, $key)
    {
        switch ($field) {
            case 'user_id': {
                $this->db->join('product_owner', "product_owner.table_id = " . $this->table . ".id AND product_owner.table_name = 'product'", 'inner');
                if (is_array($key))
                    $this->db->where_in('product_owner.owner_id', $key);
                else
                    $this->db->where('product_owner.owner_id', $key);
                break;
            }
        }
    }*/
}