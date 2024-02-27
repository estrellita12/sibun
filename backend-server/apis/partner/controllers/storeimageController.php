<?php
namespace applications\controllers;

class storeimageController extends Controller
{
    public function init()
    {
        if (_DEV) {
            $this->request["put"] = [
                "store_img_data" => "data:image/jpg;base64,111111",
                "store_img_orderby" => "1",
            ];
        }

        $this->checkAuthorization("Bearer");
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Empty Ident");
        }
        $model = new \applications\models\StoreModel();
        if (
            !$model->isOwnerPartnerCheck(
                $this->params["ident"],
                $this->token["pt_idx"]
            )
        ) {
            $this->error_json("005", "Permission Denied");
        }
    }

    public function getList()
    {
        $request = $this->request["get"];
        $request["store_img_by_store_idx"] = $this->params["ident"];
        // ------------------------------------------------
        $model = new \applications\models\StoreImageModel();
        $row = $model->getList($request);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", [
            "list" => $row["returnData"],
        ]);
    }

    public function get()
    {
        $request = $this->request["get"];
        if (empty($this->params["subident"])) {
            $this->error_json("002", "Ident Empty");
        }
        // ------------------------------------------------
        $model = new \applications\models\StoreImageModel();
        $search = [];
        $search["store_img_by_store_idx"] = $this->params["ident"];
        $search["store_img_idx"] = $this->params["subident"];
        $row = $model->get($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", [
            "data" => $row["returnData"],
        ]);
    }

    public function add()
    {
        $request = $this->request["put"];
        if (empty($request)) {
            $this->error_json("002", "Request Empty");
        }

        $required = ["store_img_data"];
        if (!check_required($required, $request)) {
            $this->error_json("002", "Required Error");
        }
        // ------------------------------------------------
        $model = new \applications\models\StoreImageModel();
        $value = [];
        $value = $model->getValue($request, "add");
        $value["store_img_by_store_idx"] = $this->params["ident"];
        $row = $model->add($value);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", [
            "data" => $row["returnData"],
        ]);
    }

    public function set()
    {
        $request = $this->request["post"];
        if (empty($request)) {
            $this->error_json("002", "Request Empty");
        }
        if (empty($this->params["subident"])) {
            $this->error_json("002", "Ident Empty");
        }
        //-----------------------------------------------
        $model = new \applications\models\StoreImageModel();
        $search = [];
        $search["store_img_by_store_idx"] = $this->params["ident"];
        $search["store_img_idx"] = $this->params["subident"];
        $value = [];
        $value = $model->getValue($request, "set");
        $row = $model->set($value, $search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", [
            "data" => $row["returnData"],
        ]);
    }

    public function remove()
    {
        $request = $this->request["delete"];
        if (empty($this->params["subident"])) {
            $this->error_json("002", "Ident Empty");
        }
        // ---------------------------------------------------
        $model = new \applications\models\StoreImageModel();
        $search = [];
        $search["store_img_by_store_idx"] = $this->params["ident"];
        $search["store_img_idx"] = $this->params["subident"];
        $row = $model->remove($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", [
            "data" => $row["returnData"],
        ]);
    }
}

?>
