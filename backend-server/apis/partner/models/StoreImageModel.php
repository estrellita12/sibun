<?php
namespace applications\models;

class StoreImageModel extends \common\models\StoreImageModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getValue($request = [], $type = "add")
    {
        $value = [];
        $valueColumnArray = [
            "store_img_by_store_idx",
            "store_img_data",
            "store_img_orderby",
        ];
        foreach ($valueColumnArray as $col) {
            if (!empty($request[$col])) {
                $value[$col] = $request[$col];
            }
        }
        if ($type != "add") {
            unset($value["store_img_by_store_idx"]);
        }
        return $value;
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "store_img_orderby * 1";
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
            ["store_img_idx", "store_img_data", "store_img_orderby"],
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
