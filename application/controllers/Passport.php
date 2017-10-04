<?php

/**
 * 文件名称:Passport.php
 * 摘    要:
 * 修改日期: 2017/10/3
 */
class Passport extends PublicController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function admin_login()
    {
        if ($this->input->method() == "post") {
            $username = $this->input->post("username", true);
            $password = $this->input->post("password", true);
            $this->load->model("UserModel", "user", true);
            $user_res = $this->user->get_user_by_name($username);
            if (empty($user_res)) {
                $this->json_result(USER_NOT_FOUND, "", "不存在此用户");
            }
            $client_pwd = md5(trim($password) . $user_res['salt']);
            if ($client_pwd == $user_res['password']) {
                $this->session->set_userdata("admin_user", $user_res['username']);
                $this->json_result(REQUEST_SUCCESS, base_url('admin/index'));
            } else {
                $this->json_result(PARAMETER_WRONG, "", "密码错误,请重新输入");
            }
        }
        $this->display("passport/admin_login.html");
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('passport/admin_login');
    }
}