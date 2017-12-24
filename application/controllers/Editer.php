<?php

/**
 * Class Editer
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
        $this->vars['page'] = "page";
        $this->load->model("PageModel", "page", true);
        $faqs = $this->page->faq_list();
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

    public function demo()
    {
        $this->load->model("EditModel", "edit", true);
        $page = $this->edit->get_page("demo");
        $this->vars['demo'] = json_decode($page['page_content'], true);
        $this->vars['nav'] = "page";
        $this->vars['page'] = "editdemo";
        $this->page("editer/demo.html");
    }

    public function save_demo()
    {
        $page_id = $this->input->post("page_id");
        $first_title = $this->input->post("first_title");
        $first_down = $this->input->post("first_down");
        $first_content = $this->input->post("first_content");
        $second_title = $this->input->post("second_title");
        $second_down = $this->input->post("second_down");
        $second_content = $this->input->post("second_content");
        $third_title = $this->input->post("third_title");
        $third_down = $this->input->post("third_down");
        $third_content = $this->input->post("third_content");
        $demo = json_encode(array(
                "first_title"    => $first_title,
                "first_down"     => $first_down,
                "first_content"  => $first_content,
                "second_title"   => $second_title,
                "second_down"    => $second_down,
                "second_content" => $second_content,
                "third_title"    => $third_title,
                "third_down"     => $third_down,
                "third_content"  => $third_content
            )
        );
        $this->load->model("EditModel", "edit", true);
        $page = $this->edit->get_page($page_id);
        if ($page) {
            $status = $this->edit->update_page($page_id, $demo);
        } else {
            $status = $this->edit->save_page($page_id, $demo);
        }
        if ($status) {
            $this->json_result(REQUEST_SUCCESS, "Save Success");
        } else {
            $this->json_result(API_ERROR, "", "Some thing may be wrong");
        }
    }
    public function order(){
        $this->vars['nav'] = "page";
        $this->vars['page'] = "editorder";
        $this->load->model("PageModel", "page", true);
        $faqs = $this->page->order_list();
        $this->vars['orders_list'] = $faqs;
        $this->page("editer/order-edit.html");
    }
    public function order_html()
    {
        $this->page("editer/editer-order.html");
    }
    public function order_show()
    {
        $id = $this->input->get("id");
        $this->load->model("PageModel", "page", true);
        $order = $this->page->get_order_by_id($id);
        $this->vars['order'] = $order;
        $this->page("editer/editer-order_html.html");
    }
    public function save_order()
    {
        $title = $this->input->post("title");
        $price = $this->input->post("price");
        $img = $this->input->post("img");
        $description = $this->input->post("description");
        $this->load->model("PageModel", "page", true);
        $save_status = $this->page->save_order($title, $price, $img, $description);
        if ($save_status) {
            $this->json_result(REQUEST_SUCCESS, "SAVE Success");
        } else {
            $this->json_result(API_ERROR, "", "Something maybe wrong");
        }
    }

    public function delete_order()
    {
        $id = $this->input->post("order_id");
        $this->load->model("PageModel", "page", true);
        $save_status = $this->page->delete_order($id);
        if ($save_status) {
            $this->json_result(REQUEST_SUCCESS, "DELETE Success");
        } else {
            $this->json_result(API_ERROR, "", "Something maybe wrong");
        }
    }

    public function edit_order()
    {
        $id = $this->input->post("order_id");
        $title = $this->input->post("title");
        $price = $this->input->post("price");
        $img = $this->input->post("img");
        $description = $this->input->post("description");
        $this->load->model("PageModel", "page", true);
        $save_status = $this->page->edit_order($id, $title, $price, $img, $description);
        if ($save_status) {
            $this->json_result(REQUEST_SUCCESS, "EDIT Success");
        } else {
            $this->json_result(API_ERROR, "", "Something maybe wrong");
        }
    }
}