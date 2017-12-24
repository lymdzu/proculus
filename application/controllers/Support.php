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
        $this->load->model("ProductModel", "video", true);
        $soft_list = $this->video->get_upload_list(0, 10000, false, "Software");
        $guide_list = $this->video->get_upload_list(0, 10000, false, "Userguide");
        $com_list = $this->video->get_upload_list(0, 10000, false, "Command Set");
        $data_list = $this->video->get_upload_list(0, 10000, false, "Datasheet");
        foreach ($soft_list as $key => $soft) {
            list($filename, $exten) = explode("." . $soft['filetype'], $soft['filename']);
            $soft_list[$key]["name"] = $filename;
        }
        foreach ($guide_list as $key => $guide) {
            list($filename, $exten) = explode("." . $guide['filetype'], $guide['filename']);
            $guide_list[$key]["name"] = $filename;
        }
        foreach ($com_list as $key => $com) {
            list($filename, $exten) = explode("." . $com['filetype'], $com['filename']);
            $com_list[$key]["name"] = $filename;
        }
        foreach ($data_list as $key => $data) {
            list($filename, $exten) = explode("." . $data['filetype'], $data['filename']);
            $data_list[$key]["name"] = $filename;
        }
        $this->vars['soft_list'] = $soft_list;
        $this->vars['guide_list'] = $guide_list;
        $this->vars['com_list'] = $com_list;
        $this->vars['data_list'] = $data_list;
        $this->page("support/software.html");
    }

    public function tutorials()
    {
        $this->load->model("ProductModel", "video", true);
        $video_list = $this->video->get_video_list(0, 10000);
        $this->vars['video_list'] = $video_list;
        $this->vars['page'] = "video";
        $this->page("support/tutorials.html");
    }

    public function faqs()
    {
        $this->vars['page'] = "faqs";
        $this->load->model("PageModel", "page", true);
        $faqs= $this->page->faq_list();
        $this->vars['faq_list'] = $faqs;
        $this->page("support/faqs.html");
    }
    public function service()
    {
        $this->vars["page"] = "service";
        $this->page("support/service.html");
    }
}