<?php
namespace applications\models;

class StoreModel extends \common\models\StoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getValue($request = [], $type = "add")
    {
        $value = [];
        $valueColumnArray = [
            "store_pt_idx",
            "store_ctg_idx",
            "store_main_simg",
            "store_name",
            "store_tel",
            "store_addr_sep1",
            "store_addr_sep2",
            "store_addr_sep3",
            "store_addr_zip",
            "store_addr1",
            "store_addr2",
            "store_addr",
            "store_addr_x",
            "store_addr_y",
            "store_open_time",
            "store_close_time",
            "store_closed_days",
            "store_closed_notice",
            "store_simg_cnt",
            "store_room_cnt",
            "store_voucher_use_yn",
            "store_amenities",
        ];
        foreach ($valueColumnArray as $col) {
            if (!empty($request[$col])) {
                $value[$col] = $request[$col];
            }
        }
        if ($type != "add") {
            unset($value["store_pt_idx"]);
        }

        if( !empty($value['store_addr1']) && !empty($value['store_addr2']) ){
            $value['store_addr'] = "{$value['store_addr1']} {$value['store_addr2']}";
        }else{
            unset($value['store_addr1']);
            unset($value['store_addr2']);
        }
        if (!empty($value["store_addr"])) {
            //if (_DEV) {
                $geo = new \common\extend\GeocodeApi();
                $res = $geo->getData($value["store_addr"]);
                $res = json_decode($res, true);
                if (!empty($res["documents"])) {
                    $documents = $res["documents"];
                    if (count($documents) > 0) {
                        $address = $documents[0]["road_address"];
                        $value["store_addr_zip"] = $address["zone_no"];
                        $value["store_addr_sep1"] = $address["region_1depth_name"];
                        $value["store_addr_sep2"] = $address["region_2depth_name"];
                        $value["store_addr_sep3"] = $address["region_3depth_name"];
                        $value["store_addr_x"] = $address["x"];
                        $value["store_addr_y"] = $address["y"];
                    }
                }
            //}
        }

        return $value;
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "store_reg_dt";
        $search["colby"] = "desc";

        if (!empty($request["store_idx"])) {
            $search["store_idx"] = $request["store_idx"];
        }
        if (!empty($request["store_pt_idx"])) {
            $search["store_pt_idx"] = $request["store_pt_idx"];
        }
        if (!empty($request["store_ctg_idx"])) {
            $search["store_ctg_idx"] = $request["store_ctg_idx"];
        }

        if (!empty($request["rpp"])) {
            $search["rpp"] = $request["rpp"];
        }
        if (!empty($request["page"])) {
            $search["page"] = $request["page"];
        }
        if (!empty($request["col"])) {
            $search["col"] = $request["col"];
            if (!empty($request["colby"])) {
                $search["colby"] = $request["colby"];
            }
        }

        return $search;
    }

    public function getList($request = [], $type = "fetch")
    {
        $col = get_alias(
            $this->alias,
            [
                "store_stt",
                "store_simg_cnt",
                "store_room_cnt",
                "store_update_dt",
            ],
            false
        );
        $search = $this->getSearch($request);
        return parent::select($col, $search, true, $type);
    }

    public function get($request = [])
    {
        $col = get_alias(
            $this->alias,
            [
                "store_stt",
                "store_simg_cnt",
                "store_room_cnt",
                "store_update_dt",
            ],
            false
        );
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }

    /*
    public function add($value = [], $check = true)
    {
        print_r($value);
        $row = parent::add($value);
        if ($row["returnCode"] != "000") {
            return $row;
        }
        $storeIdx = $this->pdo->lastInsertId();

        $roomModel = new \common\models\StoreRoomModel();
        $roomCnt = 1;
        if (!empty($value["store_room_cnt"])) {
            $roomCnt = $value["store_room_cnt"];
            unset($value["store_room_cnt"]);
        }
        for ($i = 1; $i <= $roomCnt; $i++) {
            $roomModel->add([
                "store_room_by_store_idx" => $storeIdx,
                "store_room_name" => "{$i}번",
                "store_room_orderby" => $i,
                "store_room_use_yn" => "y",
            ]);
        }

        if (!empty($value["store_img_li"])) {
            $imageModel = new \common\models\StoreImageModel();
            foreach ($value["store_image_li"] as $image) {
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
            unset($value["store_img_li"]);
        }

        print_r($value["store_pricing_li"]);
        if (!empty($value["store_pricing_li"])) {
            echo "asd";
            $pricingModel = new \common\models\StorePricingModel();
            foreach ($value["store_pricing_li"] as $pricing) {
                $pricingValue = [];
                $pricingValue["store_pricing_by_store_idx"] = $storeIdx;
                if (!empty($image["store_pricing_days"])) {
                    $imgValue["store_pricing_days"] =
                        $image["store_pricing_days"];
                }
                if (!empty($image["store_pricing_time"])) {
                    $imgValue["store_pricing_time"] =
                        $image["store_pricing_time"];
                }
                if (!empty($image["store_pricing_cnt"])) {
                    $imgValue["store_pricing_cnt"] =
                        $image["store_pricing_cnt"];
                }
                if (!empty($image["store_pricing_price"])) {
                    $imgValue["store_pricing_price"] =
                        $image["store_pricing_price"];
                }
                if (!empty($image["store_pricing_orderby"])) {
                    $imgValue["store_pricing_orderby"] =
                        $image["store_pricing_orderby"];
                }
                if (!empty($image["store_pricing_use_yn"])) {
                    $imgValue["store_pricing_use_yn"] =
                        $image["store_pricing_use_yn"];
                }
                $pricingModel->add($pricingValue);
            }
            unset($value["store_pricing_li"]);
        }

        return $row;
    }
    */
}

?>
