<?php
namespace applications\controllers;

class userController extends Controller
{
    public function init()
    {
        if (_DEV) {
            $this->request["put"] = [
                "mb_id" => "test2222",
                "mb_passwd" => "123123",
                "mb_cellphone" => "01011112222",
                "mb_name" => "윤아",
                "mb_nickname" => "파란풍선",
                "mb_birth" => "20000110",
                "mb_gender" => "w",
                "mb_email" => "test@mwd.kr",
                "mb_marketing_yn" => "y",
            ];

            $this->request["post"] = [
                "mb_id" => "test2222",
                "mb_passwd" => "123123",
                "mb_cellphone" => "01011112222",
                "mb_name" => "미미",
                "mb_nickname" => "귀여운 하루하루",
                "mb_birth" => "20000110",
                "mb_gender" => "w",
                "mb_email" => "test@mwd.kr",
                "mb_marketing_yn" => "y",
            ];
        }
    }

    public function get()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["get"];
        // -------------------------------------------
        $model = new \applications\models\MemberModel();
        $search = [];
        $search["mb_idx"] = $this->token["mb_idx"];
        $row = $model->get($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function add()
    {
        $this->checkAuthorization("Basic");
        $request = $this->request["put"];
        if (empty($request)) {
            $this->error_json("002", "Required Error");
        }
        $required = ["mb_id", "mb_passwd", "mb_cellphone"];
        if (!check_required($required, $request)) {
            $this->error_json("002", "Required Error");
        }
        // -------------------------------------------
        $model = new \applications\models\MemberModel();
        $search = [];
        $search["mb_id"] = $request["mb_id"];
        $row = $model->cnt($search);
        if ($row["returnCode"] != "000" && $row["returnCode"] != "001") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        if ($row["returnData"]["cnt"] > 0) {
            $this->error_json("003");
        }
        $value = [];
        $value = $model->getValue($request, "add");
        $row = $model->add($value);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function set()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["post"];
        if (empty($request)) {
            $this->error_json("002", "Required Error");
        }
        // -------------------------------------------
        $model = new \applications\models\MemberModel();
        $search = [];
        $search["mb_idx"] = $this->token["mb_idx"];
        $value = [];
        $value = $model->getValue($request, "set");
        $row = $model->set($value, $search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000");
    }

    public function remove()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["delete"];
        // -------------------------------------------
        $model = new \applications\models\MemberModel();
        $search = [];
        $search["mb_idx"] = $this->token["mb_idx"];
        $row = $model->leave($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000");
    }
}
?>
