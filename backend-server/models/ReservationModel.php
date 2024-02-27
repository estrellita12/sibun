<?php
namespace common\models;

class ReservationModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_reservation");
        $this->alias = [
            "reservation_idx" => "reservation_idx",
            "reservation_code" => "ifnull(reservation_code,'')",
            "reservation_stt" => "ifnull(reservation_stt,1)",
            "reservation_mb_idx" => "ifnull(reservation_mb_idx,'')",
            "reservation_user_name" => "ifnull(reservation_user_name,'')",
            "reservation_user_cellphone" =>
            "ifnull(reservation_user_cellphone,'')",
            "reservation_store_idx" => "ifnull(reservation_store_idx,'')",
            "reservation_room_idx" => "ifnull(reservation_room_idx,'')",
            "reservation_voucher_idx" => "ifnull(reservation_voucher_idx,'')",
            "reservation_date" => "ifnull(reservation_date,'')",
            "reservation_time" => "ifnull(reservation_time,'')",
            "reservation_period" => "ifnull(reservation_period,'')",
            "reservation_confirm_dt" => "ifnull(reservation_confirm_dt,'')",
            "reservation_cancel_dt" => "ifnull(reservation_cancel_dt,'')",
            "reservation_cancel_reason" =>
            "ifnull(reservation_cancel_reason,'')",
            "reservation_enter_dt" => "ifnull(reservation_enter_dt,'')",
            "reservation_review_yn" => "ifnull(reservation_review_yn,'')",
            "reservation_reg_dt" => "ifnull(reservation_reg_dt,'')",
            "reservation_update_dt" => "ifnull(reservation_update_dt,'')",
        ];
    }

    public function add($value = [], $check = true)
    {
        $row = parent::add($value,$check);
        if($row['returnCode']=="000"){
            $this->sendMessage($row['returnData']['lastInsertId'], "1", "member");
        }
        return $row;
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = [
                "reservation_code",
                "reservation_mb_idx",
                "reservation_store_idx",
                "reservation_room_idx",
            ];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            $value["reservation_reg_dt"] = _DATE_YMDHIS;
        }
        $value["reservation_update_dt"] = _DATE_YMDHIS;

        if ($type == "set") {
            unset($value["reservation_idx"]);
            unset($value["reservation_code"]);
            unset($value["reservation_mb_idx"]);
            unset($value["reservation_reg_dt"]);
        }
        return $value;
    }

    public function changeStt($value, $search, $user_type="admin")
    {
        $idx = $search["reservation_idx"];
        $stt = $value["reservation_stt"];
        $this->pdo->beginTransaction();
        $row = $this->set($value, $search);
        if ($row["returnCode"] != "000") {
            return $row;
        }
        $htyModel = new \common\models\ReservationHistoryModel();
        $htyModel->pdo = $this->pdo;
        $value = [];
        $value["rsvt_hty_by_reservation_idx"] = $idx;
        $value["rsvt_hty_stt"] = $stt;
        $value["rsvt_hty_by_user"] = $user_type;
        $htyRow = $htyModel->add($value);
        if ($htyRow["returnCode"] != "000") {
            $this->pdo->rollBack();
            return $htyRow;
        }
        $this->pdo->commit();
        $this->sendMessage($idx,$stt,$user_type);
        return $row;
    }

    public function sendMessage($reservation_idx,$stt,$user_type){
        $this->leftMultiJoin("reservation_store_idx","web_store","store_idx","store_pt_idx","web_partner","pt_idx");
        $search = ["reservation_idx"=>$reservation_idx];
        $row = $this->select("store_name,pt_idx,store_idx,reservation_mb_idx", $search, false);
        if( $row['returnCode']!="000" ){
            return;
        }
        $reservationData = $row['returnData'];
        $store_name = $reservationData['store_name'];
        $pt_idx = $reservationData['pt_idx'];
        $mb_idx = $reservationData['reservation_mb_idx'];
        switch($stt){
            case "1":
                //$msg = "사용자님, [{$store_name}] 매장에 예약을 접수하였습니다. 잠시만 기다려주세요.";
                //$this->sendUserMessage($mb_idx,$msg);  
                $msg = "사장님, [{$store_name}] 매장에 새로운 예약이 접수되었습니다. 예약을 확정해주세요.";
                $this->sendBizMessage($pt_idx,$msg);  
                break;
            case "2":
                $msg = "사용자님, [{$store_name}] 매장에 접수된 예약이 확정되었습니다.";
                $this->sendUserMessage($mb_idx, $msg);  
                //$msg = "사장님, [{$store_name}] 매장에 접수된 예약이 확정되었습니다.";
                //$this->sendBizMessage($pt_idx,$msg);  
                break;
            case "3":
                $msg = "사용자님, [{$store_name}] 매장에 접수된 예약이 취소 되었습니다.";
                $this->sendUserMessage($mb_idx,$msg);  
                $msg = "사장님, [{$store_name}] 매장에 접수된 예약이 취소 되었습니다.";
                $this->sendBizMessage($pt_idx,$msg);  
                break;
        }
    }

    private function sendUserMessage($mb_idx,$msg){
        $memberModel = new \common\models\MemberModel();
        $row = $memberModel->select("mb_name,mb_cellphone,mb_device_token",["mb_idx"=>$mb_idx], false);
        if($row['returnCode']!="000"){
            return;
        }
        $member = $row['returnData'];
        $sms = new \common\extend\SMS();
        $value = [];
        $value["receiver_phone"] = $member['mb_cellphone'];
        $value["content"] = $msg;
        $row = $sms->send($value);

        $google = new \common\extend\GoogleApi();
        $message = [
            "token" => $member['mb_device_token'],
            "notification" => [
                    "title" => "예약의 시작과 끝, 시분",
                    "body" => $msg
            ],
        ];
        $google->sendNotification($message);
    }

    private function sendBizMessage($pt_idx,$msg){
        $partnerModel = new \common\models\PartnerModel();
        $row = $partnerModel->select("pt_name,pt_cellphone,pt_device_token",["pt_idx"=>$pt_idx], false);
        if($row['returnCode']!="000"){
            return;
        }
        $partner = $row['returnData'];
        $sms = new \common\extend\SMS();
        $value = [];
        $value["receiver_phone"] = $partner['pt_cellphone'];
        $value["content"] = $msg;
        $row = $sms->send($value);

        $google = new \common\extend\GoogleApi("biz");
        $message = [
            "token" => $partner['pt_device_token'],
            "notification" => [
                    "title" => "예약의 시작과 끝, 시분",
                    "body" => $msg
            ],
        ];
        $google->sendNotification($message);
    }
    
    public function sendMessage2($reservation_idx,$stt, $user_type="admin")
    {
        $token = "";
        $msg = "";

        $this->leftMultiJoin("reservation_store_idx","web_store","store_idx","store_pt_idx","web_partner","pt_idx");
        $search = ["reservation_idx"=>$reservation_idx];
        $row = $this->select("pt_cellphone,pt_device_token,store_name,reservation_user_name,reservation_user_cellphone", $search, false);
        if( $row['returnCode']!="000" ){
            return;            
        }
        $partner = $row['returnData'];

        $this->init();
        $this->leftJoin("reservation_mb_idx","web_member","mb_idx");
        $search = ["reservation_idx"=>$reservation_idx];
        $row = $this->select("mb_name,mb_cellphone,mb_device_token,reservation_date,reservation_user_name,reservation_user_cellphone", $search, false);
        if( $row['returnCode']!="000" ){
            return;            
        }
        $member = $row['returnData'];

        if($stt=="1"){
            $sms = new \common\extend\SMS();
            $value = [];
            $value["receiver_phone"] = $partner['pt_cellphone'];
            $value["content"] = "[{$partner['store_name']}] 매장에 새로운 예약이 접수되었습니다. 예약을 확정해주세요.";
            $row = $sms->send($value);

            $google = new \common\extend\GoogleApi("biz");
            $message = [
                "token" => $partner['pt_device_token'],
                "notification" => [
                    "title" => "예약의 시작과 끝, 시분",
                    "body" => "[{$partner['store_name']}] 매장에 새로운 예약이 접수되었습니다. 예약을 확정해주세요.",
                ],
            ];
            $google->sendNotification($message,"sibunbiz");

        } else if($stt=="2"){
            $sms = new \common\extend\SMS();
            $value = [];
            $value["receiver_phone"] = $member['mb_cellphone'];
            $value["content"] = "{$member['mb_name']}님 예약이 확정되었습니다.";
            $row = $sms->send($value);

            $google = new \common\extend\GoogleApi();
            $message = [
                "token" => $partner['mb_device_token'],
                "notification" => [
                    "title" => "예약의 시작과 끝, 시분",
                    "body" => "{$member['mb_name']}님 예약이 확정되었습니다.",
                ],
            ];
            $google->sendNotification($message);

        } else if($stt=="3"){
            $sms = new \common\extend\SMS();
            $value = [];
            $value["receiver_phone"] = $member['mb_cellphone'];
            $value["content"] = "[{$partner['store_name']}] 매장의 접수된 예약이 취소되었습니다.";
            $row = $sms->send($value);

            $google = new \common\extend\GoogleApi();
            $message = [
                "token" => $member['mb_device_token'],
                "notification" => [
                    "title" => "예약의 시작과 끝, 시분",
                    "body" => "[{$partner['store_name']}] 매장의 예약이 취소되었습니다.",
                ],
            ];
            $google->sendNotification($message);
        }

    }

    public function isOwnerPartnerCheck($idx, $pt_idx)
    {
        $this->leftJoin("reservation_store_idx", "web_store", "store_idx");
        $col = "count(reservation_idx) as cnt";
        $search = [];
        $search["reservation_idx"] = $idx;
        $search["store_pt_idx"] = $pt_idx;
        $row = $this->select($col, $search, false);
        if ($row["returnCode"] == "000" && $row["returnData"]["cnt"] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isOwnerMemberCheck($idx, $mb_idx)
    {
        $this->leftJoin("reservation_mb_idx", "web_member", "mb_idx");
        $col = "count(reservation_idx) as cnt";
        $search = [];
        $search["reservation_idx"] = $idx;
        $search["mb_idx"] = $pt_idx;
        $row = $this->select($col, $search, false);
        if ($row["returnCode"] == "000" && $row["returnData"]["cnt"] > 0) {
            return true;
        } else {
            return false;
        }
    }
}
?>
