<?php

/**
 * 文件名称:ProductModel.php
 * 摘    要:
 * 修改日期: 27/10/17
 */
class ProductModel extends CI_Model
{
    public function insert_upload($file, $size, $filetype)
    {
        $insert_status = $this->db->insert("t_upload", array("filename" => $file, "filetype" => $filetype, "size" => $size, "create_time" => time()));
        $affected_rows = $this->db->affected_rows();
        if ($insert_status && $affected_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function get_upload_list($offset, $limit, $filetype)
    {
        if ($filetype) {
            $this->db->where("filetype", $filetype);
        }
        $this->db->limit($limit, $offset);
        $this->db->order_by("create_time", "desc");
        $query = $this->db->get("t_upload");
        return $query->result_array();
    }

    public function count_upload_list($filetype = false)
    {
        if ($filetype) {
            $this->db->where("filetype", $filetype);
        }
        $this->db->from("t_upload");
        return $this->db->count_all_results();
    }

    /**
     * 根据id获取上传记录
     * @param $id
     * @return mixed
     */
    public function get_upload_by_id($id)
    {
        $this->db->where("id", $id);
        $query = $this->db->get("t_upload");
        return $query->row_array();
    }

    /**
     * 根据id删除上传记录
     * @param $id
     * @return bool
     */
    public function delete_upload($id)
    {
        $this->db->where("id", $id);
        $delete_status = $this->db->delete("t_upload");
        $affected_row = $this->db->affected_rows();
        if ($delete_status && $affected_row == 1) {
            return true;
        } else {
            return false;
        }
    }
}
