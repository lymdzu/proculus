<?php
/**
 * 文件名称:Passport.php
 * 摘    要:
 * 修改日期: 2017/10/3
 */
class Passport extends PublicController
{
    public function __construct() {
        parent::__construct();
    }
    public function admin_login()
    {
        $this->display("passport/admin_login.html");
    }
}