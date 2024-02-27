<?php
namespace common\extend;

use Exception;

class GeocodeApi
{
    private $KAKAO_RESTAPI_KEY;
    public function __construct()
    {
        $this->KAKAO_RESTAPI_KEY = "1e23c3208bc2335311e9f8653b38f85b";
    }

    public function getData($address, $type = "kakao")
    {
        if ($type == "kakao") {
            return $this->kakao(["query" => $address]);
        }
    }

    public function kakao($value)
    {
        // https://developers.kakao.com/docs/latest/ko/local/dev-guide
        $url = "https://dapi.kakao.com/v2/local/search/address.json";
        $headers = ["Authorization: KakaoAK {$this->KAKAO_RESTAPI_KEY}"];
        $res = clientUrl($url, "get", $value, $headers);
        if ($res["returnCode"] != "200") {
            return "";
        }
        return $res["response"];
    }
}
