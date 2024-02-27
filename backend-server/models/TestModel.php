<?php

namespace common\models;

class TestModel extends Model
{
    public function __construct()
    {
        /*
        $this->table_column_list = [
            new TableColumn(
                "test_idx",
                "int",
                "번호",
                "auto_increment primary key"
            ),
            new TableColumn("test_name", "varchar(255)", "이름"),
            new TableColumn("test_cellphone", "varchar(255)", "핸드폰번호"),
        ];
        parent::__construct("web_test");
        */
        parent::__construct("shop_order");
    }
}
