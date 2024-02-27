<?php
namespace applications\models;

class ReservationModel extends \common\models\ReservationModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getValue($request = [], $type = "add")
    {
        $value = [];
        if (!empty($request["reservation_code"]) && $type == "add") {
            $value["reservation_code"] = $request["reservation_code"];
        }
        if (!empty($request["reservation_stt"])) {
            $value["reservation_stt"] = $request["reservation_stt"];
        }
        if (!empty($request["reservation_mb_idx"]) && $type == "add") {
            $value["reservation_mb_idx"] = $request["reservation_mb_idx"];
        }
        if (!empty($request["reservation_user_name"])) {
            $value["reservation_user_name"] = $request["reservation_user_name"];
        }
        if (!empty($request["reservation_user_cellphone"])) {
            $value["reservation_user_cellphone"] =
                $request["reservation_user_cellphone"];
        }
        if (!empty($request["reservation_store_idx"]) && $type == "add") {
            $value["reservation_store_idx"] = $request["reservation_store_idx"];
        }
        if (!empty($request["reservation_room_idx"])) {
            $value["reservation_room_idx"] = $request["reservation_room_idx"];
        }
        if (!empty($request["reservation_voucher_idx"])) {
            $value["reservation_voucher_idx"] =
                $request["reservation_voucher_idx"];
        }
        if (!empty($request["reservation_date"])) {
            $value["reservation_date"] = $request["reservation_date"];
        }
        if (!empty($request["reservation_time"])) {
            $value["reservation_time"] = $request["reservation_time"];
        }
        if (!empty($request["reservation_period"])) {
            $value["reservation_period"] = $request["reservation_period"];
        }
        return $value;
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "reservation_date";
        $search["colby"] = "asc";

        if (!empty($request["reservation_idx"])) {
            $search["reservation_idx"] = $request["reservation_idx"];
        }

        if (!empty($request["reservation_store_idx"])) {
            $search["reservation_store_idx"] =
                $request["reservation_store_idx"];
        }
        if (!empty($request["reservation_stt"])) {
            $search["reservation_stt__in__all"] = $request["reservation_stt"];
        }
        if (!empty($request["reservation_stt_in"])) {
            $search["reservation_stt__in__all"] = $request["reservation_stt_in"];
        }
        if (!empty($request["reservation_mb_idx"])) {
            $search["reservation_mb_idx"] = $request["reservation_mb_idx"];
        }
        if (!empty($request["reservation_date"])) {
            $search["reservation_date"] = $request["reservation_date"];
        }
        if (!empty($request["reservation_beg_date"])) {
            $search["reservation_date__then__ge"] =
                $request["reservation_beg_date"];
        }
        if (!empty($request["reservation_end_date"])) {
            $search["reservation_date__then__le"] =
                $request["reservation_end_date"];
        }
        if (!empty($request["reservation_time"])) {
            $search["reservation_time"] = $request["reservation_time"];
        }
        if (!empty($request["reservation_beg_time"])) {
            $search["reservation_time__then__ge"] =
                $request["reservation_beg_time"];
        }
        if (!empty($request["reservation_end_time"])) {
            $search["reservation_time__then__le"] =
                $request["reservation_end_time"];
        }
        if (!empty($request["reservation_review_yn"])) {
            $search["reservation_review_yn"] =
                $request["reservation_review_yn"];
        }
        if (!empty($request["reservation_voucher_idx"])) {
            $search["reservation_voucher_idx"] =
                $request["reservation_voucher_idx"];
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

    public function getList($request = [])
    {
        $storeModel = new \common\models\StoreModel();
        $this->leftJoin(
            "reservation_store_idx",
            $storeModel->tb_nm,
            "store_idx"
        );

        $this->alias["store_name"] = "ifnull(store_name,'')";
        $this->alias["store_addr"] = "ifnull(store_addr,'')";
        $this->alias["store_tel"] = "ifnull(store_tel,'')";
        $this->alias["store_ctg_idx"] = "ifnull(store_ctg_idx,'')";
        $this->alias["store_main_simg"] = "ifnull(store_main_simg,'')";
        $col = get_alias(
            $this->alias,
            [
                "reservation_idx",
                "reservation_date",
                "reservation_stt",
                "reservation_store_idx",
                "reservation_time",
                "reservation_period",
                "reservation_review_yn",
                "store_name",
                "store_addr",
                "store_tel",
                "store_ctg_idx",
                "store_main_simg",
            ],
            true
        );
        $search = $this->getSearch($request);

        $type = "fetch";
        if (!empty($request["type"])) {
            $group_col = [
                "reservation_date",
                "reservation_time",
                "reservation_room_idx",
                "reservation_voucher_idx",
            ];

            if (in_array($request["type"], $group_col)) {
                $type = "group";
                $col = $request["type"] . "," . $col;
            }
        }

        return parent::select($col, $search, true, $type);
    }

    public function get($request = [])
    {
        $storeModel = new \common\models\StoreModel();
        $this->leftJoin(
            "reservation_store_idx",
            $storeModel->tb_nm,
            "store_idx"
        );

        $this->alias["store_name"] = "ifnull(store_name,'')";
        $this->alias["store_addr"] = "ifnull(store_addr,'')";
        $this->alias["store_tel"] = "ifnull(store_tel,'')";
        $this->alias["store_ctg_idx"] = "ifnull(store_ctg_idx,'')";
        $col = get_alias(
            $this->alias,
            [
                "reservation_mb_idx",
                "reservation_update_dt",
                "reservation_end_time",
            ],
            false
        );
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }

    public function cancel($idx, $request = [])
    {
        $search = [];
        $search["reservation_idx"] = $idx;
        /*
        $search["reservation_date__then__ge"] = date(
            "Y-m-d",
            strtotime("+1 days")
        );
        */
        $search["reservation_stt"] = "1";
        $value = [];
        $value["reservation_stt"] = "3";
        $value["reservation_cancel_dt"] = _DATE_YMDHIS;
        if (!empty($request["reservation_cancel_reason"])) {
            $value["reservation_cancel_reasnon"] =
                $request["reservation_cancel_reason"];
        }
        $row = $this->changeStt($value, $search,"member");
        return $row;
    }
    
}

?>
