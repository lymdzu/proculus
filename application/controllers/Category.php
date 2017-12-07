<?php

/**
 * 文件名称:Category.php
 * 摘    要:
 * 修改日期: 21/11/17
 */
class Category extends AdController
{
    public function __construct()
    {
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
        $page = $this->input->get("page");
        $offset = empty($page) ? 0 : (intval($page) - 1) * PAGESIZE;
        $total = $this->product->count_product_list();
        $product_list = $this->product->get_product_list($offset, PAGESIZE);
        log_message("info", "productlist, resutl:" . json_encode($product_list));
        $this->vars['product_list'] = $product_list;
        $this->vars['category_list'] = $category;
        $this->load->library("tgpage", array('total' => $total, 'pagesize' => PAGESIZE));
        $this->vars['pagelist'] = $this->tgpage->showpage();
        $this->page("product/product.html");
    }

    public function add_product()
    {
        $this->vars['nav'] = "news";
        $this->vars['page'] = "add_product";
        $type_list = $this->config->item("prop");
        $select = array();
        foreach ($type_list as $proporty => $item) {
            $cate = $this->product->get_property_list($proporty);
            $select[$proporty] = $cate;
        }
        $this->vars['category'] = $select;
        $this->page("product/add_product.html");
    }

    public function save_prodcut()
    {
        $name = $this->input->post("name", true);
        $product_pic = $this->input->post("product-pic", true);
        $partnum = $this->input->post("Part_Number", true);
        $size = $this->input->post("Size", true);
        $catelog = $this->input->post("Catalog", true);
        $resolution = $this->input->post("Resolution(pixel)", true);
        $bright = $this->input->post("Brightness(nits)", true);
        $interface = $this->input->post("Interface", true);
        $input = $this->input->post("Input_Voltage", true);
        $working = $this->input->post("Working_Platform", true);
        $download = $this->input->post("Download_Methond", true);
        $cable = $this->input->post("Cable_Connector", true);
        $datasheet = $this->input->post("DataSheet", true);
        $driver = $this->input->post("Driver", true);
        $extras = $this->input->post("Extras", true);
        if (empty($name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please enter product name");
        }
        if (empty($product_pic)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please enter product picture");
        }
        $search_keys = $this->config->item("prop");
        foreach ($_POST as $postkey => $postvalue) {
            if (in_array($postkey, $search_keys) && $search_keys[$postkey]) {
                if (empty($postvalue)) {
                    $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please select " . $postkey);
                }
            }
        }
        $product = array(
            "name"             => $name,
            "Part_Number"      => $partnum,
            "Size"             => $size,
            "Catalog"          => $catelog,
            "Resolution"       => $resolution,
            "Brightness"       => $bright,
            "Interface"        => $interface,
            "Input_Voltage"    => $input,
            "Working_Platform" => $working,
            "Download_Method"  => $download,
            "Cable_Connector"  => $cable,
            "DataSheet"        => implode(";", $datasheet),
            "Driver"           => implode(";", $driver),
            "Extras"           => implode(";", $extras),
            "pic"              => $product_pic,
            "create_time"      => time(),
            "status"           => 1
        );
        $insert_status = $this->product->insert_product($product);
        if ($insert_status) {
            $this->json_result(REQUEST_SUCCESS, "Add Success");
        } else {
            $this->json_result(API_ERROR, "", "Server Error");
        }
    }

    public function delete_product()
    {
        $id = $this->input->post('id');
        $status = $this->product->delete_product($id);
        if ($status) {
            $this->json_result(REQUEST_SUCCESS, "DELETE Success");
        } else {
            $this->json_result(API_ERROR, "", "Server Error");
        }
    }


}