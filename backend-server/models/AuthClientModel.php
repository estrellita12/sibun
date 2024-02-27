<?php
namespace common\models;

class AuthClientModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_auth_client");
    }

    public function check($key)
    {
        $row = $this->select(
            "auth_client_id",
            [
                "auth_client_secret_key" => $key,
            ],
            false
        );
        return $row;
    }
}

?>
