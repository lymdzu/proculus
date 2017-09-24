<?php
/**
 * 文件名称:Dashboard.php
 * 摘    要:
 * 修改日期: 20/9/17
 */
class Dashboard extends PublicController
{
    public function __construct() {
        parent::__construct();
    }
    public function index()
    {
        $this->display("dashboard/dashboard.html");
    }
}