<?php
namespace applications\models;

class StoreReservationModel extends ReservationModel
{
    public function __construct()
    {
        parent::__construct();
    }
    /*
    public function getSearch($request = [])
    {
        $search = parent::getSearch($request);
        if (!empty($request["reservation_time"])) {
            $search["reservation_time__then__le"] =
                $request["reservation_time"];
            $search["reservation_end_time__then__ge"] =
                $request["reservation_time"];
        }
        if (!empty($request["reservation_beg_time"])) {
            $search["reservation_time__then__le"] =
                $request["reservation_beg_time"];
        }
        if (!empty($request["reservation_end_time"])) {
            $search["reservation_end_time__then__ge"] =
                $request["reservation_end_time"];
        }
        return $search;
    }
    */

    public function getList($request = [])
    {
        //$this->sql_from = " (select *,reservation_time+reservation_period as reservation_end_time from {$this->tb_nm} ) c ";
        $col = get_alias(
            $this->alias,
            [
                "reservation_date",
                "reservation_time",
                "reservation_room_idx",
                "reservation_voucher_idx",
                "reservation_period",
            ],
            true
        );

        $search = $this->getSearch($request);
        $type = "fetch";
        if (!empty($request["type"])) {
            $group_col = [
                "reservation_date",
                "reservation_time",
                "reservation_room_idx",
                "reservation_voucher_idx",
            ];
            if (in_array($request["type"], $group_col)) {
                $type = "group";
                $col = $request["type"] . "," . $col;
            }
        }
        return $this->select($col, $search, true, $type);
    }
}

?>
