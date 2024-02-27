<?php
namespace applications\controllers;

class memberController extends Controller
{
    public function init()
    {
        if (_DEV) {
            $this->request["post"] = ["mb_passwd" => "123123"];
        }
    }

    public function get()
    {
        $this->checkAuthorization("Bearer");
        $request = [];
        $request["mb_idx"] = $this->token["mb_idx"];

        $model = new \applications\models\MemberModel();
        $row = $model->get($request);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function add()
    {
        $this->checkAuthorization("Basic");
        $request = $this->request["put"];
        $required = ["mb_id", "mb_passwd", "mb_cellphone"];
        if (!check_required($required, $request)) {
            $this->error_json("002", "Data Empty");
        }

        $model = new \applications\models\MemberModel();
        $row = $model->get("count(mb_id) as cnt", [
            "mb_id" => $value["mb_id"],
        ]);
        if ($row["returnData"]["cnt"] > 0) {
            $this->error_json("003");
        }

        $value = [];
        $value = $request;
        $row = $model->add($value);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000");
    }

    public function set()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["post"];

        $model = new \applications\models\MemberModel();
        $search = [];
        $search["mb_idx"] = $this->token["mb_idx"];

        $value = [];
        $value = $request;
        $row = $model->set($value, $search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000");
    }

    public function remove()
    {
        $this->checkAuthorization("Bearer");

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
