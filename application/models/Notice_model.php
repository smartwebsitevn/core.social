<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notice_model extends MY_Model
{
    public $table = 'notice';
    public $order = array( array('id', 'desc'));
  /*  public $translate_auto = TRUE;
    public $translate_fields = array(
        'name',
        'description',
        'seo_title',
        'seo_description'
    );*/

    public $fields = array(
        //== Info core
        'status',  'sort_order',
        //== Info main
        'name',  'key', 'content', 'params',
        //== Info thuoc tinh bool
    );
    public $fields_filter = array(
        //== core
        'name', '%name',  'BINARY name',
        'key', '%key',  'BINARY key',
        'id','!id','id_gt', 'id_gte', 'id_lt', 'id_lte',
        'status','created', 'created_to',
    );
    public $fields_rule = array(
        'name' => 'required',
        //'key' => 'required',
        'content' => 'required',
        //'params' => 'required',
       // 'cat_id' => ['cat','required|callback__check_cat'],
    );
    public $fields_type_content = array( 'content',);
    public $actions_row = array('edit', 'del', 'translate');
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


        // hien thi san pham phia ngoai
        if (isset($filter['show'])) {
            $where[$this->table . '.status'] = '1';
        }
        return $where;
    }

}