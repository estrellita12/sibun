<?php
namespace applications\controllers;

class testController extends Controller
{
    public function getList()
    {
        $this->filename = "";
        $request = $_GET;
        $model = new \common\models\TestModel();
        $search = $this->getSearch($request);
        $search["col"] = "od_time";
        $search["colby"] = "desc";

        //$search["od_time__then__ge"] = "2023-09-01";
        $row = $model->getList($search);
        echo json_encode($row["returnData"]);
    }

    public function list()
    {
    }
}
?>
