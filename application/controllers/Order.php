<?php

/**
 * 文件名称:Order.php

 */
class Order extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->page("contact/order.html");
    }


}