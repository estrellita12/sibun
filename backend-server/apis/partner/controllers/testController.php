<?php
namespace applications\controllers;

class testController extends Controller
{
    public function init()
    {
        if (!_DEV) {
            $this->error_json("404", "Not Found");
        }
    }

    public function getToken()
    {
        $model = new \applications\models\PartnerModel();
        $pt_idx = 2;
        $row = $model->getToken($pt_idx);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", $row["returnData"]);
    }
}
?>
