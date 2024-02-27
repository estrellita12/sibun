<?php
namespace applications\controllers;

class reservationController extends Controller
{
    public function getList()
    {
        $this->filename = "";
        $request = $_GET;
        // --------------------------------------------
        $model = new \common\models\ReservationModel();
        $model->leftJoin("reservation_store_idx", "web_store", "store_idx");
        $search = $this->getSearch($request);
        $row = $model->getList($search);
        echo json_encode($row["returnData"]);
    }

    public function list()
    {
        $this->filename = "aside";
        $this->request = $_GET;
    }

    public function get()
    {
        $this->filename = "";
        if (empty($this->params["ident"])) {
            return;
        }
        // -------------------------------------------
        $model = new \common\models\ReservationModel();
        $model->leftJoin("reservation_store_idx", "web_store", "store_idx");
        $search = [];
        $search["reservation_idx"] = $this->params["ident"];
        $row = $model->get($search);
        echo json_encode($row["returnData"]);
    }

    public function form()
    {
        $this->filename = "popup";
    }

    public function add()
    {
        $this->filename = "";
        $request = $_POST;
        if (empty($request)) {
            $this->jsAccess("001", "back");
            return;
        }
        // -------------------------------------------------
        $model = new \common\models\ReservationModel();
        $value = $request;
        $value["reservation_code"] = generateRandomString(5);
        $row = $model->add($value);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        } else {
            $this->jsAccess($row["returnCode"], "back");
        }
    }

    public function set()
    {
        $this->filename = "";
        if (empty($this->params["ident"])) {
            $this->jsAccess("001", "back");
            return;
        }
        $request = $_POST;
        if (empty($request)) {
            $this->jsAccess("001", "back");
            return;
        }
        // -------------------------------------------
        $model = new \common\models\ReservationModel();
        $search = [];
        $search["reservation_idx"] = $this->params["ident"];
        $value = $request;
        unset($value["reservation_idx"]);
        unset($value["reservation_code"]);
        $row = $model->set($value, $search);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        } else {
            $this->jsAccess($row["returnCode"], "back");
        }
    }

    public function getLogList()
    {
        $this->filename = "";
        $request = $_GET;
        if (empty($this->params["ident"])) {
            echo json_encode([]);
            return;
        }
        //----------------------------------------------------
        $model = new \common\models\ReservationHistoryModel();
        $search = [];
        $search["rsvt_hty_by_reservation_idx"] = $this->params["ident"];
        $row = $model->getList($search);
        echo json_encode($row["returnData"]);
    }

    public function loglist()
    {
        $this->filename = "popup";
    }
}
?>
