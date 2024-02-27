<?php
namespace applications\controllers;

class mainController extends Controller
{

    public function index(){
        $model = new \common\models\MemberModel();
        $beforeDay = date("Y-m-d", strtotime(date("Y-m-d"). " -300 day"));
        $search = [];
        $search["mb_reg_dt__then__ge"] = $beforeDay." 00:00:00";
        $search["mb_reg_dt__then__le"] = _DATE_YMD." 23:59:59";
        $search["group"] = " left(mb_reg_dt,10) ";
        //$search["group"] = " reg_dt ";
        /*
        $col = "count(mb_idx)";
        $col .= ", sum(if(mb_reg_dt < '"._DATE_YMD." 00:00:00' , 1, 0) ) as yesterdayCnt";
        $col .= ", sum(if(mb_reg_dt >= '"._DATE_YMD." 00:00:00' , 1, 0) ) as todayCnt";
        */
        $col = " left(mb_reg_dt,10) as dc_dt ";
        $col .= " ,count(mb_idx) as cnt ";
        $row = $model->select($col,$search,true);
        $this->row = json_encode($row['returnData']);
    }
}
?>
