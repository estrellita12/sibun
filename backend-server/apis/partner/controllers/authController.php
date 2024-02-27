<?php
namespace applications\controllers;

class authController extends Controller
{
    public function init()
    {
        if (_DEV) {
            $this->request["post"] = [
                "pt_cellphone" => "01011112222",
            ];
        }
    }

    public function refresh()
    {
        $this->checkAuthorization("Bearer");
        $model = new \applications\models\PartnerModel();
        $row = $model->getToken($this->token["pt_idx"]);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", $row["returnData"]);
    }

    public function loginv1()
    {
        $this->checkAuthorization("Basic");
        $request = $this->request["post"];
        $required = ["pt_id", "pt_passwd"];
        if (!check_required($required, $request)) {
            $this->error_json("002", "Required Error");
        }
        $model = new \applications\models\PartnerModel();
        $row = $model->loginV1($request["pt_id"], $request["pt_passwd"]);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $row = $memberModel->getToken($row["returnData"]["pt_idx"]);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", $row["returnData"]);
    }

    public function loginv2()
    {
        $this->checkAuthorization("Basic");
        $request = $this->request["post"];
        $required = ["pt_cellphone"];
        if (!check_required($required, $request)) {
            $this->response_json("002");
        }
        $request["pt_cellphone"] = only_number($request["pt_cellphone"]);
        /*
        if (
            !isset($_SESSION["auth_check"]) ||
            $_SESSION["auth_check"] != true ||
            !isset($_SESSION["receiver_cellphone"]) ||
            $_SESSION["receiver_cellphone"] != $request["pt_cellphone"]
        ) {
            $this->error_json("007", "The code is not valid");
        }
        */
        $model = new \applications\models\PartnerModel();
        $row = $model->loginV2($request["pt_cellphone"]);
        if ($row["returnCode"] != "000") {
            $this->response_json($row["returnCode"]);
        }

        $row = $model->getToken($row["returnData"]["pt_idx"]);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", $row["returnData"]);
    }
}
?>
