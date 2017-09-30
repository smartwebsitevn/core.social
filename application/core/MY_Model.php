<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model Core Class
 *
 * Class xay dung cho cac model
 *
 * @author        ***
 * @version        2015-08-08
 */
class MY_Model extends CI_Model
{

    /*
     * ------------------------------------------------------
     *  Thong tin table
     * ------------------------------------------------------
     */
    // Ten table
    public $table = '';

    // Key chinh cua table
    public $key = 'id';

    // Order mac dinh (VD: $order = array('id', 'desc'), $order = array(array('id', 'desc'), array('name', 'asc')))
    public $order = '';

    // Cac field select mac dinh khi get list (VD: $select = 'id, name')
    public $select = '';

    // Sql join tuong ung voi cac table
    public $join_sql = array();

    // Cac field input
    public $fields = array();

    // Cac rule de valid field
    public $fields_rule = array();

    // Cac field de loc
    public $fields_filter = array();

    // Cac field can xu ly anh
    public $fields_type_image = array();

    // Cac field can xu ly tien te
    public $fields_type_currency = array();

    // Cac field can xu ly noi dung
    public $fields_type_content = array();

    // Cac field can ma hoa
    public $fields_type_encode = array();

    // Cac field dang list dang json
    public $fields_type_list_json = array();

    // Cac field dang list dang ngan cach bang dau phay
    public $fields_type_list_comma = array();

    // Cac field dang cat (du lieu lien ket toi bang cat - luu 1 gia tri)
    public $fields_type_relation_cat = array();
    // Cac field dang cat (du lieu lien ket toi bang cat - luu nhieu gia tri  ngan cach boi giau phay)
    public $fields_type_relation_cat_multi = array();


    // Cac actions row
    public $actions_row = array();

    // Cac actions list
    public $actions_list = array();

    // Cac field luu tru trong index
    public $index_fields = array();

    // Cac field co query tim kiem rieng
    public $index_search_special_fields = array();


    // Cac table thong tin thanh phan
    public $table_info = array();

    // Kieu tim kiem cua cac table thong tin thanh phan (VD: $table_info_search_mod = array('table_1' => 'equal', 'table_2' => 'range', ...)). Mac dinh la 'equal'
    public $table_info_search_mod = array();


    // Cac field can dich
    public $translate_fields = array();

    // Tu dong dich khi truy van du lieu
    public $translate_auto = FALSE;

    /**
     * Khai bao quan he voi cac table khac
     *    $relations[parent] = array(type, local_key, parent_key)
     *        parent        : Ten table quan he
     *        type        : Kieu quan he (one|many)
     *        local_key    : Key cua parent tren table hien tai (mac dinh la parent_id)
     *        parent_key    : Key cua parent (mac dinh la id)
     *
     * @var array
     */
    public $relations = array();


    /**
     * Luu thoi gian create va update row
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Ten cua column created
     *
     * @var string
     */
    public $field_created = 'created';


    // --------------------------------------------------------------------

    /**
     * Ham khoi dong
     */
    function __construct()
    {
        parent::__construct();

        // Load php_sql_parser_library
        if (!isset($this->php_sql_parser_library)) {
            $this->load->library('php_sql_parser_library');
        }

        // Neu su dung cac table_info
        if (count($this->table_info)) {
            // Goi class core_info_multi_model
            $this->load->model('core/core_info_multi_model', $this->table . '_info_model');
            $this->{$this->table . '_info_model'}->table = $this->table;
            $this->{$this->table . '_info_model'}->table_key = $this->key;

            // Tao sql join cua cac table_info
            foreach ($this->table_info as $n) {
                $this->join_sql[$this->table . '_' . $n] = "{$this->table}.{$this->key} = {$this->table}_{$n}.{$this->table}_{$this->key}";
            }
        }

        // Goi class core_index_model
        if (count($this->index_fields)) {
            $this->load->model('core/core_index_model');
            $this->load->model($this->table . '_index_model');
            $this->{$this->table . '_index_model'}->table = $this->table;
            $this->{$this->table . '_index_model'}->table_key = $this->key;
            $this->{$this->table . '_index_model'}->fields = $this->index_fields;
            $this->{$this->table . '_index_model'}->search_special_fields = $this->index_search_special_fields;
        }
    }


    /*
     * ------------------------------------------------------
     *  Xu ly row
     * ------------------------------------------------------
     */
    /**
     * Them row moi
     */
    function create(array $data, &$insert_id = NULL)
    {
        // Timestamps
        if ($this->timestamps) {
            $data[$this->field_created] = now();
        }

        // Them vao data
        $this->db->insert($this->table, $data);

        // Lay id vua them
        $_insert_id = $this->db->insert_id();
        if ($insert_id !== NULL) {
            $insert_id = $_insert_id;
        }
        $data[$this->key] = $_insert_id;
        // Goi ham callback
        $this->_event_change('create', array($data));
    }

    // Them row moi
    function create_rows(array $data)
    {
        // Them vao data
        $this->db->insert_batch($this->table, $data);
    }


    /**
     * Cap nhat row tu id
     */
    function update($id, array $data)
    {
        if (!$id) {
            return FALSE;
        }

        $where = array();
        $where[$this->key] = $id;
        $this->update_rule($where, $data);

        return TRUE;
    }

    /**
     * Cap nhat gia tri field cua row tu id
     */
    function update_field($id, $field, $value)
    {
        $data = array();
        $data[$field] = $value;

        return $this->update($id, $data);
    }

    /**
     * Cap nhat row tu dieu kien
     */
    function update_rule($where, array $data)
    {
        if (empty($where)) {
            return FALSE;
        }

        $this->db->where($where);
        $this->db->update($this->table, $data);
        //pr_db($this->table,0);
        // Goi ham callback
        $this->_event_change('update', array($where, $data));

        return TRUE;
    }

    /**
     * Cap nhat hoac tao moi
     *
     * @param array $where
     * @param array $data
     */
    function update_or_create($where, array $data)
    {
        if ($id = $this->get_id($where)) {
            $this->update($id, $data);
        } else {
            $data = array_merge($data, $where);

            $this->create($data);
        }
    }

    /**
     * Cap nhat thong ke
     * @param mixed $table_key Table key
     * @param array $stats_update Gia tri cap nhat them cua cac field stats (VD: array('view' => 1))
     * @param array $data Thong tin muon luu them
     * @return TRUE|FALSE
     */
    function update_stats($tblkeys, $stats_update, $data = array())
    {
        if (is_array($tblkeys))
            $info = $this->get_info_rule($tblkeys);
        else
            $info = $this->get_info($tblkeys);
        if (!$info)
            return false;
        // Tao thong ke moi
        $stats_new = array();
        foreach ($stats_update as $f => $v) {
            // if (isset($info->{$f})){
            $stats_new[$f] = $info->{$f} + $v;
            //}
        }
        // pr($stats_update,0);
        // pr($stats_new);

        $data = array_merge($data, $stats_new);
        $where = array();
        $where[$this->key] = $info->{$this->key};
        $this->db->where($where);
        $this->db->update($this->table, $data);
        // Goi ham callback
        //pr($this->db->last_query());
        $this->_event_change('update', array($where, $data));
        return TRUE;

        // khong nen goi chung voi ham update (vi co truong hop ham update nay bi update boi module len chuc nang ham nay se co the khac )
        //return $this->update($info[$this->tblkey],$data);
    }


    /**
     * Cap nhat tu phan tu thu n
     * @param int $n phan tu thu n
     * @param array $input du lieu loc list
     * @param array $where_n dieu kien cap nhap lay theo phan tu n (gom key va value)
     * @param array $where dieu kien cap nhap truyen vao
     * @param array $data Thong tin cap nhap
     * @param boll $allow_down Cho phep lay xuong phan tu thap hon neu ko co phan tu n
     * @return TRUE|FALSE

    eg: - $n=5
     * - $input = array();
     * $input['where']=array ('created_by' =>$member_id);
     * $input['order']=array ('field' => 'created','direct'=>'asc');
     * - $where = array ('created_by' => $member_id
     * - $where_n = array ('created >=' => 'created')
     * - $data=array('lock'=>1)
     */
    function update_from_item_n($n, $data, $input, $where, $where_n = array(), $allow_down = false)
    {
        $n = $n - 1; // tru 1 do phan tu mang bat dau tu 0
        if ($n < 0)
            return false;
        //- kiem tra va khoa Textlink
        $list = $this->get_list($input);

        //pr($list_web);
        if (!$list) {
            return false;
        }
        //pr($list);
        if ($allow_down) {
            if (!isset($list[$n])) {
                while ($n > 0) {
                    $n--;
                    //echo 'chay n='.$n;
                    if (isset($list[$n]))
                        break;

                }

            }
        } else {
            if (!isset($list[$n]))
                return false;
        }
        // echo 'n='.$n;         pr($list[$n]);
        // lay phan tu moc
        $it_n = $list[$n];
        if (!$it_n)
            return false;

        // lay cac dieu kien theo phan tu n
        if (count($where_n) > 0)
            foreach ($where_n as $k => $v) {
                if (isset($it_n[$v]))
                    $where[$k] = $it_n[$v];
            }
        //ex: $this->updateRule ( array ('created_by' => $member_id,'created >'=> $it_n['created']),array('lock'=>1));

        $this->update_rule($where, $data);
        //echo '<br/>Query='.$this->db->last_query();
        $this->_event_change('update', array($where, $data));
        return TRUE;

    }

    /**
     * Xoa row tu id
     */
    function del($id)
    {
        if (!$id) {
            return FALSE;
        }

        $where = array();
        $where[$this->key] = $id;
        $this->del_rule($where);

        return TRUE;
    }

    /**
     * Xoa row tu dieu kien
     */
    function del_rule($where)
    {
        if (empty($where)) {
            return FALSE;
        }

        $this->db->where($where);
        $this->db->delete($this->table);

        // Goi ham callback
        $this->_event_change('del', array($where));

        return TRUE;
    }

    // Xoa row tu dieu kien
    function del_rows($where)
    {
        if (empty($where)) {
            return FALSE;
        }

        foreach ($where as $key => $value) {
            if (is_array($value))
                $this->db->where_in($key, $value);
            else
                $this->db->where($key, $value);
        }


        $this->db->delete($this->table);

        // Goi ham callback
        $this->_event_change('del', array($where));

        return TRUE;
    }

    //============================== SETS FUNCTION CHECK DATA
    function check_id($id)
    {//by id
        if (!$id) {
            return FALSE;
        }
        $this->db->where($this->key, $id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() != 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function check_exits($where, $where_or = false)
    {

        if (!$where) {
            return FALSE;
        }
        if ($where_or) {
            $this->db->or_where($where);
        } else {
            $this->db->where($where);
        }
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            return TRUE;

        } else {
            return FALSE;

        }
    }

    /**
     * Lay id cua row tu dieu kien
     */
    function get_id($where)
    {
        $info = $this->get_info_rule($where, $this->key);
        $id = (isset($info->{$this->key})) ? $info->{$this->key} : FALSE;

        return $id;
    }

    /**
     * Lay thong tin cua row tu id
     */
    function get_info($id, $field = '', $lang_id = 0)
    {
        if (!$id) {
            return FALSE;
        }

        $where = array();
        $where[$this->table . '.' . $this->key] = $id;

        return $this->get_info_rule($where, $field, $lang_id);
    }

    /**
     * Lay thong tin cua row tu dieu kien
     */
    function get_info_rule($where, $field = '', $lang_id = 0)
    {
        if (empty($where)) {
            return FALSE;
        }

        $this->db->where($where);

        if ($field) {
            $this->db->select($field);
        }

        $this->db->limit(1, 0);

        // Tu dong join den cac table duoc khai bao
        $this->_join();

        $query = $this->db->get($this->table);
        if ($query->num_rows()) {
            $row = $query->row();

            $row = $this->_translate_auto($row, $lang_id);

            return $row;
        }

        return FALSE;
    }


    /*
     * ------------------------------------------------------
     *  Xu ly list
     * ------------------------------------------------------
     */
    /**
     * Lay tong so
     */
    function get_total($where = array())
    {
        // Gan where
        $this->db->where($where);
        $this->db->from($this->table);

        // Neu can join den table thanh phan
        if ($this->_join()) {
            $this->db->select("COUNT(DISTINCT {$this->table}.{$this->key}) AS total");

            $query = $this->db->get();
            if (!$query->num_rows()) {
                return 0;
            }

            return (int)$query->row()->total;
        }

        // Neu chi loc du lieu tren table hien tai
        return $this->db->count_all_results();
    }

    /**
     * Cộng tổng số
     */
    function get_sum($field, $where = array(), $where_or = false)
    {
        $this->db->select_sum($this->table . '.' . $field);
        if (count($where) > 0) {
            if ($where_or) {
                $this->db->or_where($where);
            } else {
                $this->db->where($where);
            }
        }
        // $this->db->where($where);
        $this->db->from($this->table);
        // Neu can join den table thanh phan
        $this->_join();

        $row = $this->db->get()->row();
        //pr($this->db->last_query());
        $sum = 0;

        foreach ($row as $f => $v) {
            $sum = $v;
        }

        return $sum;
    }

    /**
     * Lay danh sach
     */
    function get_list($input = array(),$only_key =false)
    {
        $this->_get_list_set_input($input);

        $list = $this->db->get($this->table)->result();

        $list = $this->_translate_auto($list);

        if (!empty($input['relation'])) {
            $list = $this->relations($input['relation'], $list);
        }
        if($only_key && $list){
            $tmp=[];
            foreach($list as $row){
                $tmp[]=$row->{$this->key};
            }
            $list =$tmp;
        }
        return $list;
    }

    /**
     * Lay danh sach
     */
    function get_list_rule($where = array(), $wherein = array(),$only_key =false)
    {
        $input = array(
            'where' => $where,
            'wherein' => $wherein
        );
        return $this->get_list($input,$only_key);

    }
    /**
     * Gan cac thuoc tinh trong input khi lay danh sach
     */
    /**
     * Gan cac thuoc tinh trong input khi lay danh sach
     */
    function _get_list_set_input(array $input)
    {
        // Select
        $select = (empty($input['select'])) ? $this->select : $input['select'];
        $this->db->select($select);
        /* if(isset($input['where']['ctf.user_id'])){
             echo $select;
             pr($input);

         }*/
        // From
        if (isset($input['from'])) {
            $this->db->from($input['from']);
        }
        // Where
        $where = (isset($input['where'])) ? $input['where'] : array();
        $this->db->where($where);

        if ((isset($input['wherein']))) {
            foreach ($input['wherein'] as $k => $v) {
                if (!$this->_check_field_has_table($k))
                    $k = $this->table . '.' . $k;
                $this->db->where_in($k, $v);
            }

        }
        if ((isset($input['wherenotin']))) {
            foreach ($input['wherenotin'] as $k => $v) {
                if (!$this->_check_field_has_table($k))
                    $k = $this->table . '.' . $k;
                $this->db->where_not_in($k, $v);
            }

        }
        if ((isset($input['whereor']))) {
            if (is_string($input['whereor']))
                $this->db->or_where($input['whereor']);
            else {
                // loop each param, if has value then set
                $where = array();
                foreach ($input['whereor'] as $k => $v) {
                    //echo '<br>Where Param: key='.$k.' -  value='.$v;
                    if (isset($v) && $v <> '*')// && $v <> '' we use * and  for get all data or no condition\
                    {
                        if (!$this->_check_field_has_table($k))
                            $k = $this->table . '.' . $k;
                        //echo '<br>Where Param=';print_r($k);
                        $where[$k] = $v;// need $this->table.'.'.$k for avoid ambiguous in query with over 2 table
                    }
                }
                if (count($where) > 0)
                    $this->db->or_where($where);
            }

        }
        // like < process special ^^>
        if ((isset($input['like'])) && $input['like']) {
            // loop each param, if has value then set
            $like = array();
            foreach ($input['like'] as $k => $v) {
                if (!empty($v)) {
                    if (!$this->_check_field_has_table($k))
                        $k = $this->table . '.' . $k;
                    $like[$k] = $v;// need $this->table.'.'.$k for avoid ambiguous in query with over 2 table
                }
            }
            if (count($like) > 0)
                $this->db->like($like);

        }

        // Order
        $order = (isset($input['order'])) ? $input['order'] : $this->order;
        $order = (!$order) ? array($this->table . '.' . $this->key, 'desc') : $order;
        $order = (!is_array($order[0])) ? array($order) : $order;
        foreach ($order as $v) {
            $this->db->order_by($v[0], $v[1]);
        }

        // Limit
        if (isset($input['limit'])) {
            $this->db->limit($input['limit'][1], $input['limit'][0]);
        }

        // Having
        if (isset($input['having'])) {
            $this->db->having($input['having']);
        }

        // Group by
        if (isset($input['group_by'])) {
            $this->db->group_by($input['group_by']);
        }

        // Join table
        if ((isset($input['join']))) {
            foreach ($input['join'] as $join) {
                if (count($join) == 2)
                    $this->db->join($join[0], $join[1]);
                if (count($join) == 3)
                    $this->db->join($join[0], $join[1], $join[2]);
            }
        }
        // Join den table thanh phan
        if (
            $this->_join() &&
            !isset($input['group_by'])
        ) {
            $this->db->group_by("{$this->table}.{$this->key}");
        }
    }

    // kiem tra xem 1 truong da co tien to bang hay chua
    function _check_field_has_table($field)
    {
        $dot = strrpos($field, '.');
        if ($dot !== false) {
            return true;
        }
        return false;
    }

    /**
     * Gan dieu kien tim kiem
     */
    function search($table, $field, $key)
    {
        // Tim kiem tren table hien tai
        if ($table == $this->table) {
            return $this->_search($field, $key);
        }

        // Tim kiem tren table khac
        $model = $table . '_model';
        $this->load->model($model);

        return $this->$model->_search($field, $key);
    }

    function _search($field, $key)
    {
    }


    /*
     * ------------------------------------------------------
     *  Lay danh sach join voi table thanh phan
     * ------------------------------------------------------
     */
    /**
     * Kiem tra sql hien tai va join den cac table can thiet duoc khai bao trong $join_sql
     */

    function _join_()
    {
        // Neu khong ton tai join_sql
        if (!count($this->join_sql)) {
            return FALSE;
        }
        //pr($this->db->qb_select,false);
        foreach ($this->join_sql as $tbl => $join) {
            $this->db->join($tbl, $join);
        }
        //echo $this->db->last_query();


    }

    protected function _join()
    {
        // Neu khong ton tai join_sql
        if (!count($this->join_sql)) {
            return FALSE;
        }

        // Lay danh sach column tu sql hien tai
        $sql = $this->_join_get_sql_cur();
        //pr($sql,false);
        $list_col = array();
        $this->php_sql_parser_library->get_list_col($this->php_sql_parser_library->parse($sql), $list_col);
        //echo '<br>========';
        //	echo '<br>-$list_col:';pr($list_col,false);
        // Lay danh sach table tu $list_col
        $list_table = array();
        foreach ($list_col as $col) {
            // Neu field khong phai tu table khac
            $col = explode('.', $col, 2);
            if (count($col) != 2) continue;

            // Neu la table hien tai
            //	$table = trim($col[0], $this->db->_escape_char);
            //echo '<br>-$table bef:'.$col[0];
            $table = trim($col[0], "`");
            //echo '<br>-$table af:'.$table;
            if ($table == $this->table) continue;

            // Neu table da co trong danh sach
            if (in_array($table, $list_table)) continue;

            // Them vao danh sach
            $list_table[] = $table;
        }

        //echo '<br>-$list_table:';pr($list_table,false);
        //echo '<br>-$join_sql:';pr($this->join_sql,false);
        // Lay danh sach table da duoc gan lenh join
        $list_table_join = array();
        foreach ($this->db->qb_join as $v) {
            $match = array();
            if (preg_match('#JOIN ([^\s]+) ON #i', $v, $match)) {
                //$list_table_join[] = trim($match[1], $this->db->_escape_char);
                $list_table_join[] = trim($match[1], "`");


            }
        }
        $list_table_join = array_unique($list_table_join);

        // Thuc hien join table
        $join = array();

        foreach ($list_table as $table) {
            if (
                !isset($this->join_sql[$table]) || // Neu khong ton tai join_sql cua table
                in_array($table, $list_table_join) // Table da duoc gan lenh join
            ) {
                continue;
            }

            $join[$table] = TRUE;
            $this->db->join($table, $this->join_sql[$table], 'left');

        }

        return (count($join)) ? TRUE : FALSE;
    }

    /**
     * Lay sql hien tai (from CI_DB_active_record)
     */
    protected function _join_get_sql_cur()
    {
        // Write the "SELECT" portion of the query

        $sql = (!$this->db->qb_distinct) ? 'SELECT ' : 'SELECT DISTINCT ';

        if (count($this->db->qb_select) == 0) {
            $sql .= '*';
        } else {
            // Cycle through the "select" portion of the query and prep each column name.
            // The reason we protect identifiers here rather then in the select() function
            // is because until the user calls the from() function we don't know if there are aliases
            foreach ($this->db->qb_select as $key => $val) {
                $no_escape = isset($this->db->qb_no_escape[$key]) ? $this->db->qb_no_escape[$key] : NULL;
                $this->db->qb_select[$key] = $this->db->protect_identifiers($val, FALSE, $no_escape);
            }
            //pr($this->db->qb_select,false);
            $sql .= implode(', ', $this->db->qb_select);
        }

        // ----------------------------------------------------------------

        // Write the "FROM" portion of the query

        $sql .= "\nFROM" . $this->db->protect_identifiers($this->table); // Fake from table

        // ----------------------------------------------------------------

        // Write the "WHERE" portion of the query

        if (count($this->db->qb_where) > 0) {
            $sql .= "\nWHERE ";
            foreach ($this->db->qb_where as $cond) {
                $sql .= "\n" . $cond['condition'];
            }
        }


        // ----------------------------------------------------------------

        // Write the "LIKE" portion of the query

        /*if (count($this->db->qb_like) > 0) //qb_like ko co o ci3
        {
            if (count($this->db->qb_where) > 0)
            {
                $sql .= "\nAND ";
            }

            $sql .= implode("\n", $this->db->qb_like);
        }*/

        // ----------------------------------------------------------------

        // Write the "GROUP BY" portion of the query

        if (count($this->db->qb_groupby) > 0) {
            $sql .= "\nGROUP BY ";

            $sql .= implode(', ', array_pluck($this->db->qb_groupby, 'field'));
        }

        // ----------------------------------------------------------------

        // Write the "HAVING" portion of the query

        if (count($this->db->qb_having) > 0) {
            $sql .= "\nHAVING ";
            $sql .= implode("\n", $this->db->qb_having);
        }

        // ----------------------------------------------------------------

        // Write the "ORDER BY" portion of the query
        if (count($this->db->qb_orderby) > 0) {
            $sql .= "\nORDER BY ";
            //$sql .= implode(', ', $this->db->qb_orderby);

            foreach ($this->db->qb_orderby as $cond) {
                $sql .= "\n " . $cond['field'] . ' ' . $cond['direction'];
            }


        }

        return $sql;
    }

    /**
     * Gan gia tri cho where khi filter tai table thanh phan
     */
    protected function _join_filter_set_where(array $filter, array &$where)
    {
        foreach ($this->table_info as $n) {
            // Lay ten field
            $f = "{$this->table}_{$n}.{$n}_id";

            // Lay kieu tim kiem
            $mod = (isset($this->table_info_search_mod[$n])) ? $this->table_info_search_mod[$n] : '';

            // So sanh bang
            if (isset($filter[$n])) {
                $v = $filter[$n];
                if (is_array($v) && count($v) > 1) {
                    if ($mod == 'range') {
                        $where[$f . ' >='] = $v[0];
                        $where[$f . ' <='] = $v[1];
                    } else {
                        $this->db->where_in($f, $v);
                    }
                } else {
                    $v = (is_array($v) && !count($v)) ? array('') : $v;
                    $where[$f] = (is_array($v)) ? $v[0] : $v;
                }
            }

            // So sanh khac
            if (isset($filter[$n . ' !='])) {
                $v = $filter[$n . ' !='];
                if (is_array($v) && count($v) > 1) {
                    if ($mod == 'range') {
                        $this->db->where("( {$f} < {$this->db->escape($v[0])} OR {$f} > {$this->db->escape($v[1])} )");
                    } else {
                        $this->db->where_not_in($f, $v);
                    }
                } else {
                    $v = (is_array($v) && !count($v)) ? array('') : $v;
                    $where[$f . ' !='] = (is_array($v)) ? $v[0] : $v;
                }
            }
        }
    }


    /*
     * ------------------------------------------------------
     *  Get list theo filter
     * ------------------------------------------------------
     */
    /**
     * Tao filter tu input
     */
    function filter_create_input($fields, &$input = array())
    {

        // Lay gia tri cua filter dau vao
        $input = array();
        foreach ($fields as $f) {
            $v = t('input')->get($f);
            $v = security_handle_input($v, in_array($f, array()));

            $input[$f] = $v;
        }

        if (!empty($input['id'])) {
            foreach ($input as $f => $v) {
                $input[$f] = ($f != 'id') ? '' : $v;
            }
        }

        // Tao bien filter
        $filter = array();
        $query = url_build_query($input, TRUE);

        foreach ($query as $f => $v) {
            if ($v === NULL) continue;

            $filter[$f] = $v;
        }

        return $filter;
    }

    /**
     * Lay danh sach
     */
    function filter_get_list(array $filter, array $input = array(),$only_key =false)
    {

        $where = $this->_filter_get_where($filter);
        if (isset($input['where']))
            $input['where'] = array_merge($input['where'], $where);
        else
            $input['where'] = $where;
        $list = $this->get_list($input ,$only_key);

        return $list;
    }

    /**
     * Lay info
     */
    function filter_get_info(array $filter, $fields = '')
    {

        $where = $this->_filter_get_where($filter);
        return $this->get_info_rule($where, $fields);
    }

    /**
     * Lay tong so
     */
    function filter_get_sum($field, $filter = array(), $input = array())
    {
        $where = $this->_filter_get_where($filter);
        if ($input)
            $this->_get_list_set_input($input);
        return $this->get_sum($field, $where);
    }

    /**
     * Lay tong so
     */
    function filter_get_total($filter = array(), $input = array())
    {

        $where = $this->_filter_get_where($filter);
        if ($input)
            $this->_get_list_set_input($input);
        return $this->get_total($where);
    }

    /**
     * Lay where theo filter
     */
    function _filter_get_where(array $filter)
    {
        $where = array();
        // loc inject
        if ($filter) {
            foreach ($filter as $k => $v) {
                if (is_string($v)) {
                    $filter[$k] = $this->db->escape($v);
                }
            }
        }
        // pr($filter);
        // Join filter
        $this->_join_filter_set_where($filter, $where);

        return $where;
    }

    /**
     * Gan gia tri cho where
     * @param string $mod Kieu tim kiem ('equal' OR 'range')
     */
    protected function _filter_set_where(array $filter, $param, $field, array &$where, $mod = 'equal')
    {
        // So sanh bang
        if (isset($filter[$param])) {
            $v = $filter[$param];
            if (is_array($v) && count($v) > 1) {
                if ($mod == 'range') {
                    $where[$field . ' >='] = $v[0];
                    $where[$field . ' <='] = $v[1];
                } else {
                    $this->db->where_in($field, $v);
                }
            } else {
                $v = (is_array($v) && !count($v)) ? array('') : $v;
                $where[$field] = (is_array($v)) ? $v[0] : $v;
            }
        }

        // So sanh khac
        if (isset($filter[$param . ' !='])) {
            $v = $filter[$param . ' !='];
            if (is_array($v) && count($v) > 1) {
                if ($mod == 'range') {
                    $this->db->where("( {$field} < {$this->db->escape($v[0])} OR {$field} > {$this->db->escape($v[1])} )");
                } else {
                    $this->db->where_not_in($field, $v);
                }
            } else {
                $v = (is_array($v) && !count($v)) ? array('') : $v;
                $where[$field . ' !='] = (is_array($v)) ? $v[0] : $v;
            }
        }
    }

    protected function _filter_parse_time_where($filter, $where, $times = ['created'])
    {
        foreach ($times as $time) {
            //  1: tu ngay  - den ngay
            if (isset($filter[$time]) && isset($filter[$time.'_to'])) {
                $where[$this->table . '.created >='] = is_numeric($filter[$time]) ? $filter[$time] : get_time_from_date($filter[$time]);
                $where[$this->table . '.created <='] = is_numeric($filter[$time.'_to']) ? $filter[$time.'_to'] : get_time_from_date($filter[$time.'_to']) + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
            } //2: tu ngay
            elseif (isset($filter[$time])) {
                if (is_array($filter[$time])) {
                    $where[$this->table . '.created >='] = $filter[$time][0];
                    $where[$this->table . '.created <'] = $filter[$time][1];
                } else
                    $where[$this->table . '.created >='] = is_numeric($filter[$time]) ? $filter[$time] : get_time_from_date($filter[$time]);
            } //3: den ngay
            elseif (isset($filter[$time.'_to'])) {
                $where[$this->table . '.created <='] = is_numeric($filter[$time.'_to']) ? $filter[$time.'_to'] : get_time_from_date($filter[$time.'_to']) + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
            }
        }
        return $where;

    }

    protected function _filter_parse_where($key, $filter)
    {
        // pr($key);

        if (!isset($filter[$key]))
            return;
        $value = $filter[$key];
        // Compare
        if (preg_match("#_gt$#", $key)) {
            $this->db->where($this->table . '.' . substr($key, 0, strlen($key) - 3) . ' >', $value);
            return;
        }
        if (preg_match("#_gte$#", $key)) {

            $this->db->where($this->table . '.' . substr($key, 0, strlen($key) - 4) . ' >=', $value);
            return;
        }
        if (preg_match("#_lt$#", $key)) {
            $this->db->where($this->table . '.' . substr($key, 0, strlen($key) - 3) . ' <', $value);
            return;
        }
        if (preg_match("#_lte$#", $key)) {
            $this->db->where($this->table . '.' . substr($key, 0, strlen($key) - 4) . ' <=', $value);
            return;
        }
        // Like
        if (strpos($key, "%") !== false) {
            //pr($value );
            $this->db->like($this->table . '.' . substr($key, 1, strlen($key)), $value);
            return;
        }

        // Phủ định
        if (strpos($key, "!") !== false) {
            if (is_array($value)) {
                $this->db->where_not_in($this->table . '.' . substr($key, 1, strlen($key)), $value);
                return;
            }

            $this->db->where(substr($this->table . '.' . $key, 1, strlen($key)) . " !=", $value);
            return;
        }

        // CASE sensitive
        if (strpos($key, "BINARY") !== false) {
            //7 la bao gom ca khoang trang , vd BINARY NAME
            $this->db->where($this->table . '.' . substr($key, 7, strlen($key)) . " like BINARY '" . $value . "'");
            return;
        }
        //  FIND BY KEYWORD
        if (strpos($key, "KEYWORD") !== false) {
            //9 la bao gom ca khoang trang , vd KEYWORD NAME
            $f = substr($key, 8, strlen($key));
            $keywords = '';

            if (is_array($value)) {

                $key = str_replace([',', '.'], '', $value);
                $key = trim($key);

                $query = ["`$f` LIKE '%" . t('db')->escape_like_str($key) . "%'"];
                $keys = preg_replace('/\s+/', ' ', $key);
                $keys = explode(' ', $keys);
                foreach ($keys as $v) {
                    $v = t('db')->escape_like_str($v);
                    $query[] = "`$f` LIKE '%{$v}%'";
                }
                $keywords = implode(' OR ', $query);
                $keywords = "($keywords)";
            }


            if ($keywords) {
                $this->db->where($keywords);
            }
            return;
        }
        //  FIND_IN_SET
        if (strpos($key, "FIND") !== false) {
            //5 la bao gom ca khoang trang , vd FIND NAME

            $f = substr($key, 5, strlen($key));
            // pr($f,0);     pr($key);         pr($filter);
            $keywords = [];
            if (is_array($value)) {
                foreach ($value as $v) {
                    $keywords[] = "FIND_IN_SET(" . $this->db->escape($v) . ", `" . $f . "`)";
                }
            } else
                $keywords[] = "FIND_IN_SET(" . $this->db->escape($filter[$f]) . ", `" . $f . "`)";


            if ($keywords) {
                $this->db->where('((' . implode(') or (', $keywords) . '))');
            }
            return;
        }

        if (is_array($value))
            $this->db->where_in($this->table . '.' . $key, $value);
        else
            $this->db->where($this->table . '.' . $key, $value);
    }
    /*
     * ------------------------------------------------------
     *  Handle data handle
     * ------------------------------------------------------
     */
    /**
     * Xu ly du lieu dau vao
     */
    function handle_data_input($data = array())
    {
        if (!$data) return $data;
        foreach ($data as $p => $v) {

            // Params content
            if ($this->fields_type_content && in_array($p, $this->fields_type_content)) {
                $v = handle_content($v, 'input');
            }
            // Params list
            if ($this->fields_type_list && in_array($p, $this->fields_type_list)) {
                $v = (!is_array($v)) ? array($v) : $v;
                $v = serialize($v);
            }
            // Params list json
            if ($this->fields_type_list_json && in_array($p, $this->fields_type_list_json)) {
                $v = (!is_array($v)) ? array($v) : $v;
                $v = json_encode($v);
            }
            // Params list comma
            if ($this->fields_type_list_comma && in_array($p, $this->fields_type_list_comma)) {
                $v = (!is_array($v)) ? array($v) : $v;
                $v = implode(",", $v);
            }

            // Params encode
            if ($this->fields_type_encode && in_array($p, $this->fields_type_encode)) {
                $v = security_encrypt($v, 'encode');
            }
            // Params currency
            if ($this->fields_type_currency && in_array($p, $this->fields_type_currency)) {
                $v = currency_handle_input($v);
            }

            $data[$p] = $v;
        }

        return $data;
    }

    /**
     * Xu ly du lieu xuat ra
     */
    function handle_data_output($data = array())
    {
        if (!$data) return $data;
        foreach ($data as $p => $v) {
            // Params content
            if ($this->fields_type_content && in_array($p, $this->fields_type_content)) {
                $v = handle_content($v, 'output');
            }

            // Params list
            if ($this->fields_type_list && in_array($p, $this->fields_type_list)) {
                $v = @unserialize($v);
                $v = (!is_array($v)) ? array() : $v;
            }
            // Params list json
            if ($this->fields_type_list_json && in_array($p, $this->fields_type_list_json)) {
                $v = json_decode($v);
                $v = (!is_array($v)) ? array() : $v;
            }
            // Params list comma
            if ($this->fields_type_list_comma && in_array($p, $this->fields_type_list_comma)) {
                $v = explode(",", $v);
                $v = (!is_array($v)) ? array() : $v;
            }

            // Params encode
            if ($this->fields_type_encode && in_array($p, $this->fields_type_encode)) {
                $v = security_encrypt($v, 'decode');
            }

            $data[$p] = $v;
        }

        return $data;
    }


    /*
     * ------------------------------------------------------
     *  Translate handle
     * ------------------------------------------------------
     */
    /**
     * Thuc hien dich
     * @param object $row Thong tin row can dich
     * @param int $lang_id Lang ID
     */
    function translate($row, $lang_id)
    {
        // Neu can dich 1 list
        if (is_array($row)) {
            foreach ($row as $i => $r) {
                $row[$i] = $this->translate($r, $lang_id);
            }

            return $row;
        }

        // Lay cac field can dich
        $fields = array();
        foreach ($this->translate_fields as $f) {
            if (isset($row->$f)) {
                $fields[] = $f;
            }
        }

        // Neu khong co field nao can dich
        if (!count($fields)) {
            return $row;
        }

        // Neu khong ton tai ban dich nao
        $this->load->model('translate_model');
        $translate = $this->translate_model->get($this->table, $row->{$this->key}, $fields, $lang_id);
        if (!$translate) {
            return $row;
        }

        // Gan gia tri cua cac ban dich
        foreach ($translate as $f => $l_v) {
            $row->$f = $l_v[$lang_id];
        }

        return $row;
    }

    /**
     * Lay thong tin cua ban goc (Dung trong translate mod)
     * @param int $id ID cua row
     * @param string $field Cac field muon lay (Mac dinh la cac field khai bao trong $translate_fields)
     */
    function translate_get_info($id, $field = '')
    {
        // Loai bo cac field khong duoc khai bao
        $field = explode(',', $field);
        foreach ($field as $i => $v) {
            $v = trim($v);
            if (!in_array($v, $this->translate_fields)) {
                unset($field[$i]);
            } else {
                $field[$i] = $v;
            }
        }

        // Gan field mac dinh
        $field = (!count($field)) ? $this->translate_fields : $field;
        if (!count($field)) {
            return FALSE;
        }

        // Xu ly field
        $field[] = $this->key;
        $field = implode(',', $field);

        // Lay thong tin
        $_translate_auto = $this->translate_auto;
        $this->translate_auto = FALSE; // Tat dich tu dong

        $info = $this->get_info($id, $field);

        $this->translate_auto = $_translate_auto; // Khoi phuc gia tri ban dau cua dich tu dong

        return $info;
    }

    /**
     * Xu ly dich tu dong
     * @param object $row Thong tin row
     */
    protected function _translate_auto($row, $lang_id = 0)
    {
        if ($lang_id > 0) {
            return $this->translate($row, $lang_id);
        }
        if (
            config('language_multi', 'main')
            && $this->translate_auto
            && get_area() == 'site'
            && lang_get_cur()->id != lang_get_default()->id
        ) {

            $row = $this->translate($row, lang_get_cur()->id);
        }

        return $row;
    }


    /*
     * ------------------------------------------------------
     *  Relation
     * ------------------------------------------------------
     */
    /**
     * Tao thong tin relation cho du lieu
     *
     * @param string $table
     * @param array|object $input
     * @param array|Closure $query
     * @return array|object
     */
    public function relation($table, $input, $query = null)
    {
        list($table, $table_sub) = $this->parse_table_relation($table);

        $relation = $this->get_relation($table);

        if (!$relation) {
            return $input;
        }

        $list = (!is_array($input)) ? array($input) : $input;

        if (!count($list)) {
            return $input;
        }

        list($type, $local_key, $parent_key) = $relation;

        $parents = $this->get_parents_relation($table, $table_sub, $list, $local_key, $parent_key, $query);

        foreach ($list as $row) {
            $row_parents = array_where($parents, function ($i, $parent) use ($row, $local_key, $parent_key) {
                return ($parent->{$parent_key} == $row->{$local_key});
            });

            $row->{$table} = ($type == 'one') ? head($row_parents) : array_values($row_parents);
        }

        return (!is_array($input)) ? head($list) : $list;
    }

    /**
     * Tao cac thong tin relation cho du lieu
     *
     * @param array|string $tables
     * @param array|object $input
     * @return array|object
     */
    public function relations($tables, $input)
    {
        foreach ((array)$tables as $table) {
            $input = $this->relation($table, $input);
        }

        return $input;
    }

    /**
     * Phan tich table relation
     *
     * @param string $table
     * @return array
     */
    protected function parse_table_relation($table)
    {
        $arr = explode('.', $table);

        $table = array_shift($arr);

        $table_sub = implode('.', $arr);

        return array($table, $table_sub);
    }

    /**
     * Lay relation config voi 1 table
     *
     * @param string $table
     * @return array|null
     */
    public function get_relation($table)
    {
        $relation = array_get($this->relations, $table);

        if (!$relation) return;

        $default = array(

            'one' => array(
                'local_key' => "{$table}_id",
                'parent_key' => 'id',
            ),

            'many' => array(
                'local_key' => $this->key,
                'parent_key' => "{$this->table}_{$this->key}",
            ),

        );

        $relation = (array)$relation;

        $type = array_get($relation, 0);
        $local_key = array_get($relation, 1, $default[$type]['local_key']);
        $parent_key = array_get($relation, 2, $default[$type]['parent_key']);

        return array($type, $local_key, $parent_key);
    }

    /**
     * Lay danh sach parents relation
     *
     * @param string $table
     * @param string $table_sub
     * @param array $list
     * @param string $local_key
     * @param string $parent_key
     * @param array|Closure $query
     * @return array
     */
    protected function get_parents_relation($table, $table_sub, array $list, $local_key, $parent_key, $query)
    {
        $list_local_key = array_pluck($list, $local_key);
        $list_local_key = array_unique($list_local_key);

        if ($query instanceof Closure) {
            $res = call_user_func_array($query, array($list_local_key));

            if (!is_null($res)) return $res;
        }

        $input = array();

        if ($table_sub) {
            $input['relation'] = explode('|', $table_sub);
        }

        if (is_array($query)) {
            $input = array_merge($input, $query);
        }

        t('db')->where_in("{$table}.{$parent_key}", $list_local_key);

        return model($table)->get_list($input);
    }


    /*
     * ------------------------------------------------------
     *  Other handle
     * ------------------------------------------------------
     */
    /**
     * Ham duoc goi khi du lieu thay doi
     */
    function _event_change($act, $params)
    {
        switch ($act) {
            // Update
            case 'update': {
                $where = $params[0];
                $data = $params[1];

                if (isset($where[$this->key])) {
                    $id = $where[$this->key];

                    // Cap nhat index
                    if (count($this->index_fields)) {
                        $this->{$this->table . '_index_model'}->auto_update($id, $data);
                    }
                }

                break;
            }

            // Del
            case 'del': {
                $where = $params[0];

                if (isset($where[$this->key])) {
                    $id = $where[$this->key];

                    // Xoa index
                    if (count($this->index_fields)) {
                        $this->{$this->table . '_index_model'}->del($id);
                    }

                    // Xoa tai table_info
                    foreach ($this->table_info as $n) {
                        $this->{$this->table . '_info_model'}->del($n, $id);
                    }
                }

                break;
            }
        }
    }

    function select(array $data = array(), $lang_id = 0)
    {
        $this->allf($data);
        $list = $this->db->get($this->table)->result();
        $list = $this->_translate_auto($list, $lang_id);

        if (!empty($input['relation'])) {
            $list = $this->relations($input['relation'], $list);
        }
        return $list;
    }

    // đếm tổng số dòng
    function total($data = array())
    {
        $this->_where($data);
        $this->_join($data);
        $this->_groupby($data);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    // all function
    //lấy 1 dòng dữ liệu với điều kiện
    function read($ids = null)
    {
        if (!$ids)
            return false;
        if (!is_array($ids)) {
            $data = array();
            $data['where'][$this->key] = $ids;
        } else
            $data = $ids;

        $this->_select($data);
        $this->_where($data);
        $this->_orderby($data);

        $data['limit'] = array(1);
        $this->_limit($data);

        $q = $this->db->get($this->table);
        if ($q) return $this->_translate_auto($q->row());
        return false;
    }

    function allf($data)
    {
        $this->_select($data);
        $this->__join($data);
        $this->_where($data);
        $this->_orderby($data);
        $this->_groupby($data);
        $this->_limit($data);
    }

    function field_exists($field = '', $table = '')
    {
        if (!$field) {
            return false;
        }

        if (!$table) {
            $table = $this->table;
        }

        if ($this->db->field_exists($field, $table)) {
            return true;
        }

        return false;
    }

    // xư lý chung
    function _select($data)
    {
        if (isset($data['select']) && $data['select']) {
            $this->db->select($data['select']);
        }
    }

    function _where($data)
    {
        if (isset($data['where']) && $data['where']) {
            foreach ($data['where'] as $field => $value) {
                if ($field[0] == '?') {
                    $this->db->where_in(substr($field, 1), $value);
                } else if ($field[0] == '!') {
                    $this->db->where_not_in(substr($field, 1), $value);
                } else if ($field[0] == '^') {
                    $this->db->like(substr($field, 1), $value);
                } else if ($field[0] == '|') {
                    $where = "FIND_IN_SET('" . $value . "', " . substr($field, 1) . ")";
                    $this->db->where($where);
                } else {
                    $this->db->where($field, $value);
                }
            }
        }
    }

    function _orderby($data)
    {
        if (isset($data['order']) && $data['order']) {
            foreach ($data['order'] as $key => $val) {
                $this->db->order_by($key, $val);
            }
        }
    }

    function _groupby($data)
    {
        if (isset($data['group']) && $data['group']) {
            if (is_string($data['group']))
                $this->db->group_by($data['group']);
            else if (is_array($data['group'])) {
                foreach ($data['group'] as $key) {
                    $this->db->group_by($key);
                }
            }
        }
    }

    function __join($data)
    {
        if (isset($data['join']) && ($data['join'])) {
            if (is_array($data['join'][0])) {
                foreach ($data['join'] as $join) {
                    if (count($join) >= 3)
                        $this->db->join($join[0], $join[1], $join[2]);
                    else
                        $this->db->join($join[0], $join[1]);
                }
            } else {
                if (count($data['join']) >= 3)
                    $this->db->join($data['join'][0], $data['join'][1], $data['join'][2]);
                else
                    $this->db->join($data['join'][0], $data['join'][1]);
            }
        }
    }

    function _limit($data)
    {
        if (isset($data['limit']) && count($data['limit']) == 1) {
            $this->db->limit($data['limit'][0]);
        } else if (isset($data['limit']) && count($data['limit']) == 2) {
            $this->db->limit($data['limit'][0], $data['limit'][1]);
        }
    }
}