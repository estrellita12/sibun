<?php
namespace applications\models;

class StoreModel extends \common\models\StoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSearch($request = [])
    {
        $search = [];
        if (!empty($request["store_idx"])) {
            $search["store_idx"] = $request["store_idx"];
        }
        if (!empty($request["store_idx_in"])) {
            $search["store_idx__in__all"] = emptyRemoveArrString(
                $request["store_idx_in"]
            );
        }
        if (!empty($request["store_pt_idx"])) {
            $search["store_pt_idx"] = $request["store_pt_idx"];
        }
        if (!empty($request["store_ctg_idx"])) {
            $search["store_ctg_idx"] = $request["store_ctg_idx"];
        }
        if (!empty($request["store_oper_time"])) {
            $search["store_open_time__then__le"] = $request["store_oper_time"];
            $search["store_close_time__then__ge"] = $request["store_oper_time"];
        }
        if (!empty($request["store_open_time_ge"])) {
            $search["store_open_time__then__ge"] =
                $request["store_open_time_ge"];
        }
        if (!empty($request["store_open_time_le"])) {
            $search["store_open_time__then__le"] =
                $request["store_open_time_le"];
        }
        if (!empty($request["store_close_time_ge"])) {
            $search["store_close_time__then__ge"] =
                $request["store_close_time_ge"];
        }
        if (!empty($request["store_close_time_le"])) {
            $search["store_close_time__then__le"] =
                $request["store_close_time_le"];
        }
        if (!empty($request["store_addr_x_ge"])) {
            if (!empty($request["store_addr_y_ge"])) {
                $search["store_addr_x__then__ge"] = $request["store_addr_x_ge"];
                $search["store_addr_y__then__ge"] = $request["store_addr_y_ge"];
            }
        }
        if (!empty($request["store_addr_x_le"])) {
            if (!empty($request["store_addr_y_le"])) {
                $search["store_addr_x__then__le"] = $request["store_addr_x_le"];
                $search["store_addr_y__then__le"] = $request["store_addr_y_le"];
            }
        }
        if (!empty($request["store_keyword"])) {
            $search["sql"] = " ( ";
            $search["sql"] .= " store_name like '%{$request["store_keyword"]}%' ";
            $search["sql"] .= " or ";
            $search["sql"] .= " store_addr like '%{$request["store_keyword"]}%' ";
            $search["sql"] .= " or ";
            $search["sql"] .= " store_addr_sep3 like '%{$request["store_keyword"]}%' ";
            $search["sql"] .= " ) ";
        }
        if (!empty($request["rpp"])) {
            $search["rpp"] = $request["rpp"];
        }
        if (!empty($request["page"])) {
            $search["page"] = $request["page"];
        }
        if (!empty($request["col"])) {
            $search["col"] = $request["col"];
            if (!empty($request["colby"])) {
                $search["colby"] = $request["colby"];
            }
        }
        return $search;
    }

    public function getList2($request = [], $type = "fetch")
    {
        $reviewModel = new \common\models\ReviewModel();

        // 하버사인공식
        // 경도 lon x 세로선 , 위도 lat y 가로선
        $source_x = "127.213229587651";
        $source_y = "37.55362590";
        $unit = 6371; // KM : 6371 , MILES : 3959
        $sql_col = "*";
        $sql_col .= ", (";
        $sql_col .= " {$unit} * acos( ";
        $sql_col .= "   cos( radians({$source_y}) ) ";
        $sql_col .= "   * cos( radians(store_addr_y) ) ";
        $sql_col .= "   * cos( radians(store_addr_x)-radians({$source_x}) ) ";
        $sql_col .= "   + sin( radians({$source_y}) ) ";
        $sql_col .= "   * sin( radians(store_addr_y)) ";
        $sql_col .= "   ) ";
        $sql_col .= ") AS distance";
        $this->alias["store_distance"] = "ifnull(distance,0)";
        $this->sql_from = " ( select $sql_col from web_store a left join ( select review_by_store_idx,sum(review_rating) as review_sum, count(review_idx) as review_cnt from web_review group by review_by_store_idx ) b on store_idx = review_by_store_idx ) c ";
        //$sql = "( select review_by_store_idx,sum(review_rating) as review_sum, count(review_idx) as review_cnt from {$reviewModel->tb_nm} group by review_by_store_idx )";
        //$this->leftJoin("store_idx", $sql, "review_by_store_idx");
        $this->alias["store_review_sum"] = "ifnull(review_sum,0)";
        $this->alias["store_review_cnt"] = "ifnull(review_cnt,0)";
        $this->alias["store_review_avg"] =
            "if ( review_cnt > 0, review_sum/review_cnt, 0 )";
        $col = get_alias(
            $this->alias,
            [
                "store_simg_cnt",
                "store_room_cnt",
                "store_reg_dt",
                "store_update_dt",
            ],
            false
        );
        //$col .= "( 6371*acos(cos(radians(source_lat))*cos(radians(lat))*cos(radians(lng)-radians(source_lon))+sin(radians(source_lat))*sin(radians(lat)))) AS distance ";
        $search = $this->getSearch($request);
        return parent::select($col, $search, true, $type);
    }

    public function getList($request = [], $type = "fetch")
    {
        $reviewModel = new \common\models\ReviewModel();
        $sql = "( select review_by_store_idx,sum(review_rating) as review_sum, count(review_idx) as review_cnt from {$reviewModel->tb_nm} group by review_by_store_idx )";
        $this->leftJoin("store_idx", $sql, "review_by_store_idx");
        $this->alias["store_review_sum"] = "ifnull(review_sum,0)";
        $this->alias["store_review_cnt"] = "ifnull(review_cnt,0)";
        $this->alias["store_review_avg"] =
            "if ( review_cnt > 0, review_sum/review_cnt, 0 )";
        $col = get_alias(
            $this->alias,
            [
                "store_idx",
                "store_ctg_idx",
                "store_name",
                "store_tel",
                "store_addr",
                "store_addr_x",
                "store_addr_y",
                "store_main_simg",
                "store_open_time",
                "store_close_time",
                "store_closed_days",
                "store_closed_notice",
                "store_voucher_use_yn",
                "store_amenities",
                "store_review_sum",
                "store_review_cnt",
                "store_review_avg",
            ],
            true
        );
        $search = $this->getSearch($request);
        return parent::select($col, $search, true, $type);
    }

    public function get($request = [])
    {
        $reviewModel = new \common\models\ReviewModel();
        $sql = "( select review_by_store_idx,sum(review_rating) as review_sum, count(review_idx) as review_cnt from {$reviewModel->tb_nm} where review_block_yn != 'y' group by review_by_store_idx )";
        $this->leftJoin("store_idx", $sql, "review_by_store_idx");
        $this->alias["store_review_sum"] = "ifnull(review_sum,0)";
        $this->alias["store_review_cnt"] = "ifnull(review_cnt,0)";
        $this->alias["store_review_avg"] =
            "if ( review_cnt > 0, review_sum/review_cnt, 0 )";

        $col = get_alias(
            $this->alias,
            ["store_main_simg", "store_update_dt"],
            false
        );
        $search = $this->getSearch($request);
        return parent::select($col, $search, false);
    }
}

?>
