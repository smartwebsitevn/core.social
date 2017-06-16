<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Module Controller Class
 * 
 * Class xay dung cho controller cua cac module
 *
 * @author		***
 * @version		2014-01-06
 */
class Module_controller {
	
	// Doi tuong cua module hien tai
	var $MD = '';
	// Doi tuong cua CI
	var $CI;
	// Bien luu method duoc goi hien tai cua controller
	var $area = '';
	// Bien luu method duoc goi hien tai cua controller
	var $module = '';
	// Bien luu method duoc goi hien tai cua controller
	var $controller = '';
	// Bien luu method duoc goi hien tai cua controller
	var $method = '';
	

	
	// Bien luu thong tin gui den view
	var $data = array();
	
	
	// --------------------------------------------------------------------
	
	/**
	 * Ham khoi dong
	 * @param object $MD	Doi tuong cua module
	 */
	public function __construct($MD)
	{
		// Luu doi tuong cua module
		$this->MD = $MD;
		
		// Lay doi tuong cua CI
		$this->CI =& get_instance();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Cho phep su dung cac thuoc tinh cua module
	 */
	function __get($key)
	{
		return $this->MD->$key;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Hien thi view
	 * 
	 * @param string $view		File view ('' => Lay theo method hien tai || NULL => Khong su dung view)
	 * @param string $layout	File layout ('' => Lay theo config || NULL => Khong su dung layout)
	 */
	protected function _display_($view = '', $layout = '')
	{
		if ( ! is_null($view))
		{
			$area = $this->area ;
			$module = $this->module;
			$controller = $this->controller;
			$method 	= $this->method;
		
			
			$view = ($view == '') ? $method : $view;
			$view ='tpl::modules/'.$module.'/views/'.$area.'/'.$view.'.php';
		//pr($view);
			//$view = (substr($view, 0, 1) == '/') ? ltrim($view, '/') : 'tpl::'.$controller.'/'.$view; // Xu ly load view tu mod khac
		}
		//$p=t('tpl')->get_path();
		//pr($p);
		//pr($view);
		return t('tpl')->display($view, $layout, $this->data);
	}
	
	/**
	 * Hien thi view
	 * @param string $view		File view ('' => Lay theo method hien tai || NULL => Khong su dung view)
	 * @param string $layout	File layout ('' => Lay theo config || NULL => Khong su dung layout)
	 */
	protected function _display($view = '', $layout = '')
	{
		// Kiem tra view & layout
		if ($view === NULL && $layout === NULL)
		{
			show_error('Required View or Layout');
		}
		
		// Lay input
		$area 	= get_area();
		$module = $this->module;
		$controller = $this->controller;
		$method = $this->method;
		
		// Tao duong dan den file view
		if ($view !== NULL)
		{
			$view = ($view == '') ? $controller.'/'.$method : $view;
			//$view ='modules/'.$module.'/views/'.$area.'/'.$view;
			$view =$area.'/'.$view;
			//$view = (substr($view, 0, 1) == '/') ? ltrim($view, '/') : $area.'/'.$view; // Xu ly load view tu mod khac
			
		}
		//pr($view);
		// Neu khong su dung layout
		if ($layout === NULL)
		{
			$this->load->view($view, $this->data);
			return;
		}
		
		// Lay layout theo config
		if ($layout == '')
		{
			// Neu area tai site thi lay layout trong setting cua module
			if ($area == 'site')
			{
				// Lay thong tin module
				$this->CI->load->model('module_model');
				$module = $this->CI->module_model->get($this->MD->key, 'layout');
				
				// Lay layout
				if (isset($module->layout[$method]))
				{
					$layout = $module->layout[$method];
				}
				elseif (isset($module->layout['_']))
				{
					$layout = $module->layout['_'];
				}
				else 
				{
					$layout = t('tpl')->config('layout');
				}
			}
			
			// Neu area tai admin thi lay layout theo mac dinh
			else 
			{
				$layout = t('tpl')->config('layout');
			}
		}
		//pr($layout);
		// Gan layout
		if ( ! t('tpl')->set_layout($layout))
		{
			show_error('Non-existent layout: '.$layout);
		}
	
		// Hien thi view
		if ($view && t('tpl')->has_region('content'))
		{
		//pr($view);	
			t('tpl')->write('content', $this->load->view($view, $this->data, TRUE), TRUE);
		}
		
		// Hien thi cac widget
		//$this->module->display_widgets(t('tpl')->get_regions_name());
		
		// Hien thi template
		t('tpl')->render();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Xu ly form submit output
	 */
	protected function _form_submit_output($result)
	{
		// Neu su dung ajax load
		if ($this->CI->input->post('_submit') != '1')
		{
			$output = json_encode($result);
			set_output('json', $output);
		}
		
		// Neu load form
		elseif (isset($result['complete']))
		{
			redirect($result['location']);
		}
	}

    	// --------------------------------------------------------------------

	/**
	 * Chuyen huong den cac page trong controller hien tai
	 *
	 * @param string $uri
	 * @param string $method
	 * @param number $http_response_code
	 */
	protected function _redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
		redirect($this->_uri($uri), $method, $http_response_code);
	}

	/**
	 * Tao url den cac page trong controller hien tai
	 *
	 * @param string $uri
	 * @param array $opt
	 * @return string
	 */
	protected function _url($uri = '', array $opt = array())
	{
		return site_url($this->_uri($uri), $opt);
	}

	/**
	 * Tao uri cho controller hien tai
	 *
	 * @param string $uri
	 * @return string
	 */
	protected function _uri($uri = '')
	{
		$controller = $this->uri->rsegment(1);

		$uri = (starts_with($uri, '/')) ? ltrim($uri, '/') : $controller.'/'.$uri;

		if (get_area() == 'admin')
		{
			$uri = config('admin_folder', 'main').'/'.$uri;
		}

		return $uri;
	}

	/**
	 * Lay doi tuong cua mod hien tai
	 *
	 * @return MY_Mod
	 */
	protected function _mod()
	{
		return mod($this->_get_mod());
	}

	/**
	 * Lay doi tuong cua model hien tai
	 *
	 * @return MY_Model
	 */
	protected function _model()
	{
		return model($this->_get_mod());
	}

	/**
	 * Lay key cua mod hien tai
	 *
	 * @return string
	 */
	protected function _get_mod()
	{
		return $this->uri->rsegment(1);
	}
	
}
