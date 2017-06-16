<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_answer_mod extends MY_Mod
{

    /**
     * Thuc hien hanh dong
     *
     * @param object|int $row
     * @param string $action
     * @return boolean
     */
    public function action($row, $action, $note = '')
    {
        // Lay thong tin
        if (is_numeric($row)) {
            $row = $this->_model()->get_info($row);
        }
        // Xu ly action voi tran
        if ($this->can_do($row, $action)) {

            switch ($action) {

                // kich hoat tin
                case 'verify': {

                    $this->_model()->update_field($row->id, 'status', 1);
                    break;
                }

                // bo kich hoat tin
                case 'unverify': {
                    $this->_model()->update_field($row->id, 'status', 0);
                    break;
                }


            }
            // if($note)    $this->_model()->update_field($row->id, 'note', $note);
            // Luu log
            // $this->log($row, $action);
        }


        // Neu la action del thi chay cuoi cung
        if ($this->can_do($row, $action)) {
            switch ($action) {
                // Xoa don hang
                case 'del': {
                    $this->del($row->id);
                    break;
                }
            }

            // Luu log
            //$this->log($row, $action);
        }
    }
    /**
     * Kiem tra co the thuc hien hanh dong hay khong
     *
     * @param object $row
     * @param string $action
     * @return boolean
     */
    public function can_do($row=null, $action)
    {
        // return false;
        if ( ! $row) return false;

        switch ($action)
        {
            case 'view':
            case 'get':
            {
                return true;
            }
            case 'verify':
            {

                return ($row->status == 0);
            }
            case 'unverify':
            {
                return ($row->status == 1);
            }
            case 'del':
            {
                return true;
            }


        }

        return false;
    }
}