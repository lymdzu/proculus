<?php

/**
 * 文件名称:Admin.php
 * 摘    要:
 * 修改日期: 2017/10/3
 */
class Admin extends AdController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->page("admin/index.html");
    }
}