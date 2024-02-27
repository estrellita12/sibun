<?php
namespace applications\models;

class MediaModel extends \common\models\MediaModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "media_orderby";
        $search["colby"] = "asc";
        $search["media_use_yn"] = "y";

        if (!empty($request["media_idx"])) {
            $search["media_idx"] = $request["media_idx"];
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
                "media_idx",
                "media_title",
                "media_simg",
                "media_reference",
                "media_reg_dt",
            ],
            true
        );
        $search = $this->getSearch($request);
        return parent::select($col, $search, true);
    }

    public function get($request = [])
    {
        $col = get_alias($this->alias, ["media_update_dt"], false);
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }
}

?>
