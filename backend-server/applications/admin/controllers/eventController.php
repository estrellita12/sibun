<?php
namespace applications\controllers;

class eventController extends Controller
{
    public function list()
    {
        $this->filename = "aside";
        $this->request = $_GET;
    }

    public function getList()
    {
        $this->filename = "";
        $this->request = $_GET;
        $search = $this->getSearch($this->request);
        $model = new \common\models\EventModel();
        $row = $model->getList($search);
        echo json_encode($row["returnData"]);
    }

    public function form()
    {
        $this->filename = "popup";
    }

    public function get()
    {
        if (empty($this->params["ident"])) {
            return;
        }
        $this->filename = "";
        $model = new \common\models\EventModel();
        $search = [];
        $search["event_idx"] = $this->params["ident"];
        $row = $model->get($search);
        if ($row["returnCode"] == "000") {
            $row = $row["returnData"];
            if (!empty($row["event_beg_dt"])) {
                $beg = explode(" ", $row["event_beg_dt"]);
                $row["event_beg_date"] = $beg[0];
                $row["event_beg_time"] = $beg[1];
            }
            if (!empty($row["event_end_dt"])) {
                $end = explode(" ", $row["event_end_dt"]);
                $row["event_end_date"] = $end[0];
                $row["event_end_time"] = $end[1];
            }
            echo json_encode($row);
        }
    }

    public function getValue($value)
    {
        if (!empty($value["event_beg_date"]) && !empty($value["event_beg_time"])) {
            $value["event_beg_dt"] = $value["event_beg_date"] . " " . $value["event_beg_time"];
            unset($value["event_beg_date"]);
            unset($value["event_beg_time"]);
        }
        if (!empty($value["event_end_date"]) && !empty($value["event_end_time"])) {
            $value["event_end_dt"] = $value["event_end_date"] . " " . $value["event_end_time"];
            unset($value["event_end_date"]);
            unset($value["event_end_time"]);
        }
        return $value;
    }
    public function add()
    {
        $value = $this->getValue($_POST);
        $model = new \common\models\EventModel();
        $row = $model->add($value);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }

    public function set()
    {
        $value = $this->getValue($_POST);
        $model = new \common\models\EventModel();
        $search = [];
        $search["event_idx"] = $this->params["ident"];
        $row = $model->set($value, $search);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }
}
?>
