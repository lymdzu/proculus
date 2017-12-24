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
        $this->vars['page'] = "order_buy";
        $this->load->model("PageModel", "page", true);
        $faqs = $this->page->order_list();
        $this->vars['orders_list'] = $faqs;
        $this->page("contact/order.html");
    }


}