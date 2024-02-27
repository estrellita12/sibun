<?php
namespace applications\controllers;

class storeController extends Controller
{
    public function getList()
    {
        $this->filename = "";
        $request = $_GET;

        $model = new \common\models\StoreModel();
        //$model->leftJoin("store_pt_idx", "web_partner", "pt_idx");
        $search = $this->getSearch($request);
        $row = $model->getList($search);
        echo json_encode($row["returnData"]);
    }

    public function list()
    {
        $this->filename = "aside";
        $this->request = $_GET;
    }

    public function ctgList()
    {
        $this->filename = "";
        $model = new \common\models\CategoryModel();
        $search = [];
        $search["col"] = "ctg_orderby";
        $row = $model->select("ctg_idx,ctg_title", $search, true);
        if ($row["returnCode"] == "000") {
            $ctg = [];
            foreach ($row["returnData"] as $data) {
                $ctg[$data["ctg_idx"]] = $data["ctg_title"];
            }
            echo json_encode($ctg);
        }
    }

    public function get()
    {
        $this->filename = "";
        $request = $_GET;
        if (empty($this->params["ident"])) {
            return;
        }
        // -------------------------------------
        $model = new \common\models\StoreModel();
        $search = [];
        $search["store_idx"] = $this->params["ident"];
        $row = $model->get($search);
        if ($row["returnCode"] == "000") {
            echo json_encode($row["returnData"]);
        }
    }

    public function form()
    {
        $this->filename = "popup";
    }

    private function getValue($request)
    {
        return $request;
    }

    public function add()
    {
        $this->filename = "";
        $request = $_POST;
        if( empty($request) ){
            $this->jsAccess("실패","back");
        }
        // -------------------------------------
        $model = new \common\models\StoreModel();
        $value = $this->getValue($request);
        $row = $model->add($value);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }

    public function set()
    {
        $this->filename = "";
        $request = $_POST;
        if( empty($request) ){
            $this->jsAccess("실패","back");
        }
        if( $this->params['ident'] ){
            $this->jsAccess("실패","back");
        }
        // -------------------------------------
        $model = new \common\models\StoreModel();
        $value = $this->getValue($request);
        $search = [];
        $search["store_idx"] = $this->params["ident"];
        $row = $model->set($value, $search);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }
}
?>
