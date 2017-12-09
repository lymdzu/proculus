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
        $ids = array();
        $this->db->trans_start();
        $time = time();
        foreach ($keywords as $keyword)
        {
            $this->db->query("INSERT INTO `t_keywords` (`keyword`, `use_count`, `create_time`) VALUES ('{$keyword}', '0', '{$time}') ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id), use_count=use_count+1");
            $ids[] = $this->db->insert_id();
        }
        $key_id = implode(",", $ids);
        $news = array("title" => $title, "keywords" => $key_id, "pic" => $pic, "content" => $content, "status" => 2, "create_time" => $time, "creater" => $creater);
        $this->db->insert("t_news", $news);
        $new_id = $this->db->insert_id();
        $this->db->trans_complete();
        if($this->db->trans_status() === false)
        {
            return false;
        }
        else{
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
    public function get_keywords($keyword)
    {
        $keywords = explode(",", $keyword);
        $this->db->where_in("id", $keywords);
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
        $this->db->where('id', $id);
        $delete_new = $this->db->delete("t_news");
        if ($delete_new) {
            return true;
        } else {
            return false;
        }
    }
    public function get_new_comments($new_id)
    {
        $this->db->where("new_id", $new_id);
        $query = $this->db->get("t_comments");
        return $query->result_array();
    }
}