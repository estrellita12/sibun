<?php
namespace common\extend;

require_once _LIBS . "/vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class MyJwt
{
    protected $alg;
    protected $secret_key;
    public function __construct($alg = "HS256", $secret_key = "majorworld")
    {
        $this->issuer = "suntalk"; //토큰 발급자
        $this->alg = $alg;
        $this->kid = "";
        $this->secret_key = $secret_key;
    }

    public function hashing($data = [], $exp = 1800)
    {
        try {
            $payload = [
                "iss" => $this->issuer,
                "aud" => "http://suntalk.site",
                "iat" => time(),
                "exp" => time() + $exp,
            ];
            foreach ($data as $key => $value) {
                $payload[$key] = $value;
            }
            $token = JWT::encode($payload, $this->secret_key, $this->alg);
            return ["returnCode" => "000", "returnData" => $token];
        } catch (Exception $e) {
            return ["returnCode" => "020", "returnData" => $e->getMessage()];
        }
    }

    public function dehashing($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret_key, $this->alg));
            $decoded = (array) $decoded;
            return ["returnCode" => "000", "returnData" => $decoded];
        } catch (Exception $e) {
            // Signature verification failed
            // Expired token
            if ($e->getMessage() == "Expired token") {
                return [
                    "returnCode" => "020",
                    "returnData" => $e->getMessage(),
                ];
            }
            return ["returnCode" => "020", "returnData" => $e->getMessage()];
        }
    }
    /*
    public function hashing2($data = [])
    {
        try {
            $header = json_encode(["ta" => "asd"]);
            $payload = [
                "iss" => $this->issuer,
                "iat" => time(),
                "exp" => time() + 1200,
            ];
            foreach ($data as $key => $value) {
                $payload[$key] = $value;
            }
            $payload = json_encode($payload);
            $signature = hash(
                $this->alg,
                $header . $payload . $this->secret_key
            );
            return base64_encode($header . "." . $payload . "." . $signature);
        } catch (Exception $e) {
        }
    }

    public function dehashing2($token)
    {
        try {
            $parted = explode(".", base64_decode($token));
            $signature = $parted[2];
            if (
                hash($this->alg, $parted[0] . $parted[1] . $this->secret_key) !=
                $signature
            ) {
                return "SIGNATURE ERROR";
            }

            $payload = json_decode($parted[1], true);
            if ($payload["exp"] < time()) {
                return "EXPIRED";
            }

            return json_decode($parted[1], true);
        } catch (Exception $e) {
        }
    }
    */
}

?>
