<?php
namespace applications\models;

class StorePricingModel extends \common\models\StorePricingModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "store_pricing_orderby";
        $search["colby"] = "asc";
        $search["store_pricing_use_yn"] = "y";

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
                "store_pricing_days",
                "store_pricing_time",
                "store_pricing_cnt",
                "store_pricing_price",
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
