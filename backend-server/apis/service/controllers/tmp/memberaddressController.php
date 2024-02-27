<?php
namespace applications\controllers;

class memberaddressController extends Controller
{
    public function init()
    {
        if (empty($this->params["ident"])) {
            $this->error_json("001", "Empty Ident");
        }
    }

    public function getList()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["get"];
        $request["mb_addr_by_mb_idx"] = $this->token["mb_idx"];

        $model = new \applications\models\MemberAddressModel();
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
        $this->checkAuthorization("Bearer");
        //$request = $this->request["get"];
        $request = [];
        $request["mb_addr_idx"] = $this->params["subident"];
        $request["mb_addr_by_mb_idx"] = $this->token["mb_idx"];

        $model = new \applications\models\MemberAddressModel();
        $row = $model->get($request);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function add()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["put"];

        $model = new \applications\models\MemberAddressModel();
        $value = $request;
        $value["mb_addr_by_mb_idx"] = $this->token["mb_idx"];
        $row = $model->add($value);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function set()
    {
        if (empty($this->params["ident"])) {
            $this->error_json("002");
        }

        $this->checkAuthorization("Bearer");
        $request = $this->request["post"];

        $model = new \applications\models\MemberAddressModel();
        $search = [];
        $search["mb_addr_idx"] = $this->params["ident"];
        $search["mb_addr_by_mb_idx"] = $this->token["mb_idx"];

        $value = [];
        $value = $request;
        $row = $model->set($value, $search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function remove()
    {
        if (empty($this->params["ident"])) {
            $this->error_json("002");
        }

        $this->checkAuthorization("Bearer");
        $request = $this->request["delete"];

        $model = new \applications\models\MemberAddressModel();
        $search = [];
        $search["mb_addr_idx"] = $this->params["ident"];
        $search["mb_addr_by_mb_idx"] = $this->token["mb_idx"];
        $row = $model->remove($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }
}
?>
