<?php
namespace applications\controllers;
use PDO;
class testController extends Controller
{
    public function init()
    {
    }

    public function getList()
    {
        $model = new \applications\models\ReservationModel();
        $model->changeStt(['reservation_stt'=>"3"],["reservation_idx"=>264], "admin");
    }

    public function sibun(){
        $google = new \common\extend\GoogleApi();
        $token = "cEm-zDI67Uo1ltgfzsr3zD:APA91bFwEq3FbNNnp0cVjNLpWbpXJfZU0VGVPPJp_Ug5UujS0xWgcCAnNH7m5NbOQQ7Bk_30KrwldY6D6a8_KopaXLV0ErqvmRbWUIQU067eIHFDatn9WBJgPbQ2axU73hr58oQiv-Fa";
        $token = "doRsV4lSUkpjkLLrMRM9Co:APA91bG3b4E4zx_TASjjtYf5kuc_cJd7gDtsjlZf2V8ZXe8wuw6Wxbz-21gLPZnbFmkx-loHRfVPgz1K4S39Rgi3hiThzP89qObGCGJ1Q8zyCT_BlmrDor8YYZ-E7NGxzorte5mO9Web";
        $message = [
            "token" => $token,
            "notification" => [
                "title" => "제목입니다.",
                "body" => "내용이 들어갑니다.",
            ],
        ];
        $res = $google->sendNotification($message);
        print_r($res);
    }

    public function sibunbiz(){
        $google = new \common\extend\GoogleApi("biz");
        $token = "dNNtrHOlRxyeIy2AzMPG4A:APA91bGOLjx6cDnZ9bLlohWjOs3sqNg-rTKNiCh4Yh5quzSz7bQxbxn7plG4W7qPzqNB-G0kZhn2Ud_OwnnmaP1WMhjpdzwiA0ZJYtHuuQCKBQn23pH1LLmZCHtQnR6d-nEvwetNShnk";
        $message = [
            "token" => $token,
            "notification" => [
                "title" => "사장님",
                "body" => "내용이 들어갑니다.",
            ],
        ];
        $res = $google->sendNotification($message);
        print_r($res);
    }

}
