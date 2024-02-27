<?php
namespace applications\models;

class ReviewModel extends \common\models\ReviewModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getValue($request = [], $type = "add")
    {
        $value = [];
        if (!empty($request["review_by_store_idx"])) {
            $value["review_by_store_idx"] = $request["review_by_store_idx"];
        }
        if (!empty($request["review_by_mb_idx"])) {
            $value["review_by_mb_idx"] = $request["review_by_mb_idx"];
        }
        if (!empty($request["review_title"])) {
            $value["review_title"] = $request["review_title"];
        }
        if (!empty($request["review_tags"])) {
            $value["review_tags"] = $request["review_tags"];
        }
        if (!empty($request["review_content"])) {
            $value["review_content"] = $request["review_content"];
        }
        if (!empty($request["review_img1"])) {
            $value["review_img1"] = $request["review_img1"];
        }
        if (!empty($request["review_img2"])) {
            $value["review_img2"] = $request["review_img2"];
        }
        if (!empty($request["review_img3"])) {
            $value["review_img3"] = $request["review_img3"];
        }
        if (!empty($request["review_rating"])) {
            $value["review_rating"] = $request["review_rating"];
        }
        if (!empty($request["review_reservation_idx"])) {
            $value["review_reservation_idx"] =
                $request["review_reservation_idx"];
        }
        return $value;
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "review_reg_dt";
        $search["colby"] = "desc";
        $search["review_block_yn"] = "n";

        if (!empty($request["review_idx"])) {
            $search["review_idx"] = $request["review_idx"];
        }
        if (!empty($request["review_by_store_idx"])) {
            $search["review_by_store_idx"] = $request["review_by_store_idx"];
        }
        if (!empty($request["review_by_mb_idx"])) {
            $search["review_by_mb_idx"] = $request["review_by_mb_idx"];
        }
        if (!empty($request["review_answer_yn"])) {
            if ($request["review_answer_yn"] == "y") {
                $search["review_answer__null__not"] = "";
            } else {
                $search["review_answer__null__qe"] = "";
            }
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
        //$this->leftJoin("review_by_store_idx", $storeModel->tb_nm, "store_idx");
        $this->leftMultiJoin("review_by_store_idx", "web_store", "store_idx", "review_reservation_idx", "web_reservation", "reservation_idx");
        $this->alias["store_name"] = "ifnull(store_name,'')";
        $this->alias["store_tel"] = "ifnull(store_tel,'')";
        $this->alias["store_addr"] = "ifnull(store_addr,'')";
        $this->alias["store_main_simg"] = "ifnull(store_main_simg,'')";
        $this->alias["reservation_idx"] = "ifnull(reservation_idx,'')";
        $this->alias["reservation_date"] = "ifnull(reservation_date,'')";
        $this->alias["reservation_time"] = "ifnull(reservation_time,'')";
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
                "review_reg_dt",
                "review_reservation_idx",
                "store_name",
                "store_tel",
                "store_addr",
                "store_main_simg",
                "reservation_idx",
                "reservation_date",
                "reservation_time"
            ],
            true
        );
        $search = $this->getSearch($request);
        return parent::select($col, $search, true);
    }

    public function get($request = [])
    {
        //$this->leftJoin("review_by_store_idx", "web_store", "store_idx");
        $this->leftMultiJoin("review_by_store_idx", "web_store", "store_idx", "review_reservation_idx", "web_reservation", "reservation_idx");
        $this->alias["store_name"] = "ifnull(store_name,'')";
        $this->alias["store_tel"] = "ifnull(store_tel,'')";
        $this->alias["store_addr"] = "ifnull(store_addr,'')";
        $this->alias["store_main_simg"] = "ifnull(store_main_simg,'')";
        $this->alias["reservation_idx"] = "ifnull(reservation_idx,'')";
        $this->alias["reservation_date"] = "ifnull(reservation_date,'')";
        $this->alias["reservation_time"] = "ifnull(reservation_time,'')";
        $this->alias["reservation_period"] = "ifnull(reservation_period,'')";
        $col = get_alias($this->alias, ["review_update_dt"], false);
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }

    public function add($value = [], $check = true)
    {
        $this->pdo->beginTransaction();
        $reservationModel = new \common\models\ReservationModel();
        $reservationModel->pdo = $this->pdo;
        $search = [];
        $search["reservation_idx"] = $value["review_reservation_idx"];
        $row = $reservationModel->select(
            "reservation_store_idx,reservation_review_yn",
            $search,
            false
        );
        if ($row["returnCode"] != "000") {
            return $row;
        }
        $data = $row["returnData"];
        if ($data["reservation_review_yn"] == "y") {
            return [
                "returnCode" => "003",
                "returnData" => "Duplicate Request",
            ];
        }
        $value["review_by_store_idx"] = $data["reservation_store_idx"];

        $search = [];
        $search["reservation_idx"] = $value["review_reservation_idx"];
        $row = $reservationModel->set(
            ["reservation_review_yn" => "y"],
            $search
        );
        if ($row["returnCode"] != "000") {
            return $row;
        }
        $row = parent::add($value);
        if ($row["returnCode"] != "000") {
            $this->pdo->rollBack();
            return $row;
        }

        $this->pdo->commit();
        return $row;
    }
}

?>
