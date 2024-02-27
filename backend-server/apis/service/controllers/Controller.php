<?php
namespace applications\controllers;

use \Exception;

class Controller extends \common\controllers\ApiController
{
    public function __construct($params)
    {
        try {
            $_SESSION["user_type"] = "member";
            parent::__construct($params);
        } catch (Exception $e) {
            $errorCode = $e->errorCode ? $e->errorCode : "500";
            $this->error_json($errorCode, $e->getMessage());
        }
    }

    protected function init()
    {
    }

    protected function checkAuthorization($type = "Basic")
    {
        if ($type == "Basic") {
            $this->checkBasicAuth();
        } elseif ($type == "Bearer") {
            $this->checkBearerAuth();
        } else {
            throw new \common\extend\MyException(["500", "Authorization Type Error"]);
        }
    }

    protected function checkBasicAuth()
    {
        if (_DEV) {
            $this->secretKey = "mjsuntalk";
        }
        if (empty($this->secretKey)) {
            throw new \common\extend\MyException(["010", "Secret Key Empty"]);
        }
        $model = new \applications\models\AuthClientModel();
        $row = $model->check($this->secretKey);
        if ($row["returnCode"] != "000") {
            throw new \common\extend\MyException(["010", "Secret Key Incorrect"]);
        }
    }

    protected function getAuthorization()
    {
        $headers = apache_request_headers();
        if (_DEV) {
            $model = new \applications\models\MemberModel();
            $row = $model->getToken(30);
            if ($row["returnCode"] != "000") {
                $this->error_json($row["returnCode"], $row["returnData"]);
            }
            $headers["Authorization"] = "Bearer " . $row["returnData"]["access_token"];
        }
        $authorization = "";
        if (!empty($headers["authorization"])) {
            $authorization = $headers["authorization"];
        }
        if (!empty($headers["Authorization"])) {
            $authorization = $headers["Authorization"];
        }
        if (empty($authorization)) {
            throw new \common\extend\MyException(["010", "Headers Authorization empty"]);
        }
        $authorization = explode(" ", $authorization);
        if (count($authorization) < 2 || empty($authorization[1])) {
            throw new \common\extend\MyException(["010", "Headers Authorization Token Empty"]);
        }
        $type = $authorization[0];
        $data = $authorization[1];
        return ["type" => $type, "data" => $data];
    }

    protected function checkBearerAuth()
    {
        $auth = $this->getAuthorization();
        if ($auth["type"] != "Bearer") {
            throw new \common\extend\MyException("010", "Token Type Error");
        }

        $jwt = new \common\extend\MyJwt();
        $token = $jwt->dehashing($auth["data"]);
        if ($token["returnCode"] != "000") {
            throw new \common\extend\MyException([$token["returnCode"], $token["returnData"]]);
        }
        $this->token = $token["returnData"];
        if (empty($this->token["mb_idx"])) {
            throw new \common\extend\MyException(["010", "Token mb_idx Empty"]);
        }
        $_SESSION["user_idx"] = $this->token["mb_idx"];
    }
}
?>
