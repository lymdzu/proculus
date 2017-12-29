<?php

/**
 * Class PageModel
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

    public function save_order($title, $price, $level, $img, $description)
    {
        $order = array(
            "title"       => $title,
            "price"       => $price,
            "level"       => $level,
            "img"         => $img,
            "description" => $description,
            "create_time" => time(),
            "status"      => 1
        );
        $insert_status = $this->db->insert("t_order", $order);
        $affect_row = $this->db->affected_rows();
        if ($insert_status && $affect_row == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function order_list()
    {
        $this->db->where("status", 1);
        $this->db->order_by("create_time", "desc");
        $query = $this->db->get("t_order");
        return $query->result_array();
    }
    public function get_order_by_id($id)
    {
        $this->db->where("id", $id);
        $query = $this->db->get("t_order");
        return $query->row_array();
    }

    public function delete_order($id)
    {
        $this->db->where("id", $id);
        $this->db->set("status", 0);
        $update_staus = $this->db->update("t_order");
        $affect_row = $this->db->affected_rows();
        if ($update_staus && $affect_row == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function edit_order($id, $title, $price, $level, $img, $description)
    {
        $order = array("title" => $title, "price" => $price, "level" => $level, "img" => $img, "description" => $description, "update_time" => time(), "status" => 1);
        $this->db->where("id", $id);
        $this->db->set($order);
        $update_staus = $this->db->update("t_order");
        $affect_row = $this->db->affected_rows();
        if ($update_staus && $affect_row == 1) {
            return true;
        } else {
            return false;
        }
    }
}