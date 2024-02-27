<?php
namespace applications\models;

class StoreVoucherModel extends \common\models\StoreVoucherModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "store_voucher_orderby";
        $search["colby"] = "asc";
        $search["store_voucher_use_yn"] = "y";

        if (!empty($request["store_voucher_idx"])) {
            $search["store_voucher_idx"] = $request["store_voucher_idx"];
        }
        if (!empty($request["store_voucher_by_store_idx"])) {
            $search["store_voucher_by_store_idx"] =
                $request["store_voucher_by_store_idx"];
        }
        if (!empty($request["store_voucher_available_room"])) {
            $search["store_voucher_available_room_li__like__all"] =
                $request["store_voucher_available_room"];
        }
        if (!empty($request["store_voucher_available_days"])) {
            $search["store_voucher_available_days__like__all"] =
                $request["store_voucher_available_days"];
        }
        if (!empty($request["store_voucher_date"])) {
            $search["store_voucher_beg_date__then__le"] =
                $request["store_voucher_date"];
            $search["store_voucher_end_date__then__gt"] =
                $request["store_voucher_date"];
        }
        if (!empty($request["store_voucher_time"])) {
            $search["store_voucher_beg_time__then__le"] =
                $request["store_voucher_time"];
            $search["store_voucher_end_time__then__gt"] =
                $request["store_voucher_time"];
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
        $col = get_alias(
            $this->alias,
            [
                "store_voucher_idx",
                "store_voucher_code",
                "store_voucher_title",
                "store_voucher_desc",
                "store_voucher_discount_rate",
                "store_voucher_available_days",
                "store_voucher_beg_date",
                "store_voucher_end_date",
                "store_voucher_beg_time",
                "store_voucher_end_time",
                "store_voucher_daily_total_cnt",
            ],
            true
        );
        $search = $this->getSearch($request);
        return parent::select($col, $search, true);
    }

    public function get($request = [])
    {
        $col = get_alias($this->alias, ["store_voucher_update_dt"], false);
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }
}

?>
