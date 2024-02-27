<?php
namespace applications\controllers;

class storepricingController extends Controller
{
    public function init()
    {
        if (_DEV) {
            $this->request["put"] = [
                "store_pricing_days" => "1,2,3",
                "store_pricing_time" => "60",
                "store_pricing_cnt" => "1",
                "store_pricing_price" => "1000",
            ];
        }
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
        $request["store_pricing_by_store_idx"] = $this->params["ident"];
        // -----------------------------------------------------------
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
        // --------------------------------------------------
        $model = new \applications\models\StorePricingModel();
        $search = [];
        $serach["store_pricing_by_store_idx"] = $this->params["ident"];
        $search["store_pricing_idx"] = $this->params["subident"];
        $row = $model->get($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", [
            "data" => $row["returnData"],
        ]);
    }

    public function add()
    {
        $request = $this->request["put"];
        if (empty($request)) {
            $this->error_json("002", "Request Empty");
        }
        $required = [
            "store_pricing_days",
            "store_pricing_time",
            "store_pricing_cnt",
            "store_pricing_price",
        ];
        if (!check_required($required, $request)) {
            $this->error_json("002", "Required Error");
        }
        // --------------------------------------------------
        $model = new \applications\models\StorePricingModel();
        $value = [];
        $value = $model->getValue($request, "add");
        $value["store_pricing_by_store_idx"] = $this->params["ident"];
        $row = $model->add($value);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", [
            "data" => $row["returnData"],
        ]);
    }

    public function set()
    {
        $request = $this->request["post"];
        if (empty($this->params["subident"])) {
            $this->error_json("002", "Ident Empty");
        }
        // --------------------------------------------------
        $model = new \applications\models\StorePricingModel();
        $search = [];
        $search["store_pricing_by_store_idx"] = $this->params["ident"];
        $search["store_pricing_idx"] = $this->params["subident"];
        $value = [];
        $value = $model->getValue($request, "set");
        $row = $model->set($value, $search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", [
            "data" => $row["returnData"],
        ]);
    }

    public function remove()
    {
        $request = $this->request["remove"];
        if (empty($this->params["subident"])) {
            $this->error_json("002", "Ident Empty");
        }
        // ---------------------------------------------------
        $model = new \applications\models\StorePricingModel();
        $search = [];
        $search["store_pricing_by_store_idx"] = $this->params["ident"];
        $search["store_pricing_idx"] = $this->params["subident"];
        $row = $model->remove($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", [
            "data" => $row["returnData"],
        ]);
    }
}
?>
