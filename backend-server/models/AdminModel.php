<?php
namespace common\models;

class AdminModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_admin");
        $this->alias = [
            "adm_reg_dt" => "ifnull(adm_reg_dt,'')",
        ];
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = ["adm_id", "adm_passwd"];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            $value["adm_reg_dt"] = _DATE_YMDHIS;
        }

        if ($type == "set") {
            unset($value["adm_idx"]);
            unset($value["adm_id"]);
            unset($value["adm_reg_dt"]);
        }

        if (!empty($value["adm_passwd"])) {
            $value["adm_passwd"] = hash_password($value["adm_passwd"]);
        }
        return $value;
    }

    // 아이디, 패스워드로 로그인
    public function loginV1($id, $pw)
    {
        $row = $this->select("adm_idx,adm_id,adm_stt,adm_passwd", [
            "adm_id" => $id,
            false,
        ]);
        if ($row["returnCode"] != "000") {
            return $row;
        }
        $data = $row["returnData"];
        if (!password_verify($pw, $data["adm_passwd"])) {
            return ["returnCode" => "001"];
        }
        if ($data["adm_stt"] != "1") {
            return ["returnCode" => "004"];
        }

        $value["adm_login_dt"] = _DATE_YMDHIS;
        if (!empty($_SERVER["REMOTE_ADDR"])) {
            $value["adm_login_ip"] = $_SERVER["REMOTE_ADDR"];
        }
        $res = $this->update($value, ["adm_id" => $data["adm_id"]], "value");
        return [
            "returnCode" => "000",
            "returnData" => [
                "adm_idx" => $data["adm_idx"],
                "adm_id" => $data["adm_id"],
            ],
        ];
    }
}

?>
