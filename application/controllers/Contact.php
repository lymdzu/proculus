<?php

/**
 * 文件名称:Contact.php
 * 摘    要:
 * 修改日期: 18/10/17
 */
class Contact extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->page("contact/contact.html");
    }

    public function save()
    {
        $first = $this->input->post("first_name", true);
        $last = $this->input->post("last_name", true);
        $email = $this->input->post("email", true);
        $contact_number = $this->input->post("contact_number", true);
        $contacting_department = $this->input->post("contacting_department", true);
        $message = $this->input->post("message", true);
        if (empty($first)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please enter your first name");
        }
        if (empty($last)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please enter your last name");
        }
        if (empty($email)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please enter your email");
        }
        if (empty($contact_number)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "Please enter your contact number");
        }
        $this->load->model("ContactModel", "contact", true);
        $contact = array(
            "first_name"     => $first,
            "last_name"      => $last,
            "email"          => $email,
            "contact_number" => $contact_number,
            "department"     => $contacting_department,
            "message"        => $message,
            "create_time"    => time()
        );
        $status = $this->contact->save_contact($contact);
        if ($status) {
            $this->json_result(REQUEST_SUCCESS, "Save Success! Thanks for your message");
        } else {
            $this->json_result(API_ERROR, "", "Something maybe wrong");
        }
    }
    public function subscribe()
    {
        $email = $this->input->post("email");
        $ip = $this->getIp();
        if(empty($email))
        {
            $this->json_result(LACK_REQUIRED_PARAMETER, "","Please enter your email");
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_emai');
        if ($this->form_validation->run() == FALSE)
        {
            $this->json_result(LACK_REQUIRED_PARAMETER, "","Please enter correct email");
        }
        $this->load->model("ContactModel", "contact", true);
        $subscribe = array(
            "email" => $email,
            "ip" => $ip,
            "create_time" => time()
        );
        $status = $this->contact->subscribe($subscribe);
        if ($status) {
            $this->json_result(REQUEST_SUCCESS, "Save Success! Thanks for your subscribe");
        } else {
            $this->json_result(API_ERROR, "", "Something maybe wrong");
        }
    }

    private function getIp()
    {

        if(!empty($_SERVER["HTTP_CLIENT_IP"]))
        {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        }
        else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else if(!empty($_SERVER["REMOTE_ADDR"]))
        {
            $cip = $_SERVER["REMOTE_ADDR"];
        }
        else
        {
            $cip = '';
        }
        preg_match("/[\d\.]{7,15}/", $cip, $cips);
        $cip = isset($cips[0]) ? $cips[0] : 'unknown';
        unset($cips);

        return $cip;
    }
}