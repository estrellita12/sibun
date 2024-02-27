<?php
namespace common\models;

class PartnerModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_partner");
        $this->alias = [
            "pt_idx" => "pt_idx",
            "pt_id" => "pt_id",
            "pt_passwd" => "pt_passwd",
            "pt_stt" => "pt_stt",
            "pt_name" => "ifnull(pt_name,'')",
            "pt_nickname" => "ifnull(pt_nickname,'')",
            "pt_profile_img" => "ifnull(pt_profile_img,'')",
            "pt_grade" => "ifnull(pt_grade,5)",
            "pt_cellphone" => "ifnull(pt_cellphone,'010-0000-0000')",
            "pt_birth" => "ifnull(pt_birth,'0000-00-00')",
            "pt_gender" => "ifnull(pt_gender,'n')",
            "pt_email" => "ifnull(pt_email,'')",
            "pt_marketing_yn" => "ifnull(pt_marketing_yn,'n')",
            "pt_reg_dt" => "ifnull(pt_reg_dt,'0000-00-00 00:00:00')",
            "pt_login_dt" => "ifnull(pt_login_dt,'0000-00-00 00:00:00')",
            "pt_login_ip" => "ifnull(pt_login_ip,'')",
            "pt_device_token" => "ifnull(pt_device_token,'')",
        ];
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = ["pt_id", "pt_passwd"];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            $value["pt_reg_dt"] = _DATE_YMD;
        }
        $value["pt_update_dt"] = _DATE_YMD;

        if ($type == "set") {
            unset($value["pt_idx"]);
            unset($value["pt_id"]);
            unset($value["pt_reg_dt"]);
        }

        if (!empty($value["mb_cellphone"])) {
            $value["mb_cellphone"] = only_number($value["mb_cellphone"]);
        }
        if (!empty($value["pt_passwd"])) {
            $value["pt_passwd"] = hash_password($value["pt_passwd"]);
        }
        return $value;
    }

    // 아이디, 패스워드로 로그인
    public function loginV1($id, $pw)
    {
        $row = $this->select("pt_idx,pt_id,pt_stt,pt_passwd,pt_cellphone", [
            "pt_id" => $id,
            false,
        ]);
        if ($row["returnCode"] != "000") {
            return $row;
        }
        $data = $row["returnData"];
        if (!password_verify($pw, $data["pt_passwd"])) {
            return ["returnCode" => "001"];
        }
        if ($data["pt_stt"] != "1") {
            return ["returnCode" => "004"];
        }

        $value["pt_login_dt"] = _DATE_YMDHIS;
        if (!empty($_SERVER["REMOTE_ADDR"])) {
            $value["pt_login_ip"] = $_SERVER["REMOTE_ADDR"];
        }
        $res = $this->update($value, ["pt_id" => $data["pt_id"]], "value");
        return [
            "returnCode" => "000",
            "returnData" => [
                "pt_idx" => $data["pt_idx"],
                "pt_id" => $data["pt_id"],
            ],
        ];
    }

    public function loginV2($cellphone)
    {
        $row = $this->select(
            "pt_idx,pt_id,pt_stt,pt_passwd,pt_cellphone",
            [
                "pt_cellphone" => $cellphone,
            ],
            false
        );
        if ($row["returnCode"] != "000") {
            return $row;
        }
        $data = $row["returnData"];
        if ($data["pt_stt"] != "1") {
            return ["returnCode" => "004"];
        }

        $value["pt_login_dt"] = _DATE_YMDHIS;
        if (!empty($_SERVER["REMOTE_ADDR"])) {
            $value["pt_login_ip"] = $_SERVER["REMOTE_ADDR"];
        }
        $res = $this->update($value, ["pt_id" => $data["pt_id"]], "value");
        return [
            "returnCode" => "000",
            "returnData" => [
                "pt_idx" => $data["pt_idx"],
                "pt_id" => $data["pt_id"],
            ],
        ];
    }

    public function leave($search, $reason = "")
    {
        $row = $this->select("pt_id,pt_cellphone", $search, false);
        $userData = $row["returnData"];

        // 소셜 로그인의 경우 처리 필요
        $row = $this->remove($search);
        if ($row["returnCode"] != "000") {
            return $row;
        }

        $leaveModel = new \common\models\PartnerLeaveModel();
        $leaveModel->pdo = $this->pdo;
        $value = [];
        $value["pt_leave_pt_id"] = $userData["pt_id"];
        $value["pt_leave_pt_cellphone"] = $userData["pt_cellphone"];
        $value["pt_leave_reason"] = $reason;
        $leaveModel->add($value);
        return $row;
    }
}

?>
