<?php

/**
 * 文件名称:NewModel.php
 * 摘    要:
 * 修改日期: 21/11/17
 */
class NewModel extends CI_Model
{
    public function create_new($title, $keywords, $content, $pic, $creater)
    {
        $this->db->trans_start();
        $time = time();
        $news = array("title" => $title, "pic" => $pic, "content" => $content, "status" => 2, "create_time" => $time, "creater" => $creater);
        $this->db->insert("t_news", $news);
        $new_id = $this->db->insert_id();
        foreach ($keywords as $keyword) {
            $this->db->insert("t_keywords", array("keyword" => $keyword, "new_id" => $new_id, "create_time" => $time));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function get_new_desc($id)
    {
        $this->db->where("id", $id);
        $query = $this->db->get("t_news");
        return $query->row_array();
    }

    public function get_news()
    {
        $query = $this->db->get("t_news");
        return $query->result_array();
    }

    public function get_keywords($new_id)
    {
        $this->db->where_in("new_id", $new_id);
        $query = $this->db->get("t_keywords");
        return $query->result_array();
    }

    public function max_new_id()
    {
        $this->db->select_max("id");
        $query = $this->db->get("t_news");
        return $query->row_array();
    }

    public function delete_new($id)
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->delete("t_news");
        $this->db->where("new_id", $id);
        $this->db->delete("t_keywords");
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }
    public function delete_comment($id)
    {
        $this->db->where('id', $id);
        $status = $this->db->delete("t_comments");
        $affected_row = $this->db->affected_rows();
        if ($status && $affected_row == 1) {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function random_keyword()
    {
        $this->db->group_by("keyword");
        $this->db->limit(5);
        $query = $this->db->get("t_keywords");
        return $query->result_array();
    }
    public function get_new_by_tag($keyword)
    {
        $this->db->where("k.keyword", $keyword);
        $this->db->from("t_keywords k")->join("t_news n", "n.id=k.new_id", "left");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_new_comments($new_id)
    {
        $this->db->where("new_id", $new_id);
        $query = $this->db->get("t_comments");
        return $query->result_array();
    }
    public function get_subscribe()
    {
        $this->db->order_by("create_time", "desc");
        $query = $this->db->get("t_subscribe");
        return $query->result_array();
    }
    public function get_comment()
    {
        $this->db->order_by("create_time", "desc");
        $query = $this->db->get("t_comments");
        return $query->result_array();
    }
    public function get_contact()
    {
        $this->db->order_by("create_time", "desc");
        $query = $this->db->get("t_contact");
        return $query->result_array();
    }
}