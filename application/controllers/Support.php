<?php
/**
 * 文件名称:Support.php
 * 摘    要:
 * 修改日期: 2/11/17
 */
class Support extends DashboardController
{
    public function __construct() {
        parent::__construct();
    }
    public function software()
    {
        $this->page("support/software.html");
    }
}