<?php
namespace applications\models;

class StoreReservationModel extends \common\models\ReservationModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "reservation_date";
        $search["colby"] = "desc";

        if (!empty($request["reservation_idx"])) {
            $search["reservation_idx"] = $request["reservation_idx"];
        }
        if (!empty($request["reservation_stt"])) {
            $search["reservation_stt"] = $request["reservation_stt"];
        }
        if (!empty($request["reservation_stt_in"])) {
            $search["reservation_stt__in__all"] = emptyRemoveArrString(
                $request["reservation_stt_in"]
            );
        }
        if (!empty($request["reservation_store_idx"])) {
            $search["reservation_store_idx"] =
                $request["reservation_store_idx"];
        }
        if (!empty($request["reservation_month"])) {
            $search["reservation_date__then__ge"] = $request["reservation_month"]."-01";
            $search["reservation_date__then__le"] = $request["reservation_month"]."-31";
        }
        if (!empty($request["reservation_date"])) {
            $search["reservation_date"] = $request["reservation_date"];
        }
        if (!empty($request["reservation_time"])) {
            $search["reservation_time_then_le"] = $request["reservation_time"];
            $search["reservation_end_time_then_ge"] =
                $request["reservation_time"];
        }
        if (!empty($request["reservation_voucher_idx"])) {
            $search["reservation_voucher_idx"] =
                $request["reservation_voucher_idx"];
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

    public function getList($request)
    {
        $col = get_alias(
            $this->alias,
            [
                "reservation_idx",
                "reservation_code",
                "reservation_stt",
                "reservation_mb_idx",
                "reservation_user_name",
                "reservation_user_cellphone",
                "reservation_room_idx",
                "reservation_voucher_idx",
                "reservation_date",
                "reservation_time",
                "reservation_period",
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
        return $this->select($col, $search, true, $type);
    }

    public function get($request)
    {
        $col = get_alias($this->alias, ["reservation_update_dt"], false);
        $search = $this->getSearch($request);
        return $this->select($col, $search, false);
    }
}
?>
