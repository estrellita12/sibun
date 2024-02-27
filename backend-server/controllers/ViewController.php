<?php
namespace common\controllers;

use \Exception;

abstract class ViewController extends \common\controllers\Controller
{
    protected $filename;
    protected $contents;
    protected $row;
    public function __construct($params)
    {
        try {
            parent::__construct($params);
            $this->filename = "aside";

            $this->init();
            $method = $this->params["page"];
            if (method_exists($this, $method)) {
                $this->result = $this->$method();
            }
            $this->content();
        } catch (Exception $e) {
            $this->error_json($e->errorCode, $e->getMessage());
        }
    }
    abstract protected function init();

    protected function content()
    {
        $path =
            _APPLICATIONS .
            "/views/{$this->params["controller"]}/{$this->params["page"]}";
        $this->contents = $path;

        $path = _APPLICATIONS . "/views/" . $this->filename . ".html";
        if (file_exists($path)) {
            require_once $path;
        }
    }

    /*
    protected function error_json($code, $msg = "")
    {
        $arr = ["result" => $code, "error" => $msg];
        echo json_encode($arr);
        debug_log(static::class, $code, [
            "error" => $msg,
            "request" => $_REQUEST,
        ]);
        exit();
    }

    protected function response_json($result, $data = [])
    {
        $arr = ["result" => (string) $result];
        foreach ($data as $key => $value) {
            $arr[$key] = $value;
        }
        echo json_encode($arr);
        exit();
    }
    */

    protected function jsAccess($msg, $url = "access")
    {
        $str = "<script>";
        $str .= "alert('" . $msg . "');";
        switch ($url) {
            case "back":
                $str .= "history.back();";
                break;
            case "close":
                $str .= "window.opener.location.reload();";
                $str .= "window.close();";
                break;
            default:
                $str .= "location.href='{$url}'";
                break;
        }
        $str .= "</script>";
        echo $str;
    }
}
?>
