<?php

/**
 * 文件名称:ProductModel.php
 * 摘    要:
 * 修改日期: 27/10/17
 */
class ProductModel extends CI_Model
{
    public function insert_upload($document)
    {
        $insert_status = $this->db->insert("t_upload", $document);
        $affected_rows = $this->db->affected_rows();
        if ($insert_status && $affected_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function get_upload_list($offset, $limit, $filetype = false, $category = false)
    {
        if ($filetype) {
            $this->db->where("filetype", $filetype);
        }
        if ($category) {
            $this->db->where("category", $category);
        }
        $this->db->limit($limit, $offset);
        $this->db->order_by("create_time", "desc");
        $query = $this->db->get("t_upload");
        return $query->result_array();
    }

    public function count_upload_list($filetype = false, $category = false)
    {
        if ($filetype) {
            $this->db->where("filetype", $filetype);
        }
        if ($category) {
            $this->db->where("category", $category);
        }
        $this->db->from("t_upload");
        return $this->db->count_all_results();
    }

    public function count_video_list()
    {
        $this->db->from("t_video");
        return $this->db->count_all_results();
    }

    public function get_video_list($offset, $limit)
    {
        $this->db->limit($limit, $offset);
        $this->db->order_by("create_time", "desc");
        $query = $this->db->get("t_video");
        return $query->result_array();
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
    public function get_upload_by_name($name)
    {
        $this->db->where("filename", $name);
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

    public function delete_video($id)
    {
        $this->db->where("id", $id);
        $delete_status = $this->db->delete("t_video");
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
     * @param $parent
     * @param $operater
     * @return bool
     */
    public function add_category($name, $parent, $operater)
    {
        try {
            $insert_status = $this->db->insert("t_category", array("name" => $name, "parent" => $parent, "create_time" => time(), "operater" => $operater));
            $affect_rows = $this->db->affected_rows();
            if ($insert_status && $affect_rows == 1) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
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
        $this->db->where("id", $id);
        $delete_status = $this->db->delete("t_category");
        $affect_rows = $this->db->affected_rows();
        if ($delete_status && $affect_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_product($id)
    {

        $this->db->where("id", $id);
        $delete_status = $this->db->delete("t_product");
        $affect_rows = $this->db->affected_rows();
        if ($delete_status && $affect_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function count_product_list()
    {
        $this->db->from("t_product");
        return $this->db->count_all_results();
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

    public function get_property_list($parent)
    {
        $this->db->where("parent", $parent);
        $query = $this->db->get("t_category");
        return $query->result_array();
    }

    public function insert_product($product)
    {
        $insert_status = $this->db->insert("t_product", $product);
        $affect = $this->db->affected_rows();
        if ($insert_status && $affect > 0) {
            return true;

        } else {
            return false;
        }
    }

    public function get_product_list($offset, $limit, $size = false, $catelog = false, $resolution = false, $bright = false, $interface = false, $input = false)
    {
        if ($size) {
            $this->db->where("Size", $size);
        }
        if ($catelog) {
            $this->db->where("Catalog", $size);
        }
        if ($resolution) {
            $this->db->where("Resolution", $size);
        }
        if ($bright) {
            $this->db->where("Brightness", $size);
        }
        if ($interface) {
            $this->db->where("Interface", $size);
        }
        if ($input) {
            $this->db->where("Input_Voltage", $size);
        }
        $this->db->limit($limit, $offset);
        $this->db->order_by("create_time", "desc");
        $this->db->from("t_product");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_product_desc($id)
    {
        $this->db->where("id", $id);
        $query = $this->db->get("t_product");
        return $query->row_array();

    }

    public function get_product_cate($id)
    {
        $this->db->where("product_id", $id);
        $query = $this->db->get("t_product_type");
        return $query->result_array();
    }

    public function save_video($video)
    {
        $insert_status = $this->db->insert("t_video", $video);
        $affect_rows = $this->db->affected_rows();
        if ($insert_status && $affect_rows == 1) {
            return true;
        } else {
            return false;
        }
    }
}
