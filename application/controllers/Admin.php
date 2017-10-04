<?php

/**
 * 文件名称:Admin.php
 * 摘    要:
 * 修改日期: 2017/10/3
 */
class Admin extends AdController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->model("UserModel", "user", true);
        $admin_list = $this->user->get_admin_list();
        $this->vars['admin_list'] = $admin_list;
        $this->page("admin/index.html");
    }

    public function add_user()
    {
        $username = $this->input->post("user-name", true);
        $password = $this->input->post("password", true);
        $repwd = $this->input->post("repwd", true);
        if (trim($password) != trim($repwd)) {
            $this->json_result(PARAMETER_WRONG, "", "twice password are not same");
        }
        $this->load->model("UserModel", "user", true);
        $salt = mt_rand(100000, 999999);
        $newpwd = md5(trim($password . $salt));
        $insert_status = $this->user->insert_user($username, $newpwd, $salt);
        if ($insert_status) {
            $this->json_result(REQUEST_SUCCESS, "add admin success");
        } else {
            $this->json_result(API_ERROR, "", "server wrong");
        }
    }

    public function operate_admin()
    {
        $id = $this->input->post("id", true);
        $status = $this->input->post("status", true);
        $reverse_status = $status == 1 ? 0 : 1;
        $this->load->model("UserModel", "user", true);
        $opt_res = $this->user->operate_user(trim($id), $reverse_status);
        if ($opt_res) {
            $this->json_result(REQUEST_SUCCESS, "success");
        } else {
            $this->json_result(API_ERROR, "", "server wrong");
        }
    }
}