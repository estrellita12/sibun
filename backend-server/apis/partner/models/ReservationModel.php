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
        $valueColumnArray = [
            "reservation_stt",
            "reservation_room_idx",
            "reservation_date",
            "reservation_time",
            "reservation_period",
            "reservation_cancel_reason",
        ];
        foreach ($valueColumnArray as $col) {
            if (!empty($request[$col])) {
                $value[$col] = $request[$col];
            }
        }
        return $value;
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
        if (!empty($request["reservation_store_idx"])) {
            $search["reservation_store_idx"] =
                $request["reservation_store_idx"];
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
            ["reservation_reg_dt", "reservation_update_dt"],
            false
        );
        $search = $this->getSearch($request);
        return $this->select($col, $search, true, $type);
    }

    public function get($request)
    {
        $col = get_alias($this->alias, ["reservation_update_dt"], false);
        $search = $this->getSearch($request);
        return $this->select($col, $search, false);
    }

    public function confirm($idx, $request = [])
    {
        $search = [];
        $search["reservation_idx"] = $idx;
        $search["reservation_stt"] = 1;
        $value = [];
        $value["reservation_stt"] = 2;
        $value["reservation_confirm_dt"] = _DATE_YMDHIS;
        return $this->changeStt($value, $search,"partner");
    }

    public function cancel2($idx, $request = [])
    {
        $search = [];
        $search["reservation_idx"] = $idx;
        $row = $this->select("reservation_stt",$search , false);
        if( $row['reservation_stt'] == "2" ){
            $search["reservation_date__then__ge"] = date("Y-m-d", strtotime("+3 days") );
        }
        $search["reservation_stt__in__all"] = "1,2";
        $value = [];
        $value["reservation_stt"] = 3;
        $value["reservation_cancel_dt"] = _DATE_YMDHIS;
        if (!empty($request["reservation_cancel_reason"])) {
            $value["reservation_cancel_reasnon"] =
                $request["reservation_cancel_reason"];
        }

        return $this->changeStt($value, $search,"partner");
    }

    public function cancel($idx, $request = [])
    {
        $search = [];
        $search["reservation_idx"] = $idx;
        $search["reservation_stt__in__all"] = "1,2";
        $value = [];
        $value["reservation_stt"] = 3;
        $value["reservation_cancel_dt"] = _DATE_YMDHIS;
        if (!empty($request["reservation_cancel_reason"])) {
            $value["reservation_cancel_reasnon"] =
                $request["reservation_cancel_reason"];
        }

        return $this->changeStt($value, $search,"partner");
    }

    public function noshow($idx, $request = [])
    {
        $search = [];
        $search["reservation_idx"] = $idx;
        $search["reservation_stt"] = "2";
        $value = [];
        $value["reservation_stt"] = 4;
        //$value["reservation_noshow_dt"] = _DATE_YMDHIS;
        return $this->changeStt($value, $search,"partner");
    }

    public function enter($idx, $request = [])
    {
        $search = [];
        $search["reservation_idx"] = $idx;
        $search["reservation_stt"] = "2";
        $value = [];
        $value["reservation_stt"] = 5;
        $value["reservation_enter_dt"] = _DATE_YMDHIS;
        return $this->changeStt($value, $search,"partner");
    }
}
?>
