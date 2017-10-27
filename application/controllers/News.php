<?php

/**
 * 文件名称:News.php
 * 摘    要:
 * 修改日期: 28/10/17
 */
class News extends AdController
{
    public function __construct()
    {
        parent::__construct();
        $this->vars['page'] = "news";
    }

    public function index()
    {
        $this->page("news/index.html");
    }
}