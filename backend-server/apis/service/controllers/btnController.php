<?php
namespace applications\controllers;

class btnController extends Controller
{
    public function init()
    {
    }

    public function getList()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["get"];

        $model = new \applications\models\BtnModel();
        $col = get_alias(
            $model->alias,
            ["btn_idx", "btn_by_grp_idx", "btn_title"],
            true
        );
        $search = $model->getSearch($request);
        $search["btn_grp_by_mb_idx"] = $this->token["mb_idx"];
        $type = "";
        if (!empty($request["type"])) {
            $type = "group";
            $col = $request["type"] . "," . $col;
        }
        $row = $model->getList($col, $search, $type);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["list" => $row["returnData"]]);
    }

    public function get()
    {
        $this->checkAuthorization("Bearer");
        //$request = $this->request['get'];

        $model = new \applications\models\BtnModel();
        $col = get_alias($model->alias);
        $search = [];
        $search["btn_idx"] = $this->params["ident"];
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

        $model = new \applications\models\BtnModel();
        $value = [];
        $value = $request;
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

        $model = new \applications\models\BtnModel();
        $row = $model->ownCheck($this->params["ident"], $this->token["mb_idx"]);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }

        $search = [];
        $search["btn_idx"] = $this->params["ident"];
        $value = [];
        $value = $request;
        $row = $model->set($search, $value);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function remove()
    {
        $this->checkAuthorization("Bearer");
        //$request = $this->request["delete"];

        $model = new \applications\models\BtnModel();
        $row = $model->ownCheck($this->params["ident"], $this->token["mb_idx"]);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }

        $search = [];
        $search["btn_idx"] = $this->params["ident"];
        $row = $model->remove($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }
}
?>
