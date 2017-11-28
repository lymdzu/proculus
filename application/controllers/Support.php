<?php

/**
 * 文件名称:Support.php
 * 摘    要:
 * 修改日期: 2/11/17
 */
class Support extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function software()
    {
        $this->page("support/software.html");
    }

    public function tutorials()
    {
        $this->load->model("ProductModel", "product", true);
//        $videos = $this->product->get_type_document("Video");
//        $this->product->
        $this->vars['page'] = "video";
        $this->page("support/tutorials.html");
    }

    public function faqs()
    {
        $this->vars['page'] = "faqs";
        $this->page("support/faqs.html");
    }
    public function service()
    {
        $this->vars["page"] = "service";
        $this->page("support/service.html");
    }
}