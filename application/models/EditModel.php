<?php

/**
 * 文件名称:EditModel.php
 * 摘    要:
 * 修改日期: 24/12/17
 */
class EditModel extends CI_Model
{
    public function get_page($pageid)
    {
        $this->db->where("page_id", $pageid);
        $query = $this->db->get("t_page");
        return $query->row_array();
    }

    public function save_page($page_id, $page_content)
    {
        $page = array("page_id" => $page_id, "page_content" => $page_content, "create_time" => time());
        $insert_status = $this->db->insert("t_page", $page);
        $affect_row = $this->db->affected_rows();
        if ($insert_status && $affect_row == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function update_page($page_id, $page_content)
    {
        $this->db->where("page_id", $page_id);
        $this->db->set(array("page_content" => $page_content, "update_time" => time()));
        $update_status = $this->db->update("t_page");
        $affect_row = $this->db->affected_rows();
        if ($update_status && $affect_row == 1) {
            return true;
        } else {
            return false;
        }
    }

}