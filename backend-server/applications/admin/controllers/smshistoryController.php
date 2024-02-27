<?php
namespace applications\controllers;

class smshistoryController extends Controller
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
        $model = new \common\models\SmsHistoryModel();
        $search = $this->getSearch($request);
        $search["col"] = "sms_hty_reg_dt";
        $search["colby"] = "desc";
        $row = $model->getList($search);
        echo json_encode($row["returnData"]);
    }
}
?>
