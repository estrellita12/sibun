<?php
namespace applications\controllers;

class storevoucherController extends Controller
{
    public function init()
    {
        if (_DEV) {
            $this->request["put"] = [
                "store_voucher_title" => "평일 선착순 할인",
                "store_voucher_discount_rate" => "25",
                "store_voucher_beg_date" => "2023-08-01",
                "store_voucher_end_date" => "2023-08-30",
                "store_voucher_beg_time" => "22",
                "store_voucher_end_time" => "40",
                "store_voucher_daily_total_cnt" => "10",
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
        $request["store_voucher_by_store_idx"] = $this->params["ident"];
        // --------------------------------------------------
        $model = new \applications\models\StoreVoucherModel();
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
            $this->error_json("002", "Ident Empty");
        }
        // --------------------------------------------------
        $model = new \applications\models\StoreVoucherModel();
        $search = [];
        $search["store_voucher_by_store_idx"] = $this->params["ident"];
        $search["store_voucher_idx"] = $this->params["subident"];
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
            "store_voucher_title",
            "store_voucher_discount_rate",
            "store_voucher_beg_date",
            "store_voucher_end_date",
            "store_voucher_beg_time",
            "store_voucher_end_time",
            "store_voucher_daily_total_cnt",
        ];
        if (!check_required($required, $request)) {
            $this->error_json("002", "Required Error");
        }

        // --------------------------------------------------
        $model = new \applications\models\StoreVoucherModel();
        $value = [];
        $value = $model->getValue($request, "add");
        $value["store_voucher_by_store_idx"] = $this->params["ident"];
        $value["store_voucher_code"] = generateRandomString(4);
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
        if (empty($request)) {
            $this->error_json("002", "Request Empty");
        }
        if (empty($this->params["subident"])) {
            $this->error_json("002", "Ident Empty");
        }
        // ---------------------------------------------------
        $model = new \applications\models\StoreVoucherModel();
        $search = [];
        $search["store_voucher_by_store_idx"] = $this->params["ident"];
        $search["store_voucher_idx"] = $this->params["subident"];
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
        $request = $this->request["delete"];
        if (empty($this->params["subident"])) {
            $this->error_json("002", "Ident Empty");
        }
        // ---------------------------------------------------
        $model = new \applications\models\StoreVoucherModel();
        $search = [];
        $search["store_voucher_by_store_idx"] = $this->params["ident"];
        $search["store_voucher_idx"] = $this->params["subident"];
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
