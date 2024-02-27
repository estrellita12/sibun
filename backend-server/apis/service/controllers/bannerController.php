<?php
namespace applications\controllers;

class bannerController extends Controller
{
    public function init()
    {
    }

    public function getList()
    {
        $this->checkAuthorization("Basic");
        $request = $this->request["get"];
        // -------------------------------------------
        $model = new \applications\models\BannerModel();
        $row = $model->getList($request);
        if ($row["returnCode"] != "000") {
            $this->response_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", [
            "list" => $row["returnData"],
        ]);
    }

    public function get()
    {
        $this->checkAuthorization("Basic");
        $request = $this->request["get"];
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Ident Empty");
        }
        // -------------------------------------------
        $model = new \applications\models\BannerModel();
        $search = [];
        $search["bn_idx"] = $this->params["ident"];
        $row = $model->get($search);
        if ($row["returnCode"] != "000") {
            $this->response_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", [
            "data" => $row["returnData"],
        ]);
    }
}
?>
