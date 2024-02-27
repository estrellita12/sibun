<?php
namespace common\controllers;

use \Exception;

abstract class ApiController extends \common\controllers\Controller
{
    public $request;
    public $request_method;
    public $method;
    protected $secretKey;
    protected $token;
    public function __construct($params)
    {
        try {
            parent::__construct($params);

            if (!empty($_REQUEST["secret_key"])) {
                $this->secretKey = $_REQUEST["secret_key"];
                unset($_REQUEST["secret_key"]);
                unset($_GET["secret_key"]);
            }

            unset($_REQUEST["url"]);
            unset($_GET["url"]);
            $this->request = [
                "get" => [],
                "post" => [],
                "put" => [],
                "delete" => [],
            ];

            switch ($_SERVER["REQUEST_METHOD"]) {
                case "GET":
                case "get":
                    $this->request_method = "get";
                    $this->request["get"] = $_GET;
                    if (
                        (!empty($this->params["ident"]) &&
                            empty($this->params["store"])) ||
                        !empty($this->params["subident"])
                    ) {
                        $this->method = "get";
                    } else {
                        $this->method = "getList";
                    }
                    break;
                case "POST":
                case "post":
                    $this->request_method = "post";
                    //$this->request["post"] = $_POST;
                    $this->request["post"] = json_decode(
                        file_get_contents("php://input"),
                        true
                    );
                    $this->method = "set";
                    break;
                case "PUT":
                case "put":
                    $this->request_method = "put";
                    $this->request["put"] = json_decode(
                        file_get_contents("php://input"),
                        true
                    );
                    $this->method = "add";
                    break;
                case "DELETE":
                case "delete":
                    $this->request_method = "delete";
                    $this->request["delete"] = json_decode(
                        file_get_contents("php://input"),
                        true
                    );
                    $this->method = "remove";
                    break;
                default:
                    $this->response_json("404");
            }
            if (empty($this->params["store"])) {
                if (method_exists($this, $this->params["ident"])) {
                    $this->method = $this->params["ident"];
                }
            }
            if (method_exists($this, $this->params["subident"])) {
                $this->method = $this->params["subident"];
            }

            $this->init();
            $method = $this->method;
            if (method_exists($this, $method)) {
                $this->$method();
            } else {
                throw new \common\extend\MyException(["404", "Not Found"]);
            }
        } catch (Exception $e) {
            $errorCode = $e->errorCode ? $e->errorCode : "500";
            $this->error_json($errorCode, $e->getMessage());
        }
    }

    protected function error_json($code, $msg = "")
    {
        $arr = ["result" => $code, "error" => $msg];
        echo json_encode($arr);
        if (_DEV || $_SERVER["REMOTE_ADDR"] == "58.231.24.148") {
            debug_log(static::class, $code, [
                "ERROR_MESSAGE" => $msg,
                "\$_REQUEST" => $_REQUEST,
                "\$this->request" => $this->request,
                "\$_SERVER" => $_SERVER,
            ]);
        }
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

    abstract protected function init();
}
?>
