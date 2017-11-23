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

    /**
     * add category
     * @param $name
     * @param $level
     * @param $parent
     * @param $operater
     * @return bool
     */
    public function add_category($name, $level, $parent, $operater)
    {
        $insert_status = $this->db->insert("t_category", array("name" => $name, "level" => $level, "parent" => $parent, "create_time" => time(), "operater" => $operater));
        $affect_rows = $this->db->affected_rows();
        if ($insert_status && $affect_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function edit_category($id, $name)
    {
        $this->db->where("id", $id);
        $update_status = $this->db->update("t_category", array("name" => $name));
        $affect_rows = $this->db->affected_rows();
        if ($update_status && $affect_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_category($id)
    {
        $this->db->trans_start();
        $this->db->where("id", $id);
        $this->db->delete("t_category");
        $this->db->where("parent", $id);
        $query = $this->db->get("t_category");
        $children = $query->result_array();
        $this->db->where("parent", $id);
        $this->db->delete("t_category");
        if (!empty($children)) {
            $child_where = array();
            foreach ($children as $child) {
                $child_where[] = $child['id'];
            }
            $this->db->where_in("parent", $child_where);
            $this->db->delete("t_category");
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 获取商品分类列表
     * @param $level
     * @param bool $parent
     * @return mixed
     */
    public function get_category_list($level, $parent = false)
    {
        $this->db->where("level", $level);
        if ($parent) {
            $this->db->where("parent", $parent);
        }
        $query = $this->db->get("t_category");
        return $query->result_array();
    }

    public function insert_product($product, $pro_cat)
    {
        $this->db->trans_start();
        $this->db->insert("t_product", $product);
        $product_id = $this->db->insert_id();
        foreach ($pro_cat as $key => $cat) {
            $cat['product_id'] = $product_id;
            $pro_cat[$key] = $cat;
        }
        $this->db->insert_batch("t_product_type", $pro_cat);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }
    public function get_product_list()
    {
        $this->db->select("*");
        $this->db->from("t_product");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_product_cate($id)
    {
        $this->db->where("product_id", $id);
        $query = $this->db->get("t_product_type");
        return $query->result_array();
    }
}
