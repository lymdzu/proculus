<?php

/**
 * 文件名称:Category.php
 * 摘    要:
 * 修改日期: 21/11/17
 */
class Category extends AdController
{
    public function lists()
    {
        $level = $this->input->get("level", true);
        $parent = $this->input->get("parent", true);
        $this->vars['parent'] = empty($parent) ? 0 : intval($parent);
        $level = empty($level) ? 1 : (intval($level) > 3 ? 3 : intval($level));
        $this->load->model("ProductModel", "product", true);
        $type_list = $this->product->get_category_list($level, $parent);
        $this->vars['level_list'] = $type_list;
        $this->vars['level'] = $level;
        $this->vars['next_level'] = $level + 1;
        $this->page("product/category.html");
    }
    public function product()
    {
        $this->page("product/product.html");
    }
    public function add_product()
    {
        $this->load->model("ProductModel", "product", true);
        $type_list = $this->product->get_category_list(1);
        $select = array();
        foreach ($type_list as $item)
        {
            $cate = $this->product->get_category_list(2, $item['id']);
            $select[$item['name']] = $cate;
        }
        $this->vars['category'] = $select;
        $this->page("product/add_product.html");
    }

    /**
     * add new category
     */
    public function add_cate()
    {
        $name = $this->input->post("name", true);
        $level = $this->input->post("level", true);
        $parent = $this->input->post("parent", true);
        if (empty($level)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please enter category level");
        }
        if ($level != 1 && empty($parent)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Lack parant category info");
        }
        $this->load->model("ProductModel", "product", true);
        $operate = $this->session->userdata("admin_user");
        $add_status = $this->product->add_category($name, $level, $parent, $operate);
        if ($add_status) {
            $this->json_result(REQUEST_SUCCESS, "Add category success");
        } else {
            $this->json_result(API_ERROR, "", "Add Wrong");
        }
    }

    /**
     * edit category
     */
    public function edit_cate()
    {
        $name = $this->input->post("name", true);
        $id = $this->input->post("id", true);
        if (empty($name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please enter category name");
        }
        if (empty($id)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Lack this category info");
        }
        $this->load->model("ProductModel", "product", true);
        $edit_status = $this->product->edit_category($id, $name);
        if ($edit_status) {
            $this->json_result(REQUEST_SUCCESS, "Edit category success");
        } else {
            $this->json_result(API_ERROR, "", "Edit wrong");
        }
    }
    /**
     * 删除当前及其下属菜单
     */
    public function delete_cate()
    {
        $id = $this->input->post("id", true);
        if (empty($id)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Lack this category info");
        }
        $this->load->model("ProductModel", "product", true);
        $edit_status = $this->product->delete_category($id);
        if ($edit_status) {
            $this->json_result(REQUEST_SUCCESS, "Delete category success");
        } else {
            $this->json_result(API_ERROR, "", "Delete wrong");
        }
    }
}