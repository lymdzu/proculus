<?php

/**
 * 文件名称:Upload.php
 * 摘    要:
 * 修改日期: 28/10/17
 */
class Upload extends AdController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function doc()
    {
        $this->load->model("ProductModel", "upload", true);
        if ($this->input->method() == "post") {
            $targetDir = dirname(APPPATH) . '/public/upload' . DIRECTORY_SEPARATOR . 'file_material_tmp';
            $uploadDir = dirname(APPPATH) . '/public/upload' . DIRECTORY_SEPARATOR . 'file_material';
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
                $size = sprintf("%.2f", $_REQUEST['size'] / 1024 / 1024);
                $this->upload->insert_upload($oldName, $size, "document");
                $this->json_result(REQUEST_SUCCESS, "Upload Success");
            }
        }
        $page = $this->input->get("page");
        $offset = empty($page) ? 0 : (intval($page) - 1) * PAGESIZE;
        $total = $this->upload->count_upload_list("document");
        $upload_list = $this->upload->get_upload_list("document", $offset, PAGESIZE);
        $this->load->library("tgpage", array('total' => $total, 'pagesize' => PAGESIZE));
        $this->vars['pagelist'] = $this->tgpage->showpage();
        $this->vars['upload_list'] = $upload_list;
        $this->vars['page'] = "upload";
        $this->page("upload/doc.html");
    }

    public function video()
    {
        $this->load->model("ProductModel", "upload", true);
        if ($this->input->method() == "post") {
            $targetDir = dirname(APPPATH) . '/public/upload' . DIRECTORY_SEPARATOR . 'file_material_tmp';
            $uploadDir = dirname(APPPATH) . '/public/upload' . DIRECTORY_SEPARATOR . 'file_material';
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
                $size = sprintf("%.2f", $_REQUEST['size'] / 1024 / 1024);
                $this->upload->insert_upload($oldName, $size, "video");
                $this->json_result(REQUEST_SUCCESS, "Upload Success");
            }
        }
        $page = $this->input->get("page");
        $offset = empty($page) ? 0 : (intval($page) - 1) * PAGESIZE;
        $total = $this->upload->count_upload_list("video");
        $upload_list = $this->upload->get_upload_list("video", $offset, PAGESIZE);
        $this->load->library("tgpage", array('total' => $total, 'pagesize' => PAGESIZE));
        $this->vars['pagelist'] = $this->tgpage->showpage();
        $this->vars['upload_list'] = $upload_list;
        $this->vars['page'] = "upload";
        $this->page("upload/video.html");
    }

    /**
     * 删除上传记录和文件
     */
    public function delete()
    {
        $id = $this->input->post("id");
        $this->load->model("ProductModel", "upload", true);
        $upload_file = $this->upload->get_upload_by_id($id);
        if (empty($upload_file)) {
            $this->json_result(USER_NOT_FOUND, "", "Can not find this record");
        }
        $uploadDir = dirname(APPPATH) . '/public/upload' . DIRECTORY_SEPARATOR . 'file_material';
        @unlink($uploadDir . DIRECTORY_SEPARATOR . $upload_file['filename']);
        $delete_status = $this->upload->delete_upload($id);
        if ($delete_status) {
            $this->json_result(REQUEST_SUCCESS, "Delete Success");
        } else {
            $this->json_result(API_ERROR, "", "Delete Failed");
        }
    }
}