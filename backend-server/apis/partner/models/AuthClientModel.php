<?php
namespace applications\models;

class AuthClientModel extends \common\models\AuthClientModel
{
    public function __construct()
    {
        parent::__construct();
        $this->alias = [
            "auth_client_idx" => "auth_client_idx",
            "auth_client_id" => "auth_client_id",
            "auth_client_secret_key" => "auth_client_secret_key",
            "auth_client_reg_dt" => "auth_client_reg_dt",
        ];
    }
}

?>
