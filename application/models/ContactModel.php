<?php

/**
 * 文件名称:ContactModel.php
 * 摘    要:
 * 修改日期: 25/11/17
 */
class ContactModel extends CI_Model
{
    public function save_contact($contact)
    {
        $insert_status = $this->db->insert("t_contact", $contact);
        if ($insert_status && $this->db->affected_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }
}