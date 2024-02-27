<?php
namespace applications\models;

class MemberAddressModel extends \common\models\MemberAddressModel
{
    public function __construct()
    {
        parent::__construct();
        $this->alias = [
            "mb_addr_idx" => "mb_addr_idx",
            "mb_addr_by_mb_idx" => "mb_addr_by_mb_idx",
            "mb_addr_name" => "ifnull(mb_addr_name,'')",
            "mb_addr_address" => "ifnull(mb_addr_address,'')",
            "mb_addr_x" => "ifnull(mb_addr_x,'')",
            "mb_addr_y" => "ifnull(mb_addr_y,'')",
            "mb_addr_reg_dt" => "ifnull(mb_addr_reg_dt,'')",
            "mb_addr_update_dt" => "ifnull(mb_addr_update_dt,'')",
        ];
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "mb_addr_reg_dt";
        $search["colby"] = "asc";

        if (!empty($request["mb_addr_idx"])) {
            $search["mb_addr_idx"] = $request["mb_addr_idx"];
        }
        if (!empty($request["mb_addr_by_mb_idx"])) {
            $search["mb_addr_by_mb_idx"] = $request["mb_addr_by_mb_idx"];
        }
        if (!empty($request["rpp"])) {
            $search["rpp"] = $request["rpp"];
        }
        if (!empty($request["page"])) {
            $search["page"] = $request["page"];
        }
        return $search;
    }

    public function getList($request)
    {
        $col = get_alias(
            $this->alias,
            [
                "mb_addr_idx",
                "mb_addr_name",
                "mb_addr_address",
                "mb_addr_x",
                "mb_addr_y",
            ],
            true
        );
        $search = $this->getSearch($request);
        return parent::select($col, $search, true);
    }

    public function get($request)
    {
        $col = get_alias($this->alias);
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }
}

?>
