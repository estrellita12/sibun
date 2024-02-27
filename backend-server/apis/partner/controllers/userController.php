<?php
namespace applications\controllers;

class userController extends Controller
{
    public function init()
    {
        if (_DEV) {
            $this->request["put"] = [
                "pt_id" => "testpt33",
                "pt_passwd" => "123123",
                "pt_cellphone" => "01033334444",
                "pt_name" => "고길동",
                "pt_nickname" => "하얀 풍선",
            ];
            $this->request["post"] = [
                "pt_name" => "퐁키",
                "pt_nickname" => "파란 풍선",
            ];
        }
    }

    public function get()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["get"];
        // -------------------------------------------
        $model = new \applications\models\PartnerModel();
        $search = [];
        $search["pt_idx"] = $this->token["pt_idx"];
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
        $required = ["pt_id", "pt_passwd", "pt_cellphone"];
        if (!check_required($required, $request)) {
            $this->error_json("002", "Required Error");
        }
        // -------------------------------------------
        $model = new \applications\models\PartnerModel();
        $search = [];
        $search["pt_id"] = $request["pt_id"];
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
        // --------------------------------------------
        $model = new \applications\models\PartnerModel();
        $search = [];
        $search["pt_idx"] = $this->token["pt_idx"];
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
        // --------------------------------------------
        $model = new \applications\models\PartnerModel();
        $search = [];
        $search["pt_idx"] = $this->token["pt_idx"];
        $row = $model->leave($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000");
    }
}
?>
