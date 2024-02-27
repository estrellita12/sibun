<?php
namespace common\models;

class ReviewModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_review");
        $this->alias = [
            "review_idx" => "review_idx",
            "review_by_store_idx" => "ifnull(review_by_store_idx,'')",
            "review_by_mb_idx" => "ifnull(review_by_mb_idx,'')",
            "review_title" => "ifnull(review_title,'')",
            "review_tags" => "ifnull(review_tags,'')",
            "review_content" => "ifnull(review_content,'')",
            "review_img1" => "ifnull(review_img1,'')",
            "review_img2" => "ifnull(review_img2,'')",
            "review_img3" => "ifnull(review_img3,'')",
            "review_rating" => "ifnull(review_rating,'')",
            "review_answer_yn" => "ifnull(review_answer_yn,'')",
            "review_answer" => "ifnull(review_answer,'')",
            "review_block_yn" => "ifnull(review_block_yn,'')",
            "review_reg_dt" => "ifnull(review_reg_dt,'')",
            "review_update_dt" => "ifnull(review_update_dt,'')",
        ];
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = ["review_by_store_idx"];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            $value["review_reg_dt"] = _DATE_YMDHIS;
        }
        $value["review_update_dt"] = _DATE_YMDHIS;

        if ($type == "set") {
            unset($value["review_idx"]);
            unset($value["review_by_store_idx"]);
            unset($value["review_reg_dt"]);
        }
        return $value;
    }

    public function answer($request, $search)
    {
        if (empty($request["review_answer"])) {
            return ["returnCode" => "001", "returnData" => ""];
        }
        $value = [];
        $value["review_answer"] = $request["review_answer"];
        $value["review_answer_yn"] = "y";
        $value["review_answer_dt"] = _DATE_YMDHIS;
        return parent::set($value, $search);
    }

    public function isOwnerPartnerCheck($idx, $pt_idx)
    {
        $this->leftJoin("review_by_store_idx", "web_store", "store_idx");
        $col = "count(review_idx) as cnt";
        $search = [];
        $search["review_idx"] = $idx;
        $search["store_pt_idx"] = $pt_idx;
        $row = $this->select($col, $search, false);
        if ($row["returnCode"] == "000" && $row["returnData"]["cnt"] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isOwnerMemberCheck($idx, $mb_idx)
    {
        $this->leftJoin("review_by_mb_idx", "web_member", "mb_idx");
        $col = "count(review_idx) as cnt";
        $search = [];
        $search["review_idx"] = $idx;
        $search["store_mb_idx"] = $pt_idx;
        $row = $this->select($col, $search, false);
        if ($row["returnCode"] == "000" && $row["returnData"]["cnt"] > 0) {
            return true;
        } else {
            return false;
        }
    }
}

?>
