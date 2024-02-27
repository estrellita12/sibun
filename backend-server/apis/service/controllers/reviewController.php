<?php
namespace applications\controllers;

class reviewController extends Controller
{
    public function init()
    {
        if (_DEV) {
            $this->request["put"] = [
                "review_reservation_idx" => 25,
                "review_store_idx" => 59,
                "review_rating" => 3,
            ];
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
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Ident Empty");
        }
        //---------------------------------------------
        $model = new \applications\models\ReviewModel();
        $search = [];
        $search["review_idx"] = $this->params["ident"];
        $search["review_by_mb_idx"] = $this->token["mb_idx"];
        $row = $model->get($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json($row["returnCode"], [
            "data" => $row["returnData"],
        ]);
    }

    public function add()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["put"];
        if (empty($request)) {
            $this->error_json("002", "Request Empty");
        }
        $required = ["review_reservation_idx", "review_rating"];
        if (!check_required($required, $request)) {
            $this->error_json("002", "Required Error");
        }

        //---------------------------------------------------
        $model = new \applications\models\ReviewModel();
        $value = [];
        $value = $model->getValue($request, "add");
        $value["review_by_mb_idx"] = $this->token["mb_idx"];
        $row = $model->add($value);
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
        $request = $this->request["post"];
        if (empty($request)) {
            $this->error_json("002", "Request Empty");
        }
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Ident Empty");
        }
        //---------------------------------------------------
        $model = new \applications\models\ReviewModel();
        $search = [];
        $search["review_idx"] = $this->params["ident"];
        $search["review_by_mb_idx"] = $this->token["mb_idx"];
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
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Ident Empty");
        }
        //---------------------------------------------------
        $model = new \applications\models\ReviewModel();
        $search = [];
        $search["review_idx"] = $this->params["ident"];
        $search["review_by_mb_idx"] = $this->token["mb_idx"];
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
