<?php
namespace applications\controllers;

class storepricingController extends Controller
{
    public function init()
    {
        $this->checkAuthorization("Basic");
        if (empty($this->params["ident"])) {
            $this->error_json("001", "Empty Ident");
        }
    }

    public function getList()
    {
        $request = $this->request["get"];
        $request["store_pricing_by_store_idx"] = $this->params["ident"];
        // --------------------------------------------------
        $model = new \applications\models\StorePricingModel();
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
        $request = $this->request["get"];
        if (empty($this->params["subident"])) {
            $this->error_json("001", "Empty Ident");
        }
        // --------------------------------------------------
        $model = new \applications\models\StorePricingModel();
        $search = [];
        $search["store_pricing_by_store_idx"] = $this->params["ident"];
        $search["store_pricing_idx"] = $this->params["subident"];
        $row = $model->get($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", [
            "data" => $row["returnData"],
        ]);
    }
}
?>
