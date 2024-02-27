<?php
namespace applications\controllers;

class reviewController extends Controller
{
    public function getList()
    {
        $this->filename = "";
        $request = $_GET;
        // --------------------------------------
        $model = new \common\models\ReviewModel();
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
        // --------------------------------------
        $model = new \common\models\ReviewModel();
        $search = [];
        $search["review_idx"] = $this->params["ident"];
        $row = $model->get($search);
        echo json_encode($row["returnData"]);
    }

    public function form()
    {
        $this->filename = "popup";
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
        // ---------------------------------------
        $model = new \common\models\ReviewModel();
        $search = [];
        $search["review_idx"] = $this->params["ident"];
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
