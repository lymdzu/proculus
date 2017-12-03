<?php

/**
 * 文件名称:Category.php
 * 摘    要:
 * 修改日期: 21/11/17
 */
class Category extends AdController
{
    public function __construct() {
        parent::__construct();
        $this->load->model("ProductModel", "product", true);
    }

    public function lists()
    {
        $this->vars['nav'] = "product";
        $this->vars['page'] = "property";
        $this->vars['category_list'] = array_keys($this->config->item("prop"));
        $this->page("product/category.html");
    }
    public function product_property()
    {
        $this->vars['nav'] = "product";
        $this->vars['page'] = "property";
        $parent = $this->input->get("parent");
        $property_list = $this->product->get_property_list($parent);
        $this->vars['parent'] = $parent;
        $this->vars['property_list'] = $property_list;
        $this->page("product/property.html");
    }
    /**
     * add new category
     */
    public function add_cate()
    {
        $name = $this->input->post("name", true);
        $parent = $this->input->post("parent", true);
        if (empty($name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please enter property name");
        }
        if (empty($parent)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Lack parant property info");
        }
        $this->load->model("ProductModel", "product", true);
        $operate = $this->session->userdata("admin_user");
        $add_status = $this->product->add_category($name, $parent, $operate);
        if ($add_status) {
            $this->json_result(REQUEST_SUCCESS, "Add Property success");
        } else {
            $this->json_result(API_ERROR, "", "Add Property Wrong");
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
        $edit_status = $this->product->edit_category($id, $name);
        if ($edit_status) {
            $this->json_result(REQUEST_SUCCESS, "Edit property success");
        } else {
            $this->json_result(API_ERROR, "", "Edit property wrong");
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

    public function product()
    {
        $this->load->model("ProductModel", "product", true);
        $category = $this->config->item("prop");
        $product_list = $this->product->get_product_list();
        foreach ($product_list as $key => $product)
        {
            $new_product = array("id" => $product['id'], "name" => $product['name'], "pic" => $product['pic']);
            $cate_list = $this->product->get_product_cate($product['id']);
            $product_cate = array();
            foreach ($cate_list as $cate)
            {
                $product_cate[$cate['cate']] = $cate['cate_val'];
            }
            $new_product = array_merge($new_product, $product_cate);
            $new_product['create_time'] = $product['create_time'];
            $product_list[$key] = $new_product;
        }
        $this->vars['product_list'] = $product_list;
        $this->vars['category_list'] = $category;
        $this->page("product/product.html");
    }

    public function add_product()
    {
        $this->vars['nav'] = "news";
        $this->vars['page'] = "add_product";
        $type_list = $this->config->item("prop");
        $select = array();
        foreach ($type_list as $item) {
            $cate = $this->product->get_property_list($item);
            $select[$item['name']] = $cate;
        }
        $this->vars['category'] = $select;
        $this->page("product/add_product.html");
    }

    public function save_prodcut()
    {
        $name = $this->input->post("name", true);
        $product_pic = $this->input->post("product-pic", true);
        $this->load->model("ProductModel", "product", true);
        if (empty($name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please enter product name");
        }
        foreach ($_POST as $key => $item) {
            if ($key == "name" || $key == "product-pic") {
                continue;
            } else {
                $cate_list[] = array("cate" => $key, "cate_val" => $item);
            }
        }
        $product = array("name" => $name, "pic" => $product_pic, "create_time" => time(), "status" => 1);
        $insert_status = $this->product->insert_product($product, $cate_list);
        if ($insert_status) {
            $this->json_result(REQUEST_SUCCESS, "Add Success");
        } else {
            $this->json_result(API_ERROR, "", "Server Error");
        }
    }




}