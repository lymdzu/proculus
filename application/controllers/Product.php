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
        $this->page("product/android.html");
    }
}