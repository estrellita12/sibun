<?php
namespace common\models;

class StorePricingModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_store_pricing");
        $this->alias = [
            "store_pricing_idx" => "store_pricing_idx",
            "store_pricing_by_store_idx" => "store_pricing_by_store_idx",
            "store_pricing_usage" => "ifnull(store_pricing_usage,'')",
            "store_pricing_days" => "ifnull(store_pricing_days,'')",
            "store_pricing_time" => "ifnull(store_pricing_time,'')",
            "store_pricing_cnt" => "ifnull(store_pricing_cnt,'')",
            "store_pricing_price" => "ifnull(store_pricing_price,'')",
            "store_pricing_orderby" => "ifnull(store_pricing_orderby,'')",
            "store_pricing_use_yn" => "ifnull(store_pricing_use_yn,'')",
            "store_pricing_reg_dt" => "ifnull(store_pricing_reg_dt,'')",
            "store_pricing_update_dt" => "ifnull(store_pricing_update_dt,'')",
        ];
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = ["store_pricing_by_store_idx"];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            $value["store_pricing_reg_dt"] = _DATE_YMDHIS;
        }
        $value["store_pricing_update_dt"] = _DATE_YMDHIS;

        if ($type == "set") {
            unset($value["store_pricing_idx"]);
            unset($value["store_pricing_by_store_idx"]);
            unset($value["store_reg_dt"]);
        }
        return $value;
    }

    public function bulkSet($value, $search)
    {
        $this->pdo->beginTransaction();
        $row = $this->remove($search);
        if ($row["returnCode"] != "000" && $row["returnCode"] != "001") {
            $this->pdo->rollBack();
            return $row;
        }
        foreach ($value as $pricing) {
            $pricingValue = [];
            if (!empty($search["store_pricing_by_store_idx"])) {
                $pricingValue["store_pricing_by_store_idx"] = $search["store_pricing_by_store_idx"];
            }
            if (!empty($pricing["store_pricing_by_store_idx"])) {
                $pricingValue["store_pricing_by_store_idx"] = $pricing["store_pricing_by_store_idx"];
            }
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
 
            $row = $this->add($pricingValue);
            if ($row["returnCode"] != "000") {
                $this->pdo->rollBack();
                return $row;
            }
        }
        $this->pdo->commit();
        return $row;
    }





}
