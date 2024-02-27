<?php
namespace applications\controllers;

class reservationController extends Controller
{
    public function init()
    {
        if (_DEV) {
            $this->request["put"] = [
                "reservation_user_name" => "홍길동",
                "reservation_user_cellphone" => "01012341234",
                "reservation_store_idx" => 147,
                "reservation_room_idx" => 3,
                "reservation_date" => "2023-09-06",
                "reservation_time" => 22,
                "reservation_period" => 3,
            ];
            $this->add();
            exit;
        }
        
    }

    public function getList()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["get"];
        $request["reservation_mb_idx"] = $this->token["mb_idx"];
        // ----------------------------------------------------
        $model = new \applications\models\ReservationModel();
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
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Ident Empty");
        }
        $request = $this->request["get"];
        // ----------------------------------------------------
        $model = new \applications\models\ReservationModel();
        $search = [];
        $search["reservation_idx"] = $this->params["ident"];
        $search["reservation_mb_idx"] = $this->token["mb_idx"];
        $row = $model->get($search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function add()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["put"];
        if (empty($request)) {
            $this->error_json("002", "Request Empty");
        }
        $required = [
            "reservation_user_name",
            "reservation_user_cellphone",
            "reservation_store_idx",
            "reservation_room_idx",
            "reservation_date",
            "reservation_time",
            "reservation_period",
        ];
        if (!check_required($required, $request)) {
            $this->error_json("002", "Required Error");
        }
        // ------------------------------------------------
        $model = new \applications\models\ReservationModel();
        $value = [];
        $value = $model->getValue($request, "add");
        $value["reservation_code"] = generateRandomString(5);
        $value["reservation_stt"] = 1;
        $value["reservation_mb_idx"] = $this->token["mb_idx"];
        $row = $model->add($value);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $data = $row["returnData"];
        $this->response_json("000", ["data" => $row["returnData"]]);
    }

    public function set()
    {
        $this->checkAuthorization("Bearer");
        $request = $this->request["post"];
        if (empty($request)) {
            $this->error_json("002", "Request Empty");
        }
        if (empty($this->params["ident"])) {
            $this->error_json("002", "Ident Empty");
        }
        // ------------------------------------------------
        $model = new \applications\models\ReservationModel();
        $search = [];
        $search["reservation_idx"] = $this->params["ident"];
        $search["reservation_mb_idx"] = $this->token["mb_idx"];
        $value = [];
        $value = $model->getValue($request, "set");
        $row = $model->set($value, $search);
        if ($row["returnCode"] != "000") {
            $this->error_json($row["returnCode"], $row["returnData"]);
        }
        $this->response_json("000", ["data" => $row["returnData"]]);
    }
}
?>
