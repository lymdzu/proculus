<?php
/**
 * 文件名称:Contact.php
 * 摘    要:
 * 修改日期: 18/10/17
 */
class Contact extends DashboardController
{
    public function __construct() {
        parent::__construct();
    }
    public function index()
    {
        $this->page("contact/contact.html");
    }
}