<?php
namespace applications\controllers;

class memberController extends Controller
{
    public function getList()
    {
        $this->filename = "";
        $request = $_GET;
        $model = new \common\models\MemberModel();
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
        // -------------------------------------
        $model = new \common\models\MemberModel();
        $search = [];
        $search["mb_idx"] = $this->params["ident"];
        $row = $model->get($search);
        echo json_encode($row["returnData"]);
    }

    public function form()
    {
        $this->filename = "popup";
    }

    private function getValue($request)
    {
        $value = $request;
        if (empty($request["mb_ninkname"])) {
            $value["mb_nickname"] = createNickName();
        }
        return $value;
    }

    public function add()
    {
        $this->filename = "";
        $request = $_POST;
        if( empty($request) ){
            $this->jsAccess("실패", "back");
        }
        // ---------------------------------------
        $model = new \common\models\MemberModel();
        $value = $this->getValue($request);
        $row = $model->add($value);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }else{
            $this->jsAccess("실패 {$row["returnCode"]}", "back");
        }
    }

    public function set()
    {
        $this->filename = "";
        $request = $_POST;
        if( empty($request) ){
            $this->jsAccess("실패", "back");
        }
        if( empty($this->params['ident']) ){
            $this->jsAccess("실패", "back");
        }
        // -------------------------------------
        $model = new \common\models\MemberModel();
        $value = $this->getValue($request);
        $search = [];
        $search["mb_idx"] = $this->params["ident"];
        $row = $model->set($value, $search);
        if ($row["returnCode"] == "000") {
            $this->jsAccess("성공", "close");
        }
    }

    public function getLeavelist()
    {
        $this->filename = "";
        $this->request = $_GET;
        $search = $this->getSearch($this->request);
        $model = new \common\models\MemberLeaveModel();
        $row = $model->getList($search);
        echo json_encode($row["returnData"]);
    }

    public function leavelist()
    {
        $this->filename = "aside";
        $this->request = $_GET;
        $search = $this->getSearch($this->request);
        $model = new \common\models\MemberLeaveModel();
        $row = $model->getList($search);
        if ($row["returnCode"] == "000") {
            $this->row = $row["returnData"];
        }
    }
}
?>
