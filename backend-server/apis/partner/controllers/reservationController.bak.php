<?php
namespace applications\controllers;

class reservationController extends Controller
{
    public function init()
    {
    }

    public function getList()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["get"];
        $request["store_pt_idx"] = $this->token["pt_idx"];
        //----------------------------------------------
        $model = new \applications\models\ReservationModel();
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
        // ----------------------------------------------
        $model = new \applications\models\ReservationModel();
        $search = [];
        $search["reservation_idx"] = $this->params["ident"];
        $request["store_pt_idx"] = $this->token["pt_idx"];
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
            $this->error_json("002", "Data Empty");
        }
        // ------------------------------------------------
        $model = new \applications\models\ReservationModel();
        $value = [];
        $value = $model->getValue($request, "set");
        $row = $model->set($value, $search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function set()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["post"];
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Ident Empty");
        }
        // ------------------------------------------------
        $model = new \applications\models\ReservationModel();
        if (
            !$model->isOwnerPartnerCheck(
                $this->params["ident"],
                $this->token["pt_idx"]
            )
        ) {
            $this->error_json("005", "Permission Denied");
        }
        $search = [];
        $search["reservation_idx"] = $this->params["ident"];
        $value = [];
        $value = $model->getValue($request, "set");
        $row = $model->set($value, $search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }
}
?>
