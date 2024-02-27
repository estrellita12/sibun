<?php
namespace common\models;

class TableColumn
{
    public function __construct($name, $type, $comment = "", $etc = "")
    {
        $this->name = $name;
        $this->type = $type;
        $this->comment = $comment;
        $this->etc = $etc;
    }
}

?>
