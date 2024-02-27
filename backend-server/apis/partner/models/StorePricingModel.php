<?php
namespace applications\models;

class StorePricingModel extends \common\models\StorePricingModel
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getValue($request = [], $type = "add")
    {
        $value = [];
        $valueColumnArray = [
            "store_pricing_by_store_idx",
            "store_pricing_usage",
            //"store_pricing_days",
            "store_pricing_time",
            "store_pricing_cnt",
            "store_pricing_price",
            "store_pricing_orderby",
            "store_pricing_use_yn",
        ];
        foreach ($valueColumnArray as $col) {
            if (!empty($request[$col])) {
                $value[$col] = $request[$col];
            }
        }
        if (!empty($request["store_pricing_days"])) {
            $value["store_pricing_days"] = emptyRemoveArrString(
                $request["store_pricing_days"]
            );
        }

        if ($type != "add") {
            unset($value["store_pricing_by_store_idx"]);
        }

        return $value;
    }
    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "store_pricing_orderby";
        $search["colby"] = "asc";

        if (!empty($request["store_pricing_idx"])) {
            $search["store_pricing_idx"] = $request["store_pricing_idx"];
        }
        if (!empty($request["store_pricing_by_store_idx"])) {
            $search["store_pricing_by_store_idx"] =
                $request["store_pricing_by_store_idx"];
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
                "store_pricing_idx",
                "store_pricing_by_store_idx",
                "store_pricing_days",
                "store_pricing_time",
                "store_pricing_cnt",
                "store_pricing_price",
                "store_pricing_orderby",
                "store_pricing_use_yn",
            ],
            true
        );
        $search = $this->getSearch($request);
        return parent::select($col, $search, true);
    }

    public function get($request = [])
    {
        $col = get_alias($this->alias, ["store_pricing_update_dt"], false);
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }
}

?>
