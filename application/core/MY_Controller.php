<?php

/**
 * 文件名称:MY_Controller.php
 * 摘    要:
 * 修改日期: 26/8/17
 */
class PublicController extends CI_Controller
{
    public $layout;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('smarty/Smarty');
        $this->load->library('session');
        $this->smarty->setCompileDir(APPPATH . '/cache/tpl');
        $this->smarty->setTemplateDir(VIEWPATH);
        $this->smarty->left_delimiter = '{{';
        $this->smarty->right_delimiter = '}}';
        $this->smarty->registerPlugin('function', 'site_url', array($this, 'smarty_modifier_site_url'));
        $this->smarty->registerPlugin('function', 'base_url', array($this, 'smarty_modifier_base_url'));
        $this->smarty->registerPlugin('modifier', 'site_url', array($this, 'smarty_modifier_site_url'));
        $this->smarty->registerPlugin('modifier', 'base_url', array($this, 'smarty_modifier_base_url'));
        $this->vars['title'] = "proculus";
    }

    /**
     * 显示页面
     * @param $view
     */
    public function display($view)
    {
        echo $this->fetch($view);
        exit();
    }

    /**
     * 加载页面
     * @param $view
     */
    public function page($view)
    {
        $this->vars['__PAGE__'] = $view;
        if ((isset($_SESSION['message']) && $_SESSION['message']) && !$this->vars['message']) {

            $this->vars['error_message'] = $_SESSION['message'] . '';
            $this->vars['message_code'] = intval($_SESSION['message_code']);
            unset($_SESSION['message_code']);
            unset($_SESSION['message']);
        }
        $this->display($this->layout);
        exit();
    }

    public function fetch($view)
    {
        $this->vars['BASE_URL'] = $this->config->base_url();
        $this->vars['SITE_URL'] = $this->config->site_url();
        foreach ($this->vars as $k => $v) {
            $this->smarty->assign($k, $v);
        }
        return $this->smarty->fetch($view);
    }

    public function smarty_modifier_base_url($s)
    {
        return $this->config->base_url($s);
    }


    public function smarty_modifier_site_url($s)
    {
        return $this->config->site_url($s);
    }

    /**
     * 格式化json输出
     * @param $code
     * @param $result
     * @param $emsg
     */
    public function json_result($code, $result, $emsg = "")
    {
        ob_end_clean();
        $result = array(
            'ecode'  => intval($code),
            'result' => $result,
            'emsg'   => $emsg
        );
        header('Content-Type: text/json;charset=utf8');
        echo json_encode($result);
        exit();
    }
}

class DashboardController extends PublicController
{
    public function __construct()
    {
        parent::__construct();
        $this->layout = "dashboard/layout.html";
    }
}

class AdController extends PublicController
{
    public function __construct()
    {
        parent::__construct();
        $this->layout = "admin/layout.html";
        $admin_session = $this->session->userdata("admin_user");
        if (empty($admin_session)) {
            redirect("passport/admin_login", 'auto', 302);
        } else {
            $this->layout = "admin/layout.html";
        }
    }
}