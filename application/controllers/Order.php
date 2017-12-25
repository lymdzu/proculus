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
        $total = 0;
        foreach($faqs as $order)
        {
            $total = bcadd($total, $order['price'], 3);
        }
        $this->vars['total'] = sprintf("%.2f", $total);
        $this->vars['order_list'] = $faqs;
        $this->page("contact/order.html");
    }


}