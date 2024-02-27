<?php
namespace applications\models;

class StoreRoomModel extends \common\models\StoreRoomModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "store_room_orderby";
        $search["colby"] = "asc";
        $search["store_room_use_yn"] = "y";

        if (!empty($request["store_room_idx"])) {
            $search["store_room_idx"] = $request["store_room_idx"];
        }
        if (!empty($request["store_room_by_store_idx"])) {
            $search["store_room_by_store_idx"] =
                $request["store_room_by_store_idx"];
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
            ["store_room_idx", "store_room_name", "store_room_desc"],
            true
        );

        $search = $this->getSearch($request);
        return parent::select($col, $search, true);
    }

    public function get($request = [])
    {
        $col = get_alias($this->alias, ["store_room_update_dt"], false);
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }
}

?>