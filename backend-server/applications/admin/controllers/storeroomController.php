<?php
namespace applications\controllers;

class storeroomController extends Controller
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

        $model = new \common\models\StoreRoomModel();
        $search = [];
        $search["store_room_by_store_idx"] = $this->params["ident"];
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
        $this->filename = "";
        $request = $_GET;
        if (empty($this->params["ident"])) {
            return;
        }
        // -----------------------------------------
        $model = new \common\models\StoreRoomModel();
        $search = [];
        $search["store_room_idx"] = $this->params["ident"];
        $row = $model->get($search);
        if ($row["returnCode"] == "000") {
            echo json_encode($row["returnData"]);
        }
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
        $model = new \common\models\StoreRoomModel();
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
        $model = new \common\models\StoreRoomModel();
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
