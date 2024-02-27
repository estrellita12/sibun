<?php
namespace applications\models;

class BtnModel extends \common\models\BtnModel
{
    public function __construct()
    {
        parent::__construct();
        $this->alias = [
            "btn_idx" => "btn_idx",
            "btn_by_grp_idx" => "ifnull(btn_by_grp_idx,'')",
            "btn_title" => "ifnull(btn_title,'')",
            "btn_orderby" => "ifnull(btn_orderby,0)",
            "btn_use_yn" => "ifnull(btn_use_yn,'')",
            "btn_reg_dt" => "ifnull(btn_reg_dt,'')",
            "btn_update_dt" => "ifnull(btn_update_dt,'')",
        ];
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "btn_orderby";
        $search["colby"] = "desc";
        $search["btn_use_yn"] = "y";
        if (!empty($request["btn_idx_in"])) {
            $arr = explode(",", $request["btn_idx_in"]);
            $arr = array_filter($arr);
            $in_all = implode(",", $arr);
            $search["btn_idx__in__all"] = $in_all;
        }

        if (!empty($request["btn_by_grp_idx"])) {
            $search["btn_by_grp_idx"] = $request["btn_by_grp_idx"];
        }

        if (!empty($request["rpp"])) {
            $search["rpp"] = $request["rpp"];
        }

        if (!empty($request["page"])) {
            $search["page"] = $request["page"];
        }

        return $search;
    }

    public function getList($col = "*", $search = [], $type = "assoc")
    {
        $model = new \common\models\BtnGroupModel();
        $this->leftJoin("btn_by_grp_idx", $model->tb_nm, "btn_grp_idx");
        return parent::getList($col, $search, $type);
    }

    public function get($col = "*", $search = [])
    {
        $model = new \common\models\BtnGroupModel();
        $this->leftJoin("btn_by_grp_idx", $model->tb_nm, "btn_grp_idx");
        return parent::get($col, $search);
    }
}

?>
