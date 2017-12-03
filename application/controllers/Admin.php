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
        $page = $this->input->get("page");
        $offset = empty($page) ? 0 : (intval($page) - 1) * PAGESIZE;
        $total = $this->user->count_admin_list();
        $admin_list = $this->user->get_admin_list($offset, PAGESIZE);
        $this->load->library("tgpage", array('total' => $total, 'pagesize' => PAGESIZE));
        $this->vars['pagelist'] = $this->tgpage->showpage();
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

    public function news()
    {
        $this->vars['nav'] = "news";
        $this->vars['page'] = "news";
        $this->load->model("NewModel", "new", true);
        $news_list = $this->new->get_news();
        foreach ($news_list as $key => $new) {
            $news_list[$key]['key'] = $this->new->get_keywords($new['keywords']);
        }
        $this->vars['news_list'] = $news_list;
        $this->page("news/admin_list.html");
    }

    public function add_paper()
    {
        $this->vars['nav'] = "news";
        $this->vars['page'] = "add_paper";
        $this->page("news/add_new.html");
    }

    /**
     * 生成新闻
     */
    public function save_paper()
    {
        if ($this->input->method() == "post") {
            $title = $this->input->post("title");
            $keywords = $this->input->post("keyword");
            $content = $this->input->post("content");
            $pic = $this->input->post("pic");
            if (!empty($pic)) {
                $news_pic = implode(";", $pic);
            } else {
                $news_pic = "";
            }
            $keyword_lists = explode(",", $keywords);
            $creater = $this->session->userdata("admin_user");
            $this->load->model("NewModel", "new", true);
            $status = $this->new->create_new($title, $keyword_lists, $content, $news_pic, $creater);
            if ($status) {
                $this->json_result(REQUEST_SUCCESS, "SAVE SUCCESSS");
            } else {
                $this->json_result(API_ERROR, "", "SAVE WRONG");
            }
        }
    }

    public function new_pic()
    {
        $targetDir = dirname(APPPATH) . '/public/upload' . DIRECTORY_SEPARATOR . 'file_material_tmp';
        $uploadDir = dirname(APPPATH) . '/public/upload' . DIRECTORY_SEPARATOR . 'file';
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds
        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }
        // Create target dir
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir);
        }
        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }
        $oldName = $fileName;
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
        // Remove old temp files
        if ($cleanupTargetDir) {
            $dir = opendir($targetDir);
            if (!is_dir($targetDir) || !$dir) {
                $this->json_result(API_ERROR, "", "不能写入文件");
            }
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }
                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }

        // Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            $this->json_result(WRITE_FILE_ERROR, "", "Failed to open input streams");
        }
        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                $this->json_result(WRITE_FILE_ERROR, "", "Failed to open input streams");
            }
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                $this->json_result(WRITE_FILE_ERROR, "", "Failed to open input streams");
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                $this->json_result(WRITE_FILE_ERROR, "", "Failed to open input streams");
            }
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);
        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
        $done = true;
        for ($index = 0; $index < $chunks; $index++) {
            if (!file_exists("{$filePath}_{$index}.part")) {
                $done = false;
                break;
            }
        }
        if ($done) {
            $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $oldName;

            if (!$out = @fopen($uploadPath, "wb")) {
                $this->json_result(WRITE_FILE_ERROR, "", "write file failed");
            }
            if (flock($out, LOCK_EX)) {
                for ($index = 0; $index < $chunks; $index++) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }
                flock($out, LOCK_UN);
            }
            @fclose($out);
            $pathInfo = pathinfo($fileName);
            $filetype = strtoupper($pathInfo['extension']);
            $size = sprintf("%.2f", $_REQUEST['size'] / 1024 / 1024);
            $this->json_result(REQUEST_SUCCESS, $fileName);
        }
    }
}