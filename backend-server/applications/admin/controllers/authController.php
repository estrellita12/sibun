<?php
namespace applications\controllers;

class authController extends \common\controllers\ViewController
{
    public function __construct($params)
    {
        try {
            parent::__construct($params);
        } catch (Exception $e) {
        }
    }

    public function init()
    {
    }

    public function index()
    {
        $this->filename = "popup";
    }

    public function login()
    {
        $this->filename = "";
        $request = $_POST;
        $model = new \common\models\AdminModel();
        $row = $model->loginV1($request["adm_id"], $request["adm_passwd"]);
        if ($row["returnCode"] == "000") {
            $data = $row["returnData"];
            $_SESSION["user_type"] = "admin";
            $_SESSION["user_idx"] = $data["adm_idx"];
            $_SESSION["user_id"] = $data["adm_id"];
            $this->jsAccess("관리자님 환영합니다.", _URL . "/main");
        } else {
            $this->jsAccess($row["returnCode"], "back");
        }
    }

    public function logout()
    {
        $this->filename = "";
        session_destroy();
        $this->jsAccess("로그아웃", _URL . "/auth/index");
    }
}
?>
