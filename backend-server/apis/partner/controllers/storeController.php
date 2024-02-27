<?php
namespace applications\controllers;

class storeController extends Controller
{
    public function init()
    {
        if (_DEV) {
            $this->request["put"] = [
                "store_ctg_idx" => "1",
                "store_name" => "MJ연습장",
                "store_tel" => "02-1234-1234",
                "store_addr1" => "서울시 송파구 양재대로71길",
                "store_addr2" => "2-11",
                "store_pricing_li" => [
                    [
                        "store_pricing_price" => 12000,
                        "store_pricing_days" => "1,2",
                        "store_pricing_time" => 60,
                    ],
                    [
                        "store_pricing_price" => 12000,
                        "store_pricing_days" => "1,2",
                        "store_pricing_time" => 60,
                    ],
                ],
            ];
            $this->params['ident'] = 126;
            $this->request["post"] = [
                "store_addr1" => "경기도 하남시 위례학암로",
                "store_addr2" => "30",
            ];
        }
    }

    public function getList()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["get"];
        $request["store_pt_idx"] = $this->token["pt_idx"];
        // -------------------------------------------
        $model = new \applications\models\StoreModel();
        $row = $model->getList($request);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["list" => $row["returnData"]]);
    }

    public function get()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["get"];
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Empty Ident");
        }
        // -------------------------------------------
        $model = new \applications\models\StoreModel();
        $search = [];
        $search["store_pt_idx"] = $this->token["pt_idx"];
        $search["store_idx"] = $this->params["ident"];
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
        $required = [
            "store_ctg_idx",
            "store_name",
            "store_tel",
            "store_addr1",
            "store_addr2",
        ];
        if (!check_required($required, $request)) {
            $this->error_json("002", "Required Error");
        }
        // -------------------------------------------
        $model = new \applications\models\StoreModel();
        $value = [];
        $value = $model->getValue($request, "add");
        $value["store_pt_idx"] = $this->token["pt_idx"];
        $row = $model->add($value);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $storeIdx = $model->pdo->lastInsertId();
        // --------------------------------------------
        $roomModel = new \common\models\StoreRoomModel();
        $roomCnt = 1;
        if (!empty($request["store_room_cnt"])) {
            $roomCnt = $request["store_room_cnt"];
        }
        for ($i = 1; $i <= $roomCnt; $i++) {
            $roomModel->add([
                "store_room_by_store_idx" => $storeIdx,
                "store_room_name" => "{$i}번",
                "store_room_orderby" => $i,
                "store_room_use_yn" => "y",
            ]);
        }

        if (!empty($request["store_img_li"])) {
            $imageModel = new \common\models\StoreImageModel();
            foreach ($request["store_img_li"] as $image) {
                $imgValue = [];
                $imgValue["store_img_by_store_idx"] = $storeIdx;
                if (!empty($image["store_img_data"])) {
                    $imgValue["store_img_data"] = $image["store_img_data"];
                }
                if (!empty($image["store_img_orderby"])) {
                    $imgValue["store_img_orderby"] =
                        $image["store_img_orderby"];
                }
                $imageModel->add($imgValue);
            }
        }

        if (!empty($request["store_pricing_li"])) {
            $pricingModel = new \common\models\StorePricingModel();
            foreach ($request["store_pricing_li"] as $pricing) {
                $pricingValue = [];
                $pricingValue["store_pricing_by_store_idx"] = $storeIdx;
                if (!empty($pricing["store_pricing_days"])) {
                    $pricingValue["store_pricing_days"] =
                        $pricing["store_pricing_days"];
                }
                if (!empty($pricing["store_pricing_time"])) {
                    $pricingValue["store_pricing_time"] =
                        $pricing["store_pricing_time"];
                }
                if (!empty($pricing["store_pricing_cnt"])) {
                    $pricingValue["store_pricing_cnt"] =
                        $pricing["store_pricing_cnt"];
                }
                if (!empty($pricing["store_pricing_price"])) {
                    $pricingValue["store_pricing_price"] =
                        $pricing["store_pricing_price"];
                }
                if (!empty($pricing["store_pricing_orderby"])) {
                    $pricingValue["store_pricing_orderby"] =
                        $pricing["store_pricing_orderby"];
                }
                if (!empty($pricing["store_pricing_use_yn"])) {
                    $pricingValue["store_pricing_use_yn"] =
                        $pricing["store_pricing_use_yn"];
                }
                $pricingModel->add($pricingValue);
            }
        }

        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function set()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["post"];
        if (empty($request)) {
            $this->error_json("002", "Required Error");
        }
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Empty Ident");
        }
        // ------------------------------------------
        $model = new \applications\models\StoreModel();
        $search = [];
        $search["store_idx"] = $this->params["ident"];
        $search["store_pt_idx"] = $this->token["pt_idx"];
        $value = $model->getValue($request, "set");
        $row = $model->set($value, $search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function remove()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["delete"];
        // -----------------------------------------
        $model = new \applications\models\StoreModel();
        $search = [];
        $search["store_idx"] = $this->params["ident"];
        $search["store_pt_idx"] = $this->token["pt_idx"];
        $row = $model->remove($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }
}
?>
