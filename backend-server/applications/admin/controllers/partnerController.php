<?php
namespace applications\controllers;

class partnerController extends Controller
{
    public function getList()
    {
        $this->filename = "";
        $request = $_GET;
        $search = $this->getSearch($request);
        $model = new \common\models\PartnerModel();
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
        $search = [];
        $search["pt_idx"] = $this->params["ident"];
        $model = new \common\models\PartnerModel();
        $row = $model->get($search);
        echo json_encode($row["returnData"]);
    }

    public function form()
    {
        $this->filename = "popup";
    }

    private function getValue($value)
    {
        return $value;
    }

    public function add()
    {
        $this->filename = "";
        $value = $this->getValue($_POST);
        $model = new \common\models\PartnerModel();
        $row = $model->add($value);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }

    public function set()
    {
        $this->filename = "";
        $value = $this->getValue($_POST);
        $model = new \common\models\PartnerModel();
        $search = [];
        $search["pt_idx"] = $this->params["ident"];
        $row = $model->set($value, $search);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }
}
?>
