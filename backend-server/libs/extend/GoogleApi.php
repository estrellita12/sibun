<?php
namespace common\extend;

require_once _LIBS . "/vendor/autoload.php";

//use \Google\Client\Google_Client;
use \Google_Client;
use \Exception;

class GoogleApi
{

    private $key_file;
    private $project_id;
    private $access_token;
    public function __construct($project_id="sibun-6f678")
    {
        if($project_id == "sibun-6f678"){
            $this->project_id = $project_id;
            $this->key_file = _LIBS."/extend/key/sibun-6f678-firebase-adminsdk-y8yus-93638a5347.json";
        }else{
            $this->project_id = "sibunbusiness";
            $this->key_file = _LIBS."/extend/key/sibunbusiness-firebase-adminsdk-lw3m4-ca0c099ca8.json";
        }
    }

    public function getClientToken($scope = "https://www.googleapis.com/auth/firebase.messaging")
    {
        try {
            putenv("GOOGLE_APPLICATION_CREDENTIALS=" . $this->key_file);
            $client = new Google_Client();
            $client->useApplicationDefaultCredentials();
            $client->setScopes($scope);
            $auth_key = $client->fetchAccessTokenWithAssertion();
            $this->access_token = $auth_key["access_token"];
            return $this->access_token;
        } catch (Google_Exception $e) {
            return;
        }
    }

    public function sendNotification($message){
        if( empty($message) ) {
            return ["returnCode" => "001", "returnData" => ""];
        }
        $url = "https://fcm.googleapis.com/v1/projects/{$this->project_id}/messages:send";
        $scope = "https://www.googleapis.com/auth/firebase.messaging";
        $access_token = $this->getClientToken($scope);
        $ch = curl_init();
        $headers = [
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json",
        ];
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $android_opt = [
            "notification" => [
                "default_sound" => true,
            ],
        ];
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["message" => $message]));
        $result = curl_exec($ch);
        if ($result === false) {
            return ["returnCode" => "040", "returnData" => $result];
        }
        $htyModel = new \common\models\NotificationHistoryModel();
        $value = [];
        $value["noti_hty_message"] = json_encode($message);
        $value["noti_hty_result"] = json_encode($result);
        $row = $htyModel->add($value);
        return $row;
    }

    private function test(){
        /*
        $token = "fYgmX0WySQ2ox6YzpYibd1:APA91bHLGKk0bvPjZaUIArTwR_cHGG193xGMKaKAjJCfG7E8j0z9ljOUiCKqTm7uXYT9dWl5GX6AMK2No2i72BM5ZHJ0aMUoCkI0JiiqFiRanl1e3Us8J9gR91oMb13wwQN6cIgzyfoR";
        $message = [
            "token" => $token,
            "notification" => [
                "title" => "제목입니다.",
                "body" => "내용이 들어갑니다.",
                // 'image' => 'http://sowonbyul.com/original/totalAdmin/images/Icon-512.png',
            ],
            //"android" => $android_opt,
        ];
        */
    }
}
