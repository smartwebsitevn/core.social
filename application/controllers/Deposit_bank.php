<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_bank extends MY_Controller
{
    /**
     * Ham khoi dong
     */
    public function __construct()
    {
        parent::__construct();

        if (!user_is_login()) {
            redirect_login_return();
        }

        $this->lang->load('site/deposit_bank');


    }

    /**
     * Remap method
     */
    public function _remap($method, $params = array())
    {
        return $this->_remap_action($method, $params, array('view'));
    }

    /**
     * Gan dieu kien cho cac bien
     */
    protected function _set_rules($params)
    {
        $rules = array();
        $rules['type'] = array('transfer_type', 'required|trim|callback__check_type');
        $rules['bank'] = array('transfer_bank', 'required|trim|xss_clean');
        $rules['acc_name'] = array('transfer_acc_name', 'required|trim|xss_clean');
        $rules['acc'] = array('transfer_acc', 'trim|xss_clean');
        $rules['amount'] = array('transfer_amount', 'required|trim|xss_clean');
        $rules['date'] = array('transfer_date', 'trim|xss_clean');
        $rules['desc'] = array('desc', 'required|trim|xss_clean');

        $this->form_validation->set_rules_params($params, $rules);
    }

    /**
     * Kiem tra type
     */
    public function _check_type($value)
    {
        //if ( ! in_array($value, $this->_get_types()))
        if (!array_key_exists($value, $this->_get_types())) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra bank
     */
    public function _check_bank($value)
    {
        $banks = array_pluck($this->_get_banks(), 'name');

        if (!in_array($value, $banks)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Lay transfer types
     *
     * @return array
     */
    protected function _get_types()
    {
        return $this->_mod()->setting('types');
    }

    /**
     * Lay danh sach banks
     *
     * @return array
     */
    protected function _get_banks()
    {
        return mod('bank')->get_list(array('show' => true));
    }

    /**
     * Lay danh sach bien
     *
     * @return array
     */
    protected function _get_params()
    {
        return array('type', 'bank', 'acc_name', 'acc', 'amount', 'date', 'desc');
    }

    /**
     * Lay input
     */
    protected function _get_input($key = null, $default = null)
    {
        $input = array_only(t('input')->post(), $this->_get_params());

        $input['amount'] = currency_handle_input($input['amount']);

        return array_get($input, $key, $default);
    }

    // --------------------------------------------------------------------

    /**
     * Them moi
     */
    public function add()
    {
        if (!$this->_mod()->setting('status')) {
            set_message(lang('notice_page_not_found'));
            redirect();
        }


        $form = array();

        $form['validation']['params'] = $this->_get_params();

        $form['submit'] = function () {
            return $this->_add_submit();
        };

        $form['form'] = function () {
            $this->_add_view();
        };

        $this->_form($form);
    }

    /**
     * Add submit
     *
     * @return string
     */
    protected function _add_submit()
    {
        $user = user_get_account_info();

        $input = $this->_get_input();
        $data['data'] = $input;
        $data['user'] = $user;
        $output = array();
        $this->_mod()->create($data, $output);
        set_message(lang('notice_send_success'));
        return $output['invoice_order']->url('view');
        //return $this->_url('view/'.$id);
    }

    /**
     * Add make view
     */
    protected function _add_view()
    {
        $this->data['types'] = $this->_get_types();
        $this->data['banks'] = $this->_get_banks();

        page_info('title', lang('title_deposit_bank_add'));

        $this->_display();
    }

    // --------------------------------------------------------------------

    /**
     * List
     */
    public function index()
    {
        redirect($this->_url('add'));
        // View data
        $this->data['statuss'] = mod('order')->statuss();

        // Tao list
        $list = array();
        $list['filter'] = TRUE;
        $list['filter_value']['user'] = user_get_account_info()->id;
        $list['filter_fields'] = array('status', 'created', 'created_to');
        $list['actions'] = array('view');
        $list['display'] = false;
        $this->_list($list);

        foreach ($this->data['list'] as $row) {
            $row->_can_view = true;
            $row->_url_view = $this->_url('view/' . $row->id);
        }

        page_info('title', lang('title_deposit_bank'));

        $this->_display();
    }

    // --------------------------------------------------------------------

    /**
     * View
     */
    protected function _view($info)
    {
        if ($info->user_id != user_get_account_info()->id) {
            $this->_redirect();
        }

        $info = $this->_mod()->add_info($info);

        $this->data['info'] = $info;

        page_info('title', lang('title_deposit_bank_view'));

        $this->_display();
    }

}
