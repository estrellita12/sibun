<?php
namespace common\controllers;

use \Exception;

abstract class ViewController extends \common\controllers\Controller
{
    public $method;
    public $result;
    public $header;
    public $page;
    public $footer;
    public function __construct($params)
    {
        try {
            parent::__construct($params);
            $this->filetype = "asdie";

            $this->init();
            $method = $this->params["page"];
            if (method_exists($this, $method)) {
                $this->result = $this->$method();
            }
            $this->contents();
        } catch (Exception $e) {
            $this->error_json($e->errorCode, $e->getMessage());
        }
    }
    abstract protected function init();
    protected function header()
    {
        $path = _APPLICATIONS . "/views/" . $this->header . ".php";
        if (!empty($this->header)) {
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }

    protected function content()
    {
        $path =
            _APPLICATIONS .
            "/views/{$this->params["controller"]}/{$this->page}.html";
        if (file_exists($path)) {
            require_once $path;
        }
    }

    protected function footer()
    {
        if (!empty($this->footer)) {
            $path = _APPLICATIONS . "/views/" . $this->footer . ".php";
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }

    protected function contents()
    {
        $path =
            _APPLICATIONS .
            "/views/{$this->params["controller"]}/{$this->page}.html";
        if (file_exists($path)) {
            $this->path = $path;
        }

        $pagepath = _APPLICATIONS . "/views/" . $this->header . ".html";
        require_once $pagepath;
    }

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
}
?>
