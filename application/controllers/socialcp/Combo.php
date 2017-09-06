<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Combo extends MY_Controller
{

    /**
     * Ham khoi dong
     */
    function __construct()
    {
        parent::__construct();

        // Tai cac file thanh phan
        $this->lang->load('admin/combo');

        $this->data['products'] = model('product')->get_list();
        $this->data['lessons'] = model('lesson')->get_list();
    }

    /**
     * Remap method
     */
    function _remap($method)
    {
        if (in_array($method, array('edit', 'del', 'feature', 'feature_del'))) {
            $this->_action($method);
        } elseif (method_exists($this, $method)) {
            $this->{$method}();
        } else {
            show_404('', FALSE);
        }
    }


    /*
     * ------------------------------------------------------
     *  Rule handle
     * ------------------------------------------------------
     */
    /**
     * Gan dieu kien cho cac bien
     */
    function _set_rules($params = array())
    {
        $rules = array();
        $rules['name'] = array('name', 'required|trim|xss_clean');
        $rules['description'] = array('description', 'trim|xss_clean');
        $rules['services'] = array('services', 'callback__check_services');
        $rules['price'] = array('price', 'required|trim|xss_clean');

        $rules['expire_from'] = array('expire_from', 'required|trim|xss_clean|callback__check_expire_from');
        $rules['expire_to'] = array('expire_to', 'required|trim|xss_clean|callback__check_expire_to');

        $rules['product_id'] = array('apply_for_product', 'trim|required|xss_clean|callback__check_product');
        $rules['lesson_id'] = array('apply_for_lesson', 'trim|required|xss_clean|callback__check_lesson');

        //$rules['image'] = array('image', 'callback__check_image');
        $this->form_validation->set_rules_params($params, $rules);
    }

    /**
     * Kiem tra mua tron
     */
    function _check_services()
    {
        $products = $this->input->post('product_id');
        $lessons = $this->input->post('lesson_id');

        if (!$products && !$lessons) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_services_value_require'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra tinh hop le
     */
    function _check_product($value)
    {
        if (!model('product')->check_id($value)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra tinh hop le
     */
    function _check_lesson($value)
    {
        if (!model('lesson')->check_id($value)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra expire_from
     */
    function _check_expire_from($value)
    {
        // Kiem tra su ton tai
        if (!get_time_from_date($value)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }
        return true;
    }

    /**
     * Kiem tra expire_to
     */
    function _check_expire_to($value)
    {
        $expire_to = get_time_from_date($value);
        // Kiem tra su ton tai
        if ($expire_to <= now()) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }
        $expire_from = get_time_from_date($this->input->post('expire_from'));
        if ($expire_from > $expire_to) {
            $this->form_validation->set_message(__FUNCTION__, 'Ngày hết hạn không thể nhỏ hơn ngày bắt đầu');
            return FALSE;
        }
        return true;
    }


    /**
     * Kiem tra the loai
     */
    function _check_service($value)
    {
        $service = $this->_get_service_values();
        if (!count($service)) {
            $this->form_validation->set_message(__FUNCTION__, lang('required'));
            return FALSE;
        }

        return TRUE;
    }


    /**
     * Lay gia tri cua thuoc tinh
     */
    function _get_service_values()
    {
        $this->load->model('service_model');

        $service = $this->input->post('service');
        $service = (!is_array($service)) ? array($service) : $service;

        $list = array();
        foreach ($service as $id) {
            $id = (!is_numeric($id)) ? 0 : $id;
            $info = $this->service_model->get_info($id, 'id');
            if (!$info) continue;

            $list[] = $id;
        }

        return $list;
    }

    /**
     * Kiem tra image
     */
    public function _check_image()
    {
        if (!$this->_get_image()) {
            $this->form_validation->set_message(__FUNCTION__, lang('required'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Lay image
     */
    protected function _get_image($id = NULL, $field = 'image')
    {
        if (is_null($id)) {
            $id = $this->_get_id_cur();
        }

        $image = $this->model->file->get_info_of_mod($this->_get_mod(), $id, $field, 'id, file_name');

        return $image;
    }


    /**
     * Lay file_id
     */
    function _get_image_id($id)
    {
        $this->load->model('file_model');

        $where = array();
        $where['table'] = $this->_get_mod();
        $where['table_id'] = $id;
        $file_id = $this->file_model->get_id($where);

        return $file_id;
    }


    /* ------------------------------------------------------
    *  Prepare data handle
    * ------------------------------------------------------
    */

    function _fields()
    {
        $fields = array('name', 'price',
            /*'services',*/
            'desc', 'description', 'expire_from', 'expire_to', 'meta_title', 'meta_desc', 'meta_key', 'status', 'feature', 'sort_order',);

        return $fields;
    }

    protected function _get_params()
    {

        $params = $this->_fields();
        array_push($params, 'services');
        array_push($params, 'image');
        return $params;
    }

    /**
     * Lay id xu ly hien tai
     *
     * @return int
     */
    protected function _get_id_cur()
    {
        return ($this->uri->rsegment(2) == 'add')
            ? fake_id_get($this->_get_mod())
            : $this->uri->rsegment(3);
    }


    /**
     * Lay input
     */
    protected function _get_inputs()
    {
        $data = array();
        $fields = $this->_fields();
        foreach ($fields as $f) {
            $v = $this->input->post($f);
            $data[$f] = $v;
        }
        $product_ids = $this->input->post("product_id");
        $product_watch_expired_type = $this->input->post("product_watch_expired_type");
        $product_watch_expired_value = $this->input->post("product_watch_expired_value");

        $lesson_ids = $this->input->post("lesson_id");
        $lesson_watch_expired_type = $this->input->post("lesson_watch_expired_type");
        $lesson_watch_expired_value = $this->input->post("lesson_watch_expired_value");

        $data['services'] = json_encode([
                "products" => $product_ids,
                "product_watch_expired_type" => $product_watch_expired_type,
                "product_watch_expired_value" => $product_watch_expired_value,
                "lessons" => $lesson_ids,
                "lesson_watch_expired_type" => $lesson_watch_expired_type,
                "lesson_watch_expired_value" => $lesson_watch_expired_value,
            ]


        );
        $data['price'] = currency_handle_input($data['price']);

        $data['expire_from'] = get_time_from_date($data['expire_from']);
        $data['expire_to'] = get_time_from_date($data['expire_to']);
        $data['sort_order'] = intval($data['sort_order']);
        $data['description'] = handle_content($data['description'], 'input');

        // Lay thong tin image
        foreach (array('image'/*, 'banner'*/) as $i) {
            $image = $this->_get_image(null, $i);
            if ($image) {
                $data[$i . '_id'] = $image->id;
                $data[$i . '_name'] = $image->file_name;
            }
        }
        //$data['setting'] = json_encode($tmp);
        return $data;
    }

    /**
     * Tao data gui den view
     *
     * @param int $id
     */
    protected function _create_view_data($id)
    {
        // Khai bao cac bien cua widget upload
        $widget_upload = array();
        $widget_upload['mod'] = 'single';
        $widget_upload['file_type'] = 'image';
        $widget_upload['status'] = config('file_public', 'main');
        $widget_upload['table'] = 'combo';
        $widget_upload['table_id'] = $id;
        $widget_upload['table_field'] = 'image';
        $widget_upload['resize'] = TRUE;
        $widget_upload['thumb'] = TRUE;
        $this->data['widget_upload'] = $widget_upload;

        // Khai bao cac bien cua widget upload images
        $widget_upload_files = array();
        $widget_upload_files['mod'] = 'multi';
        $widget_upload_files['file_type'] = 'image';
        $widget_upload_files['status'] = config('file_public', 'main');
        $widget_upload_files['table'] = 'combo';
        $widget_upload_files['table_id'] = $id;
        $widget_upload_files['table_field'] = 'files';
        $widget_upload_files['resize'] = FALSE;
        $widget_upload_files['thumb'] = FALSE;
        $this->data['widget_upload_files'] = $widget_upload_files;

        $this->data['currency'] = currency_get_default();

        // Luu bien gui den view
        $this->data['action'] = current_url(true);

    }
    /*
     * ------------------------------------------------------
     *  Actions
     * ------------------------------------------------------
     */
    /**
     * Them moi
     */
    public function add()
    {
        $fake_id = $this->_get_id_cur();
        $form = array();
        $form['validation']['params'] = $this->_get_params();
        $form['submit'] = function () use ($fake_id) {
            // Lay input
            $data = $this->_get_inputs();
            $data['created'] = now();
            //$data['updated'] = $data['created'];
            $id = 0;
            $this->_model()->create($data, $id);
            // Cap nhat lai table_id table file
            model('file')->update_table_id_of_mod($this->_get_mod(), $fake_id, $id);
            fake_id_del($this->_get_mod());
            set_message(lang('notice_add_success'));
            return $this->_url();
        };

        $form['form'] = function () use ($fake_id) {
            $this->_create_view_data($fake_id);
            $this->_display('form');
        };

        $this->_form($form);
    }

    /**
     * Chinh sua
     */
    protected function _edit($info)
    {

        // pr($info);
        // Cap nhat thong tin
        if ($this->input->get('act') == 'update_image') {
            $this->_update_image($info->id);
            return;
        }

        // Form
        $form = array();
        $form['validation']['params'] = $this->_get_params();
        $form['submit'] = function () use ($info) {
            $data = $this->_get_inputs();
            //$data['updated'] = now();
            $this->_model()->update($info->id, $data);
            set_message(lang('notice_update_success'));
            return $this->_url();
        };

        $form['form'] = function () use ($info) {
            $info = $this->_mod()->add_info($info, 1);
            //=== Su ly info , ta phai lay lai tu bang info phong truong hop co chinh sua trong bang nay
            /*foreach ($this->_model()->table_info_adv as $p)
            {
                $info_items= $this->_model()->info_get($p, $info->id);
                $tmp=[];
                if($info_items){
                    foreach($info_items as $i){
                        $tmp[] = $i->name;
                    }
                }
                $info->$p = implode(',', $tmp);
            }*/
            $this->data['info'] = $info;
            //pr($info);
            $this->_create_view_data($info->id);
            $this->_display('form');
        };

        $this->_form($form);
    }

    /**
     * Xoa du lieu
     */
    function _del($info)
    {
        // Thuc hien xoa
        $this->_model()->del($info->id);

        // Xoa file
        $this->load->helper('file');
        file_del_table($this->_get_mod(), $info->id);

        // Gui thong bao
        set_message(lang('notice_del_success'));
    }

    /**
     * Thuc hien tuy chinh
     */
    function _action($action)
    {
        // Lay input
        $ids = $this->uri->rsegment(3);
        $ids = (!$ids) ? $this->input->post('id') : $ids;
        $ids = (!is_array($ids)) ? array($ids) : $ids;

        // Thuc hien action
        foreach ($ids as $id) {
            // Xu ly id
            $id = (!is_numeric($id)) ? 0 : $id;

            // Kiem tra id
            $info = $this->_model()->get_info($id);
            if (!$info) continue;

            // Kiem tra co the thuc hien hanh dong nay khong
            if (!$this->_mod()->can_do($info, $action)) continue;

            // Chuyen den ham duoc yeu cau
            if (in_array($action, array('feature', 'feature_del'))) {
                $this->_action_option($info, $action);
            } else {
                $this->{'_' . $action}($info);
            }
        }
    }

    /**
     * Xu ly hanh dong voi cac thuoc tinh
     */
    function _action_option($info, $action)
    {
        // Xu ly voi cac option
        $data = array();
        switch ($action) {
            case 'feature': {
                $data[$action] = now();
                break;
            }
            case 'feature_del': {
                $p = preg_replace('#_del$#i', '', $action);
                $data[$p] = 0;
                break;
            }
        }

        // Cap nhat data
        if (count($data)) {
            $this->_model()->update($info->id, $data);

            $output = json_encode(array('complete' => TRUE));
            set_output('json', $output);
        }
    }


    /*
     * ------------------------------------------------------
     *  List handle
     * ------------------------------------------------------
     */
    /**
     * Danh sach
     */
    function index()
    {
        // Tai cac file thanh phan
        $this->load->helper('site');
        $this->load->helper('form');
// Cap nhat sort_order
        if ($this->input->get('act') == 'update_order') {
            $items = $this->input->post('items');
            $items = explode(',', $items);

            foreach ($items as $i => $id) {
                $data = array();
                $data['sort_order'] = $i;
                $this->_model()->update($id, $data);
            }

            $output = json_encode(array('complete' => TRUE));
            set_output('json', $output);
        }

        // Lay config
        $options = array('feature');

        // Tao filter
        $filter_input = array();
        $filter_fields = array('id', 'name', 'created', 'created_to', 'expire_to', 'expire_to_to', 'status', 'payment_type');
        $filter = $this->_mod()->filter_create($filter_fields, $filter_input);
        $this->data['filter'] = $filter_input;

        // Lay tong so
        $total = $this->_model()->filter_get_total($filter);
        $page_size = config('list_limit', 'main');
        $limit = $this->input->get('per_page');
        $limit = max(0, min($limit, get_limit_page_last($total, $page_size)));

        // Lay danh sach
        $input = array();
        //$input['order'] = ($filter_input['option']) ? array('combo.'.$filter_input['option'], 'desc') : array('combo.id', 'desc');
        $input['order'] = array('combo.sort_order', 'asc');
        $input['limit'] = array($limit, $page_size);
        $list = $this->_model()->filter_get_list($filter, $input);

        $actions = array('edit', 'del', 'translate');
        $list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
        foreach ($list as $row) {
            $row = $this->_mod()->add_info($row);
            $row = $this->_mod()->url($row);

            $row->_url_translate = admin_url("translate/table/combo/{$row->id}");

            foreach ($actions as $action) {
                $row->{'_can_' . $action} = ($this->_mod()->can_do($row, $action) && admin_permission_url($row->{'_url_' . $action})) ? TRUE : FALSE;
            }

            foreach (array('edit') as $action) {
                $row->{'_url_' . $action} = url_add_return($row->{'_url_' . $action});
            }
        }
        $this->data['list'] = $list;

        // Tao chia trang
        $pages_config = array();
        $pages_config['page_query_string'] = TRUE;
        $pages_config['base_url'] = current_url() . '?' . url_build_query($filter_input);
        $pages_config['total_rows'] = $total;
        $pages_config['per_page'] = $page_size;
        $pages_config['cur_page'] = $limit;
        $this->data['pages_config'] = $pages_config;


        // Tao action list
        $actions = array();
        foreach (array('del') as $v) {
            $url = admin_url(strtolower(__CLASS__) . '/' . $v);
            if (!admin_permission_url($url)) continue;

            $actions[$v] = $url;
        }
        $this->data['actions'] = $actions;

        // Luu bien gui den view
        $this->data['action'] = current_url();

        $payment_types = config('payment_types', 'service');
        $this->data['payment_types'] = $payment_types;
        $this->data['sort_url_update'] = current_url() . '?act=update_order';

        // Hien thi view
        $this->_display();
    }
    /*
 * ------------------------------------------------------
 *  Menu Callback handle
 * ------------------------------------------------------
 */
    /**
     * Danh sach
     */
    function menu_callback()
    {
        // Tai cac file thanh phan
        $this->load->helper('site');
        $this->load->helper('form');

        // Lay config
        $options = array('feature');

        // Tao filter
        $filter_input = array();
        $filter_fields = array('id', 'name', 'created', 'created_to', 'option', 'pgroup');
        $filter = $this->_model()->filter_create($filter_fields, $filter_input);
        $this->data['filter'] = $filter_input;

        // Lay tong so
        $total = $this->_model()->filter_get_total($filter);
        $page_size = config('list_limit', 'main');
        $limit = $this->input->get('per_page');
        $limit = max(0, min($limit, get_limit_page_last($total, $page_size)));

        // Lay danh sach
        $input = array();
        $input['order'] = ($filter_input['option']) ? array('combo.' . $filter_input['option'], 'desc') : array('combo.id', 'desc');
        $input['limit'] = array($limit, $page_size);
        $list = $this->_model()->filter_get_list($filter, $input);

        $actions = array('edit', 'del', 'feature', 'feature_del', 'translate');
        $list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
        foreach ($list as $row) {
            $row = $this->_mod()->add_info($row);
            $row = $this->_mod()->url($row);
            $row->_url_translate = admin_url("translate/table/combo/{$row->id}");

            foreach ($actions as $action) {
                $row->{'_can_' . $action} = ($this->_mod()->can_do($row, $action) && admin_permission_url($row->{'_url_' . $action})) ? TRUE : FALSE;
            }

            foreach (array('edit') as $action) {
                $row->{'_url_' . $action} = url_add_return($row->{'_url_' . $action});
            }
        }
        $this->data['list'] = $list;

        // Tao chia trang
        $pages_config = array();
        $pages_config['page_query_string'] = TRUE;
        $pages_config['base_url'] = current_url() . '?' . url_build_query($filter_input);
        $pages_config['total_rows'] = $total;
        $pages_config['per_page'] = $page_size;
        $pages_config['cur_page'] = $limit;
        $this->data['pages_config'] = $pages_config;


        // Tao action list
        $actions = array();
        foreach (array('del') as $v) {
            $url = admin_url(strtolower(__CLASS__) . '/' . $v);
            if (!admin_permission_url($url)) continue;

            $actions[$v] = $url;
        }
        $this->data['actions'] = $actions;

        // Luu bien gui den view
        $this->data['action'] = current_url();

        // Hien thi view
        $this->_display('menu_callback', null);
    }
}

