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
        $this->vars['max_id'] = $max_new['id'];
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
        $comments = $this->new->get_new_comments($id);
        foreach ($comments as $key => $comment) {
            $identicon = new \Identicon\Identicon();
            $imageDataUri = $identicon->getImageDataUri($comment['name']);
            $comment['pic'] = $imageDataUri;
            $comments[$key] = $comment;
        }
        $this->vars['comment_num'] = count($comments);
        $this->vars['comments'] = $comments;
        $this->vars['desc'] = $news_desc;
        $this->page('news/desc.html');
    }

    public function save_comments()
    {
        $new_id = $this->input->post("news_id", true);
        $name = $this->input->post("name", true);
        $email = $this->input->post("email", true);
        $website = $this->input->post("website", true);
        $message = $this->input->post("message", true);
        if (empty($name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please enter your name");
        }
        if (empty($email)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please enter your email");
        }
        if (empty($website)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please enter your website");
        }
        if (empty($message)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please enter your comment");
        }
        $comments = array("new_id" => $new_id, "name" => $name, "email" => $email, "website" => $website, "message" => $message, "create_time" => time());
        $this->load->model("ContactModel", "contact", true);
        $status = $this->contact->leave_comments($comments);
        if ($status) {
            $this->json_result(REQUEST_SUCCESS, "Save Success, Thanks for your comments");
        } else {
            $this->json_result(API_ERROR, "", "Something maybe wrong");
        }
    }

    public function lists()
    {
        $this->load->model("NewModel", "new", true);
        $newlist = $this->new->get_news();
        foreach ($newlist as $key => $new) {
            $new['content'] = mb_substr(strip_tags($new['content']), 0, 300) . "...";
            $newlist[$key] = $new;
        }
        $this->vars['newslist'] = $newlist;
        $this->page('news/lists.html');
    }
}