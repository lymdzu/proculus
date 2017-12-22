<?php
/**
 * 文件名称:Editer.php
 * 摘    要:
 * 修改日期: 22/12/17
 * 作    者:
 */
class Editer extends AdController
{
    public function __construct()
    {
        parent::__construct();
    }


    public function faq()
    {
        $this->vars['nav'] = "page";
        $this->vars['page']="page";
        $this->load->model("PageModel", "page", true);
        $faqs= $this->page->faq_list();
        $this->vars['faqs_list'] = $faqs;
        $this->page("editer/faq.html");
    }

    public function save_faq()
    {
        $question = $this->input->post("title");
        $answer = $this->input->post("answer");
        $this->load->model("PageModel", "page", true);
        $save_status = $this->page->save_faq($question, $answer);
        if ($save_status) {
            $this->json_result(REQUEST_SUCCESS, "SAVE Success");
        } else {
            $this->json_result(API_ERROR, "", "Something maybe wrong");
        }
    }
    public function delete_faq()
    {
        $id = $this->input->post("faq_id");
        $this->load->model("PageModel", "page", true);
        $save_status = $this->page->delete_faq($id);
        if ($save_status) {
            $this->json_result(REQUEST_SUCCESS, "DELETE Success");
        } else {
            $this->json_result(API_ERROR, "", "Something maybe wrong");
        }
    }
    public function edit_faq()
    {
        $id = $this->input->post("faq_id");
        $question = $this->input->post("title");
        $answer = $this->input->post("answer");
        $this->load->model("PageModel", "page", true);
        $save_status = $this->page->edit_faq($id, $question, $answer);
        if ($save_status) {
            $this->json_result(REQUEST_SUCCESS, "EDIT Success");
        } else {
            $this->json_result(API_ERROR, "", "Something maybe wrong");
        }
    }
}