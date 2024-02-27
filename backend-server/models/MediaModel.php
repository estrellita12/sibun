<?php
namespace common\models;

class MediaModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_media");
        $this->alias = [
            "media_idx" => "media_idx",
            "media_title" => "ifnull(media_title,'')",
            "media_simg" => "ifnull(media_simg,'')",
            "media_content" => "ifnull(media_content,'')",
            "media_reference" => "ifnull(media_reference,'')",
            "media_use_yn" => "ifnull(media_use_yn,'')",
            "media_orderby" => "ifnull(media_orderby,'')",
            "media_reg_dt" => "ifnull(media_reg_dt,'')",
            "media_update_dt" => "ifnull(media_update_dt,'')",
        ];
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            /*
            $required = [];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            */
            $value["media_reg_dt"] = _DATE_YMD;
        }
        $value["media_update_dt"] = _DATE_YMD;

        if ($type == "set") {
            unset($value["media_idx"]);
            unset($value["media_reg_dt"]);
        }
        return $value;
    }
}

?>
