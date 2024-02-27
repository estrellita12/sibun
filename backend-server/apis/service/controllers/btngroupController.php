<?php
namespace applications\controllers;

use Exception;

class btngroupController extends Controller
{
    public function init()
    {
    }

    public function getList()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["get"];

        $model = new \applications\models\BtnGroupModel();
        $col = get_alias(
            $model->alias,
            ["btn_grp_idx", "btn_grp_title", "btn_grp_color"],
            true
        );
        $search = $model->getSearch($request);
        $search["btn_grp_by_mb_idx"] = $this->token["mb_idx"];
        $row = $model->getList($col, $search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["list" => $row["returnData"]]);
    }

    public function get()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["get"];

        $model = new \applications\models\BtnGroupModel();
        $col = get_alias($model->alias);
        $search = [];
        $search["btn_grp_idx"] = $this->params["ident"];
        $search["btn_grp_by_mb_idx"] = $this->token["mb_idx"];
        $row = $model->get($col, $search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function add()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["put"];
        $required = ["mb_id", "mb_passwd", "mb_cellphone"];
        if (!check_required($required, $request)) {
            $this->response_json("002");
        }
        $model = new \applications\models\BtnGroupModel();
        $value = [];
        $value = $request;
        $value["btn_grp_by_mb_idx"] = $this->token["mb_idx"];
        $row = $model->add($value);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }

        $this->response_json("000", [
            "data" => ["insertId" => $model->pdo->lastInsertId()],
        ]);
    }

    public function set()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["post"];

        $model = new \applications\models\BtnGroupModel();
        $search = [];
        $search["btn_grp_idx"] = $this->param["ident"];
        $search["btn_grp_by_mb_idx"] = $this->token["mb_idx"];
        $value = [];
        $value = $request;
        $row = $model->set($value, $search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function remove()
    {
        $this->checkAuthorization("Bearer");

        $model = new \applications\models\BtnGroupModel();
        $search = [];
        $search["btn_grp_idx"] = $this->param["ident"];
        $search["btn_grp_by_mb_idx"] = $this->token["mb_idx"];
        $row = $model->remove($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }
}
?>
