<?php
namespace applications\controllers;

class storeimageController extends Controller
{
    public function list()
    {
        $this->filename = "popup";
        $this->request = $_GET;
    }

    public function getList()
    {
        $this->filename = "";
        $request = $_GET;

        $model = new \common\models\StoreImageModel();
        $search = [];
        $search["store_img_by_store_idx"] = $this->params["ident"];
        $row = $model->getList($search);
        if ($row["returnCode"] == "000") {
            echo json_encode($row["returnData"]);
        } else {
            echo json_encode([]);
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

        $model = new \common\models\StoreImageModel();
        $search = [];
        $search["store_img_idx"] = $this->params["ident"];
        $row = $model->get($search);
        if ($row["returnCode"] == "000") {
            echo json_encode($row["returnData"]);
        }
    }
}
?>
