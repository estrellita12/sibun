<?php
namespace applications\controllers;

class adminController extends Controller
{
    public function getList()
    {
        $this->filename = "";
        $request = $_GET;
        // -------------------------------------
        $model = new \common\models\AdminModel();
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
        // ----------------------------------------
        $model = new \common\models\AdminModel();
        $search = [];
        $search["adm_idx"] = $this->params["ident"];
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
        $request = $_POST;
        // -------------------------------------
        $model = new \common\models\AdminModel();
        $value = $this->getValue($request);
        $row = $model->add($value);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        } else {
            $this->jsAccess("실패", "back");
        }
    }

    public function set()
    {
        $request = $_POST;
        // -------------------------------------
        $model = new \common\models\AdminModel();
        $search = [];
        $search["adm_idx"] = $this->params["ident"];
        $value = $request;
        $row = $model->set($value, $search);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        } else {
            $this->jsAccess("실패", "back");
        }
    }
}
?>
