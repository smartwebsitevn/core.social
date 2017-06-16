<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_method_model extends MY_Model
{
    public $table = 'payment_method';
//    public $order = array(array('created', 'desc'), array('id', 'desc'));
    public $order = array(array('sort_order', 'asc'), array('id', 'desc'));

    public $translate_auto = TRUE;
    public $translate_fields = array(
        'name',
        'description',
    );

    public $fields = array(
        //== Info core
        'status',  'sort_order',   'created',  'updated',
        //== Info main
        'name',  'brief', 'description',// 'tags',     'video',
        //== Info thuoc tinh bool
        //'comment_fb_allow', 'comment_allow',
        'is_feature',  'is_default',//'is_new', 'is_soon',   'is_in_menu', 'is_live',  'is_slide',
        //== Info lien ket bang khac
        //'cat_id',
        // == Info seo
        // 'seo_title',   'seo_url',    'seo_description',  'seo_keywords',//  'seo_index',
        //== Info kkac
        //'icon_fa',
    );
    public $fields_filter = array(
        'cat_id',
        //== core
        'name', '%name',  'BINARY name',
        'id','!id','id_gt', 'id_gte', 'id_lt', 'id_lte',
        'seo_url',  'BINARY seo_url',
        'is_feature', 'is_new', 'is_soon',   'is_live',  'is_slide',  'is_in_menu', 'is_show', 'is_default',
        'status','created', 'created_to',
    );
    public $fields_rule = array(
        'name' => 'required',
        //'cat_id' => 'required|callback__check_cat',
        //'cat_id' => ['cat','required|callback__check_cat'],
    );
    //public $fields_type_currency = array( 'price',  );
    //public $fields_type_relation_cat = array( 'price',  );
    public $fields_type_image = array(/*'avatar',*/ 'image', 'banner', 'icon');
    public $fields_type_content = array( 'brief','description',);
    //public $fields_type_list_json = array('common_data', 'stats_data');
    //public $fields_list_comma = array('common_data', 'stats_data');
    public $actions_row = array('edit', 'del', 'feature', 'feature_del', 'default', 'default_del', 'translate');
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


         foreach ($this->fields_type_image as $f) {
            if (isset($filter[$f])) {
                $where[$this->table . '.' . $f . '_id >'] = '0';
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
            $where[$this->table . '.status'] = '1';
        }
        return $where;
    }

}