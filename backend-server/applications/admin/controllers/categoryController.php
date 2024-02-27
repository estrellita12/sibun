<?php
namespace applications\controllers;

class categoryController extends Controller
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

        $search = $this->getSearch($request);
        $model = new \common\models\CategoryModel();
        $row = $model->getList($search);
        if ($row["returnCode"] == "000") {
            echo json_encode($row["returnData"]);
        }
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
        $request = $_GET;

        $search = [];
        $search["ctg_idx"] = $this->params["ident"];
        $model = new \common\models\CategoryModel();
        $row = $model->get($search);
        if ($row["returnCode"] == "000") {
            echo json_encode($row["returnData"]);
        }
    }

    public function add()
    {
        $value = $_POST;
        $model = new \common\models\CategoryModel();
        $row = $model->add($value);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }

    public function set()
    {
        $value = $_POST;
        $model = new \common\models\CategoryModel();
        $search = [];
        $search["category_idx"] = $this->params["ident"];
        $row = $model->set($value, $search);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }
}
?>
