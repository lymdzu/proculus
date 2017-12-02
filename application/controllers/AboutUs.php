<?php

/**
 * 文件名称:AboutUs.php

 */
class AboutUs extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->page("contact/about_us.html");
    }


}