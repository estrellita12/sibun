<?php
namespace applications\controllers;

class reservationstateController extends Controller
{
    public function init()
    {
    }

    public function cancel()
    {
        $this->checkAuthorization("Bearer");
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Ident Empty");
        }
        $request = $this->request["post"];
        $request["reservation_idx"] = $this->params["ident"];
        $request["reservation_mb_idx"] = $this->token["mb_idx"];
        //--------------------------------------------------
        $model = new \applications\models\ReservationModel();
        $row = $model->cancel($this->params["ident"], $request);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }
}
?>
