<?php

/**
 * 文件名称:UserModel.php
 * 摘    要:
 * 修改日期: 2017/10/4
 */
class UserModel extends CI_Model
{
    /**
     * 通过用户名获取用户信息
     * @param $username
     * @return mixed
     */
    public function get_user_by_name($username)
    {
        $this->db->where("username", $username);
        $query = $this->db->get("t_admin");
        return $query->row_array();
    }
    /**
     * 获取管理员列表
     * @return mixed
     */
    public function get_admin_list()
    {
        $query = $this->db->get("t_admin");
        return $query->result_array();
    }

    /**
     * 添加管理员
     * @param $username
     * @param $password
     * @param $salt
     * @return bool
     */
    public function insert_user($username, $password, $salt)
    {
        $admin_info = array(
            "username"    => $username,
            "password"    => $password,
            "salt"        => $salt,
            "create_time" => time()
        );
        $insert_status = $this->db->insert("t_admin", $admin_info);
        $affected_row = $this->db->affected_rows();
        if ($insert_status && $affected_row == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 操作用户
     * @param $id
     * @param $status
     * @return bool
     */
    public function operate_user($id, $status)
    {
        $this->db->where("id", $id);
        $upt_res = $this->db->update("t_admin", array("status" => $status));
        $affected_rows = $this->db->affected_rows();
        if ($upt_res && $affected_rows == 1) {
            return true;
        } else {
            return false;
        }
    }
}