<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Aboutus extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Tai cac file thanh phan
        $this->lang->load('site/'.$this->_get_mod());
    }
    function index()
    {
        redirect(site_url('aboutus_list'));
    }

    function view()
    {
        $this->_common_process();
        //$this->_view_process();
        $this->_display();
    }

    function _common_process()
    {

        //== Lay thong tin
        $id = $this->uri->rsegment(3);
        if (!is_numeric($id) && is_slug($id)) {
            // neu la seo url
            $info = $this->_model()->filter_get_info(array('seo_url' => $id,'show'=>1));
        } else {
            $info = $this->_model()->filter_get_info(array('id' => $id,'show'=>1));
        }
        if (!$info) {
            set_message(lang('notice_page_not_found'));
            redirect();
        }

        //== Cap nhat luot view
        $data = array();
        $data['count_view'] = $info->count_view + 1;
        $this->_model()->update($info->id, $data);

        //== Su ly danh muc
        $category = model('aboutus_cat')->get_info($info->cat_id);


        //== Them thong tin
        $info = $this->_mod()->add_info($info);
        $info = $this->_mod()->add_info_images($info);
        $info = $this->_mod()->add_info_files($info);
        // pr($info);

        $this->data['info'] = $info;
        $this->data['category'] = $category;


        //== Breadcrumbs
        $this->_breadcrumbs($category);
        //== Seos
        $title = character_limiter($info->name, 60);
        if ($category->seo_title)
            $title = $category->seo_title;
        page_info('title', $title);
        if ($category->seo_description)
            page_info('description', character_limiter($category->seo_description, 160));
        if ($category->seo_keywords)
            page_info('keywords', $category->seo_keywords);


    }
    function _view_process($info)
    {
        // Lay ngay update view + ngay hien tai
        $date_update = explode('-', get_date($info->view_date_day));
        $date_now = explode('-', date("d-m-y", mktime()));

        // Neu ngay hien tai || thang || nam != Ngay update thi reset = 0
        $day = $info->view_in_day;
        if ($date_update[0] != $date_now[0] || $date_update[1] != $date_now[1] || $date_update[2] != $date_now[2] + 2000) {
            $day = 0;
        }

        // Neu tuan hien tai || thang || nam != ngay da update thi reset = 0
        $week = $info->view_in_week;
        $week_now = get_week(now());
        $week_db = get_week($info->view_date_day);
        if ($week_now != $week_db || $date_update[1] != $date_now[1] || $date_update[2] != $date_now[2] + 2000) {
            $week = 0;
        }

        // Neu thang hien tai khong phai la thang trong he thong da cap nhat thi reset = 0
        $months = $info->view_in_months;
        if ($date_update[1] != $date_now[1] || $date_update[2] != $date_now[2] + 2000) {
            $months = 0;
        }

        $number_view_random = 1;//rand(5, 50);

        // Cap nhat luot view + thoi diem xem
        $data = array();
        $data['view_total'] = $info->view_total + $number_view_random;
        $data['view_in_day'] = $day + $number_view_random;
        $data['view_in_week'] = $week + $number_view_random;
        $data['view_in_months'] = $months + $number_view_random;
        $data['view_date_day'] = now();
        $this->_model()->update($info->id, $data);

    }


    /**
     * Build breadcrumbs
     *
     * @param  [type] $category [description]
     * @return [type]           [description]
     *
     */
    protected function _breadcrumbs($category)
    {
        $breadcrumbs = array();

        $parent = $category;

        while ($parent->parent_id) {
            $parent = model('aboutus_cat')->get_info($parent->parent_id);

            $breadcrumbs[] = array(
                $parent->seo_url,
                word_limiter($parent->name, 10),
                $parent->name
            );

        }

        $breadcrumbs = array_reverse($breadcrumbs);
        $breadcrumbs[] = array(
            $category->seo_url,
            word_limiter($category->name, 10),
            $category->name
        );

        page_info('breadcrumbs', $breadcrumbs);
    }


}