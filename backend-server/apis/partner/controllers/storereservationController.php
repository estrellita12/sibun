<?php
namespace applications\controllers;

class storereservationController extends Controller
{
    public function init()
    {
        $this->checkAuthorization("Bearer");
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Empty Ident");
        }
        $model = new \applications\models\StoreModel();
        if (
            !$model->isOwnerPartnerCheck(
                $this->params["ident"],
                $this->token["pt_idx"]
            )
        ) {
            $this->error_json("005", "Permission Denied");
        }
    }

    public function getList()
    {
        $request = $this->request["get"];
        $request["reservation_store_idx"] = $this->params["ident"];
        // ------------------------------------------------------
        $model = new \applications\models\StoreReservationModel();
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
        $request = $this->request["get"];
        if (empty($this->params["subident"])) {
            $this->error_json("002", "Ident Empty");
        }
        // ------------------------------------------------------
        $model = new \applications\models\StoreReservationModel();
        $search = [];
        $search["reservation_store_idx"] = $this->params["ident"];
        $search["reservation_idx"] = $this->params["subident"];
        $row = $model->get($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json($row["returnCode"], [
            "data" => $row["returnData"],
        ]);
    }
}

?>
