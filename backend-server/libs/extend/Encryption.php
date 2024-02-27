<?php
namespace common\extend;

use Exception;

class Encryption
{
    protected $alg;
    protected $secret_key;

    public function __construct($key = "secret key", $alg = "AES-256-CBC")
    {
        $this->alg = $alg;
        $this->secret_key = $key;
    }

    public function encrypt($data = [])
    {
        try {
            $payload = json_encode($data);
            $enc = openssl_encrypt(
                $payload,
                "aes-256-cbc",
                $this->secret_key,
                true,
                str_repeat(chr(0), 16)
            );
            $enc = base64_encode($enc);
            return ["returnCode" => "000", "returnData" => $enc];
        } catch (Exception $e) {
            return ["returnCode" => "030", "returnData" => $e->getMessage()];
        }
    }

    public function decrypt($token)
    {
        try {
            $data = base64_decode($token);
            $dec = openssl_decrypt(
                $data,
                "aes-256-cbc",
                $this->secret_key,
                true,
                str_repeat(chr(0), 16)
            );
            $dec = json_decode($dec, true);
            return ["returnCode" => "000", "returnData" => $dec];
        } catch (Exception $e) {
            return ["returnCode" => "030", "returnData" => $e->getMessage()];
        }
    }
}
?>
