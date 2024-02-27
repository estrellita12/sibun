<?php
namespace applications\controllers;

class storebulkController extends Controller
{
    public function init()
    {
        if (_DEV) {
            $this->request["post"] = [
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
        }
    }

    /*
    public function room(){
        $this->checkAuthorization("Bearer");
        $request = $this->request["post"];
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Empty Ident");
        }

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
    }
     */

    public function image(){
        $this->checkAuthorization("Bearer");
        $request = $this->request["post"];
        if (empty($request["store_img_li"])) {
            $this->error_json("002", "Empty Request");
        }
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Empty Ident");
        }
        // ----------------------------------------------
        $model = new \common\models\StoreImageModel();
        $search = [];
        $search["store_img_by_store_idx"] = $this->params["ident"];
        $row = $model->bulkSet($request["store_img_li"],$search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function pricing(){
        $this->checkAuthorization("Bearer");
        $request = $this->request["post"];
        if (empty($request["store_pricing_li"])) {
            $this->error_json("002", "Empty Request");
        }
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Empty Ident");
        }
        // ----------------------------------------------
        $model = new \common\models\StorePricingModel();
        $search = [];
        $search["store_pricing_by_store_idx"] = $this->params["ident"];
        $row = $model->bulkSet($request["store_pricing_li"],$search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
 
    }

}
?>
