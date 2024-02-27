<?php
namespace applications\controllers;

class mediaController extends Controller
{
    public function getList()
    {
        $this->filename = "";
        $this->request = $_GET;
        $search = $this->getSearch($this->request);
        $model = new \common\models\MediaModel();
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
        $model = new \common\models\MediaModel();
        $search = [];
        $search["media_idx"] = $this->params["ident"];
        $row = $model->get($search);
        echo json_encode($row["returnData"]);
    }

    public function form()
    {
        $this->filename = "popup";
    }

    public function add()
    {
        $value = $_POST;
        $model = new \common\models\MediaModel();
        $row = $model->add($value);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }

    public function set()
    {
        $value = $_POST;
        $model = new \common\models\MediaModel();
        $search = [];
        $search["media_idx"] = $this->params["ident"];
        $row = $model->set($value, $search);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }
}
?>
