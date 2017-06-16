<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Blog_model extends MY_Model
{
    public $table = 'blog';
   // public $order = array( array('id', 'desc'));
    public $order = array(array('sort_order', 'asc'), array('id', 'desc'));

    public $translate_auto = TRUE;
    public $translate_fields = array(
        'name',
        'description',
        'seo_title',
        'seo_description'
    );

    public $fields = array(
        //== Info core
        'status',  'sort_order', 'created_day',
        //== Info main
        'name', 'brief', 'description',// 'tags', 'video',
        //== Info thuoc tinh bool
        // 'comment_fb_allow', 'comment_allow',
        'is_feature', //'is_new', 'is_soon',   'is_in_menu', 'is_live',  'is_slide',
        //== Info lien ket bang khac
        'cat_id', 'author_id',
        // == Info seo
         'seo_title',   'seo_url',    'seo_description',  'seo_keywords',//  'seo_index',
        //== Info kkac
        'count_view',
       // 'icon_fa',
        //'user_id' ,
        //'user_options' ,
        //'affiliate_options' ,
    );
    public $fields_filter = array(
        'name','id', 'cat_id','author_id',
        '!id', '%name', 'BINARY name', 'BINARY seo_url',
        'is_feature', 'is_new', 'is_soon',   'is_in_menu',  //'is_live',  'is_slide', 'is_show',
        'status','created', 'created_to',
    );
    public $fields_rule = array(
        'name' => 'required',
        //'cat_id' => 'required|callback__check_cat',
       // 'cat_id' => ['cat','callback__check_cat'],
    );
    //public $fields_type_currency = array( 'price',  );
    //public $fields_type_relation_cat = array( 'price',  );
    public $fields_type_image = array(/*'avatar',*/ 'image', 'banner', 'icon');
    public $fields_type_content = array( 'brief','description',);
    //public $fields_type_list_json = array('common_data', 'stats_data');
    public $fields_type_list_comma = array('cat_id');
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
        // pr($this->fields_filter );
        foreach ($this->fields_filter as $key) {
            if (isset($filter[$key]) && $filter[$key] != -1) {
                //echo '<br>key='.$key.', v='.$filter[$key];
                $this->_filter_parse_where($key, $filter);
            }
        }


        if (isset($filter['image'])) {
            $where[$this->table . '.image_id >'] = '0';
        }

        // Loc dang FIND_IN_SET
        foreach (array('tags') as $f) {
            if (isset($filter[$f]) && $filter[$f]) {
                $value = [];
                if (is_array($filter[$f])) {
                    foreach ($filter[$f] as $v) {
                        $value[] = "FIND_IN_SET(" . $this->db->escape($v) . ", `" . $f . "`)";
                    }
                } else
                    $value[] = "FIND_IN_SET(" . $this->db->escape($filter[$f]) . ", `" . $f . "`)";


                if ($value) {
                    $this->db->where('((' . implode(') or (', $value) . '))');
                }
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