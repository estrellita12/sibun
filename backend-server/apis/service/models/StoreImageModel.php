<?php
namespace applications\models;

class StoreImageModel extends \common\models\StoreImageModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "store_img_orderby";
        $search["colby"] = "asc";

        if (!empty($request["store_img_idx"])) {
            $search["store_img_idx"] = $request["store_img_idx"];
        }
        if (!empty($request["store_img_by_store_idx"])) {
            $search["store_img_by_store_idx"] =
                $request["store_img_by_store_idx"];
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
            ["store_img_idx", "store_img_data"],
            true
        );
        $search = $this->getSearch($request);
        return parent::select($col, $search, true);
    }

    public function get($request = [])
    {
        $col = get_alias($this->alias, ["store_img_update_dt"], false);
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }
}

?>
