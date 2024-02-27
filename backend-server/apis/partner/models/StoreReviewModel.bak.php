<?php
namespace applications\models;

class StoreReviewModel extends \common\models\ReviewModel
{
    public function __construct()
    {
        parent::__construct();
        $this->alias = [
            "review_idx" => "review_idx",
            "review_by_store_idx" => "ifnull(review_by_store_idx,'')",
            "review_by_mb_idx" => "ifnull(review_by_mb_idx,'')",
            "review_title" => "ifnull(review_title,'')",
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

    public function getValue($request = [], $type = "add")
    {
        $value = [];
        if (!empty($request["review_answer"])) {
            $value["review_answer"] = $request["review_answer"];
        }
        if (!empty($request["review_answer_yn"])) {
            $value["review_answer_yn"] = $request["review_answer_yn"];
            if ($request["review_answer_yn"] == "n") {
                $value["review_answer"] = "";
            }
        }
        if (!empty($request["review_block_yn"])) {
            $value["review_block_yn"] = $request["review_block_yn"];
        }
        return $value;
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "review_reg_dt";
        $search["colby"] = "desc";
        //$search["review_block_yn"] = "n";

        if (!empty($request["review_idx"])) {
            $search["review_idx"] = $request["review_idx"];
        }

        if (!empty($request["review_by_store_idx"])) {
            $search["review_by_store_idx"] = $request["review_by_store_idx"];
        }

        if (!empty($request["review_mb_idx"])) {
            $search["review_mb_idx"] = $request["review_mb_idx"];
        }

        if (!empty($request["review_answer_yn"])) {
            if ($request["review_answer_yn"] == "y") {
                $search["review_answer__null__not"] = "";
            } else {
                $search["review_answer__null__qe"] = "";
            }
        }

        if (!empty($request["review_photo_yn"])) {
            if ($request["review_photo_yn"] == "y") {
                $search["review_img1__null__not"] = "";
            } else {
                $search["review_img1__null__eq"] = "";
            }
        }

        if (!empty($request["rpp"])) {
            $search["rpp"] = $request["rpp"];
        }

        if (!empty($request["page"])) {
            $search["page"] = $request["page"];
        }
        return $search;
    }

    public function getList($request = [])
    {
        $memberModel = new \common\models\MemberModel();
        $this->leftJoin("review_by_mb_idx", $memberModel->tb_nm, "mb_idx");
        $this->alias["mb_id"] = "ifnull(mb_id,'')";
        $this->alias["mb_name"] = "ifnull(mb_name,'')";
        $this->alias["mb_nickname"] = "ifnull(mb_nickname,'')";
        $this->alias["mb_profile_img"] = "ifnull(mb_profile_img,'')";
        /*
        $col = get_alias(
            $this->alias,
            [
                "review_idx",
                "review_by_mb_idx",
                "review_title",
                "review_content",
                "review_img1",
                "review_img2",
                "review_img3",
                "review_rating",
                "review_answer",
                "review_reg_dt",
                "mb_id",
                "mb_profile_img",
                "mb_nickname",
            ],
            true
        );
        */
        $col = get_alias($this->alias, ["review_update_dt"], false);
        $search = $this->getSearch($request);
        return parent::select($col, $search, true);
    }

    public function get($request = [])
    {
        $memberModel = new \common\models\MemberModel();
        $this->leftJoin("review_by_mb_idx", $memberModel->tb_nm, "mb_idx");
        $this->alias["mb_id"] = "ifnull(mb_id,'')";
        $this->alias["mb_name"] = "ifnull(mb_name,'')";
        $this->alias["mb_nickname"] = "ifnull(mb_nickname,'')";
        $this->alias["mb_profile_img"] = "ifnull(mb_profile_img,'')";

        $col = get_alias($this->alias, ["review_update_dt"], false);
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }
}

?>
