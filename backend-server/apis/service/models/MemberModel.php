<?php
namespace applications\models;

class MemberModel extends \common\models\MemberModel
{
    public function __construct()
    {
        parent::__construct();
        $this->alias = [
            "mb_idx" => "mb_idx",
            "mb_id" => "mb_id",
            //"mb_passwd" => "mb_passwd",
            "mb_stt" => "mb_stt",
            "mb_auth_kakao_id" => "ifnull(mb_auth_kakao_id,'')",
            "mb_auth_apple_id" => "ifnull(mb_auth_apple_id,'')",
            "mb_auth_apple_token" => "ifnull(mb_auth_apple_token,'')",
            "mb_name" => "ifnull(mb_name,'')",
            "mb_nickname" => "ifnull(mb_nickname,'')",
            "mb_profile_img" => "ifnull(mb_profile_img,'')",
            "mb_grade" => "ifnull(mb_grade,5)",
            "mb_point" => "ifnull(mb_point,0)",
            "mb_cellphone" => "ifnull(mb_cellphone,'010-0000-0000')",
            "mb_birth" => "ifnull(mb_birth,'0000-00-00')",
            "mb_gender" => "ifnull(mb_gender,'n')",
            "mb_email" => "ifnull(mb_email,'')",
            "mb_marketing_yn" => "ifnull(mb_marketing_yn,'n')",
            "mb_reg_dt" => "ifnull(mb_reg_dt,'0000-00-00 00:00:00')",
            "mb_login_dt" => "ifnull(mb_login_dt,'0000-00-00 00:00:00')",
            "mb_login_ip" => "ifnull(mb_login_ip,'')",
            "mb_device_token" => "ifnull(mb_device_token,'')",
        ];
    }
    public function getValue($request = [], $type = "add")
    {
        $value = [];
        if (!empty($request["mb_id"]) && $type == "add") {
            $value["mb_id"] = $request["mb_id"];
        }
        if (!empty($request["mb_passwd"])) {
            $value["mb_passwd"] = $request["mb_passwd"];
        }
        if (!empty($request["mb_name"])) {
            $value["mb_name"] = $request["mb_name"];
        }
        if (!empty($request["mb_nickname"])) {
            $value["mb_nickname"] = $request["mb_nickname"];
        }
        if (empty($request["mb_nickname"]) && $type == "add") {
            $value["mb_nickname"] = createNickName();
        }
        if (!empty($request["mb_profile_img"])) {
            $value["mb_profile_img"] = $request["mb_profile_img"];
        }
        if (!empty($request["mb_cellphone"])) {
            $value["mb_cellphone"] = $request["mb_cellphone"];
        }
        if (!empty($request["mb_birth"])) {
            $value["mb_birth"] = $request["mb_birth"];
        }
        if (!empty($request["mb_gender"])) {
            $value["mb_gender"] = $request["mb_gender"];
        }
        if (!empty($request["mb_email"])) {
            $value["mb_email"] = $request["mb_email"];
        }
        if (!empty($request["mb_marketing_yn"])) {
            $value["mb_marketing_yn"] = $request["mb_marketing_yn"];
        }
        if (!empty($request["mb_device_token"])) {
            $value["mb_device_token"] = $request["mb_device_token"];
        }else if($request["mb_device_token"] == ''){
            $value["mb_device_token"] = null;
        }
        return $value;
    }
    public function getSearch($request = [])
    {
        $search = [];
        if (!empty($request["mb_idx"])) {
            $search["mb_idx"] = $request["mb_idx"];
        }
        if (!empty($request["mb_id"])) {
            $search["mb_id"] = $request["mb_id"];
        }
        return $search;
    }
    /*
    public function getList($request = [])
    {
        $col = get_alias($this->alias);
        $search = $this->getSearch($request);
        return parent::select($col, $search, true);
    }
    */
    public function get($request = [])
    {
        $col = get_alias($this->alias);
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }

    public function cnt($request = [])
    {
        $col = get_alias($this->alias);
        $search = $this->getSearch($request);
        return parent::select("count(mb_id) as cnt", $search, false);
    }

    public function getToken($mb_idx)
    {
        $jwt = new \common\extend\MyJwt();
        $col = "mb_idx,mb_id,mb_name,mb_cellphone,mb_nickname,mb_profile_img";
        $memberRow = $this->select($col, ["mb_idx" => $mb_idx], false);
        if ($memberRow["returnCode"] != "000") {
            return $memberRow;
        }
        $data = [];
        $data["mb_idx"] = $memberRow["returnData"]["mb_idx"];
        $data["mb_id"] = $memberRow["returnData"]["mb_id"];
        $data["is_member"] = "1";
        $accessRow = $jwt->hashing($data, 60 * 60);
        if ($accessRow["returnCode"] != "000") {
            return $accessRow;
        }
        $refreshRow = $jwt->hashing($data, 60 * 60 * 2);
        if ($refreshRow["returnCode"] != "000") {
            return $refreshRow;
        }
        return [
            "returnCode" => "000",
            "returnData" => [
                "user" => $memberRow["returnData"],
                "access_token" => $accessRow["returnData"],
                "refresh_token" => $refreshRow["returnData"],
            ],
        ];
    }
}

?>
