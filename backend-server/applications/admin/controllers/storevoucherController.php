<?php
namespace applications\controllers;

class storevoucherController extends Controller
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

        $model = new \common\models\StoreVoucherModel();
        $search = [];
        $search["store_voucher_by_store_idx"] = $this->params["ident"];
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

        $model = new \common\models\StoreVoucherModel();
        $search = [];
        $search["store_voucher_idx"] = $this->params["ident"];
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
            $this->jsAccess("필수 데이터가 없습니다.", "back");
        }
        // -------------------------------------
        $model = new \common\models\StoreVoucherModel();
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
        if( empty($this->params['ident']) ){
            $this->jsAccess("필수 데이터가 없습니다.", "back");
        }
        if( empty($request) ){
            $this->jsAccess("필수 데이터가 없습니다.", "back");
        }
        // --------------------------------------------
        $model = new \common\models\StoreVoucherModel();
        $value = $this->getValue($request);
        $search = [];
        $search["store_voucher_idx"] = $this->params["ident"];
        $row = $model->set($value, $search);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }




}
?>
