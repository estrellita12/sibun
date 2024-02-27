<?php
namespace applications\models;

class BannerModel extends \common\models\BannerModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "bn_orderby";
        $search["colby"] = "asc";
        $search["bn_use_yn"] = "y";

        if (!empty($request["bn_idx"])) {
            $search["bn_idx"] = $request["bn_idx"];
        }
        if (!empty($request["bn_beg_dt"])) {
            $beg_dt = str_replace("%20", " ", $request["bn_beg_dt"]);
            $beg_dt = explode(" ", $beg_dt);
            $search["bn_beg_dt__then__le"] = $beg_dt[0] . " " . $beg_dt[1];
        }
        if (!empty($request["bn_end_dt"])) {
            $end_dt = str_replace("%20", " ", $request["bn_end_dt"]);
            $end_dt = explode(" ", $end_dt);
            $search["bn_end_dt__then__ge"] = $end_dt[0] . " " . $end_dt[1];
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
                "bn_idx",
                "bn_title",
                "bn_img_src",
                "bn_link",
                "bn_target",
                "bn_beg_dt",
                "bn_end_dt",
            ],
            true
        );
        $search = $this->getSearch($request);
        return parent::select($col, $search, true);
    }

    public function get($request = [])
    {
        $col = get_alias($this->alias, ["bn_update_dt"], false);
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }
}

?>
