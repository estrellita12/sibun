<?php
namespace applications\controllers;

class bannerController extends Controller
{
    public function getList()
    {
        $this->filename = "";
        $this->request = $_GET;
        $search = $this->getSearch($this->request);
        $model = new \common\models\BannerModel();
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
        if (empty($this->params["ident"])) {
            return;
        }
        $this->filename = "";
        $search = [];
        $search["bn_idx"] = $this->params["ident"];
        $model = new \common\models\BannerModel();
        $row = $model->get($search);
        if ($row["returnCode"] == "000") {
            $row = $row["returnData"];
            if (!empty($row["bn_beg_dt"])) {
                $beg = explode(" ", $row["bn_beg_dt"]);
                $row["bn_beg_date"] = $beg[0];
                $row["bn_beg_time"] = $beg[1];
            }
            if (!empty($row["bn_end_dt"])) {
                $end = explode(" ", $row["bn_end_dt"]);
                $row["bn_end_date"] = $end[0];
                $row["bn_end_time"] = $end[1];
            }
            echo json_encode($row);
        }
    }

    public function form()
    {
        $this->filename = "popup";
    }

    public function getValue($value)
    {
        if (!empty($value["bn_beg_date"]) && !empty($value["bn_beg_time"])) {
            $value["bn_beg_dt"] = $value["bn_beg_date"] . " " . $value["bn_beg_time"];
            unset($value["bn_beg_date"]);
            unset($value["bn_beg_time"]);
        }
        if (!empty($value["bn_end_date"]) && !empty($value["bn_end_time"])) {
            $value["bn_end_dt"] = $value["bn_end_date"] . " " . $value["bn_end_time"];
            unset($value["bn_end_date"]);
            unset($value["bn_end_time"]);
        }
        return $value;
    }
    public function add()
    {
        $value = $this->getValue($_POST);
        $model = new \common\models\BannerModel();
        $row = $model->add($value);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }

    public function set()
    {
        $value = $this->getValue($_POST);
        $model = new \common\models\BannerModel();
        $search = [];
        $search["bn_idx"] = $this->params["ident"];
        $row = $model->set($value, $search);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }
}
?>
