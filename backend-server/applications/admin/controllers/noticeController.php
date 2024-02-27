<?php
namespace applications\controllers;

class noticeController extends Controller
{
    public function list()
    {
        $this->filename = "aside";
        $this->request = $_GET;
        $search = $this->getSearch($this->request);
        $model = new \common\models\NoticeModel();
        $row = $model->getList($search);
        if ($row["returnCode"] == "000") {
            $this->row = $row["returnData"];
        }
    }

    public function mlist()
    {
        $this->filename = "aside";
        $this->request = $_GET;
        $search = $this->getSearch($this->request);
        $search["col"] = "notice_reg_dt";
        $search["colby"] = "desc";
        $model = new \common\models\NoticeMemberModel();
        $row = $model->getList($search);
        if ($row["returnCode"] == "000") {
            $this->row = $row["returnData"];
        }
    }
    public function plist()
    {
        $this->filename = "aside";
        $this->request = $_GET;
        $search = $this->getSearch($this->request);
        $search["col"] = "notice_reg_dt";
        $search["colby"] = "desc";
        $model = new \common\models\NoticePartnerModel();
        $row = $model->getList($search);
        if ($row["returnCode"] == "000") {
            $this->row = $row["returnData"];
        }
    }
}
?>
