<?php
namespace common\models;

class MemberModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_member");
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value, $type);
        if ($type == "add") {
            $required = ["mb_id", "mb_passwd"];
            if (!check_required($required, $value)) {
                return [];
            }
            $value["mb_reg_dt"] = _DATE_YMDHIS;
        }
        $value["mb_update_dt"] = _DATE_YMDHIS;

        if ($type == "set") {
            unset($value["mb_idx"]);
            unset($value["mb_id"]);
            unset($value["mb_reg_dt"]);
        }

        if (!empty($value["mb_cellphone"])) {
            $value["mb_cellphone"] = only_number($value["mb_cellphone"]);
        }
        if (!empty($value["mb_passwd"])) {
            $value["mb_passwd"] = hash_password($value["mb_passwd"]);
        }
        return $value;
    }

    // 아이디, 패스워드로 로그인
    public function loginV1($id, $pw)
    {
        $row = $this->select("mb_idx,mb_id,mb_stt,mb_passwd,mb_cellphone", [
            "mb_id" => $id,
            false,
        ]);
        if ($row["returnCode"] != "000") {
            return $row;
        }
        $data = $row["returnData"];
        if (!password_verify($pw, $data["mb_passwd"])) {
            return ["returnCode" => "001"];
        }
        if ($data["mb_stt"] != "1") {
            return ["returnCode" => "004"];
        }

        $value["mb_login_dt"] = _DATE_YMDHIS;
        if (!empty($_SERVER["REMOTE_ADDR"])) {
            $value["mb_login_ip"] = $_SERVER["REMOTE_ADDR"];
        }
        $res = $this->update($value, ["mb_id" => $data["mb_id"]], "value");
        return [
            "returnCode" => "000",
            "returnData" => [
                "mb_idx" => $data["mb_idx"],
                "mb_id" => $data["mb_id"],
            ],
        ];
    }

    public function loginV2($cellphone)
    {
        $row = $this->select(
            "mb_idx,mb_id,mb_stt,mb_passwd,mb_cellphone",
            [
                "mb_cellphone" => $cellphone,
            ],
            false
        );
        if ($row["returnCode"] != "000") {
            return $row;
        }
        $data = $row["returnData"];
        if ($data["mb_stt"] != "1") {
            return ["returnCode" => "004"];
        }

        $value["mb_login_dt"] = _DATE_YMDHIS;
        if (!empty($_SERVER["REMOTE_ADDR"])) {
            $value["mb_login_ip"] = $_SERVER["REMOTE_ADDR"];
        }
        $res = $this->update($value, ["mb_id" => $data["mb_id"]], "value");
        return [
            "returnCode" => "000",
            "returnData" => [
                "mb_idx" => $data["mb_idx"],
                "mb_id" => $data["mb_id"],
            ],
        ];
    }

    public function leave($search, $reason = "")
    {
        $row = $this->select("mb_id,mb_cellphone", $search, false);
        $userData = $row["returnData"];

        // 소셜 로그인의 경우 처리 필요
        $row = $this->remove($search);
        if ($row["returnCode"] != "000") {
            return $row;
        }

        $leaveModel = new \common\models\MemberLeaveModel();
        $leaveModel->pdo = $this->pdo;
        $value = [];
        $value["mb_leave_mb_id"] = $userData["mb_id"];
        $value["mb_leave_mb_cellphone"] = $userData["mb_cellphone"];
        $value["mb_leave_reason"] = $reason;
        $leaveModel->add($value);
        return $row;
    }
}

?>
