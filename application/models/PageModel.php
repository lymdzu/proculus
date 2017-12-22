<?php

/**
 * 文件名称:PageModel.php
 * 摘    要:
 * 修改日期: 22/12/17
 * 作    者:
 */
class PageModel extends CI_Model
{
    public function save_faq($title, $answer)
    {
        $faq = array(
            "title"       => $title,
            "answer"      => $answer,
            "create_time" => time(),
            "status"      => 1
        );
        $insert_status = $this->db->insert("t_faq", $faq);
        $affect_row = $this->db->affected_rows();
        if ($insert_status && $affect_row == 1) {
            return true;
        } else {
            return false;
        }
    }
    public function faq_list()
    {
        $this->db->where("status", 1);
        $this->db->order_by("create_time", "desc");
        $query = $this->db->get("t_faq");
        return $query->result_array();
    }

    public function delete_faq($id)
    {
        $this->db->where("id", $id);
        $this->db->set("status", 0);
        $update_staus = $this->db->update("t_faq");
        $affect_row = $this->db->affected_rows();
        if ($update_staus && $affect_row == 1) {
            return true;
        } else {
            return false;
        }
    }
    public function edit_faq($id, $title, $answer)
    {
        $faq = array("title" => $title, "answer" => $answer, "update_time" => time(), "status" => 1);
        $this->db->where("id", $id);
        $this->db->set($faq);
        $update_staus = $this->db->update("t_faq");
        $affect_row = $this->db->affected_rows();
        if ($update_staus && $affect_row == 1) {
            return true;
        } else {
            return false;
        }
    }
}