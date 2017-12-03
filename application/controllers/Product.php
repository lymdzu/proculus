<?php

/**
 * 文件名称:Product.php
 * 摘    要:
 * 修改日期: 3/11/17
 */
class Product extends DashboardController
{
    public function overview()
    {
        $this->vars['page'] = "overview";
        $this->page("product/overview.html");
    }

    public function modules()
    {
        $size = $this->input->get("Size", true);
        $catelog = $this->input->get("Catalog", true);
        $resolution = $this->input->get("Resolution(pixel)", true);
        $bright = $this->input->get("Brightness(nits)", true);
        $interface = $this->input->get("Interface", true);
        $input = $this->input->get("Input_Voltage", true);
        $this->load->model("ProductModel", "product", true);
        $type_list = $this->config->item("prop");
        $select = array();
        foreach ($type_list as $proporty => $item) {
            if ($item) {
                $cate = $this->product->get_property_list($proporty);
                $select[$proporty] = $cate;
            }
        }
        $page = $this->input->get("page");
        $offset = empty($page) ? 0 : (intval($page) - 1) * PAGESIZE;
        $total = $this->product->count_product_list();
        $product_list = $this->product->get_product_list($offset, PAGESIZE, $size, $catelog, $resolution, $bright, $interface, $input);
        $this->vars['product_list'] = $product_list;
        $this->vars['category_list'] = $select;
        $this->load->library("tgpage", array('total' => $total, 'pagesize' => PAGESIZE));
        $this->vars['pagelist'] = $this->tgpage->showpage();
        $this->page("product/modules.html");
    }


    public function developer_kit()
    {
        $this->page("product/developer_kit.html");
    }

    public function demos()
    {
        $this->page("product/demos.html");
    }

    public function display_view()
    {
        $this->page("product/mo_desc.html");
    }

    public function unic_view()
    {
        $this->page("product/unic_view.html");
    }

    public function android()
    {
        $this->vars['page'] = "android";
        $this->page("product/android.html");
    }
}