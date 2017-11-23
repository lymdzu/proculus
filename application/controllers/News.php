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
        $id = $this->input->get("id");
        $this->load->model("NewModel", "new", true);
        $news_desc = $this->new->get_new_desc(trim($id));
        $max_new = $this->new->max_new_id();
        $this->vars['max_id'] =$max_new['id'];
        if ($news_desc['pic']) {
            $this->vars['news_pic'] = explode(";", $news_desc['pic']);
        } else {
            $this->vars['news_pic'] = "";
        }
        if ($news_desc['keywords']) {
            $keywords = $this->new->get_keywords($news_desc['keywords']);
            $this->vars['keywords'] = $keywords;
        } else {
            $this->vars['keywords'] = "";
        }
        $this->vars['desc'] = $news_desc;
        $this->page('news/desc.html');
    }

    public function lists()
    {
        $this->load->model("NewModel", "new", true);
        $newlist = $this->new->get_news();
        foreach($newlist as $key => $new)
        {
            $new['content'] = mb_substr(strip_tags($new['content']), 0, 300) . "...";
            $newlist[$key] = $new;
        }
        $this->vars['newslist'] = $newlist;
        $this->page('news/lists.html');
    }
}