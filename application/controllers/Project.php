<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Project extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Tai cac file thanh phan
        $this->lang->load('site/'.$this->_get_mod());
    }
    function index()
    {
        redirect(site_url('project_list'));
    }

    function view()
    {
        $this->_common_process();
        //$this->_view_process();
        $this->_display('view',null);
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
        $category = model('project_cat')->get_info($info->cat_id);

        //== Them thong tin
        $info = $this->_mod()->add_info($info);
        $info = $this->_mod()->add_info_images($info);
       // $info = $this->_mod()->add_info_files($info);
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
            $parent = model('project_cat')->get_info($parent->parent_id);

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