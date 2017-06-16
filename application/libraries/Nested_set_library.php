<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
class Nested_set_library {

	private $table_name              = 'user';
	private $left_column_name        = 'node_left';
	private $right_column_name       = 'node_right';
	private $primary_key_column_name = 'id';
	private $parent_column_name      = 'node_parent';
	private $text_column_name        = 'name';
	private $link_url                = '';
	private $db;
	
    
	/**
	 * Constructor
	 *
	 * @access	public
	 */
	public function __construct()	{
		$CI =& get_instance(); 
		$this->db = & $CI->db;
	}

	/**
	 * function setup: cấu hình bảng dữ liệu
	 * @param string $table_name : tên bảng dữ liệu
	 * @param string $left_column_name : Tên cột lưu vị trí bên trái của node
	 * @param string $right_column_name: : Tên cột lưu vị trí bên phải của node
	 * @param string $primary_key_column_name: Khóa chính của bảng
	 * @param string $parent_column_name: tên cột node cha
	 * @param string $text_column_name: tên cột lưu dữ liệu
	 * @param string $link_url: Link khi tạo menu ví dụ:  user/view
	 */
	public function setup($table_name = 'user', $left_column_name = 'node_left', $right_column_name = 'node_right', $parent_column_name = 'parent_id', $text_column_name = 'name', $link_url = '') {
		$this->table_name              = $table_name;
		$this->left_column_name        = $left_column_name;
		$this->right_column_name       = $right_column_name;
		$this->parent_column_name      = $parent_column_name;
		$this->text_column_name        = $text_column_name;
		$this->link_url                = $link_url;
	}
	
	/**
	 * function add_node: Thêm 1 node mới
	 * @param object or int $parent_node : node cha
	 * @param array $data_fields : Dữ liệu thêm vào
	 * @param array $insert_id : ID node vừa thêm vào
	 */
	function add_node($parent_node = 0,  $data_fields = array(), &$insert_id = null)
	{
	    //gia tri mac dinh
	    $parentval  = $leftval = 0;
	    $rightval  = 1;
	    
	    //neu day la node cha
	    if(is_numeric($parent_node) && $parent_node == 0)
	    {
	        //lay gia tri right lon nhat hien tai
	        $this->db->select_max($this->right_column_name, 'lft');
	        $query = $this->db->get($this->table_name);
	        $result = $query->row_array();

	        if(isset($result['lft']))
	        {
	            $leftval   = $result['lft'] + 1;
	            $rightval  = $result['lft']  +2;
	        }
	    }else{
	        
	        if(is_numeric($parent_node))
	        {
	            $parent_node = model($this->table_name)->get_info($parent_node);
	        }  
	        
	        //lay cac gia tri tu node cha
	        if($parent_node)
	        {
	            $parentval = $parent_node->{$this->primary_key_column_name};
	            $leftval   = $parent_node->{$this->right_column_name};
	        }
	        $rightval  = $leftval + 1;
	    }
	    
	    $node = array(
	        $this->parent_column_name => $parentval,
	        $this->left_column_name   => $leftval,
	        $this->right_column_name  => $rightval,
	    );
	    
	    if(isset($parent_node->{$this->right_column_name}))
	    {
	        //thay doi cau truc tree
	        $this->_change_structure($parent_node->{$this->right_column_name});  
	    }
	   
	    //them nut moi
	    $this->_add_node($node, $data_fields, $insert_id);
	}
	
	/**
	 * function del_node: Xóa 1 node
	 * @param object $node : node cần xóa
	 * @param boolean $del_child : Trạng thái có xóa các node con hay không, nếu không xóa dịch chuyển node con đó lên parent ông
	 */
	function del_node($node, $del_child = true)
	{
	    //Nếu cho phép xóa tất cả node con
	    if($del_child == true)
	    {
	        //dieu kien cho cac node con
	        $where = array(
	            $this->left_column_name  . ' >' => $node->{$this->left_column_name},
	            $this->right_column_name . ' <' => $node->{$this->right_column_name},
	        );
	        
	        //so luong thay doi
	        $change = 1;
	         
	        //lay tong so node con
	        $total_child = model($this->table_name)->get_total($where);
	        $change = $change  + $total_child;
	        
	        //xoa node can xoa
	        model($this->table_name)->del($node->{$this->primary_key_column_name});
	        
	        //xoa cac node con
	        model($this->table_name)->del_rule($where);
	         
	        //lay so gia tri can giam
	        $change_val = -$change*2;
	        
	        //thay doi cau truc
	        $this->_change_structure_update($node->{$this->left_column_name}, $node->{$this->right_column_name}, $change_val);
	         
	    }else{ //nếu không xóa node con thì chuyển các node con đó về node ông
	        //lay danh sach cac node con f1  
	        $where = array(
	            $this->parent_column_name => $node->{$this->primary_key_column_name}
	        );
	        $input = array();
	        $input['select'] = $this->primary_key_column_name. ','. $this->parent_column_name . ','
	            .$this->text_column_name. ','.$this->left_column_name . ','. $this->right_column_name;
	        $input['where']  = $where;
	        
	        $list_childs_f1 = model($this->table_name)->get_list($input);
	        
	        //chuyen cac node con f1 nay len ngang cap voi node can xoa
	        foreach ($list_childs_f1 as $row)
	        {
	            $this->setNodeAsNextSibling($row, $node);
	        }
	        //xoa node hien tai
	        model($this->table_name)->del($node->{$this->primary_key_column_name});
	        
	        //thay doi cau truc cay
	        $change_val = -2;
	        $this->_change_structure_update($node->{$this->left_column_name}, $node->{$this->right_column_name}, $change_val);  
	    }  
	}

	/**
	 * function getSubTree: Lấy cây từ nút cho trước
	 * @param array $node : nút bắt đầu
	 * @param string $select : Các cột cần lấy
	 * @param boolean $include_node : Có lấy luôn $node hiện tại
	 * @$unset_node: không bao gồm node này và con của chúng
	 */
	public function getSubTree($node, $select = '*', $include_node = true) {
	    $node = (!is_array($node)) ? (array) $node : $node;
	    
	    $tree_handle = $this->getTreePreorder($node , false, $select);
	
	    $menuData = array(
	        'items'   => array(),
	        'parents' => array()
	    );
	    
	    if(isset($tree_handle['result_array']))
	    {
	        foreach ($tree_handle['result_array'] as $menuItem)
	        {
	            if(!$include_node && isset($node[$this->primary_key_column_name]) && $menuItem[$this->primary_key_column_name] == $node[$this->primary_key_column_name]) continue;
	             
	            $menuData['items'][$menuItem[$this->primary_key_column_name]]  = $menuItem;
	            $menuData['parents'][$menuItem[$this->parent_column_name]][]   = $menuItem[$this->primary_key_column_name];
	        } 
	    }
	   
	    return $menuData;
	    // return $this->buildMenu($node['parent_id'], $menuData);
	}
	
	
// -------------------------------------------------------------------------
//  TREE QUERY METHODS
// -------------------------------------------------------------------------
	
	/**
	 * Function getNumberOfChildren:  trả về tổng số lượng node con
	 * @param array $node : node cần lấy
	 * @return integer : số lượng
	 */
	public function getNumberOfChildren($node) {
	    
	    $node = (!is_array($node)) ? (array) $node : $node;
	     
	    return (($node[$this->right_column_name] - $node[$this->left_column_name] - 1) / 2);
	}
	
	/**
	 * Function getNodeLevel: Lấy level của 1 node
	 * @param array $node: node cần lấy level
	 * @return integer : level của node đó
	 */
	public function getNodeLevel($node) {
	    $node = (!is_array($node)) ? (array) $node : $node;
	     
	    $leftcol	=	   $this->left_column_name;
	    $rightcol   =	   $this->right_column_name;
	    $leftval	= (int) $node[$leftcol];
	    $rightval   = (int) $node[$rightcol];
	
	    $this->db->where($leftcol . ' <', $leftval);
	    $this->db->where($rightcol . ' >', $rightval);
	
	    return $this->db->count_all_results($this->table_name);
	}
	
	/**
	 * Function getTreePreorder: Trả về một mảng của cây bắt đầu từ nút cung cấp
	 * @param array $node Các nút để sử dụng như là điểm khởi đầu (thường là root)
	 * @param boolean $notincludeSelf : không bao gồm nó
	 * @return array $tree_handle : 1 mảng dữ liệu phục vụ cho việc xử lý
	 */
	public function getTreePreorder($node = array(), $notincludeSelf = false, $field = '') {
	
	    $node = (!is_array($node)) ? (array) $node : $node;
	    
	    $leftcol	= $this->left_column_name;
	    $rightcol   = $this->right_column_name;
	    $primarykeycol	= $this->primary_key_column_name;
	    $parentcol		= $this->parent_column_name;
	   
	    
	    //neu co node truyen vao
	    if(isset($node[$leftcol])) 
	    {
	        
	        $leftval	    = (int) $node[$leftcol];
	        $rightval       = (int) $node[$rightcol];
	        $primarykeyval	= (int) $node[$primarykeycol]; 
	        
	        if( $notincludeSelf ) {
	            $this->db->where($leftcol . ' >', $leftval);
	            $this->db->where($rightcol . ' <', $rightval);
	        }else{
	            $this->db->where($leftcol . ' >=', $leftval);
	            $this->db->where($rightcol . ' <=', $rightval);
	        }
	    }else{ //lay tat ca toan bo tree
	        $leftval  = 0;
	        $rightval = 0;
	    }

	    if ($field)
	    {
	        $this->db->select($field);
	    }

	    $this->db->order_by($leftcol, 'asc');
	    $query = $this->db->get($this->table_name);
	
	    $treeArray = array();
	
	    if($query->num_rows() > 0) {
	        $treeArray = $query->result_array();
	    }
	
	    $retArray = array(
	        'result_array'  =>	  $treeArray,
	        'prev_left'	    =>	  $leftval,
	        'prev_right'	=>	  $rightval,
	        'level'      	=>	  -2,
	    );
	
	    return $retArray;
	}

	/**
	 *  function getNodeRoot: Tìm node cha cao nhất của line đó
	 * @param array $node : Node cần tìm đường dẫn
	 * @param boolean $field :  Các cột cần lấy
	 * @return array or list html
	 */
	public function getNodeRoot($node,  $field = '')
	{
	    if(empty($node)) return FALSE;
	    $node = (!is_array($node)) ? (array) $node : $node;
	     
	    $leftcol	=	   $this->left_column_name;
	    $rightcol   =	   $this->right_column_name;
	    $parentcol  =	   $this->parent_column_name;
	     
	    $leftval	= (int) $node[$leftcol];
	    $rightval   = (int) $node[$rightcol];
	
	    if ($field)
	    {
	        $this->db->select($field);
	    }
	     
	    $this->db->where($leftcol . ' < ' . $leftval . ' AND ' . $rightcol . ' > ' . $rightval. ' AND '.$parentcol. ' = 0');
	
	    $this->db->order_by($leftcol);
	    $query = $this->db->get($this->table_name);
	     
	    return $query->row_array();
	}
	
	/**
	 *  function getPath: Tìm đường dẫn tới 1 node (lấy các danh sách cha)
	 * @param array $node : Node cần tìm đường dẫn
	 * @param boolean $includeSelf :  Bao gồm cả node đó trong kết quả
	 * @param boolean $returnAsArray : Trả về dạng mảng hoặc dạng html, mặc định dạng html
	 * @param boolean $field :  Các cột cần lấy
	 * @return array or list html
	 */
	public function getPath($node, $includeSelf = FALSE, $returnAsArray = true, $field = '')
	{
	    $node    = (!is_array($node)) ? (array) $node : $node;
	    $parents = array();
	    if($includeSelf)
	    {
	        $parents[] = $node;
	    }
	    $this->_get_parent($node, $parents, $field);
	    
	    $parents = array_reverse($parents);
	    if($returnAsArray)
	    {
	        return $parents;
	    }
	    else
	    {
	        return $this->buildCrumbs($parents);
	    }
	}
	
	private function _get_parent($node, &$parents, $field)
	{
	    if(!isset($node[$this->parent_column_name]))
	    {
	        return ;
	    }
	    $parent = model($this->table_name)->get_info($node[$this->parent_column_name]);
	    if($parent)
	    {
	        $parent    = (array)$parent;
	        $parents[] = $parent;
	        $this->_get_parent($parent, $parents, $field);
	    }
	    
	}
	
	/**
	 *  function getPath: Tìm đường dẫn tới 1 node (lấy các danh sách cha)
	 * @param array $node : Node cần tìm đường dẫn
	 * @param boolean $includeSelf :  Bao gồm cả node đó trong kết quả
	 * @param boolean $returnAsArray : Trả về dạng mảng hoặc dạng html, mặc định dạng html
	 * @param boolean $field :  Các cột cần lấy
	 * @return array or list html
	 */
	public function getPath_old($node, $includeSelf = FALSE, $returnAsArray = true, $field = '') 
	{   
	    $node = (!is_array($node)) ? (array) $node : $node;
	     
	    $leftcol	=	   $this->left_column_name;
	    $rightcol   =	   $this->right_column_name;
	    if(!isset($node[$leftcol])) return FALSE;
	   
	    $leftval	= (int) $node[$leftcol];
	    $rightval   = (int) $node[$rightcol];
	
	    if ($field)
	    {
	        $this->db->select($field);
	    }
	    
	    if($includeSelf)
	    {
	        $this->db->where($leftcol . ' <= ' . $leftval . ' AND ' . $rightcol . ' >= ' . $rightval);
	    }
	    else
	    {
	        $this->db->where($leftcol . ' < ' . $leftval . ' AND ' . $rightcol . ' > ' . $rightval);
	    }
	
	    $this->db->order_by($leftcol);
	    $query = $this->db->get($this->table_name);
	    
	    if($query->num_rows() > 0)
	    {
	        if($returnAsArray)
			{
				return $query->result_array();
			}
			else
			{
				return $this->buildCrumbs($query->result_array());
			}
	    }
	
	    return array();
	}
	
	/**
	 * Kiểm tra xem 1 node có nằm trong danh sách node cha của 1 node hay không
	 *  @param array $node : Node cần kiểm tra
	 *  @param array $node_cur : Node hiện tại
	 *  @param string $field : Field cần lấy
	 */
	function check_is_parents($node, $node_cur, $include_node = FALSE)
	{
	    
	    if(empty($node)) return FALSE;
	    $node = (!is_array($node)) ? (array) $node : $node; 
	    $node_cur = (!is_array($node_cur)) ? (array) $node_cur : $node_cur;
	    
	    $node_id = $node['id'];
	    if(($node_id == $node_cur['id']) && $include_node)
	    {
	        return true;
	    }
	    
	    //lay danh sách tài khoản cha
	    $select = 'id, name, phone, email, node_left, node_right';
	    $list_parents = $this->getPath($node_cur, false, true, $select);
	    
	    //kiem tra xem node này có trong danh sách cha của node hiện tại
	    $status = false;
	    foreach ($list_parents as $row){
	        if($row['id'] == $node_id){
	            $status = true;
	            break;
	        }
	    }
	    return $status;
	}
	
	/**
	 * Lấy danh sách phả hệ của 1 nút, bao gồm tất cả cha ông, con cháu
	 *  @param array $node : Node cần tìm đường dẫn
	 *  @param string $field : Field cần lấy
	 */
	function get_parentage($node, $field = '')
	{
	    //lay danh sach cha
	     $parents = $this->getPath($node, false, true, $field);
	     //lay danh sach con
	     $childs  = $this->getTreePreorder($node, false, $field);
	     $childs  = isset($childs['result_array']) ? $childs['result_array'] : array();
	     
	     $parentage = array_merge($parents, $childs);
	     return $parentage;
	}
	
	
	/**
	 * function buildCrumbs: Tạo breadcrumbs
	 * @param array $crumbData : Mảng dữ liệu
	 */
	function buildCrumbs($crumbData)
	{
	    $retVal = '';
	
	    $retVal = '<ul id="breadcrumbs">';
	
	    foreach ($crumbData as $itemId)
	    {
	        if($itemId['id'] > 1) $retVal .= '<span class="divider">></span>';
	
	        $retVal .= '<li>' . anchor(
	            $this->link_url.'/' . $itemId[$this->primary_key_column_name],
	            $itemId[$this->text_column_name],
	            array(
	                'name' => $itemId[$this->text_column_name])
	        );
	
	        $retVal .= '</li>';
	    }
	
	    $retVal .= '</ul>';
	
	    return $retVal;
	}
	
	/**
	 * function buildMenu: Tạo menu đa cấp
	 * @param array $menuData : Mảng dữ liệu
	 * @param int $parentId : ID cha
	 * @param int $depth : Tạo ký tự thêm vào class
	 */
	function buildMenu($parentId, $menuData, $depth = 0)
	{
	    $retVal = '';
	
	    if (isset($menuData['parents'][$parentId]))
	    {
	        $retVal = '<ul>';
	
	        foreach ($menuData['parents'][$parentId] as $itemId)
	        {
	            $level = isset($menuData['items'][$itemId]['level']) ? ' - <b>Level '.$menuData['items'][$itemId]['level'].'</b>' : '';
	            $retVal .= '<li class="depth-' . $depth . '">' . anchor(
	                $this->link_url.'/' . $menuData['items'][$itemId][$this->primary_key_column_name],
	                $menuData['items'][$itemId][$this->text_column_name] . $level,
	                array(
	                    'class' => 'id-' . $this->primary_key_column_name
	                )
	            );
	            $retVal .= $this->buildMenu($itemId, $menuData, $depth+1);
	
	            $retVal .= '</li>';
	        }
	
	        $retVal .= '</ul>';
	    }
	
	    return $retVal;
	}
	

	/**
	 * function buildMenu: Tạo mảng  đa cấp
	 * @param array $menuData : Mảng dữ liệu
	 * @param int $parentId : ID cha
	 * @param int $depth : Tạo ký tự thêm vào class
	 */
	function buildArrayTree($parentId, $menuData, $depth = 0)
	{
	    if (isset($menuData['parents'][$parentId]))
	    {
	        foreach ($menuData['parents'][$parentId] as $itemId)
	        {
	            $subs[] = $menuData['items'][$itemId];
	            $this->buildArrayTree($itemId, $menuData, $depth+1);
	        }
	        $data[$parentId] = $subs;
	    }else{
	        $data = array();
	    }
	
	    return $data;
	}
	
	
	// -------------------------------------------------------------------------
	// Sửa đổi, cập nhật cây 
	// Các phương pháp để di chuyển các nút xung quanh cây
	// -------------------------------------------------------------------------
	
	/**
	 * Chuyển 1 node và con của nó về làm a chị em kế tiếp của 1 nút
	 * @param array $node: Node cần chuyển
	 * @param array $target: Node đích
	 * @return array $newpos: giá trị left và right của node được chuyển
	 */
	public function setNodeAsNextSibling($node, $target) {
	    $node = (!is_array($node)) ? (array) $node : $node;
	    $target = (!is_array($target)) ? (array) $target : $target;
	     
	    $this->_setParent($node, $target[$this->parent_column_name]);
	    return $this->_moveSubtree($node, $target[$this->right_column_name]+1);
	}
	
	/**
	 * Chuyển 1 node con của nó về làm a chị em trước của 1 nút
	 * @param array $node: Node cần chuyển
	 * @param array $target: Node đích
	 * @return array $newpos: giá trị left và right của node được chuyển
	 */
	public function setNodeAsPrevSibling($node, $target) {
	    $node = (!is_array($node)) ? (array) $node : $node;
	    $target = (!is_array($target)) ? (array) $target : $target;
	    
	    $this->_setParent($node, $target[$this->parent_column_name]);
	    return $this->_moveSubtree($node, $target[$this->left_column_name]);
	}
	
	/**
	 * Chuyển 1 node con của nó về làm con đầu tiền của 1 nút
	 * @param array $node: Node cần chuyển
	 * @param array $target: Node đích
	 * @return array $newpos: giá trị left và right của node được chuyển
	 */
	public function setNodeAsFirstChild($node, $target) {
	    $node   = (!is_array($node)) ? (array) $node : $node;
	    $target = (!is_array($target)) ? (array) $target : $target;
	    
	    $this->_setParent($node, $target[$this->primary_key_column_name]);
	    return $this->_moveSubtree($node, $target[$this->left_column_name]+1);
	}
	
	/**
	 * Chuyển 1 node con của nó về làm con cuối cùng của 1 nút
	 * @param array $node: Node cần chuyển
	 * @param array $target: Node đích
	 * @return array $newpos: giá trị left và right của node được chuyển
	 */
	public function setNodeAsLastChild($node, $target) {
	    $node   = (!is_array($node)) ? (array) $node : $node;
	    $target = (!is_array($target)) ? (array) $target : $target;
	    
	    $this->_setParent($node, $target[$this->primary_key_column_name]);
	    return $this->_moveSubtree($node, $target[$this->right_column_name]);
	}
	
//  Các câu truy vấn
//
//  Lấy thông tin các node từ cây
//
// -------------------------------------------------------------------------
	
	/**
	 * Trả về thông tin của 1 node từ 1 điều kiện nào đó
	 * @param array $where: Điều kiện
	 * @param string $field: Các cột dữ liệu cần lấy
	 * @return string $field: Các cột dữ liệu cần lấy
	 */
	public function getNodeWhere($where = array(), $field = '') {
	    if ($field)
	    {
	        $this->db->select($field);
	    }
	    return model($this->table_name)->get_info_rule($where, $field);
	}
	
	
	/**
	 * lấy node con đầu tiên của node cha
	 * @param array $parentNode: node cha
	 * @param string $field: Các cột dữ liệu cần lấy
	 */
	public function getFirstChild($parentNode, $field = '') {
	    $parentNode = (!is_array($parentNode)) ? (array) $parentNode : $parentNode;
	    $where = array(
	        $this->left_column_name => $parentNode[$this->left_column_name]+1,
	    );
	    return $this->getNodeWhere($where, $field);
	}
	
	/**
	 * lấy node con cuối cùng của node cha
	 * @param array $parentNode: node cha
	 * @param string $field: Các cột dữ liệu cần lấy
	 */
	public function getLastChild($parentNode, $field = '') {
	    $parentNode = (!is_array($parentNode)) ? (array) $parentNode : $parentNode;
	    $where = array(
	        $this->right_column_name => $parentNode[$this->right_column_name]-1,
	    );
	    return $this->getNodeWhere($where, $field);
	}
	
	/**
	 * Lấy anh chị em phía bên trái của nút hiện tại
	 * @param array $currNode : Node hiện tại
	 * @param string $field: Các cột dữ liệu cần lấy
	 */
	public function getPrevSibling($currNode, $field = '') {
	    $currNode = (!is_array($currNode)) ? (array) $currNode : $currNode;
	    $where = array(
	        $this->right_column_name => $currNode[$this->left_column_name]-1,
	    );
	    return $this->getNodeWhere($where, $field);
	}
	
	/**
	 * Lấy anh chị em phía bên phải của nút hiện tại
	 * @param array $currNode : Node hiện tại
	 * @param string $field: Các cột dữ liệu cần lấy
	 */
	public function getNextSibling($currNode, $field = '') {
	    $currNode = (!is_array($currNode)) ? (array) $currNode : $currNode;
	    $where = array(
	        $this->left_column_name => $currNode[$this->right_column_name]+1,
	    );
	    return $this->getNodeWhere($where, $field);
	}
	
	/**
	 *Lấy thông tin node cha của node hiện tại
	 * @param array $currNode  Node hiện tại
	 * @param string $field: Các cột dữ liệu cần lấy
	 */
	public function getAncestor($currNode, $field = '') {
	    $currNode = (!is_array($currNode)) ? (array) $currNode : $currNode;
	    $where = array(
	        $this->primary_key_column_name => $currNode[$this->parent_column_name],
	    );
	    return $this->getNodeWhere($where, $field);
	}
	
	/**
	 * function add_node: Thêm 1 node mới
	 * @param array $node : mảng dữ liệu chứa dữ liệu của  array($parent_column_name, $left_column_name, $right_column_name)
	 * @param array $data_fields : Dữ liệu thêm vào
	 * @param array $insert_id : ID node vừa thêm vào
	 */
	private function _add_node($node, $data_fields = array(), &$insert_id = null)
	{
	    foreach (array($this->parent_column_name, $this->left_column_name, $this->right_column_name) as $p)
	    {
	        if(!isset($node[$p])) return false;
	         
	        $data[$p] = (int) $node[$p];
	    }
	
	    if(is_array($data_fields) && !empty($data_fields)) $data = array_merge($data, $data_fields);
	     
	    //them vao bang du lieu
	    $insert_id = 0;
	    model($this->table_name)->create($data, $insert_id);
	}
	
	
	
	/**
	 * Gắn lại node cha cho 1 node
	 *
	 * @param array $node : Node cần xử lý
	 * @param integer $parent_id: ID cha của node
	 * @access private
	 */
	private function _setParent($node, $parent_id) {
	    $node = (!is_array($node)) ? (array) $node : $node;
	    
	    $primarykeycol	=	$this->primary_key_column_name;
	    $parentcol		=	$this->parent_column_name;
	    $primarykeyval	=	(int) $node[$primarykeycol];
	    $parentval		=	(int) $node[$parentcol];
	
	    if($parentval != $parent_id) {
	        $data = array(
	            $parentcol => $parent_id,
	        );
	
	        $this->db->where($primarykeycol, $primarykeyval);
	        $this->db->update($this->table_name, $data);
	    }
	}
	
	/**
	 * Chuyển node
	 *
	 * @param array $node: Node cần chuyển
	 * @param array $targetValue : Vị trí số nguyên để sử dụng như là mục tiêu
	 * @return array $newpos The new left and right values of the node moved
	 * @access private
	 */
	private function _moveSubtree($node, $targetValue) {
	    $node = (!is_array($node)) ? (array) $node : $node;
	    
	    $sizeOfTree = $node[$this->right_column_name] - $node[$this->left_column_name] + 1;
	    $this->_change_structure($targetValue, $sizeOfTree);
	
	    if($node[$this->left_column_name] >= $targetValue)
	    {
	        $node[$this->left_column_name] += $sizeOfTree;
	        $node[$this->right_column_name] += $sizeOfTree;
	    }
	
	    $newpos = $this->_change_structure_update($node[$this->left_column_name], $node[$this->right_column_name], $targetValue - $node[$this->left_column_name]);
	
	    $this->_change_structure($node[$this->right_column_name]+1, - $sizeOfTree);
	
	    if($node[$this->left_column_name] <= $targetValue)
	    {
	        $newpos[$this->left_column_name] -= $sizeOfTree;
	        $newpos[$this->right_column_name] -= $sizeOfTree;
	    }
	
	    return $newpos;
	}
	
	
	/**
	 * function change_structure: Thay đổi cấu trúc của cây nhị phân khi thêm vào
	 * @param int $node_int : Vị trí số nguyên để sử dụng như là mục tiêu
	 * @param int $change_val : Giá trị cần thay đổi gia trị left và right
	 */
	private function _change_structure($node_int, $change_val = 2)
	{
	    $leftcol	=	$this->left_column_name;
	    $rightcol	=	$this->right_column_name;
	
	    //tăng giá trị của tất cả các nút có left lớn hơn hoặc bằng right của node hien tại
	    $this->db->set($leftcol, $leftcol . '+' . (int) $change_val, FALSE);
	    $this->db->where($leftcol . ' >=', (int) $node_int);
	    $this->db->update($this->table_name);
	
	    //tăng giá trị của tất cả các nút có right lớn hơn hoặc bằng right của node hien tại
	    $this->db->set($rightcol, $rightcol . '+' . (int) $change_val, FALSE);
	    $this->db->where($rightcol . ' >=', (int) $node_int);
	    $this->db->update($this->table_name);
	}
	
	/**
	 * function change_structure_del: Thay đổi cấu trúc của cây nhị phân khi xóa node
	 * @param object $node : Node cần xóa
	 * @param int $lowerbound : Giá trị cần thay đổi gia trị left
	 * @param int $upperbound : Giá trị cần thay đổi gia trị right
	 */
	private function _change_structure_update($lowerbound, $upperbound, $change_val = 2)
	{
	    $leftcol	=	$this->left_column_name;
	    $rightcol	=	$this->right_column_name;
	
	    $this->db->set($leftcol, $leftcol . '+' . (int) $change_val, FALSE);
	    $this->db->where($leftcol . ' >=', (int) $lowerbound);
	    $this->db->where($leftcol . ' <=', (int) $upperbound);
	    $this->db->update($this->table_name);
	
	    $this->db->set($rightcol, $rightcol . '+' . (int) $change_val, FALSE);
	    $this->db->where($rightcol . ' >=', (int) $lowerbound);
	    $this->db->where($rightcol . ' <=', (int) $upperbound);
	    $this->db->update($this->table_name);
	
	    //ket qua tra ve
	    $result = array(
	        $this->left_column_name  =>  $lowerbound + $change_val,
	        $this->right_column_name =>  $upperbound + $change_val
	    );
	
	    return $result;
	}
}


