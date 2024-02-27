<?php
namespace applications\controllers;

class notihistoryController extends Controller
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
        // ------------------------------------------
        $model = new \common\models\NotificationHistoryModel();
        $search = $this->getSearch($request);
        $search["col"] = "noti_hty_reg_dt";
        $search["colby"] = "desc";
        $row = $model->getList($search);
        echo json_encode($row["returnData"]);
    }
}
?>
