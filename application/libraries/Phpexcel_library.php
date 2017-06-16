<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phpexcel_library
{
	/**
	 * Thuc hien export dữ liệu
	 * @$headers : Mang header, VD: $headers array('username' => 'Tài khoản', 'email' => 'Email', 'phone' => 'Điện thoại');
	 * @$list : Danh sach du lieu
	 * @$full_path : Đường dẫn file, ví dụ: './upload/export/data.xlsx'
	 * 
	 */
	public function export($headers = array(), $list = array(), $full_path, $type = 'Excel2007')
	{
	    if(empty($headers) || empty($list) || !$full_path) return;
	    
		//luu cac thong tin vao file excel
        require_once APPPATH.'libraries/PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        
        $keys = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O');
        $k = 0;
        //set header
        foreach ($headers as $key => $val)
        {
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($keys[$k].'1', $val);
            $k++;
        }
        
        //set gia tri cho cac cot du lieu
        $i = 2;
        foreach ($list as $row)
        {
            $k = 0;
            foreach ($headers as $key => $val)
            {
                 if(isset($row[$key]))
                 {
                     $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue($keys[$k].$i, $row[$key]);
                 } 
                 $k++;
            }
            
            $i++;
        }
        
        //ghi du lieu vao file,định dạng file excel 2007
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $type);
        $objWriter->save($full_path);
	}
	
	

	/**
	 * Đọc nội dung trong file excel
	 */
	function read_file($full_path)
	{
	    require_once APPPATH . '/libraries/PHPExcel/PHPExcel.php';
	    $inputFileType = PHPExcel_IOFactory::identify("$full_path");
	
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	
	    $objReader->setReadDataOnly(true);
	
	    /**  Load $inputFileName to a PHPExcel Object  **/
	    $objPHPExcel = $objReader->load("$full_path");
	
	    $total_sheets=$objPHPExcel->getSheetCount();
	
	    $allSheetName=$objPHPExcel->getSheetNames();
	    $objWorksheet  = $objPHPExcel->setActiveSheetIndex(0);
	    $highestRow    = $objWorksheet->getHighestRow();
	    $highestColumn = $objWorksheet->getHighestColumn();
	    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
	    $arraydata = array();
	    for ($row = 2; $row <= $highestRow;++$row)
	    {
	        for ($col = 0; $col <$highestColumnIndex;++$col)
	        {
	            $value=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
	            $arraydata[$row-2][$col]=$value;
	        }
	    }
	     
	    return $arraydata;
	
	}
	
}

