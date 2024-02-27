<?php
namespace applications\controllers;

class popupController extends Controller
{
    public function list()
    {
        $this->filename = "aside";
        $this->request = $_GET;
    }

    public function getList()
    {
        $this->filename = "";
        $request = $_GET;
        $model = new \common\models\PopupModel();
        $search = $this->getSearch($request);
        $row = $model->getList($search);
        echo json_encode($row["returnData"]);
    }

    public function form()
    {
        $this->filename = "popup";
    }

    public function get()
    {
        $this->filename = "";
        if (empty($this->params["ident"])) {
            return;
        }
        $model = new \common\models\PopupModel();
        $search = [];
        $search["popup_idx"] = $this->params["ident"];
        $row = $model->get($search);
        if ($row["returnCode"] == "000") {
            $row = $row["returnData"];
            if (!empty($row["popup_beg_dt"])) {
                $beg = explode(" ", $row["popup_beg_dt"]);
                $row["popup_beg_date"] = $beg[0];
                $row["popup_beg_time"] = $beg[1];
            }
            if (!empty($row["popup_end_dt"])) {
                $end = explode(" ", $row["popup_end_dt"]);
                $row["popup_end_date"] = $end[0];
                $row["popup_end_time"] = $end[1];
            }
            echo json_encode($row);
        }
    }

    public function getValue($value)
    {
        if (!empty($value["popup_beg_date"]) && !empty($value["popup_beg_time"])) {
            $value["popup_beg_dt"] = $value["popup_beg_date"] . " " . $value["popup_beg_time"];
            unset($value["popup_beg_date"]);
            unset($value["popup_beg_time"]);
        }
        if (!empty($value["popup_end_date"]) && !empty($value["popup_end_time"])) {
            $value["popup_end_dt"] = $value["popup_end_date"] . " " . $value["popup_end_time"];
            unset($value["popup_end_date"]);
            unset($value["popup_end_time"]);
        }
        return $value;
    }
    public function add()
    {
        $value = $this->getValue($_POST);
        $model = new \common\models\PopupModel();
        $row = $model->add($value);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }

    public function set()
    {
        $value = $this->getValue($_POST);
        $model = new \common\models\PopupModel();
        $search = [];
        $search["popup_idx"] = $this->params["ident"];
        $row = $model->set($value, $search);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }
}
?>
