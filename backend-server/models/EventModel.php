<?php
namespace common\models;

class EventModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_event");
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
            $value["event_reg_dt"] = _DATE_YMD;
        }
        $value["event_update_dt"] = _DATE_YMD;

        if ($type == "set") {
            unset($value["event_idx"]);
            unset($value["event_reg_dt"]);
        }
        return $value;
    }
}

?>
