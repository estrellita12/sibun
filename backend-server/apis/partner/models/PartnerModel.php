<?php
namespace applications\models;

class PartnerModel extends \common\models\PartnerModel
{
    public function __construct()
    {
        parent::__construct();
        $this->alias = [
            "pt_idx" => "pt_idx",
            "pt_id" => "pt_id",
            //"pt_passwd" => "pt_passwd",
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

    public function getValue($request = [], $type = "add")
    {
        $value = [];
        if (!empty($request["pt_id"]) && $type == "add") {
            $value["pt_id"] = $request["pt_id"];
        }
        if (!empty($request["pt_passwd"])) {
            $value["pt_passwd"] = $request["pt_passwd"];
        }
        if (!empty($request["pt_name"])) {
            $value["pt_name"] = $request["pt_name"];
        }
        if (!empty($request["pt_nickname"])) {
            $value["pt_nickname"] = $request["pt_nickname"];
        }
        if (empty($request["pt_nickname"]) && $type == "add") {
            $value["pt_nickname"] = createNickName();
        }
        if (!empty($request["pt_profile_img"])) {
            $value["pt_profile_img"] = $request["pt_profile_img"];
        }
        if (!empty($request["pt_cellphone"])) {
            $value["pt_cellphone"] = $request["pt_cellphone"];
        }
        if (!empty($request["pt_birth"])) {
            $value["pt_birth"] = $request["pt_birth"];
        }
        if (!empty($request["pt_gender"])) {
            $value["pt_gender"] = $request["pt_gender"];
        }
        if (!empty($request["pt_email"])) {
            $value["pt_email"] = $request["pt_email"];
        }
        if (!empty($request["pt_marketing_yn"])) {
            $value["pt_marketing_yn"] = $request["pt_marketing_yn"];
        }
        if (!empty($request["pt_device_token"])) {
            $value["pt_device_token"] = $request["pt_device_token"];
        }else if($request["pt_device_token"] == ''){
            $value["pt_device_token"] = null;
        }        
        return $value;
    }

    public function getSearch($request = [])
    {
        $search = [];
        if (!empty($request["pt_idx"])) {
            $search["pt_idx"] = $request["pt_idx"];
        }
        if (!empty($request["pt_id"])) {
            $search["pt_id"] = $request["pt_id"];
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
        if( empty($search) ){
            return ["returnCode"=>"001"];
        }
        return parent::select($col, $search, false);
    }

    public function cnt($request = [])
    {
        $col = "count(pt_idx) as cnt";
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }

    public function getToken($pt_idx)
    {
        $jwt = new \common\extend\MyJwt();
        $col = "pt_idx,pt_id,pt_name,pt_cellphone,pt_nickname,pt_profile_img";
        $userRow = $this->select($col, ["pt_idx" => $pt_idx], false);
        if ($userRow["returnCode"] != "000") {
            return $userRow;
        }
        $data = [];
        $data["pt_idx"] = $userRow["returnData"]["pt_idx"];
        $data["pt_id"] = $userRow["returnData"]["pt_id"];
        $data["is_partner"] = "1";
        $accessRow = $jwt->hashing($data, 60 * 5);
        if ($accessRow["returnCode"] != "000") {
            return $accessRow;
        }
        $refreshRow = $jwt->hashing($data, 60 * 100);
        if ($refreshRow["returnCode"] != "000") {
            return $refreshRow;
        }
        return [
            "returnCode" => "000",
            "returnData" => [
                "user" => $userRow["returnData"],
                "access_token" => $accessRow["returnData"],
                "refresh_token" => $refreshRow["returnData"],
            ],
        ];
    }
}

?>
