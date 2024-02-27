<?php
namespace applications\models;

class MemberLikeModel extends \common\models\MemberLikeModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getValue($request = [], $type = "add")
    {
        $value = [];
        if (!empty($request["mb_like_by_mb_idx"])) {
            $value["mb_like_by_mb_idx"] = $request["mb_like_by_mb_idx"];
        }
        if (!empty($request["mb_like_store_idx"])) {
            $value["mb_like_store_idx"] = $request["mb_like_store_idx"];
        }
        return $value;
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "mb_like_reg_dt";
        $search["colby"] = "asc";

        if (!empty($request["mb_like_idx"])) {
            $search["mb_like_idx"] = $request["mb_like_idx"];
        }
        if (!empty($request["mb_like_by_mb_idx"])) {
            $search["mb_like_by_mb_idx"] = $request["mb_like_by_mb_idx"];
        }
        if (!empty($request["mb_like_store_idx"])) {
            $search["mb_like_store_idx"] = $request["mb_like_store_idx"];
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
        $col = get_alias($this->alias, ["mb_like_idx","mb_like_store_idx","mb_like_reg_dt"], true);
        $search = $this->getSearch($request);
        return parent::select($col, $search, true);
    }

    public function get($request = [])
    {
        $col = get_alias($this->alias, [], false);
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }

}

?>
