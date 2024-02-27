<?php
namespace applications\controllers;

class userreviewController extends Controller
{
    public function init()
    {
        if (empty($this->params["ident"])) {
            $this->error_json("001", "Empty Ident");
        }
    }

    public function getList()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["get"];
        $request["review_by_mb_idx"] = $this->token["mb_idx"];
        //---------------------------------------------
        $model = new \applications\models\ReviewModel();
        $row = $model->getList($request);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json($row["returnCode"], [
            "list" => $row["returnData"],
        ]);
    }

    public function get()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["get"];
        if (empty($this->params["subident"])) {
            $this->error_json("002", "Ident Empty");
        }
        //---------------------------------------------
        $model = new \applications\models\ReviewModel();
        $search = [];
        $search["store_review_idx"] = $this->params["subident"];
        $search["store_review_by_mb_idx"] = $this->token["mb_idx"];
        $row = $model->get($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json($row["returnCode"], [
            "data" => $row["returnData"],
        ]);
    }

    public function set()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["put"];
        if (empty($request)) {
            $this->error_json("002", "Request Empty");
        }
        if (empty($this->params["subident"])) {
            $this->error_json("002", "Ident Empty");
        }
        //---------------------------------------------------
        $model = new \applications\models\ReviewModel();
        $search = [];
        $search["store_review_idx"] = $this->params["subident"];
        $search["store_review_by_mb_idx"] = $this->token["mb_idx"];
        $value = [];
        $value = $model->getValue($request, "set");
        $row = $model->set($value, $search);
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
        if (empty($this->params["subident"])) {
            $this->error_json("002", "Ident Empty");
        }
        //---------------------------------------------------
        $model = new \applications\models\ReviewModel();
        $search = [];
        $search["store_review_idx"] = $this->params["subident"];
        $search["store_review_by_mb_idx"] = $this->token["mb_idx"];
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
