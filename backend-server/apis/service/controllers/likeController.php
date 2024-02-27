<?php
namespace applications\controllers;

class likeController extends Controller
{
    public function init()
    {
        if (_DEV) {
            $this->request["put"] = [
                "mb_like_store_idx" => 103,
            ];
        }
    }

    public function getList()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["get"];
        $request["mb_like_by_mb_idx"] = $this->token["mb_idx"];
        // -------------------------------------------
        $model = new \applications\models\MemberLikeModel();
        $row = $model->getList($request);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", [
            "list" => $row["returnData"],
        ]);
    }

    public function get()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["get"];
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Ident Empty");
        }
        // -------------------------------------------
        $model = new \applications\models\MemberLikeModel();
        $search = [];
        $search["mb_like_by_mb_idx"] = $this->params["ident"];
        $row = $model->get($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function add()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["put"];
        if (empty($request)) {
            $this->error_json("002", "Request Empty");
        }
        $required = ["mb_like_store_idx"];
        if (!check_required($required, $request)) {
            $this->error_json("002", "Required Error");
        }
        //---------------------------------------------------
        $model = new \applications\models\MemberLikeModel();
        $value = [];
        $value = $model->getValue($request, "add");
        $value["mb_like_by_mb_idx"] = $this->token["mb_idx"];
        $row = $model->add($value);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json($row["returnCode"], [
            "data" => $row["returnData"],
        ]);
    }

    public function remove()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["delete"];
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Ident Empty");
        }
        //---------------------------------------------------
        $model = new \applications\models\MemberLikeModel();
        $search = [];
        $search["mb_like_idx"] = $this->params["ident"];
        $search["mb_like_by_mb_idx"] = $this->token["mb_idx"];
        $row = $model->remove($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json($row["returnCode"], [
            "data" => $row["returnData"],
        ]);
    }


}
?>
