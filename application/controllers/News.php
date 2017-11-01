<?php

/**
 * 文件名称:News.php
 * 摘    要:
 * 修改日期: 28/10/17
 */
class News extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
        $this->vars['page'] = "news";
    }

    public function desc()
    {
        $this->page('news/desc.html');
    }
    public function lists()
    {
        $this->page('news/lists.html');
    }
}