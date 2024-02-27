<?php
namespace common\models;

class StoreImageModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_store_image");
        $this->alias = [
            "store_img_idx" => "store_img_idx",
            "store_img_by_store_idx" => "store_img_by_store_idx",
            "store_img_data" => "ifnull(store_img_data,'')",
            "store_img_orderby" => "ifnull(store_img_orderby,'1')",
            "store_img_reg_dt" => "ifnull(store_img_reg_dt,'')",
            "store_img_update_dt" => "ifnull(store_img_update_dt,'')",
        ];
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = ["store_img_by_store_idx"];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            $value["store_img_reg_dt"] = _DATE_YMDHIS;
        }
        $value["store_img_update_dt"] = _DATE_YMDHIS;

        if ($type == "set") {
            unset($value["store_img_by_store_idx"]);
            unset($value["store_img_reg_dt"]);
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

        foreach ($value as $image) {
            $imgValue = [];
            if (!empty($search["store_img_by_store_idx"])) {
                $imgValue["store_img_by_store_idx"] = $search["store_img_by_store_idx"];
            }
            if (!empty($image["store_img_by_store_idx"])) {
                $imgValue["store_img_by_store_idx"] = $image["store_img_by_store_idx"];
            }
            if (!empty($image["store_img_data"])) {
                $imgValue["store_img_data"] = $image["store_img_data"];
            }
            if (!empty($image["store_img_orderby"])) {
                $imgValue["store_img_orderby"] = $image["store_img_orderby"];
            }
            $row = $this->add($imgValue);
            if ($row["returnCode"] != "000") {
                $this->pdo->rollBack();
                return $row;
            }
        }
        $this->pdo->commit();
        return $row;
    }



}
