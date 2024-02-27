<?php
namespace common\models;

class StoreReviewModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_store_review");
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = ["store_review_by_store_idx"];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            $value["store_review_reg_dt"] = _DATE_YMDHIS;
        }
        $value["store_review_update_dt"] = _DATE_YMDHIS;

        if ($type == "set") {
            unset($value["store_review_idx"]);
            unset($value["store_review_by_store_idx"]);
            unset($value["store_review_reg_dt"]);
        }
        return $value;
    }
}

?>
