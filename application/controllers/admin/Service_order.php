<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use App\User\UserFactory;
use App\Customer\CustomerFactory;
use App\ServiceOrder\Model\ServiceOrderModel as ServiceOrderModel;
use App\ServiceOrder\Library\ServiceStatus;

class Service_order extends MY_Controller
{
    /**
     * Ham khoi dong
     */
    public function __construct()
    {
        parent::__construct();
        // Tai cac file thanh phan
        $this->lang->load('admin/service_order');
        $this->lang->load('modules/service_order/common');
    }


    /**
     * Remap method
     */
    function _remap($method)
    {
        if (in_array($method, array('view', 'edit', 'suspend', 'upgrade_config', 'upgrade_service', 'renew', 'del'))) {
            $this->_action($method);
        } elseif (method_exists($this, $method)) {
            $this->{$method}();
        } else {
            show_404('', FALSE);
        }
    }

    /**
     * Gan dieu kien cho cac bien
     */
    function _set_rules($params = array())
    {
        $rules = array();
        $rules['title'] = array('title', 'required|trim|xss_clean');
        $rules['status'] = array('status', 'required|trim|xss_clean|callback__check_status');
        $rules['expire_from'] = array('expire_from', 'required|trim|xss_clean|callback__check_expire_from');
        $rules['expire_to'] = array('expire_to', 'required|trim|xss_clean|callback__check_expire_to');

        //$rules['image'] = array('image', 'callback__check_image');
        $this->form_validation->set_rules_params($params, $rules);
    }


    /**
     * Kiem tra expire_from
     */
    function _check_expire_from($value)
    {
        // Kiem tra su ton tai
        if (!get_time_from_date($value)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return FALSE;
        }
        return true;
    }

    /**
     * Kiem tra expire_to
     */
    function _check_expire_to($value)
    {
        // Kiem tra su ton tai
        if (!get_time_from_date($value)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return FALSE;
        }
        $expire_from = get_time_from_date($this->input->post('expire_from'));
        $expire_to = get_time_from_date($value);
        if ($expire_from > $expire_to) {
            $this->form_validation->set_message(__FUNCTION__, 'Ngày hết hạn không thể nhỏ hơn ngày bắt đầu');
            return FALSE;
        }
        return true;
    }


    /**
     * Kiem tra status
     */
    function _check_status($value)
    {
        // Kiem tra su ton tai
        $service_status = config('service_statuss', 'main');
        if (!in_array($value, $service_status)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return FALSE;
        }
        return true;
    }

    /**
     * List
     */
    public function index()
    {
        $filter = [];

        $user_key = t('input')->get('user_key');

        if ($user = UserFactory::user()->find($user_key)) {
            $filter['user_id'] = $user->id;
        }


        $this->_list($list_args = [
            'filter' => true,
            'filter_fields' => [
                'id', 'invoice', 'invoice_order', 'user', 'user_key', 'key', 'amount',
                'order_status', 'status', 'expire', 'created', 'created_to',
            ],
            'filter_value' => $filter,
            'input' => ['relation' => ['invoice.tran', 'user', 'customer']],
            'order' => true,
            'order_fields' => ['id', 'status', 'amount', 'created'],
            'actions' => ['view', 'edit', 'suspend'],
            'actions_list' => [],
            'display' => false,
        ]);

        foreach ($this->data['list'] as &$row) {
            $invoice = (array)$row->invoice;

            $invoice['trans'] = array_pull($invoice, 'tran');

            $row->invoice = (object)$invoice;
        }

        $this->data['list'] = ServiceOrderModel::makeCollection($this->data['list']);

        $product_types = config('product_types', 'pservice');
        $this->data['product_types'] = $product_types;

        //$this->data['pservices'] = model('pservice')->get_list();
        $this->data['list_service_status'] = config('service_statuss', 'main');

        $this->_display();
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

            $info = $this->_mod()->add_info($info);

            // Chuyen den ham duoc yeu cau
            $this->{'_' . $action}($info);
        }
    }


    /**
     * Xem chi tiet
     */
    function _view($info)
    {
        t('lang')->load('modules/invoice/invoice');

        $this->data['service_order'] = $info;

        $invoice_order = model('invoice_order')->get_info($info->invoice_order_id);
        $this->data['invoice_order'] = $invoice_order;

        $this->_display();
    }


    /**
     * Chinh sua
     */
    function _edit($info)
    {
        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->data['service_status'] = config('service_statuss', 'main');

        // Tu dong kiem tra gia tri cua 1 bien
        $param = $this->input->post('_autocheck');
        if ($param) {
            $this->_autocheck($param);
        }


        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array('title', /*'expire_from',*/ 'expire_to', 'status');

            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
                $admin = admin_get_account_info();
                $expire_to = $this->input->post('expire_to');
                $expire_from = $this->input->post('expire_from');


                // Them du lieu vao data
                $status = $this->input->post('status');
                /* if($status != $info->status)
                {*/
                $data = array();
                $data['title'] = $this->input->post('title');
                $data['device_id'] = $this->input->post('device_id');
                $data['status'] = $this->input->post('status');
                $data['expire_to'] = get_time_from_date($expire_to);
                $data['expire_from'] = get_time_from_date($expire_from);
                $data['admin_update'] = $admin->username;
                $data['last_update_status'] = now();
                $this->_model()->update($info->id, $data);
                //}

                // Khai bao du lieu tra ve
                $result['complete'] = TRUE;
               // $result['location'] = admin_url('service_order/view/' . $info->id);
                $result['location'] = admin_url('service_order' );
                set_message(lang('notice_update_success'));
            }

            // Neu du lieu khong phu hop
            if (empty($result['complete'])) {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }
            }

            // Form output
            $this->_form_submit_output($result);
        }

        $this->data['info'] = $info;

        // Luu bien gui den view
        $this->data['action'] = current_url(true);

        // Hien thi view
        view('tpl::service_order/edit', $this->data);
    }


    /**
     * Gia han
     */
    function _renew($info)
    {
        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');

        // Tu dong kiem tra gia tri cua 1 bien
        $param = $this->input->post('_autocheck');
        if ($param) {
            $this->_autocheck($param);
        }


        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array('expire_to', 'expire_from');

            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
                // Them du lieu vao data
                $expire_to = $this->input->post('expire_to');
                $expire_from = $this->input->post('expire_from');

                $admin = admin_get_account_info();
                $data = array();
                $data['admin_update'] = $admin->username;
                $data['expire_to'] = get_time_from_date($expire_to);
                $data['expire_from'] = get_time_from_date($expire_from);
                $data['last_update_status'] = now();
                $this->_model()->update($info->id, $data);

                // Khai bao du lieu tra ve
                $result['complete'] = TRUE;
                $result['location'] = admin_url('service_order/view/' . $info->id);
                set_message(lang('notice_update_success'));
            }

            // Neu du lieu khong phu hop
            if (empty($result['complete'])) {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }
            }

            // Form output
            $this->_form_submit_output($result);
        }

        $this->data['info'] = $info;

        // Luu bien gui den view
        $this->data['action'] = current_url(true);

        // Hien thi view
        view('tpl::service_order/renew', $this->data);
    }


    /**
     * suspend
     */
    function _suspend($info)
    {
        // Thuc hien xoa
        $data = array();
        $data['status'] = 'suspended';
        $data['last_update_status'] = now();

        $admin = admin_get_account_info();
        $data['admin_update'] = $admin->username;

        $this->_model()->update($info->id, $data);

        // Gui thong bao
        set_message(lang('notice_update_success'));
    }

    /**
     * Xoa du lieu
     */
    function _del($info)
    {
        // Thuc hien xoa
        $this->_model()->del($info->id);
        // Gui thong bao
        $this->session->set_flashdata('flash_message', array('success', $this->lang->line('notice_del_success')));
        return TRUE;
    }

}
