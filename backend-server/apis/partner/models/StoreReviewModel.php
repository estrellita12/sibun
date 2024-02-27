<?php
namespace applications\models;

class StoreReviewModel extends \common\models\ReviewModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getValue($request = [], $type = "add")
    {
        $value = [];
        if (!empty($request["review_answer"])) {
            $value["review_answer"] = $request["review_answer"];
            $value["review_answer_yn"] = "y";
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

        if (!empty($request["review_idx"])) {
            $search["review_idx"] = $request["review_idx"];
        }
        if (!empty($request["review_by_mb_idx"])) {
            $search["review_by_mb_idx"] = $request["review_by_mb_idx"];
        }
        if (!empty($request["review_by_store_idx"])) {
            $search["review_by_store_idx"] = $request["review_by_store_idx"];
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
        $memberModel = new \common\models\MemberModel();
        $this->leftJoin("review_by_mb_idx", $memberModel->tb_nm, "mb_idx");
        $this->alias["mb_id"] = "ifnull(mb_id,'')";
        $this->alias["mb_name"] = "ifnull(mb_name,'')";
        $this->alias["mb_nickname"] = "ifnull(mb_nickname,'')";
        $this->alias["mb_profile_img"] = "ifnull(mb_profile_img,'')";
        $col = get_alias(
            $this->alias,
            [
                "review_idx",
                "review_by_mb_idx",
                "review_title",
                "review_tags",
                "review_content",
                "review_img1",
                "review_img2",
                "review_img3",
                "review_rating",
                "review_answer",
                "review_block_yn",
                "review_reg_dt",
                "mb_id",
                "mb_name",
                "mb_nickname",
                "mb_profile_img",
            ],
            true
        );
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
