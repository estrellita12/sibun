<?php
namespace applications\controllers;

class authController extends Controller
{
    public function init()
    {
        if (_DEV) {
            $this->request["post"] = [
                "mb_id" => "test",
                "mb_passwd" => "123123",
                "mb_cellphone" => "01012341234",
            ];
        }
    }

    public function refresh()
    {
        $this->checkAuthorization("Bearer");
        $memberModel = new \applications\models\MemberModel();
        $row = $memberModel->getToken($this->token["mb_idx"]);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", $row["returnData"]);
    }

    public function loginv1()
    {
        $this->checkAuthorization("Basic");
        $request = $this->request["post"];
        $required = ["mb_id", "mb_passwd"];
        if (!check_required($required, $request)) {
            $this->error_json("002", "Required Error");
        }
        $memberModel = new \applications\models\MemberModel();
        $row = $memberModel->loginV1($request["mb_id"], $request["mb_passwd"]);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $row = $memberModel->getToken($row["returnData"]["mb_idx"]);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", $row["returnData"]);
    }

    public function loginv2()
    {
        $this->checkAuthorization("Basic");
        $request = $this->request["post"];
        $required = ["mb_cellphone"];
        if (!check_required($required, $request)) {
            $this->response_json("002");
        }
        $request["mb_cellphone"] = only_number($request["mb_cellphone"]);

        if (
            !isset($_SESSION["auth_check"]) ||
            $_SESSION["auth_check"] != true ||
            !isset($_SESSION["receiver_cellphone"]) ||
            $_SESSION["receiver_cellphone"] != $request["mb_cellphone"]
        ) {
            $this->error_json("007", "The code is not valid");
        }

        $memberModel = new \applications\models\MemberModel();
        $row = $memberModel->loginV2($request["mb_cellphone"]);
        if ($row["returnCode"] != "000") {
            $this->response_json($row["returnCode"]);
        }

        $row = $memberModel->getToken($row["returnData"]["mb_idx"]);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", $row["returnData"]);
    }
}
?>
