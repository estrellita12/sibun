<?php
namespace common\extend;

use \Exception;

class SMS
{
    private $sms_url;
    private $user_id;
    private $user_key;
    private $sender;

    public function send($arr, $test = "N")
    {
        return $this->kpmobile($arr, "N");
    }

    public function kpmobile($arr, $test = "N")
    {
        try {
            $this->sms_url = "https://munjaro.net/apis/send/";
            $this->user_id = "mwomcp";
            $this->user_key = "major0111!";
            $this->sender = "0264261235";

            $value = [];
            $value["key"] = $this->user_key;
            $value["user_id"] = $this->user_id;
            $value["sender"] = $this->sender;
            $value["receiver"] = $arr["receiver_phone"];
            $value["msg"] = $arr["content"];
            $value["testmode_yn"] = $test;

            $res = clientUrl($this->sms_url, "post", $value);
            $intReturnCode = $res["returnCode"];
            $response = $res["response"];

            if ($intReturnCode != "200") {
                return [
                    "resultCode" => "040",
                    "resultData" => $intReturnCode . " : Network Error",
                ];
            }
            $data = json_decode($response, true);
            if( $data["result_code"] != 1 ){
                return ["resultCode" => "040", "resultData" => $response];
            }

            $htyModel = new \common\models\SmsHistoryModel();
            $value = [];
            $value["sms_hty_sender"] = $this->sender;
            $value["sms_hty_receiver"] = $arr["receiver_phone"];
            $value["sms_hty_message"] = $arr["content"];
            $value["sms_hty_result"] = json_encode($response);
            $row = $htyModel->add($value);
            return ["returnCode" => "000", "returnData" => $row];
        } catch (Exception $e) {
            return ["returnCode" => "040", "returnData" => $e->getMessage()];
        }
    }
}
