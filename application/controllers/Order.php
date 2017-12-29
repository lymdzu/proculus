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
        foreach ($faqs as $order) {
            $total = bcadd($total, $order['price'], 3);
            if ($order['level'] == "industrial") {
                $in_order[] = $order;
            } else {
                $con_order[] = $order;
            }
        }
        $this->vars['total'] = sprintf("%.2f", $total);
        $this->vars['order_list'] = $faqs;
        $this->vars['con_count'] = count($con_order);
        $this->vars['con_order'] = $con_order;
        $this->vars['in_count'] = count($in_order);
        $this->vars['in_order'] = $in_order;
        $this->page("contact/order.html");
    }


}