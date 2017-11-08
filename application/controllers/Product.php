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
        $this->page("product/overview.html");
    }
    public function developer_kit()
    {
        $this->page("product/developer_kit.html");
    }
}