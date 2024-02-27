<?php
namespace applications\controllers;

class reservationstateController extends Controller
{
    public function init()
    {
        $this->checkAuthorization("Bearer");
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Ident Empty");
        }
        $this->model = new \applications\models\ReservationModel();
        if (
            !$this->model->isOwnerPartnerCheck(
                $this->params["ident"],
                $this->token["pt_idx"]
            )
        ) {
            $this->error_json("005", "Permission Denied");
        }
    }

    public function confirm()
    {
        $request = $this->request["get"];
        //--------------------------------------------------
        $model = $this->model;
        $row = $model->confirm($this->params["ident"], $request);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function cancel()
    {
        $request = $this->request["get"];
        //--------------------------------------------------
        $model = $this->model;
        $row = $model->cancel($this->params["ident"], $request);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function noshow()
    {
        $request = $this->request["get"];
        //--------------------------------------------------
        $model = $this->model;
        $row = $model->noshow($this->params["ident"], $request);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function enter()
    {
        $request = $this->request["get"];
        //--------------------------------------------------
        $model = $this->model;
        $row = $model->enter($this->params["ident"], $request);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }
}
?>
